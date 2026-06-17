<?php

namespace App\Services\AI;

use App\Models\CareerReport;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class CareerReportService
{
    public function __construct(
        protected AiService $ai,
        protected CareerPromptService $prompts,
    ) {
    }

    public function generate(User $user): CareerReport
    {
        $cvReview = $user->cvReviews()->latest()->first();
        $jobMatch = $user->jobMatchChecks()->latest()->first();
        $redFlag = $user->redFlagScans()->latest()->first();
        $interview = $user->interviewSessions()->where('status', 'completed')->latest()->first();

        $rendered = $this->prompts->render('career_report', [
            'cv_review' => $this->summ($cvReview, ['target_position', 'score', 'ats_score', 'hrd_first_impression', 'strengths', 'weaknesses']),
            'job_match' => $this->summ($jobMatch, ['job_title', 'match_score', 'should_apply', 'missing_skills']),
            'red_flag_scan' => $this->summ($redFlag, ['risk_level', 'score', 'candidate_red_flags']),
            'interview_report' => $this->summ($interview, ['target_position', 'final_score', 'feedback_summary']),
        ], [
            'system' => 'Kamu adalah career consultant. Gabungkan semua data user menjadi report yang jelas, ringkas, dan actionable. Report harus terasa premium dan personal.',
            'user' => "CV Review:\n{{cv_review}}\n\nJob Match:\n{{job_match}}\n\nRed Flag Scan:\n{{red_flag_scan}}\n\nInterview Report:\n{{interview_report}}\n\nJSON: overall_score, headline_summary, career_readiness_level, top_strengths[], top_weaknesses[], main_red_flags[], priority_fixes[], seven_day_action_plan[], fourteen_day_action_plan[], recommended_templates[], recommended_next_features[], closing_message.",
        ]);

        $data = $this->ai->chatJson(
            featureKey: 'career_report',
            systemPrompt: $rendered['system'],
            userPrompt: $rendered['user'],
            user: $user,
            mockFallback: fn () => $this->mock($user, $cvReview, $interview),
        );

        $report = CareerReport::create([
            'user_id' => $user->id,
            'cv_review_id' => $cvReview?->id,
            'job_match_check_id' => $jobMatch?->id,
            'red_flag_scan_id' => $redFlag?->id,
            'title' => 'Career Diagnosis Report — ' . now()->format('d M Y'),
            'overall_score' => (int) ($data['overall_score'] ?? 0),
            'report_data' => $data,
            'status' => 'completed',
        ]);

        $this->renderPdf($report, $user, $data);

        return $report->fresh();
    }

    protected function renderPdf(CareerReport $report, User $user, array $data): void
    {
        $pdf = Pdf::loadView('pdf.career-report', [
            'user' => $user,
            'report' => $report,
            'data' => $data,
        ])->setPaper('a4');

        $path = "reports/{$user->id}/career-report-{$report->id}.pdf";
        Storage::disk('local')->put($path, $pdf->output());

        $report->update(['pdf_path' => $path]);
    }

    protected function summ($model, array $fields): array
    {
        if (! $model) {
            return ['_note' => 'belum ada data'];
        }

        $out = [];
        foreach ($fields as $f) {
            $out[$f] = $model->{$f};
        }

        return $out;
    }

    protected function mock(User $user, $cvReview, $interview): array
    {
        $cvScore = $cvReview->score ?? 0;
        $intScore = $interview->final_score ?? 0;
        $overall = (int) round((($cvScore ?: 60) + ($intScore ?: 60)) / 2);

        return [
            'overall_score' => $overall,
            'headline_summary' => "Secara keseluruhan kamu berada di jalur yang baik untuk posisi {$user->target_position}. Beberapa perbaikan kecil bisa naikin peluang kamu signifikan.",
            'career_readiness_level' => $overall >= 75 ? 'strong' : ($overall >= 60 ? 'ready' : ($overall >= 45 ? 'developing' : 'beginner')),
            'top_strengths' => ['Komunikasi cukup jelas', 'CV rapi & relevan', 'Sikap profesional'],
            'top_weaknesses' => ['Pencapaian belum terukur (kurang angka)', 'Beberapa keyword posisi belum muncul'],
            'main_red_flags' => ['Gap kerja perlu dijelaskan dengan baik'],
            'priority_fixes' => ['Tambahkan metrik di CV', 'Latih jawaban interview dengan STAR', 'Perbaiki summary CV'],
            'seven_day_action_plan' => [
                'Hari 1-2: Revisi CV (tambah angka + keyword)',
                'Hari 3-4: Latihan interview 2 sesi',
                'Hari 5: Cek 1 lowongan dengan Job Match',
                'Hari 6: Latihan salary negotiation',
                'Hari 7: Generate report & review progress',
            ],
            'fourteen_day_action_plan' => [
                'Minggu 2: Apply ke 5 lowongan relevan',
                'Perkuat LinkedIn & portfolio',
                'Follow up lamaran yang sudah dikirim',
            ],
            'recommended_templates' => ['CV Summary', 'Follow Up HR', 'Salary Negotiation Script'],
            'recommended_next_features' => ['interview_simulator', 'job_match', 'salary_simulator'],
            'closing_message' => 'Kamu sudah selangkah lebih siap dari kebanyakan kandidat. Konsisten ya, peluang kamu terbuka lebar! 🚀',
        ];
    }
}
