<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\user;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if ($user->role !== 'admin') {
            Auth::logout();
            abort(403, 'Access denied');
        }
        return redirect('/admin/dashboard');
    }
    public function dashboard()
    {
        return view('Admin.dashboard');
    }
}
