<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\StoreController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\DashboardController;

Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
});

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    
    Route::resource('products', ProductController::class)->names([
        'index' => 'admin.products.index',
        'create' => 'admin.products.create',
        'store' => 'admin.products.store',
        'edit' => 'admin.products.edit',
        'update' => 'admin.products.update',
        'destroy' => 'admin.products.destroy',
    ]);

    Route::resource('stores', StoreController::class)->names([
        'index' => 'admin.stores.index',
        'create' => 'admin.stores.create',
        'store' => 'admin.stores.store',
        'edit' => 'admin.stores.edit',
        'update' => 'admin.stores.update',
        'destroy' => 'admin.stores.destroy',
    ]);
});

Route::post('logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::resource('orders', OrderController::class)->only(['index', 'edit', 'update'])->names([
        'index' => 'admin.orders.index',
        'edit' => 'admin.orders.edit',
        'update' => 'admin.orders.update',
    ]);
});