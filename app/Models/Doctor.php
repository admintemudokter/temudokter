<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\SoftDeletes;

class Doctor extends Authenticatable
{
    use Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'specialization',
        'experience_years',
        'practice_location',
        'education',
        'str_number',
        'sip_number',
        'phone',
        'photo',
        'status',
        'bio',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    // Relationships
    public function consultations()
    {
        return $this->hasMany(Consultation::class);
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }

    public function activeConsultations()
    {
        return $this->hasMany(Consultation::class)->where('consultation_status', 'active');
    }

    // Scopes
    public function scopeOnline($query)
    {
        return $query->where('status', 'online');
    }

    public function scopeAvailable($query)
    {
        return $query->whereIn('status', ['online']);
    }

    public function isAvailable(): bool
    {
        return $this->status === 'online';
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'online'   => 'Online',
            'offline'  => 'Offline',
            'busy'     => 'Sedang Konsultasi',
            'inactive' => 'Tidak Aktif',
            default    => 'Offline',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'online'   => 'emerald',
            'busy'     => 'amber',
            'offline'  => 'slate',
            'inactive' => 'red',
            default    => 'slate',
        };
    }
}
