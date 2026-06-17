<?php

namespace App\Services\AI;

use App\Models\SalarySimulation;
use App\Models\User;

class SalaryNegotiationService
{
    public const SCENARIOS = [
        'first_offer' => 'First Offer',
        'lowball_offer' => 'Lowball Offer',
        'negotiation' => 'Counter Offer',
        'final_offer' => 'Final Negotiation',
    ];

    public function __construct(
        protected AiService $ai,
        protected CareerPromptService $prompts,
    ) {
    }

    /**
     * @param  array<string,mixed>  $input
     */
    public function simulate(User $user, array $input): SalarySimulation
    {
        $rendered = $this->prompts->render('salary_negotiation', [
            'target_position' => $input['target_position'],
            'city' => $input['city'] ?? '-',
            'experience_level' => $input['experience_level'] ?? '-',
            'expected_salary' => $input['expected_salary'] ?? '-',
            'offered_salary' => $input['offered_salary'] ?? '-',
            'user_answer' => $input['user_answer'] ?? '-',
            'scenario' => $input['scenario'],
        ], [
            'system' => 'Kamu adalah HR dan salary negotiation coach. Bantu user latihan menjawab offering dan negosiasi gaji secara sopan, realistis, dan percaya diri.',
            'user' => "Posisi: {{target_position}}\nKota: {{city}}\nPengalaman: {{experience_level}}\nExpected: {{expected_salary}}\nOffered: {{offered_salary}}\nJawaban user: {{user_answer}}\nScenario: {{scenario}}\n\nJSON: score, feedback, issue[], suggested_answer, negotiation_strategy[], hr_reply.",
        ]);

        $data = $this->ai->chatJson(
            featureKey: 'salary_negotiation',
            systemPrompt: $rendered['system'],
            userPrompt: $rendered['user'],
            user: $user,
            mockFallback: fn () => $this->mock($input),
        );

        return SalarySimulation::create([
            'user_id' => $user->id,
            'target_position' => $input['target_position'],
            'city' => $input['city'] ?? null,
            'experience_level' => $input['experience_level'] ?? null,
            'expected_salary' => $input['expected_salary'] ?? null,
            'offered_salary' => $input['offered_salary'] ?? null,
            'scenario' => $input['scenario'],
            'score' => (int) ($data['score'] ?? 0),
            'ai_feedback' => $data['feedback'] ?? null,
            'suggested_answer' => $data['suggested_answer'] ?? null,
            'report_data' => $data,
        ]);
    }

    protected function mock(array $input): array
    {
        $answer = strtolower((string) ($input['user_answer'] ?? ''));
        $len = mb_strlen(trim($answer));
        $mentionsValue = str_contains($answer, 'pengalaman') || str_contains($answer, 'kontribusi') || str_contains($answer, 'value') || str_contains($answer, 'skill');
        $tooPasrah = str_contains($answer, 'terserah') || str_contains($answer, 'berapa saja') || str_contains($answer, 'ikut') || $len < 25;
        $tooAgresif = str_contains($answer, 'harus') || str_contains($answer, 'pokoknya') || str_contains($answer, 'wajib');

        $score = 60;
        $issues = [];
        if ($tooPasrah) { $score -= 20; $issues[] = 'Terlalu pasrah — kamu belum menyebut nilai/kontribusi kamu.'; }
        if ($tooAgresif) { $score -= 10; $issues[] = 'Nadanya terlalu menekan, bisa terkesan tidak fleksibel.'; }
        if ($mentionsValue) { $score += 20; }
        $score = max(20, min(95, $score));

        return [
            'score' => $score,
            'feedback' => $tooPasrah
                ? 'Kamu terlalu cepat menyerah. Sebutkan kontribusi konkret kamu sebelum menyebut angka.'
                : ($mentionsValue ? 'Bagus, kamu sudah menghubungkan angka dengan value. Tinggal jaga nada tetap sopan dan terbuka.' : 'Cukup oke, tapi perkuat dengan menyebut value dan kisaran angka yang jelas.'),
            'issue' => $issues,
            'suggested_answer' => 'Terima kasih atas penawarannya. Berdasarkan pengalaman saya di bidang ini dan kontribusi yang bisa saya berikan, saya berharap di kisaran ' . ($input['expected_salary'] ?? '[angka harapan]') . '. Apakah ada ruang untuk mendekati angka tersebut?',
            'negotiation_strategy' => [
                'Selalu mulai dengan apresiasi sebelum menawar',
                'Hubungkan angka dengan kontribusi/nilai, bukan kebutuhan pribadi',
                'Beri kisaran, bukan satu angka kaku',
                'Tutup dengan nada terbuka untuk diskusi',
            ],
            'hr_reply' => 'Oke, kami catat. Kami akan diskusikan internal dan kabari kamu soal kemungkinan penyesuaian.',
        ];
    }
}
