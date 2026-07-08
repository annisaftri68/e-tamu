<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('login');
    }

    public function prosesLogin(Request $request)
    {
        // 1. Validasi input form
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // 2. Coba login menggunakan 'username' ATAU 'nip'
        $field = filter_var($credentials['username'], FILTER_VALIDATE_INT) ? 'nip' : 'username';

        if (Auth::attempt([$field => $credentials['username'], 'password' => $credentials['password']])) {
            // Jika sukses, buat ulang session baru demi keamanan
            $request->session()->regenerate();
            return redirect()->route('dashboard');
        }

        // 3. Jika gagal, kembalikan dengan pesan error nyata
        return back()->withErrors([
            'loginError' => 'Username/NIP atau Kata Sandi yang Anda masukkan salah!',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}