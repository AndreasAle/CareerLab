<?php

namespace App\Services;

use App\Mail\VerificationCodeMail;
use App\Models\EmailVerificationCode;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class EmailVerificationService
{
    public const MAX_SENDS = 5;        // total verification emails per cycle
    public const CODE_TTL_MINUTES = 15;
    public const MAX_ATTEMPTS = 8;     // wrong-code guesses before forcing a resend

    /**
     * Generate a fresh 6-digit code, persist it, and email it to the user.
     * Respects the per-cycle send limit. Returns false when the limit is hit.
     */
    public function sendCode(User $user): bool
    {
        $record = EmailVerificationCode::firstOrNew(['user_id' => $user->id]);

        if (($record->sent_count ?? 0) >= self::MAX_SENDS) {
            return false;
        }

        $code = (string) random_int(100000, 999999);

        $record->fill([
            'code_hash' => Hash::make($code),
            'expires_at' => now()->addMinutes(self::CODE_TTL_MINUTES),
            'attempts' => 0,
            'sent_count' => ($record->sent_count ?? 0) + 1,
            'last_sent_at' => now(),
        ])->save();

        try {
            Mail::to($user->email)->send(new VerificationCodeMail($user, $code));
        } catch (Throwable $e) {
            // Never block the flow on mail transport problems; the code is also logged in dev.
            Log::warning('Verification email failed to send: ' . $e->getMessage());
            Log::info("[DEV] Verification code for {$user->email}: {$code}");
        }

        return true;
    }

    /**
     * Validate a submitted code. Returns one of: 'ok', 'expired', 'invalid', 'too_many'.
     */
    public function verify(User $user, string $code): string
    {
        $record = EmailVerificationCode::where('user_id', $user->id)->first();

        if (! $record) {
            return 'invalid';
        }
        if ($record->attempts >= self::MAX_ATTEMPTS) {
            return 'too_many';
        }
        if ($record->isExpired()) {
            return 'expired';
        }

        if (! Hash::check($code, $record->code_hash)) {
            $record->increment('attempts');
            return 'invalid';
        }

        // Success: mark verified and clean up.
        if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }
        $record->delete();

        return 'ok';
    }

    public function sendsUsed(User $user): int
    {
        return (int) (EmailVerificationCode::where('user_id', $user->id)->value('sent_count') ?? 0);
    }

    public function sendsRemaining(User $user): int
    {
        return max(0, self::MAX_SENDS - $this->sendsUsed($user));
    }

    public function canResend(User $user): bool
    {
        return $this->sendsRemaining($user) > 0;
    }
}
