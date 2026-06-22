<?php

namespace App\Http\Controllers;

use App\Services\AI\CareerChatService;
use App\Services\AI\CvReviewService;
use App\Services\GuestTrialService;
use App\Services\PdfTextExtractor;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class FreeTrialController extends Controller
{
    public function index(Request $request, GuestTrialService $guest)
    {
        if ($request->user()) {
            return redirect()->route('cv.index');
        }

        return view('public.free-cv', [
            'review' => session('guest_review'),
            'position' => session('guest_position'),
            'canCheck' => $guest->canCheckCv(),
            'checksLeft' => $guest->checksRemaining(),
            'maxChecks' => GuestTrialService::FREE_CV_CHECKS,
            'resetAt' => $guest->cvResetAt(),
            'chatHistory' => session('guest_chat', []),
            'tokensLeft' => $guest->chatTokensRemaining(),
            'maxTokens' => GuestTrialService::FREE_CHAT_TOKENS,
        ]);
    }

    public function analyze(Request $request, GuestTrialService $guest, PdfTextExtractor $extractor, CvReviewService $service)
    {
        if ($request->user()) {
            return redirect()->route('cv.index');
        }

        if (! $guest->canCheckCv()) {
            return redirect()->route('pricing')
                ->with('upgrade', 'Free trial habis (' . GuestTrialService::FREE_CV_CHECKS . '/' . GuestTrialService::FREE_CV_CHECKS . '). Daftar / upgrade untuk cek CV tanpa batas di dashboard.');
        }

        $request->validate([
            'cv_file' => ['nullable', 'file', 'mimes:pdf', 'max:5120'],
            'manual_text' => ['nullable', 'string', 'max:20000'],
            'target_position' => ['required', 'string', 'max:150'],
        ], [
            'target_position.required' => 'Isi dulu posisi yang kamu incar.',
            'cv_file.mimes' => 'CV harus berupa file PDF.',
            'cv_file.max' => 'Ukuran CV maksimal 5MB.',
        ]);

        $text = null;
        if ($request->hasFile('cv_file')) {
            $text = $extractor->fromAbsolutePath($request->file('cv_file')->getRealPath());
        }
        if (blank($text) && filled($request->input('manual_text'))) {
            $text = $request->input('manual_text');
        }

        if (blank($text)) {
            throw ValidationException::withMessages([
                'cv_file' => 'Teks CV tidak terbaca. Coba upload PDF lain atau tempel teks CV-mu.',
            ]);
        }

        $data = $service->analyzeText($text, $request->target_position);
        $guest->recordCvCheck();

        // Free = teaser. Keep the accurate core, trim depth, lock the rest behind sign-up.
        $free = [
            'overall_score' => (int) ($data['overall_score'] ?? 0),
            'ats_score' => (int) ($data['ats_score'] ?? 0),
            'call_probability' => $data['call_probability'] ?? 'medium',
            'hrd_first_impression' => $data['hrd_first_impression'] ?? null,
            'strengths' => array_slice($data['strengths'] ?? [], 0, 3),
            'weaknesses' => array_slice($data['weaknesses'] ?? [], 0, 3),
            'red_flags' => array_slice($data['red_flags'] ?? [], 0, 1),
            // counts of what's hidden, to entice sign-up
            'locked' => [
                'weaknesses' => max(0, count($data['weaknesses'] ?? []) - 3),
                'red_flags' => max(0, count($data['red_flags'] ?? []) - 1),
                'missing_keywords' => count($data['missing_keywords'] ?? []),
                'improvements' => count($data['improvement_suggestions'] ?? []),
                'has_rewritten_summary' => ! empty($data['rewritten_summary']),
            ],
        ];

        $opener = 'Hai! Aku Clara, career coach AI kamu. Aku udah baca CV kamu untuk posisi '
            . $request->target_position . '. Mau tanya apa? (mis. "Perbaiki summary aku"). '
            . 'Kamu punya ' . GuestTrialService::FREE_CHAT_TOKENS . ' pesan gratis.';

        session([
            'guest_review' => $free,
            'guest_position' => $request->target_position,
            'guest_cv_text' => mb_substr($text, 0, 4000),
            'guest_chat' => [['role' => 'assistant', 'content' => $opener]],
        ]);

        return redirect()->route('free.cv')->with('success', 'Analisis CV selesai! Scroll ke bawah untuk lihat hasilnya.');
    }

    public function chat(Request $request, GuestTrialService $guest, CareerChatService $chat)
    {
        if ($request->user()) {
            return response()->json(['error' => 'redirect', 'url' => route('cv.index')], 403);
        }

        if (! session()->has('guest_review')) {
            return response()->json(['error' => 'no_review', 'message' => 'Cek CV kamu dulu ya.'], 422);
        }

        $validated = $request->validate(['message' => ['required', 'string', 'max:1000']]);

        if (! $guest->canChat()) {
            return response()->json([
                'error' => 'no_tokens',
                'message' => 'Free trial habis. Daftar untuk ngobrol tanpa batas & buka semua fitur.',
                'url' => route('pricing'),
            ], 402);
        }

        $history = session('guest_chat', []);
        // brief = true -> minimised free responses
        $reply = $chat->reply($validated['message'], session('guest_cv_text', ''), $history, brief: true);

        $guest->recordChatToken();

        $history[] = ['role' => 'user', 'content' => $validated['message']];
        $history[] = ['role' => 'assistant', 'content' => $reply];
        session(['guest_chat' => $history]);

        return response()->json([
            'reply' => $reply,
            'tokensLeft' => $guest->chatTokensRemaining(),
        ]);
    }
}
