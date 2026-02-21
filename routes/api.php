<?php


use App\Http\Controllers\AuthController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController\CategoriesController;
use App\Http\Controllers\ApiController\ProductsController;
use App\Http\Controllers\ApiController\BrandController;
use App\Http\Controllers\ApiController\CartController;
use App\Http\Controllers\ApiController\FavoriteController;
use App\Http\Controllers\ApiController\OrdersController;
use Google\Service\Adsense\Row;
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/add-to-cart', [CartController::class, 'addToCart']);
    Route::get('/card', [CartController::class, 'getCard']);
    Route::post('/update-cart', [CartController::class, 'updateCart']);
    Route::delete('/remove-cart/{product_id}', [CartController::class, 'deleteCart']);

    Route::post('/favorite', [FavoriteController::class, 'addFavorite']);
    Route::get('/favorites', [FavoriteController::class, 'myFavorites']);
    Route::delete('/favorite/{product_id}', [FavoriteController::class, 'removeFavorite']);
    
    Route::get('/checkout', [OrdersController::class, 'checkout']);
    Route::post('/orders', [OrdersController::class, 'placeOrder']);
    
});

Route::post('/send-otp', [AuthController::class, 'sendOtp']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('/google-login', [AuthController::class, 'googleLogin']);


Route::get('/categories', [CategoriesController::class, 'index']);
Route::get('/categories/{id}', [CategoriesController::class, 'getProductsByCategory']);
Route::get('/brand', [BrandController::class, 'index']);
Route::get('/brand/{id}', [BrandController::class, 'getProductById']);
Route::get('/products', [ProductsController::class, 'index']);
Route::get('/products/{id}', [ProductsController::class, 'getProductById']);







