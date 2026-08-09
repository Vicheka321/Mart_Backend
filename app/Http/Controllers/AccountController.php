<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class AccountController extends Controller
{
    /**
     * Display account page.
     */
    public function index()
    {
        $user = User::with('roles')
            ->findOrFail(Auth::id());

        return view('Admin.account', compact('user'));
    }
    /**
     * Update profile.
     */
    public function updateProfile(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'full_name' => [
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                'nullable',
                'email',
                'max:255',
                'unique:users,email,' . $user->id,
            ],
            'phone' => [
                'nullable',
                'string',
                'max:30',
                'unique:users,phone,' . $user->id,
            ],
            'avatar' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ]);

        // Upload avatar to Cloudflare R2
        if ($request->hasFile('avatar')) {

            $file = $request->file('avatar');

            if ($file->isValid()) {

                // Delete old avatar (optional)
                if (!empty($user->avatar)) {

                    $oldPath = str_replace(
                        rtrim(env('R2_PUBLIC_BASE_URL'), '/') . '/',
                        '',
                        $user->avatar
                    );

                    Storage::disk('r2')->delete($oldPath);
                }

                $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();

                $path = 'avatars/' . $fileName;

                Storage::disk('r2')->put(
                    $path,
                    file_get_contents($file),
                    'public'
                );

                $validated['avatar'] =
                    rtrim(env('R2_PUBLIC_BASE_URL'), '/') . '/' . $path;
            }
        }

        $user->update($validated);

        return back()->with(
            'success',
            'Profile updated successfully.'
        );
    }

    /**
     * Change password.
     */
    // public function updatePassword(Request $request)
    // {
    //     /** @var User $user */
    //     $user = Auth::user();

    //     $validated = $request->validate([
    //         'current_password' => [
    //             'required',
    //         ],
    //         'password' => [
    //             'required',
    //             'string',
    //             'min:8',
    //             'confirmed',
    //         ],
    //     ]);

    //     if (!Hash::check(
    //         $validated['current_password'],
    //         $user->password
    //     )) {

    //         return back()->withErrors([
    //             'current_password' => 'Current password is incorrect.',
    //         ]);
    //     }

    //     $user->update([
    //         'password' => Hash::make(
    //             $validated['password']
    //         ),
    //     ]);

    //     return back()->with(
    //         'success',
    //         'Password updated successfully.'
    //     );
    // }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => [
                'required',
                'current_password',
            ],

            'new_password' => [
                'required',
                'string',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
        ]);

        $user = Auth::user();

        $user->update([
            'password' => Hash::make($validated['new_password']),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully.',
        ]);
    }
}
