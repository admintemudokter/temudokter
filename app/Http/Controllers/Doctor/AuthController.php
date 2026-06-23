<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('doctor.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::guard('doctor')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('doctor.dashboard')
                ->with('success', 'Selamat datang, ' . Auth::guard('doctor')->user()->name . '!');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('doctor')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('doctor.login')->with('success', 'Berhasil logout.');
    }
}
