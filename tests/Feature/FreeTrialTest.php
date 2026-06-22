<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\PlanSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FreeTrialTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['services.openai.fallback_mock' => true]);
        $this->seed(PlanSeeder::class);
    }

    public function test_free_cv_page_loads_for_guest(): void
    {
        $this->get('/cek-cv')->assertOk()->assertSee('Cek CV kamu sekarang');
    }

    public function test_guest_can_run_one_free_cv_check(): void
    {
        $this->post('/cek-cv', [
            'target_position' => 'Backend Developer',
            'manual_text' => 'Pengalaman Laravel 1 tahun, MySQL, REST API.',
        ])->assertRedirect(route('free.cv'));

        // Result is shown on the page now.
        $this->get('/cek-cv')->assertOk()->assertSee('Hasil Analisis');
    }

    public function test_sixth_free_check_is_blocked_and_redirected_to_pricing(): void
    {
        $payload = ['target_position' => 'Dev', 'manual_text' => 'CV teks contoh yang cukup panjang untuk dianalisis.'];

        // 5 free checks allowed per IP.
        for ($i = 0; $i < 5; $i++) {
            $this->post('/cek-cv', $payload)->assertRedirect(route('free.cv'));
        }
        // 6th from the same IP is blocked.
        $this->post('/cek-cv', $payload)->assertRedirect(route('pricing'));
    }

    public function test_chat_requires_a_prior_review(): void
    {
        $this->postJson('/cek-cv/chat', ['message' => 'halo'])->assertStatus(422);
    }

    public function test_chat_returns_reply_and_decrements_tokens(): void
    {
        // Need a review in session first.
        $this->post('/cek-cv', [
            'target_position' => 'Backend Developer',
            'manual_text' => 'Pengalaman Laravel, MySQL.',
        ]);

        $res = $this->postJson('/cek-cv/chat', ['message' => 'Gimana cara perbaiki summary aku?']);
        $res->assertOk()
            ->assertJsonStructure(['reply', 'tokensLeft']);

        // 3 free chat tokens -> 2 left after the first message.
        $this->assertSame(2, $res->json('tokensLeft'));
        $this->assertNotEmpty($res->json('reply'));
    }

    public function test_free_result_is_trimmed_and_locks_premium(): void
    {
        $this->post('/cek-cv', [
            'target_position' => 'Backend Developer',
            'manual_text' => 'Pengalaman Laravel, MySQL, REST API.',
        ]);

        $review = session('guest_review');
        // Teaser: max 3 weaknesses, locked counts present.
        $this->assertLessThanOrEqual(3, count($review['weaknesses'] ?? []));
        $this->assertArrayHasKey('locked', $review);
        // Premium fields are NOT exposed to guests.
        $this->assertArrayNotHasKey('rewritten_summary', $review);
        $this->assertArrayNotHasKey('missing_keywords', $review);
    }

    public function test_logged_in_user_is_redirected_to_real_cv_page(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user)->get('/cek-cv')->assertRedirect(route('cv.index'));
    }
}
