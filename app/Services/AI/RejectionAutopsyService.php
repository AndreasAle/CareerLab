<?php

namespace App\Services\AI;

use App\Models\RejectionAutopsy;
use App\Models\User;

class RejectionAutopsyService
{
    public const TYPES = [
        'no_response' => 'Tidak ada respon',
        'failed_hr_interview' => 'Gagal di interview HR',
        'failed_user_interview' => 'Gagal di interview user',
        'failed_test' => 'Gagal di tes',
        'ghosted' => 'Di-ghosting',
        'offering_failed' => 'Gagal di tahap offering',
    ];

    public function __construct(
        protected AiService $ai,
        protected CareerPromptService $prompts,
    ) {
    }

    public function analyze(User $user, string $rejectionType, string $story, ?string $targetPosition): RejectionAutopsy
    {
        $rendered = $this->prompts->render('rejection_autopsy', [
            'rejection_type' => self::TYPES[$rejectionType] ?? $rejectionType,
            'story' => mb_substr($story, 0, 6000),
        ], [
            'system' => 'Kamu adalah career coach yang membantu user mengevaluasi kegagalan proses rekrutmen. Jangan menyalahkan user. Beri kemungkinan penyebab dan langkah perbaikan.',
            'user' => "Jenis rejection: {{rejection_type}}\nCerita user:\n{{story}}\n\nJSON: possible_causes[], most_likely_issue, improvement_plan[], next_action_7_days[], follow_up_template, recommended_features[].",
        ]);

        $data = $this->ai->chatJson(
            featureKey: 'rejection_autopsy',
            systemPrompt: $rendered['system'],
            userPrompt: $rendered['user'],
            user: $user,
            mockFallback: fn () => $this->mock($rejectionType),
        );

        return RejectionAutopsy::create([
            'user_id' => $user->id,
            'rejection_type' => $rejectionType,
            'story' => $story,
            'target_position' => $targetPosition,
            'possible_causes' => $data['possible_causes'] ?? [],
            'improvement_plan' => $data['improvement_plan'] ?? [],
            'next_action' => $data['next_action_7_days'] ?? [],
            'ai_raw_response' => json_encode($data, JSON_UNESCAPED_UNICODE),
        ]);
    }

    protected function mock(string $type): array
    {
        $causesByType = [
            'no_response' => ['CV belum lolos screening ATS', 'Keyword posisi belum muncul di CV', 'Lamaran terlalu generic'],
            'failed_hr_interview' => ['Jawaban perkenalan diri kurang fokus', 'Alasan melamar kurang meyakinkan', 'Kurang riset tentang perusahaan'],
            'failed_user_interview' => ['Pemahaman teknis perlu diperdalam', 'Contoh pengalaman kurang konkret', 'Kurang menunjukkan problem solving'],
            'failed_test' => ['Manajemen waktu saat tes', 'Latihan soal kurang', 'Grogi saat dikejar deadline'],
            'ghosted' => ['Proses internal perusahaan berubah', 'Posisi mungkin di-hold', 'Follow up belum dilakukan'],
            'offering_failed' => ['Ekspektasi gaji terlalu jauh dari budget', 'Negosiasi kurang fleksibel', 'Timing keputusan terlalu lama'],
        ];
        $causes = $causesByType[$type] ?? ['Beberapa faktor di luar kontrol kamu', 'Kompetisi kandidat ketat'];

        return [
            'possible_causes' => $causes,
            'most_likely_issue' => $causes[0],
            'improvement_plan' => [
                'Perbaiki titik lemah yang paling sering muncul',
                'Latihan ulang bagian yang gagal (CV/interview/tes)',
                'Minta feedback bila memungkinkan',
            ],
            'next_action_7_days' => [
                'Hari 1-2: Revisi CV & keyword',
                'Hari 3-4: Latihan interview / tes',
                'Hari 5: Kirim follow up sopan',
                'Hari 6-7: Lamar 3 posisi baru yang relevan',
            ],
            'follow_up_template' => 'Halo {Nama HR}, terima kasih atas kesempatan prosesnya. Saya tetap sangat tertarik dengan posisi {Posisi}. Jika ada update terkait hasil seleksi, saya akan senang menerimanya. Terima kasih!',
            'recommended_features' => ['cv_review', 'interview_simulator', 'job_match'],
        ];
    }
}
