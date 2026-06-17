<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicationTracker extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'applied_at' => 'date',
        'follow_up_date' => 'date',
    ];

    public const STATUSES = ['saved', 'applied', 'screening', 'interview', 'test', 'offering', 'accepted', 'rejected', 'ghosted'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
