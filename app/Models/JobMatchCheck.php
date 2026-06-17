<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobMatchCheck extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'matched_skills' => 'array',
        'missing_skills' => 'array',
        'required_keywords' => 'array',
        'recommended_cv_changes' => 'array',
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
