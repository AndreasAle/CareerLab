<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class GoogleController extends Controller
{
    protected function configured(): bool
    {
        return filled(config('services.google.client_id')) && filled(config('services.google.client_secret'));
    }

    public function redirect()
    {
        if (! $this->configured()) {
            return redirect()->route('login')->withErrors(['email' => 'Login dengan Google belum diaktifkan. Hubungi admin.']);
        }

        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        if (! $this->configured()) {
            return redirect()->route('login');
        }

        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (Throwable $e) {
            return redirect()->route('login')->withErrors(['email' => 'Gagal login dengan Google. Coba lagi ya.']);
        }

        $user = User::where('email', $googleUser->getEmail())->first();

        if (! $user) {
            $user = User::create([
                'name' => $googleUser->getName() ?: 'Pengguna Google',
                'email' => $googleUser->getEmail(),
                'password' => Hash::make(Str::random(32)),
                'role' => 'user',
                'is_active' => true,
                // Google accounts are already email-verified.
                'email_verified_at' => now(),
            ]);
        } elseif (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        Auth::login($user, remember: true);

        return redirect()->intended(route('dashboard'));
    }
}
