<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'full_name',
        'email',
        'whatsapp_number',
        'age',
        'gender',
        'occupation',
        'bekasi_area',
        'province',
        'city',
        'district',
        'village',
        'rt_rw',
        'complaint_description',
        'drug_allergies',
        'medical_image',
        'medical_document',
        'session_token',
    ];

    // Relationships
    public function consultations()
    {
        return $this->hasMany(Consultation::class);
    }

    public function latestConsultation()
    {
        return $this->hasOne(Consultation::class)->latestOfMany();
    }

    public function getGenderLabelAttribute(): string
    {
        return match ($this->gender) {
            'laki-laki' => 'Laki-laki',
            'perempuan'  => 'Perempuan',
            default      => '-',
        };
    }

    public function getFullAddressAttribute(): string
    {
        if ($this->bekasi_area && !$this->province) {
            return 'Wilayah ' . $this->bekasi_area;
        }

        if ($this->province) {
            $addressParts = array_filter([
                $this->rt_rw ? $this->rt_rw : null,
                $this->village ? "Kel/Desa. {$this->village}" : null,
                $this->district ? "Kec. {$this->district}" : null,
                $this->city,
                $this->province,
            ]);
            return implode(', ', $addressParts);
        }

        return '-';
    }
}
