<?php

namespace App\Services;

use App\Models\Plan;
use App\Models\User;
use Carbon\Carbon;

/**
 * Central place to resolve a user's current plan and enforce fair-usage limits.
 *
 * Limit convention on Plan columns: -1 = unlimited, 0 = not allowed, n = n uses.
 */
class SubscriptionService
{
    /** Map feature key => [plan limit column, usage model class] */
    public const FEATURES = [
        'cv_review'  => ['cv_review_limit', \App\Models\CvReview::class],
        'interview'  => ['interview_limit', \App\Models\InterviewSession::class],
        'job_match'  => ['job_match_limit', \App\Models\JobMatchCheck::class],
        'report'     => ['report_limit', \App\Models\CareerReport::class],
    ];

    /**
     * The plan currently in effect for the user. Falls back to the Free plan.
     */
    public function planFor(User $user): Plan
    {
        $subscription = $user->activeSubscription();

        if ($subscription && $subscription->plan) {
            return $subscription->plan;
        }

        return Plan::where('slug', 'free')->first()
            ?? new Plan([
                'name' => 'Free',
                'slug' => 'free',
                'cv_review_limit' => 1,
                'interview_limit' => 1,
                'job_match_limit' => 1,
                'report_limit' => 0,
            ]);
    }

    /**
     * Start of the current usage window. For paid plans = subscription start,
     * for free users = account creation (all-time fair usage).
     */
    protected function windowStart(User $user): Carbon
    {
        $subscription = $user->activeSubscription();

        return $subscription && $subscription->starts_at
            ? $subscription->starts_at
            : $user->created_at ?? now()->subYears(10);
    }

    public function limitFor(User $user, string $feature): int
    {
        [$column] = self::FEATURES[$feature] ?? [null];

        if (! $column) {
            return -1;
        }

        return (int) $this->planFor($user)->{$column};
    }

    public function usageCount(User $user, string $feature): int
    {
        [, $modelClass] = self::FEATURES[$feature] ?? [null, null];

        if (! $modelClass) {
            return 0;
        }

        return $modelClass::where('user_id', $user->id)
            ->where('created_at', '>=', $this->windowStart($user))
            ->count();
    }

    public function remaining(User $user, string $feature): int
    {
        $limit = $this->limitFor($user, $feature);

        if ($limit === -1) {
            return PHP_INT_MAX; // unlimited
        }

        return max(0, $limit - $this->usageCount($user, $feature));
    }

    public function canUse(User $user, string $feature): bool
    {
        $limit = $this->limitFor($user, $feature);

        if ($limit === -1) {
            return true;
        }

        if ($limit === 0) {
            return false;
        }

        return $this->usageCount($user, $feature) < $limit;
    }

    public function isUnlimited(User $user, string $feature): bool
    {
        return $this->limitFor($user, $feature) === -1;
    }
}
