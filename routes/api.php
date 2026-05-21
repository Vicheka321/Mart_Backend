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
use App\Http\Controllers\ApiController\AddressController;
use App\Http\Controllers\ApiController\TelegramController;
use App\Http\Controllers\ApiController\ProfileController;
use App\Http\Controllers\ApiController\BannerController;
use App\Http\Controllers\ApiController\CouponsController;
use App\Http\Controllers\ApiController\PaymentController;

Route::post('/send-otp', [AuthController::class, 'sendOtp'])->middleware('throttle:3,1');;
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('/send-sms', [AuthController::class, 'sendSms']);
Route::post('/verify-sms', [AuthController::class, 'verifySms']);
Route::post('/google-login', [AuthController::class, 'googleLogin']);

Route::get('/banners', [BannerController::class, 'index']);
Route::get('/categories', [CategoriesController::class, 'index']);
Route::get('/category/{id}', [CategoriesController::class, 'getProductsByCategory']);
Route::get('/brands', [BrandController::class, 'index']);
Route::get('/brand/{id}', [BrandController::class, 'getProductsByBrand']);
Route::get('/products', [ProductsController::class, 'index']);
Route::get('/product/{id}', [ProductsController::class, 'getProductById']);
Route::get('/best-sellers', [ProductsController::class, 'bestSellers']);
Route::get('/all-best-sellers', [ProductsController::class, 'allBestSellers']);
Route::get('/new-arrivals', [ProductsController::class, 'newArrivals']);
Route::get('/all-new-arrivals', [ProductsController::class, 'allNewArrivals']);
Route::get('/recommended', [ProductsController::class, 'recommended']);
Route::get('/all-recommended', [ProductsController::class, 'allrecommended']);




Route::post('/telegram/webhook', [TelegramController::class, 'handle']);
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/update-profile', [AuthController::class, 'updateProfile']);


    Route::post('/add-to-cart', [CartController::class, 'addToCart']);
    Route::get('/cart', [CartController::class, 'getCart']);
    Route::post('/update-cart', [CartController::class, 'updateCart']);
    Route::delete('/remove-cart/{product_id}', [CartController::class, 'deleteCart']);


    Route::get('/checkout', [OrdersController::class, 'checkout']);
    Route::post('/coupons/apply', [CouponsController::class, 'applyCoupon']);
    Route::post('/address', [AddressController::class, 'storeAddress']);
    Route::post('/payment/khqr', [PaymentController::class, 'generateQR']);
    Route::post('/check', [PaymentController::class, 'checkPayment']);
    Route::post('/update-order', [OrdersController::class, 'updateOrder']);
    Route::post('/status', [PaymentController::class, 'getPaymentStatus']);
    Route::post('/aba-pay',[PaymentController::class, 'ABAPay']);
    Route::post('/check-aba-pay', [PaymentController::class, 'checkStatusMD5ABA']);

    Route::post('/order', [OrdersController::class, 'placeOrder']);
    Route::post('/order/cancel/{id}', [OrdersController::class, 'cancelOrder']);
    Route::get('/orders', [OrdersController::class, 'myOrders']);


    Route::post('/favorite', [FavoriteController::class, 'addFavorite']);
    Route::get('/favorites', [FavoriteController::class, 'myFavorites']);
    Route::delete('/favorite/{product_id}', [FavoriteController::class, 'removeFavorite']);

    Route::get('/my-profile', [ProfileController::class, 'myProfile']);
    Route::post('/my-profile/update', [ProfileController::class, 'updateProfile']);
});
