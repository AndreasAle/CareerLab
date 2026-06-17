<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\AiPromptSeeder;
use Database\Seeders\PlanSeeder;
use Database\Seeders\TemplateSeeder;
use Database\Seeders\TestimonialSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminRenderTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_admin_pages_render(): void
    {
        $this->seed([PlanSeeder::class, AiPromptSeeder::class, TemplateSeeder::class, TestimonialSeeder::class]);
        $admin = User::factory()->create(['role' => 'admin']);

        foreach ([
            '/admin', '/admin/users', '/admin/plans', '/admin/plans/create', '/admin/orders',
            '/admin/templates', '/admin/templates/create', '/admin/ai-prompts', '/admin/ai-logs',
            '/admin/blog', '/admin/blog/create', '/admin/testimonials', '/admin/consultations',
        ] as $url) {
            $this->actingAs($admin)->get($url)->assertOk();
        }
    }

    public function test_coach_pages_render(): void
    {
        $coach = User::factory()->create(['role' => 'coach']);
        $this->actingAs($coach)->get('/coach')->assertOk();
        $this->actingAs($coach)->get('/coach/consultations')->assertOk();
    }

    public function test_user_order_and_consultation_pages_render(): void
    {
        $this->seed(PlanSeeder::class);
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user)->get('/orders')->assertOk();
        $this->actingAs($user)->get('/consultation')->assertOk();
    }
}
