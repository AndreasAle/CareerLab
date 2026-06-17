<?php

namespace App\Http\Controllers;

use App\Models\Challenge;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request, SubscriptionService $subs)
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        if ($user->isCoach()) {
            return redirect()->route('coach.dashboard');
        }

        $latestCvReview = $user->cvReviews()->latest()->first();
        $latestInterview = $user->interviewSessions()->latest()->first();

        // ---- Stat counts + this-month deltas ----
        $monthStart = now()->startOfMonth();
        $stat = fn ($rel) => [
            'total' => $user->{$rel}()->count(),
            'month' => $user->{$rel}()->where('created_at', '>=', $monthStart)->count(),
        ];
        $cv = $stat('cvReviews');
        $interview = $stat('interviewSessions');
        $jobMatch = $stat('jobMatchChecks');
        $applications = $user->applicationTrackers()->get();

        // ---- 6-month activity series (CV + interview + job match + report) ----
        $series = collect(range(5, 0))->map(function ($i) use ($user) {
            $start = now()->subMonths($i)->startOfMonth();
            $end = (clone $start)->endOfMonth();
            $count = $user->cvReviews()->whereBetween('created_at', [$start, $end])->count()
                + $user->interviewSessions()->whereBetween('created_at', [$start, $end])->count()
                + $user->jobMatchChecks()->whereBetween('created_at', [$start, $end])->count()
                + $user->careerReports()->whereBetween('created_at', [$start, $end])->count();
            return ['label' => $start->isoFormat('MMM'), 'value' => $count];
        })->values();

        $seriesTotal = $series->sum('value');
        $thisMonthActivity = $series->last()['value'] ?? 0;
        $prevMonthActivity = $series->slice(-2, 1)->first()['value'] ?? 0;
        $activityTrend = $prevMonthActivity > 0
            ? round(($thisMonthActivity - $prevMonthActivity) / $prevMonthActivity * 100)
            : ($thisMonthActivity > 0 ? 100 : 0);

        // ---- Application funnel ----
        $funnel = [
            ['label' => 'Applied', 'count' => $applications->whereIn('status', ['applied', 'screening'])->count(), 'color' => '#3b82f6'],
            ['label' => 'Interview', 'count' => $applications->whereIn('status', ['interview', 'test'])->count(), 'color' => '#8b5cf6'],
            ['label' => 'Offering', 'count' => $applications->whereIn('status', ['offering', 'accepted'])->count(), 'color' => '#10b981'],
            ['label' => 'Closed', 'count' => $applications->whereIn('status', ['rejected', 'ghosted'])->count(), 'color' => '#f43f5e'],
        ];
        $appTotal = max(1, $applications->count());

        // ---- Recent activity feed (merged) ----
        $feed = collect();
        foreach ($user->cvReviews()->latest()->take(3)->get() as $r) {
            $feed->push(['icon' => 'doc', 'color' => 'emerald', 'title' => 'CV Review', 'desc' => $r->target_position . ' · skor ' . $r->score, 'at' => $r->created_at, 'url' => route('cv.review.detail', $r)]);
        }
        foreach ($user->interviewSessions()->latest()->take(3)->get() as $s) {
            $feed->push(['icon' => 'chat', 'color' => 'purple', 'title' => 'Interview ' . ($s->status === 'completed' ? 'selesai' : 'berlangsung'), 'desc' => $s->target_position . ($s->final_score ? ' · skor ' . $s->final_score : ''), 'at' => $s->created_at, 'url' => route('interview.show', $s)]);
        }
        foreach ($user->jobMatchChecks()->latest()->take(3)->get() as $j) {
            $feed->push(['icon' => 'target', 'color' => 'blue', 'title' => 'Job Match', 'desc' => $j->job_title . ' · ' . $j->match_score . '% match', 'at' => $j->created_at, 'url' => route('job-match.show', $j)]);
        }
        foreach ($applications->take(3) as $a) {
            $feed->push(['icon' => 'briefcase', 'color' => 'amber', 'title' => 'Lamaran', 'desc' => $a->position . ' @ ' . $a->company_name, 'at' => $a->created_at, 'url' => route('applications.index')]);
        }
        $feed = $feed->sortByDesc('at')->take(6)->values();

        // ---- Upcoming consultation ----
        $upcoming = $user->consultationBookings()
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('scheduled_at', '>=', now())
            ->orderBy('scheduled_at')->take(2)->get();

        // ---- Challenge ----
        $challenge = Challenge::where('is_active', true)->with('tasks')->first();
        $completedTaskIds = $user->challengeProgress()->where('status', 'completed')->pluck('challenge_task_id');
        $challengePercent = $challenge && $challenge->tasks->count()
            ? round($completedTaskIds->count() / $challenge->tasks->count() * 100) : 0;

        // ---- Scores ----
        $cvScore = $latestCvReview->score ?? 0;
        $atsScore = $latestCvReview->ats_score ?? 0;
        $interviewScore = $latestInterview->final_score ?? 0;
        $readiness = (int) round(($cvScore + ($interviewScore ?: $cvScore)) / 2);

        return view('dashboard', [
            'user' => $user,
            'readiness' => $readiness,
            'cvScore' => $cvScore,
            'atsScore' => $atsScore,
            'interviewScore' => $interviewScore,
            'latestCvReview' => $latestCvReview,
            'statCards' => [
                ['key' => 'cv', 'label' => 'CV Review', 'total' => $cv['total'], 'month' => $cv['month'], 'icon' => 'doc', 'grad' => 'from-emerald-400 to-emerald-600'],
                ['key' => 'interview', 'label' => 'Interview', 'total' => $interview['total'], 'month' => $interview['month'], 'icon' => 'chat', 'grad' => 'from-violet-400 to-purple-600'],
                ['key' => 'jobmatch', 'label' => 'Job Match', 'total' => $jobMatch['total'], 'month' => $jobMatch['month'], 'icon' => 'target', 'grad' => 'from-blue-400 to-blue-600'],
                ['key' => 'apply', 'label' => 'Lamaran', 'total' => $applications->count(), 'month' => $applications->where('created_at', '>=', $monthStart)->count(), 'icon' => 'briefcase', 'grad' => 'from-amber-400 to-orange-600'],
            ],
            'series' => $series,
            'seriesTotal' => $seriesTotal,
            'activityTrend' => $activityTrend,
            'funnel' => $funnel,
            'appTotal' => $appTotal,
            'feed' => $feed,
            'upcoming' => $upcoming,
            'challenge' => $challenge,
            'completedTaskIds' => $completedTaskIds,
            'challengePercent' => $challengePercent,
            'plan' => $subs->planFor($user),
            'limits' => [
                'cv_review' => $subs->remaining($user, 'cv_review'),
                'interview' => $subs->remaining($user, 'interview'),
                'job_match' => $subs->remaining($user, 'job_match'),
            ],
        ]);
    }
}
