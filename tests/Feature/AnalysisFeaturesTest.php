<?php

namespace Tests\Feature;

use App\Models\JobMatchCheck;
use App\Models\RedFlagScan;
use App\Models\ToxicJobScan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnalysisFeaturesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['services.openai.fallback_mock' => true]);
    }

    public function test_job_match_check_runs(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)->post('/job-match/check', [
            'job_title' => 'Backend Developer',
            'company_name' => 'Acme',
            'job_description' => 'Membutuhkan Backend Developer berpengalaman Laravel, MySQL, REST API, dan teamwork yang baik.',
        ])->assertRedirect();

        $check = JobMatchCheck::where('user_id', $user->id)->firstOrFail();
        $this->assertGreaterThan(0, $check->match_score);
        $this->actingAs($user)->get(route('job-match.show', $check))->assertOk()->assertSee('Match Score');
    }

    public function test_red_flag_scan_runs_and_reframes_resign_reason(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)->post('/red-flag/scan', [
            'target_position' => 'Developer',
            'resign_reason' => 'resign karena bos toxic',
            'work_gap' => '6 bulan',
        ])->assertRedirect();

        $scan = RedFlagScan::where('user_id', $user->id)->firstOrFail();
        $this->assertNotEmpty($scan->candidate_red_flags);
        $this->actingAs($user)->get(route('red-flag.show', $scan))->assertOk()->assertSee('Versi Profesional');
    }

    public function test_toxic_job_scan_runs(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)->post('/toxic-job/scan', [
            'job_title' => 'Staff',
            'job_description' => 'Harus tahan banting, siap kerja di bawah tekanan, gaji kompetitif, multitasking.',
        ])->assertRedirect();

        $scan = ToxicJobScan::where('user_id', $user->id)->firstOrFail();
        $this->assertGreaterThan(0, $scan->toxicity_score);
        $this->actingAs($user)->get(route('toxic-job.show', $scan))->assertOk()->assertSee('Toxicity Score');
    }

    public function test_features_are_isolated_per_user(): void
    {
        $owner = User::factory()->create(['role' => 'user']);
        $intruder = User::factory()->create(['role' => 'user']);
        $check = JobMatchCheck::create([
            'user_id' => $owner->id, 'job_title' => 'X', 'job_description' => 'y', 'match_score' => 50, 'should_apply' => 'maybe',
        ]);

        $this->actingAs($intruder)->get(route('job-match.show', $check))->assertForbidden();
    }
}
