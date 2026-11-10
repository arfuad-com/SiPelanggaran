<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user()->role);
        }

        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'nip' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'nip.required' => 'Nomor Induk Pegawai (NIP) wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        $remember = $request->boolean('remember');

        if (! Auth::attempt(['nip' => $credentials['nip'], 'password' => $credentials['password'], 'is_active' => true], $remember)) {
            throw ValidationException::withMessages([
                'nip' => 'NIP atau kata sandi yang Anda masukkan salah, atau akun Anda nonaktif.',
            ]);
        }

        $request->session()->regenerate();

        return $this->redirectBasedOnRole(Auth::user()->role)
            ->with('status', 'Selamat datang kembali, '.Auth::user()->name.'!');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('status', 'Anda telah berhasil keluar dari sistem.');
    }

    private function redirectBasedOnRole(string $role): RedirectResponse
    {
        return match ($role) {
            'admin' => redirect()->route('admin.dashboard'),
            'bk' => redirect()->route('bk.dashboard'),
            'guru' => redirect()->route('guru.dashboard'),
            default => redirect()->route('login'),
        };
    }
}
