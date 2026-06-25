<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        /** @var User $user */
        $user = Auth::user();

        if ($user->can('access_admin_panel')) {
            return redirect()->route('admin.dashboard');
        }

        abort(403, 'Access denied');
    }
}