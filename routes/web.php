<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\BrandsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TelegramController;

Route::get('/', function () {
    return view('Auth.login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/admin', [AdminController::class, 'index']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::middleware(['auth', 'is_admin'])->group(function () {


    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');

    Route::get('/admin/banners', [BannerController::class, 'index'])->name('banners.index');
    Route::post('/admin/banners', [BannerController::class, 'store'])->name('banners.store'); 
    Route::put('/admin/banners/{banner}', [BannerController::class, 'update'])->name('banners.update');
    Route::delete('/admin/banners/{banner}', [BannerController::class, 'destroy'])->name('banners.destroy');

    Route::get('/admin/products', [ProductController::class, 'index'])->name('products.index');
    Route::post('/admin/products', [ProductController::class, 'store'])->name('products.store');
    Route::put('/admin/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/admin/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::get('/admin/products/export/csv', [ProductController::class, 'exportCSV'])->name('products.export.csv');
    Route::get('/admin/products/export/pdf', [ProductController::class, 'exportPDF'])->name('products.export.pdf');

    Route::get('/admin/category', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/admin/category/store', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/admin/category/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/admin/category/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    Route::get('/admin/category/export/csv', [CategoryController::class, 'exportCSV'])->name('categories.export.csv');
    Route::get('/admin/category/export/pdf', [CategoryController::class, 'exportPDF'])->name('categories.export.pdf');

    Route::get('/admin/brands', [BrandsController::class, 'index'])->name('brands.index');
    Route::post('/admin/brands/store', [BrandsController::class, 'store'])->name('brands.store');
    Route::put('/admin/brand/{brand}', [BrandsController::class, 'update'])->name('brands.update');
    Route::delete('/admin/brands/{brand}', [BrandsController::class, 'destroy'])->name('brands.destroy');
    Route::get('/admin/brands/export/csv', [BrandsController::class, 'exportCSV'])->name('brands.export.csv');
    Route::get('/admin/brands/export/pdf', [BrandsController::class, 'exportPDF'])->name('brands.export.pdf');

    Route::get('/admin/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/admin/orders/notifications', [OrderController::class, 'notifications']);
    Route::post('/admin/orders/{id}/status', [OrderController::class, 'changeStatus']);
    Route::post('/admin/orders/{id}/cancel', [OrderController::class, 'cancel']);
    Route::get('/admin/orders/{id}', [OrderController::class, 'show']);



    Route::get('/admin/customers', [CustomersController::class, 'customers']);
});




Route::middleware(['auth', 'is_staff'])->group(function () {

    Route::get('/staff/dashboard', [DashboardController::class, 'staff'])
        ->name('staff.dashboard');
});
