<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BrandsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;

Route::get('/', function () {
    return view('Auth.login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/admin', [AdminController::class, 'index']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::middleware(['auth', 'is_admin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');
  

    Route::get('/admin/products', [ProductController::class, 'index'])
        ->name('products.index');
    Route::post('/admin/products', [ProductController::class, 'store'])
        ->name('products.store');
    Route::put('/admin/products/{product}', [ProductController::class, 'update'])
        ->name('products.update');
    Route::delete('/admin/products/{product}', [ProductController::class, 'destroy'])
        ->name('products.destroy');

    Route::get('/admin/category', [CategoryController::class, 'index'])
        ->name('categories.index');
    Route::post('/admin/category/store', [CategoryController::class, 'store'])
        ->name('categories.store');
    Route::put('/admin/category/{category}', [CategoryController::class, 'update'])
        ->name('categories.update');
    Route::delete('/admin/category/{category}', [CategoryController::class, 'destroy'])
        ->name('categories.destroy');

    Route::get('/admin/brands', [BrandsController::class, 'index'])
        ->name('brands.index');
    Route::post('/admin/brands/store', [BrandsController::class, 'store'])
        ->name('brands.store');
    Route::put('/admin/brands/{brand}', [BrandsController::class, 'update'])
        ->name('brands.update');
    Route::delete('/admin/brands/{brand}', [BrandsController::class, 'destroy'])
        ->name('brands.destroy');
});



Route::middleware(['auth', 'is_staff'])->group(function () {

    Route::get('/staff/dashboard', [DashboardController::class, 'staff'])
        ->name('staff.dashboard');

});
