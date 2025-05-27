<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController
{
    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            if (Auth::user()->role === 'admin') {
                session()->flash('success', 'Login berhasil! Selamat datang, Admin.');
                return redirect()->route('admin.dashboard');
            }
            Auth::logout();
            return back()->withErrors(['email' => 'Unauthorized: Not an admin']);
        }

        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('admin.login');
    }
}

