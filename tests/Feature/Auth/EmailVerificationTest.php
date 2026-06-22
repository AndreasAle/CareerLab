<?php

namespace Tests\Feature\Auth;

use App\Mail\VerificationCodeMail;
use App\Models\User;
use App\Services\EmailVerificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_email_verification_screen_can_be_rendered(): void
    {
        Mail::fake();
        $user = User::factory()->unverified()->create();

        $this->actingAs($user)->get('/verify-email')->assertStatus(200);
    }

    public function test_email_can_be_verified_with_otp_code(): void
    {
        Mail::fake();
        $user = User::factory()->unverified()->create();

        app(EmailVerificationService::class)->sendCode($user);

        $code = null;
        Mail::assertSent(VerificationCodeMail::class, function ($mail) use (&$code, $user) {
            if ($mail->hasTo($user->email)) { $code = $mail->code; return true; }
            return false;
        });

        $this->actingAs($user)->post('/verify-email', ['code' => $code]);

        $this->assertTrue($user->fresh()->hasVerifiedEmail());
    }

    public function test_email_is_not_verified_with_invalid_code(): void
    {
        Mail::fake();
        $user = User::factory()->unverified()->create();
        app(EmailVerificationService::class)->sendCode($user);

        $this->actingAs($user)->post('/verify-email', ['code' => '111111']);

        $this->assertFalse($user->fresh()->hasVerifiedEmail());
    }
}
