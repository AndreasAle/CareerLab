<?php

namespace App\Services\AI;

use App\Models\CvReview;
use App\Models\CvUpload;

class CvReviewService
{
    public function __construct(
        protected AiService $ai,
        protected CareerPromptService $prompts,
    ) {
    }

    public function review(CvUpload $cv, string $targetPosition): CvReview
    {
        $cvText = $this->safeText($cv->extracted_text);

        $rendered = $this->prompts->render('cv_review', [
            'target_position' => $targetPosition,
            'cv_text' => $cvText,
        ], $this->fallbackPrompt());

        $data = $this->ai->chatJson(
            featureKey: 'cv_review',
            systemPrompt: $rendered['system'],
            userPrompt: $rendered['user'],
            user: $cv->user,
            mockFallback: fn () => $this->mock($targetPosition),
        );

        return CvReview::create([
            'user_id' => $cv->user_id,
            'cv_upload_id' => $cv->id,
            'target_position' => $targetPosition,
            'score' => (int) ($data['overall_score'] ?? 0),
            'ats_score' => (int) ($data['ats_score'] ?? 0),
            'hrd_first_impression' => $data['hrd_first_impression'] ?? null,
            'strengths' => $data['strengths'] ?? [],
            'weaknesses' => $data['weaknesses'] ?? [],
            'red_flags' => $data['red_flags'] ?? [],
            'improvement_suggestions' => $data['improvement_suggestions'] ?? [],
            'rewritten_summary' => $data['rewritten_summary'] ?? null,
            'missing_keywords' => $data['missing_keywords'] ?? [],
            'ai_raw_response' => json_encode($data, JSON_UNESCAPED_UNICODE),
            'status' => 'completed',
        ]);
    }

    protected function safeText(?string $text): string
    {
        $text = trim((string) $text);

        if ($text === '') {
            return '(CV kosong / teks tidak terbaca)';
        }

        // Limit size to keep token cost sane.
        return mb_substr($text, 0, 8000);
    }

    protected function fallbackPrompt(): array
    {
        return [
            'system' => 'Kamu adalah HR Consultant profesional, career coach, dan recruiter berpengalaman. '
                . 'Kamu membantu job seeker memahami bagaimana HRD membaca CV mereka. Jawaban harus jujur, praktis, '
                . 'tidak menjatuhkan, dan fokus pada perbaikan. Jangan membuat klaim pasti diterima kerja.',
            'user' => "Analisis CV berikut untuk target posisi: {{target_position}}\n\nCV Text:\n{{cv_text}}\n\n"
                . 'Berikan output JSON valid dengan kunci: overall_score, ats_score, hrd_first_impression, call_probability, '
                . 'strengths[], weaknesses[], red_flags[{title,risk_level,explanation,fix}], missing_keywords[], '
                . 'improvement_suggestions[], rewritten_summary, quick_wins[], next_steps[].',
        ];
    }

    /**
     * Realistic mock so the feature works end-to-end without an API key.
     */
    protected function mock(string $targetPosition): array
    {
        return [
            'overall_score' => 68,
            'ats_score' => 62,
            'hrd_first_impression' => "CV kamu untuk posisi {$targetPosition} terlihat rapi, tapi HRD belum langsung nangkep impact dan pencapaian konkret di 10 detik pertama.",
            'call_probability' => 'medium',
            'strengths' => [
                'Format bersih dan mudah dibaca',
                'Pengalaman relevan dengan posisi yang dituju',
                'Ada kata kunci teknis yang sesuai industri',
            ],
            'weaknesses' => [
                'Bullet point masih deskripsi tugas, belum menonjolkan hasil/angka',
                'Summary di atas terlalu umum',
                'Belum ada metrik pencapaian yang terukur',
            ],
            'red_flags' => [
                [
                    'title' => 'Gap kerja tidak dijelaskan',
                    'risk_level' => 'medium',
                    'explanation' => 'Ada jeda waktu antar pekerjaan yang bisa memicu pertanyaan HRD.',
                    'fix' => 'Tambahkan keterangan singkat seperti kursus, freelance, atau proyek pribadi di periode tersebut.',
                ],
            ],
            'missing_keywords' => ['stakeholder management', 'KPI', 'data-driven'],
            'improvement_suggestions' => [
                'Ubah bullet menjadi format: Action + Hasil + Angka',
                'Tambahkan ringkasan profil 2-3 kalimat yang spesifik ke posisi',
                'Sisipkan 3-5 keyword dari deskripsi lowongan target',
            ],
            'rewritten_summary' => "Profesional di bidang {$targetPosition} dengan rekam jejak meningkatkan efisiensi proses dan kolaborasi lintas tim. Terbiasa bekerja berbasis data untuk mendukung keputusan bisnis.",
            'quick_wins' => [
                'Tambahkan angka pada minimal 3 bullet pengalaman',
                'Perbaiki email menjadi versi profesional',
            ],
            'next_steps' => [
                'Generate Career Diagnosis Report',
                'Latihan interview berdasarkan CV ini',
            ],
        ];
    }
}
