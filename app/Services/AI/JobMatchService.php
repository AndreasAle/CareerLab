<?php

namespace App\Services\AI;

use App\Models\CvUpload;
use App\Models\JobMatchCheck;
use App\Models\User;

class JobMatchService
{
    public function __construct(
        protected AiService $ai,
        protected CareerPromptService $prompts,
    ) {
    }

    public function check(User $user, ?CvUpload $cv, string $jobTitle, ?string $company, string $jobDescription): JobMatchCheck
    {
        $cvText = $this->safe($cv?->extracted_text, '(CV tidak dilampirkan)');

        $rendered = $this->prompts->render('job_match', [
            'cv_text' => $cvText,
            'job_description' => mb_substr($jobDescription, 0, 6000),
            'job_title' => $jobTitle,
        ], [
            'system' => 'Kamu adalah recruiter dan career advisor. Cocokkan CV kandidat dengan job description. Jangan membuat janji diterima kerja. Beri analisis realistis dan saran praktis.',
            'user' => "CV:\n{{cv_text}}\n\nJob Description:\n{{job_description}}\n\nTarget posisi:\n{{job_title}}\n\nJSON: match_score, should_apply(yes|maybe|no), summary, matched_skills[], missing_skills[], required_keywords[], cv_changes[], suggested_cv_summary, interview_risks[], next_steps[].",
        ]);

        $data = $this->ai->chatJson(
            featureKey: 'job_match',
            systemPrompt: $rendered['system'],
            userPrompt: $rendered['user'],
            user: $user,
            mockFallback: fn () => $this->mock($jobTitle, $cvText),
        );

        return JobMatchCheck::create([
            'user_id' => $user->id,
            'cv_upload_id' => $cv?->id,
            'job_title' => $jobTitle,
            'company_name' => $company,
            'job_description' => $jobDescription,
            'match_score' => (int) ($data['match_score'] ?? 0),
            'matched_skills' => $data['matched_skills'] ?? [],
            'missing_skills' => $data['missing_skills'] ?? [],
            'required_keywords' => $data['required_keywords'] ?? [],
            'recommended_cv_changes' => $data['cv_changes'] ?? [],
            'suggested_cv_summary' => $data['suggested_cv_summary'] ?? null,
            'should_apply' => in_array($data['should_apply'] ?? 'maybe', ['yes', 'maybe', 'no'], true) ? $data['should_apply'] : 'maybe',
            'ai_raw_response' => json_encode($data, JSON_UNESCAPED_UNICODE),
        ]);
    }

    protected function safe(?string $text, string $fallback): string
    {
        $text = trim((string) $text);
        return $text === '' ? $fallback : mb_substr($text, 0, 6000);
    }

    protected function mock(string $jobTitle, string $cvText): array
    {
        $hasCv = ! str_starts_with($cvText, '(CV');
        $score = $hasCv ? 71 : 55;

        return [
            'match_score' => $score,
            'should_apply' => $score >= 70 ? 'yes' : 'maybe',
            'summary' => "CV kamu cukup relevan untuk posisi {$jobTitle}. Ada beberapa skill yang sudah cocok, tapi beberapa keyword penting belum muncul.",
            'matched_skills' => ['Komunikasi', 'Problem solving', 'Dasar teknis sesuai posisi'],
            'missing_skills' => ['Pengalaman tools spesifik', 'Sertifikasi relevan'],
            'required_keywords' => ['teamwork', 'deadline', 'analisis', 'stakeholder'],
            'cv_changes' => [
                'Tambahkan 3-4 keyword dari job desc ke bagian skill/pengalaman',
                'Tonjolkan pencapaian yang relevan dengan kebutuhan posisi ini',
            ],
            'suggested_cv_summary' => "Kandidat {$jobTitle} dengan fondasi kuat di bidangnya, terbiasa bekerja kolaboratif dan berorientasi hasil.",
            'interview_risks' => ['Kemungkinan ditanya pengalaman pakai tools spesifik yang belum kamu kuasai'],
            'next_steps' => ['Perbaiki CV untuk lowongan ini', 'Latihan interview untuk posisi ini'],
        ];
    }
}
