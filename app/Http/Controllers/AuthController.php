<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register'); // make sure you have a Blade view at resources/views/auth/register.blade.php
    }

    public function register(Request $request)
    {
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

         
        return redirect()->route('home')->with('success', 'Registration successful.');
    }

    public function showLoginForm()
    {
        return view('auth.login'); // make sure you have a Blade view at resources/views/auth/login.blade.php
    }

public function loginUser(Request $request)
{
    $credentials = $request->validate([
        'email_login' => 'required|email',
        'password' => 'required|string',
    ]);

    // Map the input field to actual DB field
    if (Auth::attempt([
        'email' => $credentials['email_login'],
        'password' => $credentials['password'],
    ])) {
        $request->session()->regenerate();
        return redirect()->route('home')->with('success', 'Login successful.');
    }

    return back()->withErrors([
        'email_login' => 'Invalid email or password.',
    ])->onlyInput('email_login');
}

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Logged out successfully.');
    }
}
