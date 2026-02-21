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

class AuthController extends Controller
{
    public function index() {}

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
            return redirect()->intended('/admin/dashboard');
        }
        elseif ($user->role === 'staff') {
             return redirect()->intended('/staff/dashboard');
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

        Mail::raw("Your OTP Code is: $otp", function ($message) use ($request) {
            $message->to($request->email)
                ->subject('Your OTP Code');
        });

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
            'access_token' => $token,
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
}
