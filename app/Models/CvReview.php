<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CvReview extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'strengths' => 'array',
        'weaknesses' => 'array',
        'red_flags' => 'array',
        'improvement_suggestions' => 'array',
        'missing_keywords' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cvUpload(): BelongsTo
    {
        return $this->belongsTo(CvUpload::class);
    }
}
