<?php

namespace Tests\Feature;

use App\Models\CvUpload;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SmokeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Force mock AI so tests never hit the network.
        config(['services.openai.fallback_mock' => true]);
    }

    public function test_landing_page_renders(): void
    {
        $this->get('/')->assertOk()->assertSee('Latihan Masuk Kerja');
    }

    public function test_user_reaches_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user)->get('/dashboard')->assertOk()->assertSee('Career Readiness');
    }

    public function test_admin_is_redirected_to_admin_panel(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin)->get('/dashboard')->assertRedirect('/admin');
    }

    public function test_coach_cannot_access_user_cv_pages(): void
    {
        $coach = User::factory()->create(['role' => 'coach']);
        $this->actingAs($coach)->get('/cv')->assertForbidden();
    }

    public function test_user_can_upload_and_review_cv_end_to_end(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)
            ->post('/cv/upload', ['manual_text' => 'Pengalaman: Laravel developer 1 tahun. Skill PHP, MySQL.'])
            ->assertRedirect();

        $cv = CvUpload::where('user_id', $user->id)->firstOrFail();
        $this->assertNotNull($cv->extracted_text);

        $this->actingAs($user)
            ->post("/cv/{$cv->id}/review", ['target_position' => 'Backend Developer'])
            ->assertRedirect(route('cv.review.show', $cv));

        $review = $cv->reviews()->first();
        $this->assertNotNull($review);
        $this->assertGreaterThan(0, $review->score);
        $this->assertSame('completed', $review->status);

        $this->actingAs($user)->get(route('cv.review.show', $cv))->assertOk()->assertSee('Overall Score');
    }

    public function test_user_cannot_view_another_users_cv(): void
    {
        $owner = User::factory()->create(['role' => 'user']);
        $intruder = User::factory()->create(['role' => 'user']);

        $cv = CvUpload::create([
            'user_id' => $owner->id,
            'original_filename' => 'x.txt',
            'file_path' => '',
            'extracted_text' => 'hello',
            'status' => 'completed',
        ]);

        $this->actingAs($intruder)->get(route('cv.review.show', $cv))->assertForbidden();
    }
}
