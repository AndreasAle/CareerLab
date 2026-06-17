<?php

namespace App\Services\AI;

use App\Models\User;

class First90DaysService
{
    public function __construct(
        protected AiService $ai,
        protected CareerPromptService $prompts,
    ) {
    }

    /**
     * @return array<string,mixed>
     */
    public function generate(User $user, string $position, string $industry, ?string $experienceLevel, ?string $mainConcern): array
    {
        $rendered = $this->prompts->render('first_90_days', [
            'position' => $position,
            'industry' => $industry,
            'experience_level' => $experienceLevel ?? '-',
            'main_concern' => $mainConcern ?? '-',
        ], [
            'system' => 'Kamu adalah career coach yang membantu pekerja baru bertahan dan berkembang di 90 hari pertama kerja.',
            'user' => "Posisi: {{position}}\nIndustri: {{industry}}\nLevel: {{experience_level}}\nKekhawatiran: {{main_concern}}\n\nJSON: week_1_plan[], day_30_plan[], day_60_plan[], day_90_plan[], how_to_communicate[], how_to_ask_questions[], how_to_report_progress[], how_to_handle_toxic_senior[], success_metrics[].",
        ]);

        return $this->ai->chatJson(
            featureKey: 'first_90_days',
            systemPrompt: $rendered['system'],
            userPrompt: $rendered['user'],
            user: $user,
            mockFallback: fn () => $this->mock($position),
        );
    }

    protected function mock(string $position): array
    {
        return [
            'week_1_plan' => [
                'Kenali tim, atasan, dan alur kerja',
                'Catat semua tools & akses yang dibutuhkan',
                'Pahami ekspektasi atasan untuk 90 hari ke depan',
            ],
            'day_30_plan' => [
                'Selesaikan onboarding & quick win pertama',
                'Bangun relasi dengan rekan kunci',
                'Pahami metrik sukses peran ' . $position,
            ],
            'day_60_plan' => [
                'Mulai ambil tanggung jawab penuh di area kamu',
                'Berikan kontribusi yang terukur',
                'Minta feedback tengah jalan',
            ],
            'day_90_plan' => [
                'Tunjukkan dampak konkret dari pekerjaan kamu',
                'Diskusikan rencana pengembangan dengan atasan',
                'Set target untuk kuartal berikutnya',
            ],
            'how_to_communicate' => ['Update progress secara proaktif', 'Gunakan bahasa jelas & ringkas', 'Konfirmasi pemahaman sebelum eksekusi'],
            'how_to_ask_questions' => ['Riset dulu sebelum bertanya', 'Tanyakan secara spesifik', 'Catat jawabannya agar tidak mengulang'],
            'how_to_report_progress' => ['Format: yang selesai, sedang dikerjakan, blocker', 'Laporkan rutin (mingguan)', 'Sertakan angka bila ada'],
            'how_to_handle_toxic_senior' => ['Tetap profesional & dokumentasikan', 'Fokus pada pekerjaan, bukan drama', 'Cari mentor/sekutu yang suportif'],
            'success_metrics' => ['Quick win di 30 hari pertama', 'Feedback positif dari atasan', 'Kontribusi terukur di 90 hari'],
        ];
    }
}
