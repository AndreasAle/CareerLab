<?php

namespace Database\Seeders;

use App\Models\Challenge;
use App\Models\ChallengeTask;
use Illuminate\Database\Seeder;

class ChallengeSeeder extends Seeder
{
    public function run(): void
    {
        $challenge = Challenge::updateOrCreate(
            ['title' => 'Challenge 7 Hari Siap Kerja'],
            [
                'description' => 'Tantangan 7 hari untuk menyiapkan diri masuk dunia kerja, dari CV sampai negosiasi gaji.',
                'duration_days' => 7,
                'is_active' => true,
            ]
        );

        $tasks = [
            [1, 'Upload dan review CV', 'Upload CV kamu dan lihat bagaimana HRD membacanya.', 'cv_review'],
            [2, 'Perbaiki summary CV', 'Tulis ulang summary CV berdasarkan saran AI.', 'reflection'],
            [3, 'Buat LinkedIn bio', 'Susun LinkedIn bio yang menjual value kamu.', 'linkedin_bio'],
            [4, 'Latihan interview perkenalan diri', 'Latihan menjawab "ceritakan tentang dirimu".', 'interview'],
            [5, 'Cek 1 lowongan kerja', 'Paste 1 lowongan dan cek match score-nya.', 'job_apply'],
            [6, 'Latihan salary negotiation', 'Latihan menjawab offering gaji.', 'salary_practice'],
            [7, 'Generate career report', 'Gabungkan semua hasil jadi Career Report.', 'reflection'],
        ];

        foreach ($tasks as [$day, $title, $desc, $type]) {
            ChallengeTask::updateOrCreate(
                ['challenge_id' => $challenge->id, 'day_number' => $day],
                ['title' => $title, 'description' => $desc, 'task_type' => $type]
            );
        }
    }
}
