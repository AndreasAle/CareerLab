<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CareerReport extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'report_data' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cvReview(): BelongsTo
    {
        return $this->belongsTo(CvReview::class);
    }

    public function jobMatchCheck(): BelongsTo
    {
        return $this->belongsTo(JobMatchCheck::class);
    }

    public function redFlagScan(): BelongsTo
    {
        return $this->belongsTo(RedFlagScan::class);
    }
}
