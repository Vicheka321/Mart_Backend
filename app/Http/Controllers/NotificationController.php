<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\user;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $users = User::role('Customer')
            ->select('id', 'full_name')
            ->orderBy('full_name')
            ->get();

        return view('admin.notifications', compact('users'));
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

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'message' => 'required|max:200',
            'type' => 'required',
            'target' => 'required',
            'image_url' => 'nullable|url',
            'schedule' => 'required',
            'scheduled_at' => 'nullable',
        ]);

        $notification = Notification::create([
            'title'        => $request->title,
            'message'      => $request->message,
            'type'         => $request->type,
            'target'       => $request->target,
            'image_url'    => $request->image_url,
            'status'       => $request->schedule === 'later'
                ? 'scheduled'
                : 'sent',
            'scheduled_at' => $request->scheduled_at,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Notification created',
            'data'    => $notification
        ]);
    }
}
