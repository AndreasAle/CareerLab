<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'is_premium' => 'boolean',
        'is_active' => 'boolean',
    ];

    public const TYPES = [
        'cv' => 'CV Summary',
        'email_lamaran' => 'Email Lamaran',
        'follow_up_hr' => 'Follow Up HR',
        'interview_answer' => 'Jawaban Interview',
        'linkedin_bio' => 'LinkedIn Bio',
        'resign_reason' => 'Alasan Resign Profesional',
        'salary_negotiation' => 'Salary Negotiation Script',
    ];

    public function typeLabel(): string
    {
        return self::TYPES[$this->type] ?? $this->type;
    }
}
