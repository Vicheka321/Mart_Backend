<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\user;
use App\Services\FirebaseNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\DeviceToken;

class PushNotificationController extends Controller
{
    public function index()
    {
        $users = User::role('Customer')
            ->select('id', 'full_name')
            ->orderBy('full_name')
            ->get();

        return view('admin.notifications', compact('users'));
    }


    public function store(
        Request $request,
        FirebaseNotificationService $firebase
    ) {
        $request->validate([
            'title'        => 'required|max:255',
            'message'      => 'required|max:200',
            'type'         => 'required',
            'target'       => 'required',
            'image_url'    => 'nullable|url',
            'schedule'     => 'required',
            'scheduled_at' => 'nullable',
        ]);

        DB::beginTransaction();

        try {

            // Save Notification History
            $notification = Notification::create([
                'title'        => $request->title,
                'message'      => $request->message,
                'type'         => $request->type,
                'target'       => $request->target,
                'image_url'    => $request->image_url,
                'status'       => $request->schedule == 'later'
                    ? 'scheduled'
                    : 'sent',
                'scheduled_at' => $request->scheduled_at,
            ]);

            // Schedule only
            if ($request->schedule == 'later') {

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Notification scheduled.',
                ]);
            }

            // Send Now
            $this->sendNotification($notification, $firebase);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Notification sent successfully.',
            ]);
        } catch (\Throwable $e) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

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

        $firebase->sendToTokens(
            tokens: $tokens->toArray(),
            title: $notification->title,
            body: $notification->message,
            data: [
                'type' => $notification->type,
                'notification_id' => (string) $notification->id,
            ],
            image: $notification->image_url,
        );
    }



    public function test(FirebaseNotificationService $firebase)
    {
        $tokens = DeviceToken::pluck('fcm_token');

        foreach ($tokens as $token) {

            $firebase->sendToToken(
                token: $token,
                title: 'Laravel Test',
                body: 'Hello All Devices 🎉',
                data: [
                    'type' => 'general',
                ]
            );
        }

        return 'Notification Sent!';
    }

    // public function send(Request $request, FirebaseNotificationService $firebase)
    // {
    //     $request->validate([
    //         'title'       => ['required', 'string', 'max:255'],
    //         'body'        => ['required', 'string'],
    //         'image'       => ['nullable', 'url'],
    //         'target_type' => ['required', 'in:all,user,topic'],
    //         'user_id'     => ['nullable', 'exists:users,id'],
    //         'topic'       => ['nullable', 'string', 'max:255'],
    //     ]);

    //     try {
    //         $title      = $request->title;
    //         $body       = $request->body;
    //         $image      = $request->image;
    //         $targetType = $request->target_type;

    //         $data = [
    //             'type'      => 'general',
    //             'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
    //         ];

    //         // ==========================
    //         // Send to All Users
    //         // ==========================
    //         if ($targetType === 'all') {
    //             // Flutter app should subscribe to topic "all"
    //             $firebase->sendToTopic(
    //                 'all',
    //                 $title,
    //                 $body,
    //                 $data,
    //                 $image
    //             );

    //             return redirect()
    //                 ->back()
    //                 ->with('success', 'Notification sent to all users successfully.');
    //         }

    //         // ==========================
    //         // Send to Specific User
    //         // ==========================
    //         if ($targetType === 'user') {
    //             if (!$request->filled('user_id')) {
    //                 return redirect()
    //                     ->back()
    //                     ->with('error', 'Please select a user.');
    //             }

    //             $user = User::findOrFail($request->user_id);

    //             if (empty($user->fcm_token)) {
    //                 return redirect()
    //                     ->back()
    //                     ->with('error', 'Selected user does not have an FCM token.');
    //             }

    //             $firebase->sendToToken(
    //                 $user->fcm_token,
    //                 $title,
    //                 $body,
    //                 $data,
    //                 $image
    //             );

    //             return redirect()
    //                 ->back()
    //                 ->with('success', "Notification sent to {$user->name} successfully.");
    //         }

    //         // ==========================
    //         // Send to Custom Topic
    //         // ==========================
    //         if ($targetType === 'topic') {
    //             if (!$request->filled('topic')) {
    //                 return redirect()
    //                     ->back()
    //                     ->with('error', 'Please enter a topic name.');
    //             }

    //             $firebase->sendToTopic(
    //                 $request->topic,
    //                 $title,
    //                 $body,
    //                 $data,
    //                 $image
    //             );

    //             return redirect()
    //                 ->back()
    //                 ->with('success', "Notification sent to topic '{$request->topic}' successfully.");
    //         }

    //         return redirect()
    //             ->back()
    //             ->with('error', 'Invalid target type.');
    //     } catch (\Throwable $e) {
    //         report($e);

    //         return redirect()
    //             ->back()
    //             ->with('error', $e->getMessage());
    //     }
    // }


}
