<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SickLeave extends Model
{
    protected $fillable = [
        'consultation_id',
        'doctor_id',
        'patient_id',
        'start_date',
        'end_date',
        'reason',
        'file_path',
    ];

    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
