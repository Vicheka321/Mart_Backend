<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

Route::get('/admin', [AdminController::class, 'index']);
Route::middleware(['auth', 'is_admin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class,'admin'])->name('admin.dashboard');
    Route::get('/staff/dashboard', [DashboardController::class,'staff'])->name('staff.dashboard');

    
});

