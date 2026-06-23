<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    protected $fillable = [
        'consultation_id',
        'doctor_id',
        'notes',
        'file_path',
        'file_type',
    ];

    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function items()
    {
        return $this->hasMany(PrescriptionItem::class);
    }
}
