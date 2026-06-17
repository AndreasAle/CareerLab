<?php

namespace App\Services\AI;

use App\Models\InterviewMessage;
use App\Models\InterviewSession;

class InterviewService
{
    public const HRD_MODES = [
        'friendly' => 'Friendly HRD',
        'corporate' => 'Corporate HRD',
        'startup' => 'Startup HRD',
        'strict' => 'Strict HRD',
        'galak_mode' => 'HRD Galak Mode',
        'trap_question' => 'Trap Question Mode',
    ];

    public const DIFFICULTIES = ['easy' => 'Easy', 'normal' => 'Normal', 'hard' => 'Hard'];

    public function __construct(
        protected AiService $ai,
        protected CareerPromptService $prompts,
    ) {
    }

    /**
     * Opening message of an interview (no user answer yet).
     */
    public function openSession(InterviewSession $session): InterviewMessage
    {
        $data = $this->callTurn($session, null);

        return $this->storeAiMessage($session, $data, isOpening: true);
    }

    /**
     * Process a user's answer: score it, then produce HRD reaction + next question.
     */
    public function answer(InterviewSession $session, string $userAnswer): array
    {
        $userMessage = $session->messages()->create([
            'sender' => 'user',
            'message' => $userAnswer,
        ]);

        $data = $this->callTurn($session, $userAnswer);

        // Attach the AI's evaluation to the answer that was just given.
        $userMessage->update([
            'score' => isset($data['answer_score']) ? (int) $data['answer_score'] : null,
            'feedback' => $data['feedback'] ?? null,
            'meta' => [
                'detected_issue' => $data['detected_issue'] ?? [],
                'better_answer_example' => $data['better_answer_example'] ?? null,
            ],
        ]);

        $aiMessage = $this->storeAiMessage($session, $data, isOpening: false);

        return [
            'user_message' => $userMessage->fresh(),
            'ai_message' => $aiMessage,
            'is_ready_to_finish' => (bool) ($data['is_ready_to_finish'] ?? false),
        ];
    }

    /**
     * Final evaluation report for the whole interview.
     */
    public function finalize(InterviewSession $session): InterviewSession
    {
        $rendered = $this->prompts->render('interview_final', [
            'target_position' => $session->target_position,
            'hrd_mode' => self::HRD_MODES[$session->hrd_mode] ?? $session->hrd_mode,
            'conversation_history' => $this->historyText($session),
        ], $this->fallbackFinalPrompt());

        $data = $this->ai->chatJson(
            featureKey: 'interview_final',
            systemPrompt: $rendered['system'],
            userPrompt: $rendered['user'],
            user: $session->user,
            mockFallback: fn () => $this->mockFinal($session),
        );

        $session->update([
            'status' => 'completed',
            'final_score' => (int) ($data['final_score'] ?? 0),
            'feedback_summary' => $data['summary'] ?? null,
            'report_data' => $data,
        ]);

        return $session->fresh();
    }

    /* ---------------- internals ---------------- */

    protected function callTurn(InterviewSession $session, ?string $userAnswer): array
    {
        $rendered = $this->prompts->render('interview_turn', [
            'target_position' => $session->target_position,
            'hrd_mode' => self::HRD_MODES[$session->hrd_mode] ?? $session->hrd_mode,
            'difficulty' => $session->difficulty,
            'conversation_history' => $this->historyText($session),
            'user_answer' => $userAnswer ?? '(Interview baru dimulai. Sapa kandidat sesuai karakter HRD-mu lalu ajukan pertanyaan pertama.)',
        ], $this->fallbackTurnPrompt());

        return $this->ai->chatJson(
            featureKey: 'interview_turn',
            systemPrompt: $rendered['system'],
            userPrompt: $rendered['user'],
            user: $session->user,
            mockFallback: fn () => $this->mockTurn($session, $userAnswer),
        );
    }

    protected function storeAiMessage(InterviewSession $session, array $data, bool $isOpening): InterviewMessage
    {
        $reply = trim((string) ($data['hrd_reply'] ?? ''));
        $next = trim((string) ($data['next_question'] ?? ''));

        // Compose a single HRD bubble: reaction + next question.
        $content = $isOpening
            ? trim($reply . "\n\n" . $next)
            : trim($reply . "\n\n" . $next);

        if ($content === '') {
            $content = $next ?: $reply ?: 'Bisa kamu ceritakan lebih lanjut?';
        }

        return $session->messages()->create([
            'sender' => 'ai_hrd',
            'message' => $content,
            'meta' => [
                'is_ready_to_finish' => (bool) ($data['is_ready_to_finish'] ?? false),
            ],
        ]);
    }

    protected function historyText(InterviewSession $session): string
    {
        $lines = $session->messages()->get()->map(function ($m) {
            $who = $m->sender === 'ai_hrd' ? 'HRD' : 'Kandidat';
            return "{$who}: {$m->message}";
        });

        return $lines->isEmpty() ? '(belum ada percakapan)' : $lines->implode("\n");
    }

