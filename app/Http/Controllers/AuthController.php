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
use App\Services\SmsService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function index() {}

    public function showLoginForm()
    {
        return view('Auth.login');
    }
    // public function login(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required',
    //     ]);

    //     if (!Auth::attempt($request->only('email', 'password'))) {
    //         return back()->withErrors([
    //             'email' => 'Invalid email or password',
    //         ]);
    //     }

    //     $user = Auth::user();

    //     if ($user->role === 'admin') {
    //         return redirect()->intended('admin/dashboard');
    //     } elseif ($user->role === 'staff') {
    //         return redirect()->intended('/staff/dashboard');
    //     }
    //     Auth::logout();
    //     abort(403, 'Access denied');
    // }


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
            'email' => 'required|email',
            'otp' => 'required'
        ]);

        $verify = Otp::where('email', $request->email)->first();

        if (!$verify) {
            return response()->json(['message' => 'Email not found'], 404);
        }

        if ($verify->otp != $request->otp) {
            return response()->json(['message' => 'Invalid OTP'], 400);
        }

        if (now()->isAfter($verify->expires_at)) {
            return response()->json(['message' => 'OTP expired'], 400);
        }

        $user = user::firstOrCreate(
            ['email' => $request->email],
            ['first_name' => preg_replace('/\d+$/', '', explode('@', $request->email)[0])]
        );

        $token = $user->createToken('auth_token')->plainTextToken;


        return response()->json([
            'status' => 'Otp Verified Successfully',
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ]);
    }

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

    protected SmsService $sms;
    public function __construct(SmsService $sms)
    {
        $this->sms = $sms;
    }
    public function send(Request $request)
    {
        $request->validate([
            'phone'   => 'required|string',
            'message' => 'required|string|max:160',
        ]);

        // ផ្ញើ SMS ជាភាសាខ្មែរបានដែរ ✅
        $success = $this->sms->send(
            $request->phone,
            $request->message
        );

        if ($success) {
            return response()->json([
                'status'  => 'success',
                'message' => 'SMS បានផ្ញើដោយជោគជ័យ!',
            ]);
        }

        return response()->json([
            'status'  => 'error',
            'message' => 'ផ្ញើ SMS មិនបានទេ!',
        ], 500);
    }
    public function sendSms(Request $request)
    {
        $request->validate([
            'phone' => 'required|string'
        ]);

        $phone = $request->phone;

        $code = random_int(100000, 999999);
        $msg  = "លេខកូដ OTP: {$code} (មានសុពលភាព 5 នាទី)";

        $this->sms->send($phone, $msg);

        Cache::put("otp_{$phone}", $code, now()->addMinutes(5));

        return response()->json([
            'status' => 'success'
        ]);
    }

    public function verifySms(Request $request)
    {
        $otp = Otp::where('phone', $request->phone)
            ->where('otp', $request->otp)
            ->where('expires_at', '>', now())
            ->first();

        if (!$otp) {
            return response()->json([
                'message' => 'Invalid OTP'
            ], 400);
        }

        $otp->delete();

        return response()->json([
            'message' => 'Verified'
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
            'first_name'      => 'nullable|string|max:255',
            'last_name'       => 'nullable|string|max:255',
            'email'           => 'nullable|email|unique:users,email,' . $user->id,
            'phone'           => 'nullable|string|max:20',
            'facebook_id'     => 'nullable|string|max:255',
            'remember_token'  => 'nullable|string|max:100',
            'avatar'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Keep old data if field is not sent
        $data = [
            'first_name' => $request->filled('first_name')
                ? $request->first_name
                : $user->first_name,

            'last_name' => $request->filled('last_name')
                ? $request->last_name
                : $user->last_name,

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
                'first_name'     => $user->first_name,
                'last_name'      => $user->last_name,
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
}
