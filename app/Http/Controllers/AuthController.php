<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Services\LogService;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Proses login user berdasarkan role
     */
    public function login(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Coba autentikasi user
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Update waktu login terakhir
            $user->update(['last_login_at' => now()]);

            // Log login activity
            LogService::logLogin('User logged in successfully');

            // Redirect sesuai role
            if ($user->hasRole('admin')) {
                return redirect()->route('admin.dashboard');
            } elseif ($user->hasRole('guru')) {
                return redirect()->route('teacher.dashboard');
            } elseif ($user->hasRole('siswa')) {
                return redirect()->route('student.dashboard');
            } elseif ($user->hasRole('orang_tua')) {
                return redirect()->route('parent.dashboard');
            } else {
                // Jika tidak punya role yang dikenal
                Auth::logout();
                return redirect('/login')->withErrors(['role' => 'Role pengguna tidak dikenali.']);
            }
        }

        // Jika gagal login
        return back()->withErrors([
            'email' => 'Email atau password salah. Silakan coba lagi.',
        ])->onlyInput('email');
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        // Log logout before destroying session
        if (Auth::check()) {
            LogService::logLogout('User logged out');
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('status', 'Anda telah keluar dari sistem.');
    }
}
