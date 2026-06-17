<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RedFlagScan extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'candidate_red_flags' => 'array',
        'explanation' => 'array',
        'safe_fix_suggestions' => 'array',
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