    protected function fallbackTurnPrompt(): array
    {
        return [
            'system' => 'Kamu adalah AI HRD Interviewer. Berperan sesuai mode HRD yang dipilih. Tanyakan pertanyaan interview satu per satu, beri feedback singkat, nilai jawaban, lalu lanjut ke pertanyaan berikutnya. Fokus pada kualitas jawaban, profesionalisme, kejelasan, relevansi, dan red flag.',
            'user' => "Target posisi: {{target_position}}\nMode HRD: {{hrd_mode}}\nDifficulty: {{difficulty}}\nRiwayat:\n{{conversation_history}}\n\nJawaban terakhir user:\n{{user_answer}}\n\nJSON: hrd_reply, answer_score(0-100), feedback, detected_issue[], better_answer_example, next_question, is_ready_to_finish(boolean).",
        ];
    }

    protected function fallbackFinalPrompt(): array
    {
        return [
            'system' => 'Kamu adalah career coach yang menilai simulasi interview. Berikan evaluasi akhir yang jujur, suportif, dan actionable.',
            'user' => "Target posisi: {{target_position}}\nMode HRD: {{hrd_mode}}\nPercakapan:\n{{conversation_history}}\n\nJSON: final_score, confidence_score, clarity_score, relevance_score, professionalism_score, summary, strengths[], weaknesses[], red_flag_answers[], recommended_practice[], best_answer_templates[].",
        ];
    }

    protected function mockTurn(InterviewSession $session, ?string $userAnswer): array
    {
        $userTurns = $session->messages()->where('sender', 'user')->count();
        $pos = $session->target_position;

        $questions = [
            "Coba ceritakan tentang diri kamu dan kenapa tertarik di posisi {$pos}.",
            'Apa pencapaian yang paling kamu banggakan sejauh ini? Ceritakan dampaknya.',
            'Ceritakan satu situasi sulit yang pernah kamu hadapi dan bagaimana kamu menyelesaikannya.',
            'Menurut kamu, apa kelemahan terbesar kamu dan bagaimana kamu mengatasinya?',
            'Kenapa kami harus memilih kamu dibanding kandidat lain?',
            'Ada pertanyaan yang ingin kamu sampaikan untuk kami?',
        ];
        $nextQuestion = $questions[min($userTurns, count($questions) - 1)];

        if ($userAnswer === null) {
            $greeting = match ($session->hrd_mode) {
                'galak_mode' => 'Langsung saja, waktu saya terbatas.',
                'strict' => 'Selamat datang. Saya akan menilai jawaban Anda dengan teliti.',
                'startup' => 'Halo! Santai aja ya, kita ngobrol bareng.',
                'corporate' => 'Selamat datang di sesi interview. Mari kita mulai.',
                'trap_question' => 'Selamat datang. Saya punya beberapa pertanyaan menarik untuk Anda.',
                default => 'Hai, senang ketemu kamu! Yuk kita mulai pelan-pelan.',
            };
            return [
                'hrd_reply' => $greeting,
                'answer_score' => null,
                'feedback' => null,
                'detected_issue' => [],
                'better_answer_example' => null,
                'next_question' => $questions[0],
                'is_ready_to_finish' => false,
            ];
        }

        $len = mb_strlen(trim($userAnswer));
        $score = max(40, min(90, 50 + intdiv($len, 12)));

        return [
            'hrd_reply' => $len < 40
                ? 'Jawaban kamu masih terlalu singkat, HRD belum dapat gambaran utuh.'
                : 'Oke, jawaban yang cukup jelas. Saya tangkap poinnya.',
            'answer_score' => $score,
            'feedback' => $len < 40
                ? 'Coba tambahkan konteks, contoh konkret, dan hasil terukur (angka).'
                : 'Bagus, sudah ada struktur. Akan lebih kuat kalau ditambah metrik hasil.',
            'detected_issue' => $len < 40 ? ['terlalu singkat', 'kurang contoh konkret'] : [],
            'better_answer_example' => 'Gunakan struktur STAR: Situasi, Tugas, Aksi, Hasil — dan akhiri dengan angka/dampak.',
            'next_question' => $nextQuestion,
            'is_ready_to_finish' => $userTurns >= 5,
        ];
    }

    protected function mockFinal(InterviewSession $session): array
    {
        $userMsgs = $session->messages()->where('sender', 'user')->get();
        $avg = $userMsgs->whereNotNull('score')->avg('score');
        $final = (int) round($avg ?: 65);

        return [
            'final_score' => $final,
            'confidence_score' => min(100, $final + 5),
            'clarity_score' => $final,
            'relevance_score' => max(0, $final - 3),
            'professionalism_score' => min(100, $final + 8),
            'summary' => "Secara keseluruhan kamu cukup siap untuk posisi {$session->target_position}. Jawaban kamu jelas, tapi masih bisa lebih kuat dengan contoh terukur.",
            'strengths' => ['Komunikasi cukup jelas', 'Sikap profesional terjaga', 'Menjawab relevan dengan pertanyaan'],
            'weaknesses' => ['Beberapa jawaban kurang spesifik', 'Belum konsisten menyebut hasil terukur'],
            'red_flag_answers' => [],
            'recommended_practice' => ['Latih jawaban dengan metode STAR', 'Siapkan 3 cerita pencapaian dengan angka'],
            'best_answer_templates' => [
                'Saya pernah {situasi}. Tugas saya {tugas}. Saya {aksi}, hasilnya {angka/dampak}.',
            ],
        ];
    }
}
