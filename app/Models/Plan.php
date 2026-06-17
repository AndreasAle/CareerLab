<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'features' => 'array',
        'has_manual_review' => 'boolean',
        'has_consultation' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function isFree(): bool
    {
        return $this->price <= 0;
    }

    public function priceFormatted(): string
    {
        return $this->isFree() ? 'Gratis' : 'Rp' . number_format($this->price, 0, ',', '.');
    }
}
