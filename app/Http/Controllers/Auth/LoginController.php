<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'phone_number' => 'required',
            'password'=>'required'
        ]);

        if (Auth::attempt($credentials)) {
            if (Auth::user()->role === 'admin') {
                return redirect()->intended('/admin/dashboard');
            }
            
            Auth::logout();
            return back()->withErrors([
                'phone_number' => 'You do not have admin access.',
            ]);
        }

        return back()->withErrors([
            'phone_number' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}