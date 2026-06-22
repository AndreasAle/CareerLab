<?php

namespace Tests\Feature;

use App\Mail\VerificationCodeMail;
use App\Models\User;
use App\Services\EmailVerificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class EmailVerificationCodeTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_sends_code_and_redirects_to_verify_page(): void
    {
        Mail::fake();

        $this->post('/register', [
            'name' => 'Budi',
            'email' => 'budi@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ])->assertRedirect(route('verification.notice'));

        $user = User::where('email', 'budi@example.com')->firstOrFail();
        $this->assertFalse($user->hasVerifiedEmail());
        $this->assertDatabaseHas('email_verification_codes', ['user_id' => $user->id, 'sent_count' => 1]);
        Mail::assertSent(VerificationCodeMail::class);
    }

    public function test_unverified_user_is_redirected_from_dashboard(): void
    {
        $user = User::factory()->unverified()->create(['role' => 'user']);
        $this->actingAs($user)->get('/dashboard')->assertRedirect(route('verification.notice'));
    }

    public function test_correct_code_verifies_and_unlocks_dashboard(): void
    {
        Mail::fake();
        $user = User::factory()->unverified()->create(['role' => 'user']);

        app(EmailVerificationService::class)->sendCode($user);

        $code = null;
        Mail::assertSent(VerificationCodeMail::class, function ($mail) use (&$code, $user) {
            if ($mail->hasTo($user->email)) { $code = $mail->code; return true; }
            return false;
        });

        $this->actingAs($user)->post('/verify-email', ['code' => $code])
            ->assertRedirect(route('dashboard'));

        $this->assertTrue($user->fresh()->hasVerifiedEmail());
        $this->assertDatabaseMissing('email_verification_codes', ['user_id' => $user->id]);
    }

    public function test_wrong_code_is_rejected(): void
    {
        Mail::fake();
        $user = User::factory()->unverified()->create(['role' => 'user']);
        app(EmailVerificationService::class)->sendCode($user);

        $this->actingAs($user)->from(route('verification.notice'))
            ->post('/verify-email', ['code' => '000000'])
            ->assertRedirect(route('verification.notice'))
            ->assertSessionHasErrors('code');

        $this->assertFalse($user->fresh()->hasVerifiedEmail());
    }

    public function test_resend_is_capped_at_five(): void
    {
        Mail::fake();
        $user = User::factory()->unverified()->create(['role' => 'user']);
        $service = app(EmailVerificationService::class);

        for ($i = 0; $i < 5; $i++) {
            $this->assertTrue($service->sendCode($user), "send #" . ($i + 1) . " should succeed");
        }
        // 6th send is blocked.
        $this->assertFalse($service->sendCode($user));
        $this->assertSame(0, $service->sendsRemaining($user));
    }

    public function test_verified_user_skips_verification(): void
    {
        $user = User::factory()->create(['role' => 'user']); // verified by default
        $this->actingAs($user)->get('/dashboard')->assertOk();
    }
}
