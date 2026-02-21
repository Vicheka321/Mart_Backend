<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        // ❗ Check login
        if (!Auth::check()) {
            return redirect('/login');
        }

        // ❗ Check role
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}



// php artisan make:middleware IsAdmin
