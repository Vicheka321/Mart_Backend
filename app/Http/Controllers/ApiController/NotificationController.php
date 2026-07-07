<?php

namespace App\Http\Controllers\ApiController;

use App\Http\Controllers\Controller;
use App\Models\NotificationUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user_id = Auth::id();

        $notifications = NotificationUser::with('notification')

            ->where('user_id', $user_id)

            ->latest()

            ->paginate(20);

        return response()->json($notifications);
    }

    public function read(NotificationUser $notification)
    {
        $user_id = Auth::id();

        abort_if(

            $notification->user_id != $user_id,

            403

        );

        $notification->update([

            'is_read' => true,

            'read_at' => now()

        ]);

        return response()->json([

            'success' => true

        ]);
    }

    public function readAll()
    {
        $user_id = Auth::id();

        NotificationUser::where(

            'user_id',

            $user_id

        )

            ->update([

                'is_read' => true,

                'read_at' => now()

            ]);

        return response()->json([

            'success' => true

        ]);
    }
}
