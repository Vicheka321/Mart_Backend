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
            ['name' => preg_replace('/\d+$/', '', explode('@', $request->email)[0])] // Default name from email
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
}
