<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\ChallengeSeeder;
use Database\Seeders\PlanSeeder;
use Database\Seeders\TemplateSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardRenderTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_user_pages_render(): void
    {
        config(['services.openai.fallback_mock' => true]);
        $this->seed([PlanSeeder::class, TemplateSeeder::class, ChallengeSeeder::class]);

        $user = User::factory()->create(['role' => 'user']); // verified by default

        $pages = [
            '/dashboard', '/cv', '/interview', '/job-match', '/red-flag', '/toxic-job',
            '/salary-simulator', '/rejection-autopsy', '/social-audit', '/first-90-days',
            '/career-report', '/applications', '/templates', '/challenge', '/consultation',
            '/orders',
        ];

        foreach ($pages as $url) {
            $this->actingAs($user)->get($url)->assertOk();
        }
    }
}
