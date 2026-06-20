<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

/**
 * Tracks anonymous (not-logged-in) usage of the public free trial:
 *  - 1 free CV check per guest
 *  - 5 free chat messages (tokens) per guest
 *
 * Identity = a long-lived signed cookie UUID, with IP as a secondary guard.
 * Counters live in the cache (database cache store) so they survive page loads.
 */
class GuestTrialService
{
    public const COOKIE = 'cl_guest';
    public const FREE_CV_CHECKS = 1;
    public const FREE_CHAT_TOKENS = 5;
    public const TTL_DAYS = 30;

    protected string $id;

    public function __construct(Request $request)
    {
        $id = $request->cookie(self::COOKIE);

        if (! is_string($id) || ! Str::isUuid($id)) {
            $id = (string) Str::uuid();
            // Persist for a year so the same browser keeps the same guest id.
            Cookie::queue(Cookie::make(self::COOKIE, $id, 60 * 24 * 365));
        }

        $this->id = $id;
        // Secondary guard: fold in the IP so a fresh cookie from the same IP
        // can't farm unlimited checks too easily.
        $this->ipKey = 'guest:ip:' . sha1((string) $request->ip());
    }

    protected string $ipKey;

    protected function cvKey(): string
    {
        return 'guest:cv:' . $this->id;
    }

    protected function chatKey(): string
    {
        return 'guest:chat:' . $this->id;
    }

    /* ---------------- CV check ---------------- */

    public function cvChecksUsed(): int
    {
        return max(
            (int) Cache::get($this->cvKey(), 0),
            (int) Cache::get($this->ipKey . ':cv', 0),
        );
    }

    public function canCheckCv(): bool
    {
        return $this->cvChecksUsed() < self::FREE_CV_CHECKS;
    }

    public function recordCvCheck(): void
    {
        $ttl = now()->addDays(self::TTL_DAYS);
        Cache::put($this->cvKey(), $this->cvChecksUsed() + 1, $ttl);
        Cache::put($this->ipKey . ':cv', (int) Cache::get($this->ipKey . ':cv', 0) + 1, $ttl);
    }

    /* ---------------- Chat tokens ---------------- */

    public function chatTokensUsed(): int
    {
        return (int) Cache::get($this->chatKey(), 0);
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
        Cache::put($this->chatKey(), $this->chatTokensUsed() + 1, now()->addDays(self::TTL_DAYS));
    }

    public function id(): string
    {
        return $this->id;
    }
}
