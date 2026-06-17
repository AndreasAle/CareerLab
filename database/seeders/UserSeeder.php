<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@careerlab.test'],
            [
                'name' => 'Admin CareerLab',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $coach = User::updateOrCreate(
            ['email' => 'coach@careerlab.test'],
            [
                'name' => 'Clara Coach',
                'password' => Hash::make('password'),
                'role' => 'coach',
                'headline' => 'Career Coach & Ex-HRD',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $user = User::updateOrCreate(
            ['email' => 'user@careerlab.test'],
            [
                'name' => 'Budi Job Seeker',
                'password' => Hash::make('password'),
                'role' => 'user',
                'headline' => 'Fresh Graduate - Informatika',
                'target_position' => 'Junior Backend Developer',
                'experience_level' => 'fresh_graduate',
                'city' => 'Bandung',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        UserProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'education' => 'S1',
                'major' => 'Teknik Informatika',
                'graduation_year' => '2024',
                'current_status' => 'fresh_graduate',
                'target_industry' => 'Technology',
                'target_role' => 'Backend Developer',
                'skills' => ['PHP', 'Laravel', 'MySQL', 'Git'],
            ]
        );
    }
}
