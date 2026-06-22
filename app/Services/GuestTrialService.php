<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

/**
 * Tracks anonymous (not-logged-in) usage of the public free trial.
 *
 * Hard limit is keyed by **IP address** so one IP can't farm unlimited checks
 * (cookie is only used to keep a stable guest id for the session UX).
 *
 *  - 5 free CV checks per IP, on a rolling monthly window anchored to the
 *    first use (first used on the 10th -> resets next month on the 10th).
 *  - 3 free chat messages (tokens) per IP, same rolling window.
 *
 * Counters live in the cache so they survive page loads.
 */
class GuestTrialService
{
    public const COOKIE = 'cl_guest';
    public const FREE_CV_CHECKS = 5;
    public const FREE_CHAT_TOKENS = 3;
    public const WINDOW_TTL_DAYS = 40; // a bit over a month so the record survives until reset

    protected string $id;
    protected string $ipHash;

    public function __construct(Request $request)
    {
        $id = $request->cookie(self::COOKIE);
        if (! is_string($id) || ! Str::isUuid($id)) {
            $id = (string) Str::uuid();
            Cookie::queue(Cookie::make(self::COOKIE, $id, 60 * 24 * 365));
        }
        $this->id = $id;
        $this->ipHash = sha1((string) $request->ip());
    }

    /* ---------------- generic rolling-window counter ---------------- */

    /**
     * @return array{count:int, first:?string}
     */
    protected function read(string $key): array
    {
        $data = Cache::get($key);
        if (! is_array($data)) {
            return ['count' => 0, 'first' => null];
        }
        // Window elapsed since first use -> treat as fresh.
        if (! empty($data['first']) && now()->gte(Carbon::parse($data['first'])->addMonthNoOverflow())) {
            return ['count' => 0, 'first' => null];
        }
        return ['count' => (int) ($data['count'] ?? 0), 'first' => $data['first'] ?? null];
    }

    protected function increment(string $key): void
    {
        $data = $this->read($key);
        $first = $data['first'] ?? now()->toIso8601String();
        Cache::put($key, ['count' => $data['count'] + 1, 'first' => $first], now()->addDays(self::WINDOW_TTL_DAYS));
    }

    protected function resetAtFor(string $key): ?Carbon
    {
        $data = $this->read($key);
        return $data['first'] ? Carbon::parse($data['first'])->addMonthNoOverflow() : null;
    }

    protected function cvKey(): string
    {
        return 'guest:cv:' . $this->ipHash;
    }

    protected function chatKey(): string
    {
        return 'guest:chat:' . $this->ipHash;
    }

    /* ---------------- CV checks ---------------- */

    public function cvChecksUsed(): int
    {
        return $this->read($this->cvKey())['count'];
    }

    public function checksRemaining(): int
    {
        return max(0, self::FREE_CV_CHECKS - $this->cvChecksUsed());
    }

    public function canCheckCv(): bool
    {
        return $this->cvChecksUsed() < self::FREE_CV_CHECKS;
    }

    public function recordCvCheck(): void
    {
        $this->increment($this->cvKey());
    }

    /** When the free CV quota resets (null if never used yet). */
    public function cvResetAt(): ?Carbon
    {
        return $this->resetAtFor($this->cvKey());
    }

    /* ---------------- Chat tokens ---------------- */

    public function chatTokensUsed(): int
    {
        return $this->read($this->chatKey())['count'];
    }

    public function chatTokensRemaining(): int
    {
        return max(0, self::FREE_CHAT_TOKENS - $this->chatTokensUsed());
    }

    public function canChat(): bool
    {
        return $this->chatTokensRemaining() > 0;
    }

    public function recordChatToken(): void
    {
        $this->increment($this->chatKey());
    }

    public function id(): string
    {
        return $this->id;
    }
}
