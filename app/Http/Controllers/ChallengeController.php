<?php

namespace App\Http\Controllers;

use App\Models\Challenge;
use App\Models\ChallengeTask;
use App\Models\UserChallengeProgress;
use Illuminate\Http\Request;

class ChallengeController extends Controller
{
    public function index(Request $request)
    {
        $challenge = Challenge::where('is_active', true)->with('tasks')->first();
        $completedTaskIds = collect();

        if ($challenge) {
            $completedTaskIds = $request->user()->challengeProgress()
                ->where('challenge_id', $challenge->id)
                ->where('status', 'completed')
                ->pluck('challenge_task_id');
        }

        return view('challenge.index', [
            'challenge' => $challenge,
            'completedTaskIds' => $completedTaskIds,
            'progressPercent' => $challenge && $challenge->tasks->count()
                ? round($completedTaskIds->count() / $challenge->tasks->count() * 100)
                : 0,
        ]);
    }

    public function toggle(Request $request, ChallengeTask $task)
    {
        $user = $request->user();

        $progress = UserChallengeProgress::firstOrNew([
            'user_id' => $user->id,
            'challenge_id' => $task->challenge_id,
            'challenge_task_id' => $task->id,
        ]);

        if ($progress->exists && $progress->status === 'completed') {
            $progress->status = 'pending';
            $progress->completed_at = null;
        } else {
            $progress->status = 'completed';
            $progress->completed_at = now();
        }
        $progress->save();

        return back()->with('success', 'Progress challenge diperbarui!');
    }
}
