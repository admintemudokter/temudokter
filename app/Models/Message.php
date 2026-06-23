<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'consultation_id',
        'sender_type',
        'sender_id',
        'message',
        'attachment',
        'attachment_type',
        'is_read',
    ];

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
        ];
    }

    protected static function booted()
    {
        static::created(function ($message) {
            broadcast(new \App\Events\MessageSent($message));
        });
    }

    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }

    public function sender()
    {
        return match ($this->sender_type) {
            'doctor' => $this->belongsTo(Doctor::class, 'sender_id'),
            'admin'  => $this->belongsTo(Admin::class, 'sender_id'),
            default  => null,
        };
    }

    public function isFromPatient(): bool
    {
        return $this->sender_type === 'patient';
    }

    public function isFromDoctor(): bool
    {
        return $this->sender_type === 'doctor';
    }

    public function isSystem(): bool
    {
        return $this->sender_type === 'system';
    }

    public function hasAttachment(): bool
    {
        return !empty($this->attachment) && $this->attachment_type !== 'none';
    }

    public function getSenderNameAttribute(): string
    {
        return match ($this->sender_type) {
            'patient' => 'Pasien',
            'system'  => 'Sistem',
            default   => 'Dokter',
        };
    }
}
