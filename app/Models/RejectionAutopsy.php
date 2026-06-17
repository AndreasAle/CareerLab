<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RejectionAutopsy extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'possible_causes' => 'array',
        'improvement_plan' => 'array',
        'next_action' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
