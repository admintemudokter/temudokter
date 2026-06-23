<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentSession extends Model
{
    protected $fillable = [
        'transaction_id',
        'method',
        'provider',
        'simulated_number',
        'expires_at',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
        ];
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function isExpired(): bool
    {
        return now()->greaterThan($this->expires_at) || $this->status === 'expired';
    }

    public function getRemainingSecondsAttribute(): int
    {
        return max(0, now()->diffInSeconds($this->expires_at, false));
    }
}
