<?php

namespace Tests\Feature;

use App\Models\RejectionAutopsy;
use App\Models\SalarySimulation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Phase6FeaturesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['services.openai.fallback_mock' => true]);
    }

    public function test_salary_simulation_runs(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)->post('/salary-simulator/start', [
            'target_position' => 'Backend Developer',
            'scenario' => 'first_offer',
            'expected_salary' => '8.000.000',
            'user_answer' => 'Terima kasih, berdasarkan pengalaman dan kontribusi saya, saya berharap di kisaran 8 juta.',
        ])->assertRedirect();

        $sim = SalarySimulation::where('user_id', $user->id)->firstOrFail();
        $this->assertGreaterThan(0, $sim->score);
        $this->actingAs($user)->get(route('salary.show', $sim))->assertOk()->assertSee('Skor Negosiasi');
    }

    public function test_rejection_autopsy_runs(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)->post('/rejection-autopsy/analyze', [
            'rejection_type' => 'failed_hr_interview',
            'story' => 'Saya gagal di interview HR setelah ditanya kenapa resign dari tempat lama, jawaban saya kurang meyakinkan.',
        ])->assertRedirect();

        $autopsy = RejectionAutopsy::where('user_id', $user->id)->firstOrFail();
        $this->assertNotEmpty($autopsy->possible_causes);
        $this->actingAs($user)->get(route('rejection.show', $autopsy))->assertOk()->assertSee('Action Plan 7 Hari');
    }

    public function test_social_audit_returns_inline_result(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)->post('/social-audit/check', [
            'linkedin_bio' => 'Fresh graduate informatika yang suka ngoding.',
            'target_role' => 'Backend Developer',
        ])->assertOk()->assertSee('Branding Score');
    }

    public function test_first_90_days_returns_inline_result(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)->post('/first-90-days/generate', [
            'position' => 'Backend Developer',
            'industry' => 'Teknologi',
        ])->assertOk()->assertSee('Minggu Pertama');
    }

    public function test_salary_isolated_per_user(): void
    {
        $owner = User::factory()->create(['role' => 'user']);
        $intruder = User::factory()->create(['role' => 'user']);
        $sim = SalarySimulation::create([
            'user_id' => $owner->id, 'target_position' => 'X', 'scenario' => 'first_offer', 'score' => 60,
        ]);

        $this->actingAs($intruder)->get(route('salary.show', $sim))->assertForbidden();
    }
}
