<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function index()
    {

        return view('login.index');
    }

    public function auth(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8'
        ], [
            'email.required' => 'Email Tidak Boleh Kosong!',
            'email.email' => 'Format Email Salah!',
            'password.required' => 'Password Tidak Boleh Kosong!',
            'password.min' => 'Password Minimal 8 Karakter!'
        ]);

        // Cari pengguna berdasarkan email
        $user = \App\Models\User::where('email', $request->email)->first();

        // Cek apakah email tidak terdaftar
        if (!$user) {
            return back()->withErrors([
                'email' => 'Email tidak terdaftar',
            ])->onlyInput('email');
        }

        // Cek apakah password salah
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'password' => 'Password salah',
            ])->onlyInput('email');
        }


        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            if (Auth::user()->role == 'siswa') {
                return redirect()->intended('/');
            }

            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'Autentifikasi Gagal',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
