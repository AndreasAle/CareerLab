<?php

namespace Tests\Feature;

use App\Models\AiPromptTemplate;
use App\Models\ConsultationBooking;
use App\Models\Order;
use App\Models\Plan;
use App\Models\User;
use Database\Seeders\AiPromptSeeder;
use Database\Seeders\PlanSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Phase8AdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PlanSeeder::class);
    }

    public function test_user_creates_order_and_admin_approval_activates_subscription(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $admin = User::factory()->create(['role' => 'admin']);
        $plan = Plan::where('slug', 'pro')->first();

        // User creates order from pricing.
        $this->actingAs($user)->post('/orders/create', ['plan_id' => $plan->id])->assertRedirect();
        $order = Order::where('user_id', $user->id)->firstOrFail();
        $this->assertSame('unpaid', $order->payment_status);

        // Admin approves -> subscription active.
        $this->actingAs($admin)->patch("/admin/orders/{$order->id}/approve")->assertRedirect();
        $this->assertSame('paid', $order->fresh()->payment_status);
        $this->assertDatabaseHas('subscriptions', [
            'user_id' => $user->id, 'plan_id' => $plan->id, 'status' => 'active',
        ]);
        $this->assertNotNull($user->fresh()->activeSubscription());
    }

    public function test_admin_can_toggle_user_active(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $target = User::factory()->create(['role' => 'user', 'is_active' => true]);

        $this->actingAs($admin)->patch("/admin/users/{$target->id}/toggle")->assertRedirect();
        $this->assertFalse($target->fresh()->is_active);
    }

    public function test_admin_can_edit_ai_prompt_without_deploy(): void
    {
        $this->seed(AiPromptSeeder::class);
        $admin = User::factory()->create(['role' => 'admin']);
        $prompt = AiPromptTemplate::where('key', 'cv_review')->firstOrFail();

        $this->actingAs($admin)->put("/admin/ai-prompts/{$prompt->id}", [
            'name' => 'CV Review Edited',
            'system_prompt' => 'system baru',
            'user_prompt_template' => 'user baru {{cv_text}}',
            'is_active' => '1',
        ])->assertRedirect();

        $this->assertSame('CV Review Edited', $prompt->fresh()->name);
    }

    public function test_admin_plan_crud(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->post('/admin/plans', [
            'name' => 'Test Plan', 'price' => 25000, 'duration_days' => 30,
            'cv_review_limit' => 5, 'interview_limit' => 5, 'job_match_limit' => 5, 'report_limit' => 1,
            'features_text' => "Fitur A\nFitur B",
        ])->assertRedirect();

        $this->assertDatabaseHas('plans', ['name' => 'Test Plan', 'price' => 25000]);
    }

    public function test_consultation_booking_and_coach_update(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $coach = User::factory()->create(['role' => 'coach']);

        $this->actingAs($user)->post('/consultation/book', [
            'topic' => 'Review CV', 'scheduled_at' => now()->addDays(2)->format('Y-m-d H:i:s'),
        ])->assertRedirect();

        $booking = ConsultationBooking::where('user_id', $user->id)->firstOrFail();

        $this->actingAs($coach)->patch("/coach/consultations/{$booking->id}", [
            'status' => 'confirmed', 'meeting_link' => 'https://meet.example/abc',
        ])->assertRedirect();

        $booking->refresh();
        $this->assertSame('confirmed', $booking->status);
        $this->assertSame($coach->id, $booking->coach_id);
    }

    public function test_user_cannot_access_admin(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user)->get('/admin/users')->assertForbidden();
    }
}
