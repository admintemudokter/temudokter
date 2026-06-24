<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'consultation_id',
        'invoice_number',
        'payment_method',
        'payment_provider',
        'amount',
        'payment_status',
    ];

    // Relationships
    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }

    public function paymentSessions()
    {
        return $this->hasMany(PaymentSession::class);
    }

    public function latestPaymentSession()
    {
        return $this->hasOne(PaymentSession::class)->latestOfMany();
    }

    public function paymentProofs()
    {
        return $this->hasMany(PaymentProof::class);
    }

    public function latestProof()
    {
        return $this->hasOne(PaymentProof::class)->latestOfMany();
    }

    public function getFormattedAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    public function getMethodLabelAttribute(): string
    {
        return match ($this->payment_method) {
            'qris'            => 'QRIS',
            'bank_transfer'   => 'Manual Transfer Bank',
            'virtual_account' => 'Virtual Account',
            'ewallet'         => 'E-Wallet',
            default           => '-',
        };
    }
}
