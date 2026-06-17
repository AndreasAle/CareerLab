<?php

namespace App\Services\AI;

use App\Models\ToxicJobScan;
use App\Models\User;

class ToxicJobService
{
    public function __construct(
        protected AiService $ai,
        protected CareerPromptService $prompts,
    ) {
    }

    public function scan(User $user, ?string $jobTitle, ?string $company, string $text): ToxicJobScan
    {
        $rendered = $this->prompts->render('toxic_job', [
            'job_description_or_story' => mb_substr($text, 0, 6000),
        ], [
            'system' => 'Kamu adalah career coach yang membantu kandidat membaca tanda-tanda red flag dari lowongan kerja atau proses interview. Jangan menuduh perusahaan secara pasti. Gunakan bahasa hati-hati: "berpotensi", "perlu diklarifikasi", "sebaiknya ditanyakan".',
            'user' => "Job description atau cerita interview:\n{{job_description_or_story}}\n\nJSON: toxicity_score, risk_level(low|medium|high), summary, warning_signs[{sign,why_it_matters,severity}], questions_to_ask_hr[], safe_conclusion, recommendation.",
        ]);

        $data = $this->ai->chatJson(
            featureKey: 'toxic_job',
            systemPrompt: $rendered['system'],
            userPrompt: $rendered['user'],
            user: $user,
            mockFallback: fn () => $this->mock($text),
        );

        return ToxicJobScan::create([
            'user_id' => $user->id,
            'job_title' => $jobTitle,
            'company_name' => $company,
            'job_description' => $text,
            'toxicity_score' => (int) ($data['toxicity_score'] ?? 0),
            'warning_signs' => $data['warning_signs'] ?? [],
            'questions_to_ask_hr' => $data['questions_to_ask_hr'] ?? [],
            'risk_level' => in_array($data['risk_level'] ?? 'low', ['low', 'medium', 'high'], true) ? $data['risk_level'] : 'low',
            'ai_raw_response' => json_encode($data + ['summary' => $data['summary'] ?? null, 'safe_conclusion' => $data['safe_conclusion'] ?? null, 'recommendation' => $data['recommendation'] ?? null], JSON_UNESCAPED_UNICODE),
        ]);
    }

    protected function mock(string $text): array
    {
        $t = strtolower($text);
        $signs = [];
        $triggers = [
            'tahan banting' => 'Bisa jadi sinyal beban kerja & tekanan berlebihan.',
            'kerja di bawah tekanan' => 'Perlu diklarifikasi seberapa intens tekanannya.',
            'gaji kompetitif' => 'Gaji tidak transparan, sebaiknya ditanyakan nominalnya.',
            'multitasking' => 'Role berpotensi tidak jelas / terlalu luas.',
            'unpaid' => 'Trial tidak dibayar adalah red flag kuat.',
            'lembur' => 'Jam kerja berpotensi tidak wajar.',
            'serabutan' => 'Tanggung jawab tidak terdefinisi dengan jelas.',
        ];
        foreach ($triggers as $kw => $why) {
            if (str_contains($t, $kw)) {
                $signs[] = ['sign' => "Menyebut \"{$kw}\"", 'why_it_matters' => $why, 'severity' => str_contains($kw, 'unpaid') ? 'high' : 'medium'];
            }
        }
        if (empty($signs)) {
            $signs[] = ['sign' => 'Tidak ada tanda toxic yang menonjol', 'why_it_matters' => 'Deskripsi terlihat cukup wajar, tetap klarifikasi hal penting saat interview.', 'severity' => 'low'];
        }

        $score = min(95, count($signs) * 22);

        return [
            'toxicity_score' => $score,
            'risk_level' => $score >= 60 ? 'high' : ($score >= 35 ? 'medium' : 'low'),
            'summary' => 'Lowongan ini bukan pasti toxic, tapi ada beberapa hal yang perlu kamu klarifikasi sebelum lanjut.',
            'warning_signs' => $signs,
            'questions_to_ask_hr' => [
                'Berapa kisaran gaji dan komponen benefit untuk posisi ini?',
                'Bagaimana pembagian tanggung jawab utama role ini sehari-hari?',
                'Bagaimana kebijakan jam kerja dan lembur di tim ini?',
            ],
            'safe_conclusion' => $score >= 60 ? 'Ada cukup banyak hal yang perlu diklarifikasi sebelum apply.' : 'Masih layak dicoba selama hal-hal di atas diklarifikasi.',
            'recommendation' => $score >= 60 ? 'Hati-hati, tanyakan detail sebelum lanjut ke tahap berikutnya.' : 'Lanjutkan dengan beberapa pertanyaan klarifikasi.',
        ];
    }
}
