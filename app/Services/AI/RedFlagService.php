<?php

namespace App\Services\AI;

use App\Models\CvUpload;
use App\Models\RedFlagScan;
use App\Models\User;

class RedFlagService
{
    public function __construct(
        protected AiService $ai,
        protected CareerPromptService $prompts,
    ) {
    }

    /**
     * @param  array<string,mixed>  $profile  status, experience, work_gap, resign_reason, career_target
     */
    public function scan(User $user, ?CvUpload $cv, string $targetPosition, array $profile): RedFlagScan
    {
        $cvText = trim((string) ($cv?->extracted_text)) ?: '(CV tidak dilampirkan)';

        $rendered = $this->prompts->render('red_flag', [
            'profile_data' => $profile,
            'cv_text' => mb_substr($cvText, 0, 6000),
            'target_position' => $targetPosition,
        ], [
            'system' => 'Kamu adalah HR Consultant yang membantu kandidat mengidentifikasi potensi red flag dari sisi recruiter. Jangan menghakimi. Jelaskan risiko dan cara memperbaikinya secara profesional.',
            'user' => "Profil kandidat:\n{{profile_data}}\n\nCV:\n{{cv_text}}\n\nTarget posisi:\n{{target_position}}\n\nJSON: red_flag_score, risk_level(low|medium|high), candidate_red_flags[{title,why_it_matters,risk_level,safe_explanation,fix_action}], professional_reframes[{original_issue,better_wording}], action_plan[].",
        ]);

        $data = $this->ai->chatJson(
            featureKey: 'red_flag',
            systemPrompt: $rendered['system'],
            userPrompt: $rendered['user'],
            user: $user,
            mockFallback: fn () => $this->mock($profile),
        );

        return RedFlagScan::create([
            'user_id' => $user->id,
            'cv_upload_id' => $cv?->id,
            'target_position' => $targetPosition,
            'score' => (int) ($data['red_flag_score'] ?? 0),
            'risk_level' => in_array($data['risk_level'] ?? 'low', ['low', 'medium', 'high'], true) ? $data['risk_level'] : 'low',
            'candidate_red_flags' => $data['candidate_red_flags'] ?? [],
            'explanation' => $data['professional_reframes'] ?? [],
            'safe_fix_suggestions' => $data['action_plan'] ?? [],
            'ai_raw_response' => json_encode($data, JSON_UNESCAPED_UNICODE),
        ]);
    }

    protected function mock(array $profile): array
    {
        $resign = strtolower((string) ($profile['resign_reason'] ?? ''));
        $flags = [];

        if (str_contains($resign, 'toxic') || str_contains($resign, 'bos')) {
            $flags[] = [
                'title' => 'Alasan resign menyalahkan tempat kerja lama',
                'why_it_matters' => 'HRD bisa khawatir kamu akan menjelekkan perusahaan ini juga nanti.',
                'risk_level' => 'medium',
                'safe_explanation' => 'Fokuskan pada apa yang kamu cari ke depan, bukan keburukan tempat lama.',
                'fix_action' => 'Gunakan reframe profesional di bawah ini.',
            ];
        }
        if (! empty($profile['work_gap'])) {
            $flags[] = [
                'title' => 'Ada gap kerja yang belum dijelaskan',
                'why_it_matters' => 'Jeda tanpa keterangan memicu pertanyaan dan asumsi.',
                'risk_level' => 'medium',
                'safe_explanation' => 'Jelaskan apa yang kamu lakukan di periode itu (belajar, freelance, dll).',
                'fix_action' => 'Tambahkan 1 baris keterangan di CV untuk periode gap.',
            ];
        }
        if (empty($flags)) {
            $flags[] = [
                'title' => 'Profil relatif aman',
                'why_it_matters' => 'Tidak ada red flag besar yang terdeteksi, tapi tetap perkuat narasi.',
                'risk_level' => 'low',
                'safe_explanation' => 'Pastikan konsistensi cerita antara CV dan jawaban interview.',
                'fix_action' => 'Siapkan 2-3 cerita pencapaian yang konsisten.',
            ];
        }

        return [
            'red_flag_score' => count($flags) >= 2 ? 58 : 30,
            'risk_level' => count($flags) >= 2 ? 'medium' : 'low',
            'candidate_red_flags' => $flags,
            'professional_reframes' => [
                ['original_issue' => 'Resign karena bos toxic', 'better_wording' => 'Saya mencari lingkungan kerja yang lebih terstruktur dan mendukung pengembangan profesional saya.'],
            ],
            'action_plan' => [
                'Reframe semua alasan negatif jadi berorientasi masa depan',
                'Lengkapi keterangan periode gap di CV',
                'Latih jawaban dengan nada netral & profesional',
            ],
        ];
    }
}
