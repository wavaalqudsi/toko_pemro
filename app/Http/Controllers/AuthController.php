<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'no_hp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'role' => 'nullable|in:penjual,pembeli',
        ]);

        $user = User::create([
            'nama' => $data['nama'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'no_hp' => $data['no_hp'] ?? null,
            'alamat' => $data['alamat'] ?? null,
            'role' => $data['role'] ?? User::ROLE_PEMBELI,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        if ($user->isPenjual()) {
            return redirect()->route('penjual.toko.create')
                ->with('success', 'Registrasi berhasil! Silakan lengkapi informasi toko baru Anda.');
        }

        return redirect()->route('home')
            ->with('success', 'Registrasi berhasil! Selamat berbelanja di NAVA Market.');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function showSellerLogin()
    {
        if (Auth::check()) {
            if (Auth::user()->isPenjual()) {
                return redirect()->route('penjual.dashboard');
            }

            return redirect()->route('penjual.toko.create');
        }

        return view('auth.seller-login');
    }

    public function login(Request $request)
    {
        $inputLogin = $request->input('email') ?? $request->input('login');

        $request->merge(['email' => $inputLogin]);

        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $kolom = filter_var($inputLogin, FILTER_VALIDATE_EMAIL) ? 'email' : 'no_hp';

        if (Auth::attempt([$kolom => $inputLogin, 'password' => $request->input('password')], $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($request->boolean('seller_login') || $user->isPenjual()) {
                if (! $user->store) {
                    return redirect()->route('penjual.toko.create');
                }

                return redirect()->intended(route('penjual.dashboard'));
            }

            return redirect()->intended(route('home'));
        }

        return back()->withErrors([
            'email' => 'Email / No. Handphone atau password salah.',
        ])->onlyInput('email');
    }

    public function mulaiBerjualan()
    {
        if (! Auth::check()) {
            return redirect()->route('seller.login');
        }

        if (Auth::user()->store) {
            return redirect()->route('penjual.dashboard');
        }

        return redirect()->route('penjual.toko.create');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
