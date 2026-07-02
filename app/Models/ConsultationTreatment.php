<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsultationTreatment extends Model
{
    protected $fillable = [
        'consultation_id',
        'treatment_id',
        'treatment_name',
        'bentuk_sediaan',
        'price'
    ];

    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }

    public function treatment()
    {
        return $this->belongsTo(Treatment::class);
    }
}
