<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ToxicJobScan extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'warning_signs' => 'array',
        'questions_to_ask_hr' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
