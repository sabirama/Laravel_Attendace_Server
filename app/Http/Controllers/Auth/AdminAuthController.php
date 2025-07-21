<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('dashboard');
        }
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required', // can be email or username
            'password' => 'required',
        ]);

        $remember = $request->filled('remember');

        $loginType = filter_var($request->input('login'), FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

        $credentials = [
            $loginType => $request->input('login'),
            'password' => $request->input('password'),
        ];

        if (Auth::guard('admin')->attempt($credentials, $remember)) {
            $request->session()->regenerate(); // Always regenerate after login (recommended for security)
            return redirect()->intended('dashboard');
        }

        return back()->with('error', 'The provided credentials do not match our records.');
    }


    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    public function showRegisterForm()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('dashboard');
        }
        return view('admin.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:admins,name',
            'email' => 'required|email|max:255|unique:admins,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $admin = Admin::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        Auth::guard('admin')->login($admin);

        return redirect()->intended('dashboard');
    }
}
