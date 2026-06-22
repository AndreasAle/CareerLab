<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\EmailVerificationService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class VerifyCodeController extends Controller
{
    public function __construct(protected EmailVerificationService $service)
    {
    }

    /** Show the OTP entry page (verification.notice). */
    public function notice(Request $request)
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard'));
        }

        // First arrival with no code yet -> send one.
        if ($this->service->sendsUsed($user) === 0) {
            $this->service->sendCode($user);
        }

        return view('auth.verify-code', [
            'email' => $user->email,
            'remaining' => $this->service->sendsRemaining($user),
            'maxSends' => EmailVerificationService::MAX_SENDS,
        ]);
    }

    /** Validate a submitted code. */
    public function verify(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string'],
        ]);

        $user = $request->user();
        $code = preg_replace('/\D/', '', $request->input('code')); // keep digits only

        $result = $this->service->verify($user, $code);

        return match ($result) {
            'ok' => redirect()->intended(route('dashboard'))->with('success', 'Email berhasil diverifikasi! Selamat datang 🎉'),
            'expired' => throw ValidationException::withMessages(['code' => 'Kode sudah kedaluwarsa. Klik "Kirim ulang" untuk kode baru.']),
            'too_many' => throw ValidationException::withMessages(['code' => 'Terlalu banyak percobaan. Minta kode baru lewat "Kirim ulang".']),
            default => throw ValidationException::withMessages(['code' => 'Kode salah. Coba periksa lagi email kamu.']),
        };
    }

    /** Resend a fresh code (limited to MAX_SENDS per cycle). */
    public function resend(Request $request)
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard'));
        }

        if (! $this->service->sendCode($user)) {
            return back()->with('error', 'Batas pengiriman email tercapai (' . EmailVerificationService::MAX_SENDS . '×). Coba lagi nanti atau hubungi support.');
        }

        return back()->with('success', 'Kode verifikasi baru sudah dikirim ke email kamu.');
    }
}
