<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('login', function() {
    return view('auth.login');
});

Route::get('home', function() {
    return view('admin.dashboard');
})->middleware('auth');
