<?php

namespace App\Http\Controllers\ApiController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\user;

class ProfileController extends Controller
{
    public function myProfile()
    {
        $user = Auth::user();

        return response()->json([
            'id' => $user->id,
            'full_name' => $user->full_name,
            'email' => $user->email,
            'phone' => $user->phone,
            'avatar' => $user->avatar,
            'created_at' => $user->created_at
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user_id = Auth::id();
        $user = User::find($user_id);

        $validated = $request->validate([
            'full_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|max:2048',
            'facebook_id' => 'nullable|string|max:255|unique:users,facebook_id,' . $user->id,
            'fcm_token' => 'nullable|string|max:255',
        ]);

        /// Default update data
        $data = [
            'full_name' => $validated['full_name'] ?? $user->full_name,
            'email' => $validated['email'] ?? $user->email,
            'phone' => $validated['phone'] ?? $user->phone,
            'facebook_id' => $validated['facebook_id'] ?? $user->facebook_id,
            'fcm_token' => $validated['fcm_token'] ?? $user->fcm_token,
            'avatar' => $user->avatar,
        ];
        /// Upload avatar
        if ($request->hasFile('avatar')) {

            /// Delete old avatar
            if ($user->avatar) {
                Storage::disk('r2')->delete(
                    $user->avatar
                );
            }

            $file = $request->file('avatar');

            $fileName =
                Str::uuid() . '.' .
                $file->getClientOriginalExtension();

            $path = 'avatars/' . $fileName;

            /// Upload to R2
            Storage::disk('r2')->put(
                $path,
                file_get_contents($file),
                'public'
            );

            /// Save full URL
            $data['avatar'] =
                rtrim(
                    env('R2_PUBLIC_BASE_URL'),
                    '/'
                ) . '/' . $path;
        }

        /// Update user
        $user->update($data);

        return response()->json([

            'message' => 'Profile updated',

            'user' => [
                'id' => $user->id,
                'phone' => $user->phone,

                'avatar' => $user->avatar
                    ? rtrim(
                        env('R2_PUBLIC_BASE_URL'),
                        '/'
                    ) . '/' . $user->avatar
                    : null,
            ]
        ]);
    }
}
