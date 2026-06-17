<?php

namespace Tests\Feature;

use App\Models\InterviewSession;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InterviewTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['services.openai.fallback_mock' => true]);
    }

    public function test_user_can_run_a_full_interview(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        // Start -> creates session + opening AI message.
        $this->actingAs($user)->post('/interview/start', [
            'target_position' => 'Backend Developer',
            'hrd_mode' => 'friendly',
            'difficulty' => 'normal',
        ])->assertRedirect();

        $session = InterviewSession::where('user_id', $user->id)->firstOrFail();
        $this->assertSame(1, $session->messages()->where('sender', 'ai_hrd')->count());

        // Answer a question -> user msg scored + new AI msg.
        $this->actingAs($user)
            ->post("/interview/{$session->id}/message", ['message' => 'Saya lulusan informatika dengan pengalaman membangun API Laravel dan meningkatkan performa query hingga 30%.'])
            ->assertRedirect(route('interview.show', $session));

        $answer = $session->messages()->where('sender', 'user')->first();
        $this->assertNotNull($answer->score);

        // Finish -> final report.
        $this->actingAs($user)->post("/interview/{$session->id}/finish")
            ->assertRedirect(route('interview.show', $session));

        $session->refresh();
        $this->assertSame('completed', $session->status);
        $this->assertNotNull($session->final_score);
        $this->assertIsArray($session->report_data);

        $this->actingAs($user)->get(route('interview.show', $session))
            ->assertOk()->assertSee('Laporan Akhir Interview');
    }

    public function test_user_cannot_access_others_interview(): void
    {
        $owner = User::factory()->create(['role' => 'user']);
        $intruder = User::factory()->create(['role' => 'user']);
        $session = $owner->interviewSessions()->create([
            'target_position' => 'QA', 'hrd_mode' => 'strict', 'difficulty' => 'hard', 'status' => 'active',
        ]);

        $this->actingAs($intruder)->get(route('interview.show', $session))->assertForbidden();
    }
}
