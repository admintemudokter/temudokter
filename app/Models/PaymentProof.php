<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentProof extends Model
{
    protected $fillable = [
        'transaction_id',
        'file_path',
        'file_type',
        'notes',
        'status',
        'rejection_reason',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
