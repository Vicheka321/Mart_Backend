<?php

namespace App\Http\Controllers;


use App\Mail\SendOtpMail;
use App\Models\Otp;
use App\Models\User;
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

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        // Mobile app: allow only Customer role
        if (!$user->hasRole('Customer')) {
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
    // public function forgotPassword(Request $request)
    // {
    //     $request->validate([
    //         'login' => 'required'
    //     ]);

    //     $field = filter_var($request->login, FILTER_VALIDATE_EMAIL)
    //         ? 'email'
    //         : 'phone';

    //     $user = User::where($field, $request->login)->first();

    //     if (!$user) {
    //         return response()->json([
    //             'message' => 'User not found'
    //         ], 404);
    //     }

    //     $otp = random_int(100000, 999999);

    //     Otp::updateOrCreate(
    //         [$field => $request->login],
    //         [
    //             'otp' => $otp,
    //             'expires_at' => now()->addMinutes(5),
    //         ]
    //     );

    //     if ($field === 'email') {
    //         Mail::to($request->login)
    //             ->queue(new SendOtpMail($otp));
    //     } else {
    //         // $this->sms->sendSms(
    //         //     $request->login,
    //         //     "Your reset OTP is {$otp}"
    //         // );
    //     }

    //     return response()->json([
    //         'message' => 'OTP sent successfully'
    //     ]);
    // }


    public function forgotPassword(Request $request)
    {
        $request->validate([
            'login' => 'required'
        ]);

        $field = filter_var($request->login, FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'phone';

        $login = $request->login;

        if ($field === 'phone') {

            $login = preg_replace('/\D/', '', $login);

            if (str_starts_with($login, '0')) {
                $login = '855' . substr($login, 1);
            }
        }

        $user = User::where($field, $login)->first();

        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        $otp = random_int(100000, 999999);

        Otp::updateOrCreate(
            [$field => $login],
            [
                'otp' => $otp,
                'expires_at' => now()->addMinutes(5),
            ]
        );

        if ($field === 'email') {

            Mail::to($login)->queue(new SendOtpMail($otp));
        } else {

            $this->sms->sendSms(
                $login,
                "Your reset OTP is {$otp}"
            );
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

        // $field = filter_var($request->login, FILTER_VALIDATE_EMAIL)
        //     ? 'email'
        //     : 'phone';

        // $verify = Otp::where($field, $request->login)
        //     ->first();
        $field = filter_var($request->login, FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'phone';

        $login = $request->login;

        if ($field === 'phone') {

            $login = preg_replace('/\D/', '', $login);

            if (str_starts_with($login, '0')) {
                $login = '855' . substr($login, 1);
            }
        }

        $verify = Otp::where($field, $login)->first();

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
            ])->withInput();
        }

        $request->session()->regenerate();

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Admin panel users
        if ($user->can('access_admin_panel')) {
            return redirect()->route('admin.dashboard');
        }

        // If you later want web customer login, redirect somewhere else
        if ($user->hasRole('Customer')) {
            return redirect('/');
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

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
        // Otp::updateOrCreate(
        //     ['email' => $request->email],
        //     ['otp' => $otp, 'expires_at' => now()->addMinutes(5)]
        // );

        Mail::to($request->email)->send(new SendOtpMail($otp));

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

        $login = $request->login;

        if ($field === 'phone') {

            $login = preg_replace('/\D/', '', $login);

            if (str_starts_with($login, '0')) {
                $login = '855' . substr($login, 1);
            }
        }

        $verify = Otp::where($field, $login)->first();


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
        $user->assignRole('Customer');

        $verify->delete();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'OTP Verified Successfully',
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }




    // public function googleLogin(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'id_token' => 'required|string',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'message' => $validator->errors()->first(),
    //         ], 422);
    //     }



    //     try {

    //         $client = new GoogleClient([
    //             'client_id' => config('services.google.client_id'),
    //         ]);
    //         $payload = $client->verifyIdToken($request->id_token);

    //         if (!$payload) {
    //             return response()->json([
    //                 'message' => 'Invalid Google Token',
    //             ], 401);
    //         }

    //         $user = User::firstOrNew([
    //             'email' => $payload['email'],
    //         ]);

    //         if (!$user->exists) {

    //             $user->full_name = $payload['name'] ?? '';
    //             $user->email = $payload['email'];
    //             $user->avatar = $payload['picture'] ?? null;

    //             // Password random because Google login
    //             $user->password = bcrypt(\Illuminate\Support\Str::random(32));

    //             $user->save();

    //             // Customer Role
    //             $user->assignRole('Customer');
    //         }

    //         // Only Customer login
    //         if (!$user->hasRole('Customer')) {

    //             return response()->json([
    //                 'message' => 'Access denied',
    //             ], 403);
    //         }

    //         // Delete old token
    //         $user->tokens()->delete();

    //         $token = $user->createToken('auth_token')->plainTextToken;

    //         return response()->json([
    //             'message' => 'Login successful',
    //             'token' => $token,
    //             'token_type' => 'Bearer',
    //             'user' => $user,
    //         ]);
    //     } catch (\Throwable $e) {

    //         return response()->json([
    //             'message' => $e->getMessage(),
    //         ], 500);
    //     }
    // }



    public function googleLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
            ], 422);
        }

        try {

            $parts = explode('.', $request->id_token);

            if (count($parts) !== 3) {
                return response()->json([
                    'message' => 'Invalid token format',
                ], 401);
            }

            $payload = json_decode(
                base64_decode(strtr($parts[1], '-_', '+/')),
                true
            );

            if (!$payload || empty($payload['email'])) {
                return response()->json([
                    'message' => 'Invalid token payload',
                ], 401);
            }

            $user = User::firstOrNew([
                'email' => $payload['email'],
            ]);

            if (!$user->exists) {

                $user->full_name = $payload['name'] ?? '';
                $user->email = $payload['email'];
                $user->avatar = $payload['picture'] ?? null;
                $user->password = bcrypt(Str::random(32));

                $user->save();

                $user->assignRole('Customer');
            }

            if (!$user->hasRole('Customer')) {
                return response()->json([
                    'message' => 'Access denied',
                ], 403);
            }

            $user->tokens()->delete();

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Login successful',
                'token' => $token,
                'token_type' => 'Bearer',
                'user' => $user,
                'google_payload' => $payload, 
            ]);
        } catch (\Throwable $e) {

            return response()->json([
                'message' => $e->getMessage(),
            ], 500);
        }
    }



    // public function googleLogin(Request $request)
    // {
    //     try {

    //         $idToken = $request->input('id_token');

    //         if (!$idToken) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'No ID Token received',
    //             ], 400);
    //         }

    //         // Decode JWT payload (without verifying)
    //         $parts = explode('.', $idToken);

    //         $jwtPayload = [];

    //         if (count($parts) === 3) {
    //             $jwtPayload = json_decode(
    //                 base64_decode(strtr($parts[1], '-_', '+/')),
    //                 true
    //             );
    //         }

    //         $client = new GoogleClient([
    //             'client_id' => config('services.google.client_id'),
    //         ]);

    //         $verifiedPayload = $client->verifyIdToken($idToken);

    //         return response()->json([
    //             'success' => true,

    //             // Laravel Config
    //             'config_client_id' => config('services.google.client_id'),

    //             // Request
    //             'received' => $request->has('id_token'),
    //             'token_length' => strlen($idToken),

    //             // JWT Payload
    //             'jwt_payload' => $jwtPayload,

    //             // Google verify result
    //             'verify_success' => $verifiedPayload ? true : false,
    //             'verified_payload' => $verifiedPayload,
    //         ]);
    //     } catch (\Throwable $e) {

    //         return response()->json([
    //             'success' => false,

    //             'config_client_id' => config('services.google.client_id'),

    //             'message' => $e->getMessage(),
    //             'exception' => get_class($e),

    //             'trace' => $e->getTraceAsString(),
    //         ], 500);
    //     }
    // }
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
