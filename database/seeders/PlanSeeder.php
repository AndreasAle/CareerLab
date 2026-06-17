<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Free',
                'slug' => 'free',
                'price' => 0,
                'duration_days' => 3650,
                'cv_review_limit' => 1,
                'interview_limit' => 1,
                'job_match_limit' => 1,
                'report_limit' => 0,
                'has_manual_review' => false,
                'has_consultation' => false,
                'features' => ['1x CV Review', '1x Job Match', '1x Interview', 'Template gratis terbatas'],
            ],
            [
                'name' => 'Starter',
                'slug' => 'starter',
                'price' => 19000,
                'duration_days' => 30,
                'cv_review_limit' => 3,
                'interview_limit' => 3,
                'job_match_limit' => 3,
                'report_limit' => 1,
                'has_manual_review' => false,
                'has_consultation' => false,
                'features' => ['3x CV Review', '3x Job Match', '3x Interview', '1 Career Report'],
            ],
            [
                'name' => 'Pro',
                'slug' => 'pro',
                'price' => 49000,
                'duration_days' => 30,
                'cv_review_limit' => 10,
                'interview_limit' => 10,
                'job_match_limit' => 10,
                'report_limit' => 5,
                'has_manual_review' => false,
                'has_consultation' => false,
                'features' => ['10x CV Review', '10x Job Match', '10x Interview', 'Red Flag Scanner', 'Toxic Job Detector', 'Career Report PDF', 'Template premium'],
            ],
            [
                'name' => 'Serious Job Seeker',
                'slug' => 'serious',
                'price' => 99000,
                'duration_days' => 7,
                'cv_review_limit' => -1,
                'interview_limit' => -1,
                'job_match_limit' => -1,
                'report_limit' => -1,
                'has_manual_review' => false,
                'has_consultation' => false,
                'features' => ['Unlimited 7 hari (fair usage)', 'Career Diagnosis PDF', 'Salary Simulator', 'First 90 Days Plan', 'Priority AI'],
            ],
            [
                'name' => 'Coach Premium',
                'slug' => 'coach-premium',
                'price' => 199000,
                'duration_days' => 30,
                'cv_review_limit' => -1,
                'interview_limit' => -1,
                'job_match_limit' => -1,
                'report_limit' => -1,
                'has_manual_review' => true,
                'has_consultation' => true,
                'features' => ['Manual CV Review', 'Konsultasi 1-on-1', 'Mock Interview', 'Semua fitur Serious'],
            ],
        ];

        foreach ($plans as $plan) {
            Plan::updateOrCreate(['slug' => $plan['slug']], $plan);
        }
    }
}
