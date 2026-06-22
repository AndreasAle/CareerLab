<?php

namespace App\Http\Controllers;

use App\Http\Requests\InterviewStartRequest;
use App\Models\InterviewSession;
use App\Services\AI\InterviewService;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class InterviewController extends Controller
{
    public function __construct(protected InterviewService $service)
    {
    }

    public function index(Request $request)
    {
        $sessions = $request->user()->interviewSessions()->latest()->get();

        return view('interview.index', [
            'sessions' => $sessions,
            'modes' => InterviewService::HRD_MODES,
            'difficulties' => InterviewService::DIFFICULTIES,
        ]);
    }

    public function start(InterviewStartRequest $request, SubscriptionService $subs)
    {
        $user = $request->user();

        if (! $subs->canUse($user, 'interview')) {
            return redirect()->route('pricing')
                ->with('upgrade', 'Limit Interview kamu sudah habis. Upgrade untuk latihan tanpa batas.');
        }

        $session = $user->interviewSessions()->create([
            'target_position' => $request->target_position,
            'hrd_mode' => $request->hrd_mode,
            'difficulty' => $request->difficulty,
            'status' => 'active',
        ]);

        $this->service->openSession($session);

        // Video-conference mode (AI avatar that speaks) vs classic chat.
        if ($request->input('mode') === 'video') {
            return redirect()->route('interview.video', $session);
        }

        return redirect()->route('interview.show', $session);
    }

    public function show(Request $request, InterviewSession $session)
    {
        $this->authorizeSession($request, $session);

        return view('interview.show', [
            'session' => $session,
            'messages' => $session->messages()->get(),
            'modes' => InterviewService::HRD_MODES,
        ]);
    }

    /** Immersive video-call interview with a talking AI avatar. */
    public function video(Request $request, InterviewSession $session)
    {
        $this->authorizeSession($request, $session);

        return view('interview.video', [
            'session' => $session,
            'messages' => $session->messages()->get(),
            'modes' => InterviewService::HRD_MODES,
        ]);
    }

    /** JSON turn endpoint used by the video UI (no page reload). */
    public function videoMessage(Request $request, InterviewSession $session)
    {
        $this->authorizeSession($request, $session);

        if ($session->status === 'completed') {
            return response()->json(['error' => 'completed', 'message' => 'Interview ini sudah selesai.'], 422);
        }

        $validated = $request->validate(['message' => ['required', 'string', 'max:4000']]);

        $result = $this->service->answer($session, $validated['message']);

        return response()->json([
            'answer_score' => $result['user_message']->score,
            'feedback' => $result['user_message']->feedback,
            'ai_message' => $result['ai_message']->message,
            'is_ready_to_finish' => $result['is_ready_to_finish'],
            'answered' => $session->messages()->where('sender', 'user')->count(),
        ]);
    }

    public function message(Request $request, InterviewSession $session)
    {
        $this->authorizeSession($request, $session);

        if ($session->status === 'completed') {
            return back()->with('warning', 'Interview ini sudah selesai.');
        }

        $validated = $request->validate([
            'message' => ['required', 'string', 'max:4000'],
        ], [
            'message.required' => 'Tulis jawaban kamu dulu ya.',
        ]);

        $this->service->answer($session, $validated['message']);

        return redirect()->route('interview.show', $session);
    }

    public function finish(Request $request, InterviewSession $session)
    {
        $this->authorizeSession($request, $session);

        $answeredCount = $session->messages()->where('sender', 'user')->count();
        if ($answeredCount < 1) {
            throw ValidationException::withMessages([
                'message' => 'Jawab minimal 1 pertanyaan sebelum menyelesaikan interview.',
            ]);
        }

        if ($session->status !== 'completed') {
            $this->service->finalize($session);
        }

        return redirect()->route('interview.show', $session)
            ->with('success', 'Interview selesai! Lihat laporan akhirnya di bawah.');
    }

    protected function authorizeSession(Request $request, InterviewSession $session): void
    {
        abort_unless($session->user_id === $request->user()->id, 403);
    }
}
