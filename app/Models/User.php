<?php

namespace App\Models;

use App\Services\EmailVerificationService;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const ROLE_ADMIN = 'admin';
    public const ROLE_COACH = 'coach';
    public const ROLE_USER = 'user';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'avatar',
        'headline',
        'target_position',
        'experience_level',
        'city',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Use a 6-digit OTP code instead of the default signed verification link.
     * Triggered by the Registered event (and on resend).
     */
    public function sendEmailVerificationNotification(): void
    {
        app(EmailVerificationService::class)->sendCode($this);
    }

    /* ---------------- Role helpers ---------------- */

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isCoach(): bool
    {
        return $this->role === self::ROLE_COACH;
    }

    public function isUser(): bool
    {
        return $this->role === self::ROLE_USER;
    }

    public function hasRole(string ...$roles): bool
    {
        return in_array($this->role, $roles, true);
    }

    /* ---------------- Relations ---------------- */

    public function profile(): HasOne
    {
        return $this->hasOne(UserProfile::class);
    }

    public function cvUploads(): HasMany
    {
        return $this->hasMany(CvUpload::class);
    }

    public function cvReviews(): HasMany
    {
        return $this->hasMany(CvReview::class);
    }

    public function interviewSessions(): HasMany
    {
        return $this->hasMany(InterviewSession::class);
    }

    public function jobMatchChecks(): HasMany
    {
        return $this->hasMany(JobMatchCheck::class);
    }

    public function redFlagScans(): HasMany
    {
        return $this->hasMany(RedFlagScan::class);
    }

    public function toxicJobScans(): HasMany
    {
        return $this->hasMany(ToxicJobScan::class);
    }

    public function salarySimulations(): HasMany
    {
        return $this->hasMany(SalarySimulation::class);
    }

    public function rejectionAutopsies(): HasMany
    {
        return $this->hasMany(RejectionAutopsy::class);
    }

    public function careerReports(): HasMany
    {
        return $this->hasMany(CareerReport::class);
    }

    public function applicationTrackers(): HasMany
    {
        return $this->hasMany(ApplicationTracker::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function consultationBookings(): HasMany
    {
        return $this->hasMany(ConsultationBooking::class, 'user_id');
    }

    public function coachBookings(): HasMany
    {
        return $this->hasMany(ConsultationBooking::class, 'coach_id');
    }

    public function challengeProgress(): HasMany
    {
        return $this->hasMany(UserChallengeProgress::class);
    }

    /**
     * Currently active subscription (if any).
     */
    public function activeSubscription(): ?Subscription
    {
        return $this->subscriptions()
            ->where('status', 'active')
            ->where('ends_at', '>=', now())
            ->latest('ends_at')
            ->first();
    }
}
