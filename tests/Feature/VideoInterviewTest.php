<?php

namespace Tests\Feature;

use App\Models\InterviewSession;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VideoInterviewTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['services.openai.fallback_mock' => true]);
    }

    public function test_starting_in_video_mode_redirects_to_video_page(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)->post('/interview/start', [
            'target_position' => 'Backend Developer',
            'hrd_mode' => 'friendly',
            'difficulty' => 'normal',
            'mode' => 'video',
        ])->assertRedirect();

        $session = InterviewSession::where('user_id', $user->id)->firstOrFail();
        $this->actingAs($user)->get(route('interview.video', $session))
            ->assertOk()->assertSee('Clara · AI HRD');
    }

    public function test_video_message_endpoint_returns_json_turn(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $session = $user->interviewSessions()->create([
            'target_position' => 'Backend Developer', 'hrd_mode' => 'friendly', 'difficulty' => 'normal', 'status' => 'active',
        ]);

        $res = $this->actingAs($user)->postJson(route('interview.video.message', $session), [
            'message' => 'Saya punya pengalaman Laravel 2 tahun dan meningkatkan performa API 30%.',
        ]);

        $res->assertOk()->assertJsonStructure(['answer_score', 'feedback', 'ai_message', 'is_ready_to_finish', 'answered']);
        $this->assertNotEmpty($res->json('ai_message'));
    }

    public function test_video_page_is_owner_only(): void
    {
        $owner = User::factory()->create(['role' => 'user']);
        $intruder = User::factory()->create(['role' => 'user']);
        $session = $owner->interviewSessions()->create([
            'target_position' => 'QA', 'hrd_mode' => 'strict', 'difficulty' => 'hard', 'status' => 'active',
        ]);

        $this->actingAs($intruder)->get(route('interview.video', $session))->assertForbidden();
    }
}
