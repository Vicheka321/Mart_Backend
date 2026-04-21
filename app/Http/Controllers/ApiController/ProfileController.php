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
    public function me()
    {
        $user = Auth::user();

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'avatar' => $user->avatar,
            'created_at' => $user->created_at
        ]);
    }

    // public function update(Request $request)
    // {
    //     $user = Auth::user();

    //     $request->validate([
    //         'name' => 'required',
    //         'phone' => 'nullable',
    //         'avatar' => 'nullable|image|max:2048'
    //     ]);

    //     $data = [
    //         'name' => $request->name,
    //         'phone' => $request->phone,
    //     ];

    //     if ($request->hasFile('avatar')) {

    //         // 🔥 delete old avatar (optional but good)
    //         if ($user->avatar) {
    //             Storage::disk('r2')->delete($user->avatar);
    //         }

    //         $file = $request->file('avatar');

    //         $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();

    //         $path = 'avatars/' . $fileName;

    //         Storage::disk('r2')->putFileAs(
    //             'avatars',
    //             $file,
    //             $fileName,
    //             'public'
    //         );

    //         $data['avatar'] = $path;
    //     }

    //     $user->update($data);

    //     return response()->json([
    //         'message' => 'Profile updated',

    //         'avatar' => isset($data['avatar'])
    //             ? rtrim(env('R2_PUBLIC_BASE_URL'), '/') . '/' . $data['avatar']
    //             : (
    //                 $user->avatar
    //                 ? rtrim(env('R2_PUBLIC_BASE_URL'), '/') . '/' . $user->avatar
    //                 : null
    //             )
    //     ]);
    // }
}
