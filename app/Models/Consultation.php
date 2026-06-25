<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $invoice_number
 * @property \App\Models\Prescription|null $prescription
 * @property \App\Models\SickLeave|null $sickLeave
 * @property int|null $rating
 * @property string|null $review
 */
class Consultation extends Model
{
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'invoice_number',
        'history_code',
        'consultation_status',
        'duration_minutes',
        'started_at',
        'expires_at',
        'ended_at',
        'admin_notes',
        'type',
        'address',
        'homecare_schedule_date',
        'homecare_schedule_time',
        'price',
        'homecare_report',
        'rating',
        'review',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'expires_at' => 'datetime',
            'ended_at'   => 'datetime',
            'homecare_schedule_date' => 'date',
        ];
    }

    protected static function booted()
    {
        static::updated(function ($consultation) {
            if ($consultation->wasChanged(['consultation_status'])) {
                broadcast(new \App\Events\ConsultationStatusUpdated($consultation));
            }
        });
    }

    // Relationships
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class)->orderBy('created_at');
    }

    public function prescription()
    {
        return $this->hasOne(Prescription::class);
    }

    public function sickLeave()
    {
        return $this->hasOne(SickLeave::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('consultation_status', 'active');
    }

    public function scopeWaitingAssignment($query)
    {
        return $query->where('consultation_status', 'waiting_assignment');
    }

    public function scopeCompleted($query)
    {
        return $query->where('consultation_status', 'completed');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeArchived($query)
    {
        return $query->where('is_archived', true);
    }

    public function scopeUnarchived($query)
    {
        return $query->where('is_archived', false);
    }

    // Helpers
    public function isActive(): bool
    {
        return $this->consultation_status === 'active';
    }

    public function isCompleted(): bool
    {
        return $this->consultation_status === 'completed';
    }

    public function isExpired(): bool
    {
        return $this->expires_at && now()->greaterThan($this->expires_at);
    }

    public function getRemainingSecondsAttribute(): int
    {
        if (!$this->expires_at || !$this->isActive()) {
            return 0;
        }
        return max(0, now()->diffInSeconds($this->expires_at, false));
    }

    public function getStatusLabelAttribute(): string
    {
        if ($this->consultation_status === 'waiting_payment' && $this->type === 'homecare' && is_null($this->price)) {
            return 'Menunggu Harga Admin';
        }
        if ($this->consultation_status === 'active' && $this->type === 'homecare') {
            return 'Kunjungan Berlangsung';
        }

        return match ($this->consultation_status) {
            'waiting_payment'           => 'Menunggu Pembayaran',
            'waiting_upload'            => 'Menunggu Upload Bukti',
            'waiting_admin_confirmation'=> 'Menunggu Verifikasi Admin',
            'payment_rejected'          => 'Pembayaran Ditolak',
            'waiting_assignment'        => 'Menunggu Dokter',
            'active'                    => 'Konsultasi Aktif',
            'completed'                 => 'Selesai',
            'cancelled'                 => 'Dibatalkan',
            'expired'                   => 'Kedaluwarsa',
            default                     => '-',
        };
    }

    public function getStatusColorAttribute(): string
    {
        if ($this->consultation_status === 'waiting_payment' && $this->type === 'homecare' && is_null($this->price)) {
            return 'purple';
        }
        
        return match ($this->consultation_status) {
            'waiting_payment', 'waiting_upload'  => 'amber',
            'waiting_admin_confirmation'          => 'blue',
            'payment_rejected'                    => 'red',
            'waiting_assignment'                  => 'purple',
            'active'                              => 'emerald',
            'completed'                           => 'teal',
            'cancelled', 'expired'                => 'slate',
            default                               => 'slate',
        };
    }
}
