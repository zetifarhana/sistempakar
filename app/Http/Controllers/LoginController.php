<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    public function login()
    {
        $user = Auth::user();
        if ($user) {
            // Redirect otomatis berdasarkan level
            if ($user->level === 'superadmin') {
                return redirect()->route('user.index'); // misalnya ke data user
            } elseif ($user->level === 'admin') {
                return redirect()->route('dashboard');
            }
        }
        return view('login');
    }

    public function proses_login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $credential = $request->only('username', 'password');

        if (Auth::attempt($credential)) {
            $user = Auth::user();

            // Redirect berdasarkan level user
            if ($user->level === 'superadmin') {
                return redirect()->route('dashboard');
            } elseif ($user->level === 'admin') {
                return redirect()->route('dashboard');
            } else {
                Auth::logout(); // jika level tidak dikenali
                return redirect()->route('login')->withErrors([
                    'login_gagal' => 'Level user tidak dikenali.'
                ]);
            }
        }

        return redirect()->route('login')
            ->withInput()
            ->withErrors(['login_gagal' => 'Username atau password yang Anda masukkan salah']);
    }
        public function logout(Request $request)
    {
        Auth::logout();

        // Kosongkan session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Berhasil logout');
    }
}
