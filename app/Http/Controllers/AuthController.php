<?php

namespace App\Http\Controllers;


use App\Mail\SendOtpMail;
use App\Models\Otp;
use App\Models\user;
use Illuminate\Http\Request;
use Google\Client as GoogleClient;
use Illuminate\Support\Facades\Auth;
use Google\Service\Directory\Users as DirectoryUsers;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Services\InfobipService;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{

    protected InfobipService $sms;
    public function __construct(InfobipService $sms)
    {
        $this->sms = $sms;
    }
    public function register(Request $request)
    {
        $request->validate([
            'full_name' => 'required',
            'login'      => 'required',

            'password' => [
                'required',
                Password::min(8)
                    ->mixedCase()
                    ->letters()
                    ->numbers()
                    ->symbols(),
            ],
        ]);

        $login = $request->login;

        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {

            if (User::where('email', $login)->exists()) {

                return response()->json([
                    'success' => false,
                    'message' => 'Email already registered'
                ], 409);
            }
        } else {

            $phone = preg_replace('/\D/', '', $login);

            if (str_starts_with($phone, '0')) {
                $phone = '855' . substr($phone, 1);
            }

            if (User::where('phone', $phone)->exists()) {

                return response()->json([
                    'success' => false,
                    'message' => 'Phone number already registered'
                ], 409);
            }
        }

        $otp = random_int(100000, 999999);

        $payload = [
            'full_name' => $request->full_name,
            'password'   => Hash::make($request->password),
        ];

        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {

            Otp::updateOrCreate(
                ['email' => $login],
                [
                    'otp' => $otp,
                    'payload' => json_encode($payload),
                    'expires_at' => now()->addMinutes(5),
                ]
            );

            Mail::to($login)->queue(new SendOtpMail($otp));
        } else {
            $login = preg_replace('/\D/', '', $login);

            if (str_starts_with($login, '0')) {
                $login = '855' . substr($login, 1);
            }

            Otp::updateOrCreate(
                ['phone' => $login],
                [
                    'otp' => $otp,
                    'payload' => json_encode($payload),
                    'expires_at' => now()->addMinutes(5),
                ]
            );

            try {

                $this->sms->sendSms(
                    $login,
                    "Your OTP is {$otp}"
                );

                return response()->json([
                    'success' => true,
                    'message' => 'OTP sent successfully'
                ]);
            } catch (\Exception $e) {



                return response()->json([
                    'success' => false,
                    'error' => $e->getMessage(),
                ], 500);
            }
        }

        return response()->json([
            'message' => 'OTP sent successfully'
        ]);
    }
    public function userLogin(Request $request)
    {
        $request->validate([
            'login' => 'required',
            'password' => 'required',
        ]);

        $field = filter_var($request->login, FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'phone';

        if (!Auth::attempt([
            $field => $request->login,
            'password' => $request->password
        ])) {

            return response()->json([
                'message' => 'Invalid email/phone or password'
            ], 401);
        }

        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        if ($user->role !== 'customer') {
            Auth::logout();

            return response()->json([
                'message' => 'Access denied'
            ], 403);
        }
        $user = User::find($user->id);
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'login' => 'required'
        ]);

        $field = filter_var($request->login, FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'phone';

        $user = User::where($field, $request->login)->first();

        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        $otp = random_int(100000, 999999);

        Otp::updateOrCreate(
            [$field => $request->login],
            [
                'otp' => $otp,
                'expires_at' => now()->addMinutes(5),
            ]
        );

        if ($field === 'email') {
            Mail::to($request->login)
                ->queue(new SendOtpMail($otp));
        } else {
            // $this->sms->sendSms(
            //     $request->login,
            //     "Your reset OTP is {$otp}"
            // );
        }

        return response()->json([
            'message' => 'OTP sent successfully'
        ]);
    }
    public function verifyResetOtp(Request $request)
    {
        $request->validate([
            'login' => 'required',
            'otp' => 'required',
        ]);

        $field = filter_var($request->login, FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'phone';

        $verify = Otp::where($field, $request->login)
            ->first();

        if (!$verify) {
            return response()->json([
                'message' => 'OTP record not found'
            ], 404);
        }

        if ($verify->otp != $request->otp) {
            return response()->json([
                'message' => 'Invalid OTP'
            ], 400);
        }

        if (now()->isAfter($verify->expires_at)) {
            return response()->json([
                'message' => 'OTP expired'
            ], 400);
        }

        $resetToken = Str::random(64);

        $verify->update([
            'reset_token' => $resetToken,
        ]);

        return response()->json([
            'message' => 'OTP verified',
            'reset_token' => $resetToken,
        ]);
    }
    public function resetPassword(Request $request)
    {
        $request->validate([
            'reset_token' => 'required',
            'new_password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
        ]);

        $verify = Otp::where(
            'reset_token',
            $request->reset_token
        )->first();

        if (!$verify) {
            return response()->json([
                'message' => 'Invalid reset token'
            ], 400);
        }

        $field = $verify->email ? 'email' : 'phone';

        $login = $verify->email ?? $verify->phone;

        $user = User::where($field, $login)->first();

        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        $user->update([
            'password' => Hash::make(
                $request->new_password
            ),
        ]);

        $verify->delete();

        $user->tokens()->delete();

        $token = $user->createToken(
            'auth_token'
        )->plainTextToken;

        return response()->json([
            'message' => 'Password reset successfully',
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ]);
    }
    public function resendOtp(Request $request)
    {
        $request->validate([
            'login' => 'required',
        ]);

        $field = filter_var(
            $request->login,
            FILTER_VALIDATE_EMAIL
        )
            ? 'email'
            : 'phone';

        $otpRecord = Otp::where(
            $field,
            $request->login
        )->first();

        if (!$otpRecord) {
            return response()->json([
                'message' => 'OTP record not found'
            ], 404);
        }

        $otp = random_int(100000, 999999);

        $otpRecord->update([
            'otp' => $otp,
            'expires_at' => now()->addMinutes(5),
        ]);

        if ($field === 'email') {
            Mail::to($request->login)
                ->queue(new SendOtpMail($otp));
        } else {
            // $this->sms->sms(
            //     $request->login,
            //     "Your OTP is {$otp}"
            // );
        }

        return response()->json([
            'message' => 'OTP resent successfully'
        ]);
    }
    public function showLoginForm()
    {
        return view('Auth.login');
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return back()->withErrors([
                'email' => 'Invalid email or password',
            ]);
        }

        $user = Auth::user();

        if ($user->role === 'admin') {
            return redirect()->intended('admin/dashboard');
        }

        if ($user->role === 'staff') {
            return redirect()->intended('staff/dashboard');
        }

        if ($user->role === 'customer') {
            return redirect()->intended('/');
        }

        Auth::logout();
        abort(403, 'Access denied');
    }
    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);


        $otp = rand(100000, 999999);
        Otp::updateOrCreate(
            ['email' => $request->email],
            ['otp' => $otp, 'expires_at' => now()->addMinutes(5)]
        );

        Mail::to($request->email)->queue(new SendOtpMail($otp));

        return response()->json(['message' => 'OTP sent to email']);
    }
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'login' => 'required',
            'otp'   => 'required'
        ]);

        $field = filter_var($request->login, FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'phone';

        $verify = Otp::where($field, $request->login)->first();

        if (!$verify) {
            return response()->json([
                'message' => 'OTP record not found'
            ], 404);
        }

        if ($verify->otp != $request->otp) {
            return response()->json([
                'message' => 'Invalid OTP'
            ], 400);
        }

        if (now()->isAfter($verify->expires_at)) {
            return response()->json([
                'message' => 'OTP expired'
            ], 400);
        }

        $payload = json_decode($verify->payload, true);

        $userData = [
            'full_name' => $payload['full_name'],
            'password'   => $payload['password'],
        ];

        if ($field === 'email') {
            $userData['email'] = $request->login;
        } else {
            $userData['phone'] = $request->login;
        }

        $user = User::create($userData);

        $verify->delete();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'OTP Verified Successfully',
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }
    public function googleLogin(Request $request)
    {
        try {
            $idToken = $request->input('id_token');

            if (!$idToken) {
                return response()->json(['error' => 'No ID Token sent'], 400);
            }
            $client = new GoogleClient([
                'client_id' => '105211609304-g4g9f9vfiq268lneii7231cjecq781hr.apps.googleusercontent.com'
            ]);

            $payload = $client->verifyIdToken($idToken);

            if (!$payload) {
                return response()->json(['error' => 'Invalid ID Token'], 401);
            }
            $name = $payload['name'] ?? 'No name';
            $email = $payload['email'];
            $picture = $payload['picture'] ?? null;

            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'profile_image_url' => $picture,
                ]
            );
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'status' => 'success',
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                "raw" => $request->all()
            ], 500);
        }
    }
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'full_name'      => 'nullable|string|max:255',
            'email'           => 'nullable|email|unique:users,email,' . $user->id,
            'phone'           => 'nullable|string|max:20',
            'facebook_id'     => 'nullable|string|max:255',
            'remember_token'  => 'nullable|string|max:100',
            'avatar'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Keep old data if field is not sent
        $data = [
            'full_name' => $request->filled('full_name')
                ? $request->full_name
                : $user->full_name,


            'email' => $request->has('email')
                ? $request->email
                : $user->email,

            'phone' => $request->has('phone')
                ? $request->phone
                : $user->phone,

            'facebook_id' => $request->has('facebook_id')
                ? $request->facebook_id
                : $user->facebook_id,

            'remember_token' => $request->has('remember_token')
                ? $request->remember_token
                : $user->remember_token,
        ];

        if ($request->hasFile('avatar')) {

            if ($user->avatar) {
                $oldPath = str_replace(
                    rtrim(env('R2_PUBLIC_BASE_URL'), '/') . '/',
                    '',
                    $user->avatar
                );

                Storage::disk('r2')->delete($oldPath);
            }

            $file = $request->file('avatar');

            $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();

            $path = 'avatars/' . $fileName;

            Storage::disk('r2')->put(
                $path,
                file_get_contents($file),
                'public'
            );


            $data['avatar'] = rtrim(env('R2_PUBLIC_BASE_URL'), '/') . '/' . $path;
        } else {

            $data['avatar'] = $user->avatar;
        }

        // Update user
        User::where('id', $user->id)->update($data);

        // Reload fresh data
        $user = User::find($user->id);

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => [
                'id'             => $user->id,
                'full_name'      => $user->full_name,
                'email'          => $user->email,
                'phone'          => $user->phone,
                'facebook_id'    => $user->facebook_id,
                'remember_token' => $user->remember_token,
                'avatar'         => $user->avatar
                    ? rtrim(env('R2_PUBLIC_BASE_URL'), '/') . '/' . $user->avatar
                    : null,
                'role'           => $user->role,
            ]
        ]);
    }










    // public function verifyOtp(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|email',
    //         'otp' => 'required'
    //     ]);

    //     $verify = Otp::where('email', $request->email)->first();

    //     if (!$verify) {
    //         return response()->json(['message' => 'Email not found'], 404);
    //     }

    //     if ($verify->otp != $request->otp) {
    //         return response()->json(['message' => 'Invalid OTP'], 400);
    //     }

    //     if (now()->isAfter($verify->expires_at)) {
    //         return response()->json(['message' => 'OTP expired'], 400);
    //     }

    //     $user = user::firstOrCreate(
    //         ['email' => $request->email],
    //         ['first_name' => preg_replace('/\d+$/', '', explode('@', $request->email)[0])]
    //     );

    //     $token = $user->createToken('auth_token')->plainTextToken;


    //     return response()->json([
    //         'status' => 'Otp Verified Successfully',
    //         'token' => $token,
    //         'token_type' => 'Bearer',
    //         'user' => $user
    //     ]);
    // }

    // public function sendSms(Request $request, TwilioService $twilio)
    // {
    //     $request->validate([
    //         'phone' => 'required'
    //     ]);

    //     $phone = $request->phone;

    //     $otp = random_int(100000, 999999); // better than rand()

    //     Otp::updateOrCreate(
    //         ['phone' => $phone],
    //         [
    //             'otp' => $otp,
    //             'expires_at' => now()->addMinutes(5)
    //         ]
    //     );

    //     try {

    //         $twilio->sendSms(
    //             $phone,
    //             "Your OTP is $otp"
    //         );

    //         return response()->json([
    //             'message' => 'OTP Sent'
    //         ]);
    //     } catch (\Exception $e) {

    //         return response()->json([
    //             'error' => $e->getMessage()
    //         ], 400);
    //     }
    // }

    // protected SmsService $sms;
    // public function __construct(SmsService $sms)
    // {
    //     $this->sms = $sms;
    // }
    // public function send(Request $request)
    // {
    //     $request->validate([
    //         'phone'   => 'required|string',
    //         'message' => 'required|string|max:160',
    //     ]);

    //     // ផ្ញើ SMS ជាភាសាខ្មែរបានដែរ ✅
    //     $success = $this->sms->send(
    //         $request->phone,
    //         $request->message
    //     );

    //     if ($success) {
    //         return response()->json([
    //             'status'  => 'success',
    //             'message' => 'SMS បានផ្ញើដោយជោគជ័យ!',
    //         ]);
    //     }

    //     return response()->json([
    //         'status'  => 'error',
    //         'message' => 'ផ្ញើ SMS មិនបានទេ!',
    //     ], 500);
    // }
    // public function sendSms(Request $request)
    // {
    //     $request->validate([
    //         'phone' => 'required|string'
    //     ]);

    //     $phone = $request->phone;

    //     $code = random_int(100000, 999999);
    //     $msg  = "លេខកូដ OTP: {$code} (មានសុពលភាព 5 នាទី)";

    //     $this->sms->send($phone, $msg);

    //     Cache::put("otp_{$phone}", $code, now()->addMinutes(5));

    //     return response()->json([
    //         'status' => 'success'
    //     ]);
    // }


}
