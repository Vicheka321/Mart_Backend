<?php

namespace App\Http\Controllers;

use App\Models\DeviceToken;
use App\Models\Notification;
use App\Models\User;
use App\Services\FirebaseNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PushNotificationController extends Controller
{
    public function index()
    {
        $users = User::role('Customer')
            ->select('id', 'full_name')
            ->orderBy('full_name')
            ->get();

        $stats = [
            'total' => Notification::count(),
            'sent' => Notification::where('status', 'sent')->count(),
            'scheduled' => Notification::where('status', 'scheduled')->count(),
            'failed' => Notification::where('status', 'failed')->count(),
        ];

        $stats['delivery_rate'] = $stats['total'] > 0
            ? round(($stats['sent'] / $stats['total']) * 100)
            : 0;

        $recentNotifications = Notification::latest()
            ->take(5)
            ->get();

        $notifications = Notification::latest()->get();

        return view(
            'Admin.notifications',
            compact(
                'users',
                'stats',
                'recentNotifications',
                'notifications'
            )
        );
    }

    public function store(
        Request $request,
        FirebaseNotificationService $firebase
    ) {

        $request->validate([
            'title' => 'required|max:255',
            'message' => 'required|max:200',
            'target' => 'required',
            'image_url' => 'nullable|url',
            'schedule' => 'required',
            'scheduled_at' => 'nullable|date',
        ]);

        DB::beginTransaction();

        try {

            $notification = Notification::create([
                'title' => $request->title,
                'message' => $request->message,
                'target' => $request->target,
                'image_url' => $request->image_url,

                'status' => $request->schedule == 'later'
                    ? 'scheduled'
                    : 'pending',

                'scheduled_at' => $request->schedule == 'later'
                    ? $request->scheduled_at
                    : null,
            ]);

            /**
             * Schedule only
             */
            if ($request->schedule == 'later') {

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Notification scheduled successfully.',
                ]);
            }

            /**
             * Send immediately
             */
            $this->sendNotification(
                $notification,
                $firebase
            );

            $notification->update([
                'status' => 'sent',
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Notification sent successfully.',
            ]);
        } catch (\Throwable $e) {

            DB::rollBack();

            if (isset($notification)) {

                $notification->update([
                    'status' => 'failed',
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Send Notification
     */
    private function sendNotification(
        Notification $notification,
        FirebaseNotificationService $firebase
    ) {

        $tokens = collect();

        switch ($notification->target) {

            case 'all':

                $tokens = DeviceToken::where('is_active', true)
                    ->pluck('fcm_token');

                break;

            case 'customers':

                $tokens = DeviceToken::whereHas('user', function ($q) {
                    $q->role('Customer');
                })
                    ->where('is_active', true)
                    ->pluck('fcm_token');

                break;

            case 'active':

                $tokens = DeviceToken::where('is_active', true)
                    ->pluck('fcm_token');

                break;

            case 'inactive':

                $tokens = DeviceToken::where('is_active', false)
                    ->pluck('fcm_token');

                break;

            default:

                return;
        }

        if ($tokens->isEmpty()) {
            return;
        }

        $firebase->sendToTokens(
            tokens: $tokens->toArray(),
            title: $notification->title,
            body: $notification->message,
            data: [
                'notification_id' => (string) $notification->id,
            ],
            image: $notification->image_url,
        );
    }

    /**
     * Test Notification
     */
    public function update(Request $request, Notification $notification)
    {
        if ($notification->status !== 'scheduled') {
            return response()->json([
                'success' => false,
                'message' => 'Only scheduled notifications can be edited.'
            ], 422);
        }

        $request->validate([
            'title' => 'required|max:255',
            'message' => 'required|max:200',
            'target' => 'required',
            'image_url' => 'nullable|url',
            'scheduled_at' => 'required|date',
        ]);

        $notification->update([
            'title' => $request->title,
            'message' => $request->message,
            'target' => $request->target,
            'image_url' => $request->image_url,
            'scheduled_at' => $request->scheduled_at,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Notification updated successfully.',
        ]);
    }

    public function destroy(Notification $notification)
    {
        $notification->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notification deleted successfully.',
        ]);
    }

    public function resend(
        Notification $notification,
        FirebaseNotificationService $firebase
    ) {
        try {

            $this->sendNotification(
                $notification,
                $firebase
            );

            $notification->update([
                'status' => 'sent',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Notification sent again successfully.',
            ]);
        } catch (\Throwable $e) {

            $notification->update([
                'status' => 'failed',
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
