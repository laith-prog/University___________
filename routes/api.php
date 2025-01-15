<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\FavoriteController;


Route::post('auth/send-otp', [AuthController::class, 'sendOTP']);
Route::post('/auth/verify-otp', [AuthController::class, 'verifyOTP']);



Route::prefix('stores')->group(function () {
    Route::get('/search', [StoreController::class, 'search']);
    Route::get('/id/{store}', [StoreController::class, 'show']);
    Route::get('/', [StoreController::class, 'index']);
    Route::get('/category', [StoreController::class, 'getStoresByCategory']);

    Route::get('/trending', [StoreController::class, 'getTrendingStores']);

});
Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::get('/category', [ProductController::class, 'getProductsByCategory']);
    Route::get('/id/{product}', [ProductController::class, 'show']);
    Route::get('/most-selling', [ProductController::class, 'getMostSellingProducts']);
    Route::get('/trending', [ProductController::class, 'getTrendingProducts']);
    Route::get('/search', [ProductController::class, 'search']);
    

});


Route::middleware('auth:sanctum')->group(function () {

    Route::get('/profile', [AuthController::class, 'getProfile']);
    Route::post('/users/update-profile', [AuthController::class, 'updateProfile']);
    Route::post('/auth/sign-out', [AuthController::class, 'signOut']);

    Route::post('/cart/add', [CartController::class, 'addToCart']);
    Route::put('/cart/edit', [CartController::class, 'editCartItem']);



    Route::post('/order/create', [OrderController::class, 'createOrder']); // Create an order
    Route::get('/orders', [OrderController::class, 'getUserOrders']); // Get all user orders
    Route::put('/orders/{orderId}', [OrderController::class, 'editOrder']); // Edit an order
    Route::post('/orders/{orderId}/cancel', [OrderController::class, 'cancelOrder']);
    Route::get('/orders/{orderId}', [OrderController::class, 'getOrderDetails']);



    Route::post('favorites', [FavoriteController::class, 'addToFavorites']);
    Route::delete('favorites/{productId}', [FavoriteController::class, 'removeFromFavorites']);
    Route::get('favorites', [FavoriteController::class, 'getFavorites']);


});