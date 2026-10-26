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
        'date_of_birth',
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

    protected $casts = [
        'date_of_birth' => 'date',
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

    public function getFormattedDateOfBirthAttribute(): string
    {
        if ($this->date_of_birth) {
            return \Carbon\Carbon::parse($this->date_of_birth)->translatedFormat('d M Y');
        }
        if ($this->age) {
            $baseDate = $this->created_at ?? now();
            return \Carbon\Carbon::parse($baseDate)->subYears((int) $this->age)->translatedFormat('d M Y');
        }
        return '-';
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
