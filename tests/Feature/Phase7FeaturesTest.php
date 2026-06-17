<?php

namespace Tests\Feature;

use App\Models\ApplicationTracker;
use App\Models\CareerReport;
use App\Models\CvUpload;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Database\Seeders\ChallengeSeeder;
use Database\Seeders\PlanSeeder;
use Database\Seeders\TemplateSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class Phase7FeaturesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['services.openai.fallback_mock' => true]);
        $this->seed(PlanSeeder::class);
    }

    private function paidUser(): User
    {
        $user = User::factory()->create(['role' => 'user']);
        $plan = Plan::where('slug', 'pro')->first();
        Subscription::create([
            'user_id' => $user->id, 'plan_id' => $plan->id,
            'starts_at' => now(), 'ends_at' => now()->addDays(30), 'status' => 'active',
        ]);
        return $user;
    }

    public function test_career_report_generates_pdf(): void
    {
        $user = $this->paidUser();
        // Needs at least one CV review.
        $cv = CvUpload::create([
            'user_id' => $user->id, 'original_filename' => 'cv.txt', 'file_path' => '',
            'extracted_text' => 'Pengalaman Laravel', 'status' => 'completed',
        ]);
        $cv->reviews()->create([
            'user_id' => $user->id, 'target_position' => 'Backend Dev', 'score' => 70, 'ats_score' => 65, 'status' => 'completed',
        ]);

        $this->actingAs($user)->post('/career-report/generate')->assertRedirect();

        $report = CareerReport::where('user_id', $user->id)->firstOrFail();
        $this->assertNotNull($report->pdf_path);
        $this->assertTrue(Storage::disk('local')->exists($report->pdf_path));

        $this->actingAs($user)->get(route('career-report.download', $report))->assertOk();
    }

    public function test_career_report_requires_cv_review(): void
    {
        $user = $this->paidUser();
        $this->actingAs($user)->post('/career-report/generate')
            ->assertRedirect()->assertSessionHas('warning');
    }

    public function test_application_tracker_crud_and_stats(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)->post('/applications', [
            'company_name' => 'Acme', 'position' => 'Dev', 'status' => 'offering',
        ])->assertRedirect();

        $app = ApplicationTracker::where('user_id', $user->id)->firstOrFail();

        $this->actingAs($user)->patch("/applications/{$app->id}/status", ['status' => 'accepted'])->assertRedirect();
        $this->assertSame('accepted', $app->fresh()->status);

        $this->actingAs($user)->get('/applications')->assertOk()->assertSee('Conversion');

        $this->actingAs($user)->delete("/applications/{$app->id}")->assertRedirect();
        $this->assertDatabaseMissing('application_trackers', ['id' => $app->id]);
    }

    public function test_templates_page_loads(): void
    {
        $this->seed(TemplateSeeder::class);
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user)->get('/templates')->assertOk()->assertSee('Template Library');
    }

    public function test_challenge_toggle_tracks_progress(): void
    {
        $this->seed(ChallengeSeeder::class);
        $user = User::factory()->create(['role' => 'user']);

        $task = \App\Models\ChallengeTask::first();
        $this->actingAs($user)->post("/challenge/task/{$task->id}/toggle")->assertRedirect();

        $this->assertDatabaseHas('user_challenge_progress', [
            'user_id' => $user->id, 'challenge_task_id' => $task->id, 'status' => 'completed',
        ]);

        // Toggle off.
        $this->actingAs($user)->post("/challenge/task/{$task->id}/toggle")->assertRedirect();
        $this->assertDatabaseHas('user_challenge_progress', [
            'user_id' => $user->id, 'challenge_task_id' => $task->id, 'status' => 'pending',
        ]);
    }
}
