<?php

namespace App\Services;

use App\Models\Consultation;
use App\Models\Doctor;
use App\Models\Message;
use App\Mail\ConsultationSummaryMail;
use App\Mail\PatientHistoryCodeNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class ConsultationService
{
    /**
     * Activate a consultation after payment is approved.
     */
    public function activate(Consultation $consultation): void
    {
        $consultation->update([
            'consultation_status' => 'waiting_assignment',
        ]);

        // System message
        $this->addSystemMessage($consultation, 'Pembayaran telah diverifikasi. Menunggu penugasan dokter.');
    }

    /**
     * Assign a doctor and start the consultation.
     */
    public function assignDoctor(Consultation $consultation, Doctor $doctor): void
    {
        $duration = (int) env('CONSULTATION_DURATION_MINUTES', 15);
        $startedAt = now();

        $consultation->update([
            'doctor_id'            => $doctor->id,
            'consultation_status'  => 'active',
            'started_at'           => $startedAt,
            'expires_at'           => $startedAt->copy()->addMinutes($duration),
        ]);

        // Set doctor status to busy
        $doctor->update(['status' => 'busy']);

        $this->addSystemMessage(
            $consultation,
            "Dr. {$doctor->name} telah bergabung. Konsultasi dimulai. Waktu konsultasi {$duration} menit."
        );

        // Auto-Greeting by Doctor
        if ($consultation->type !== 'homecare') {
            Message::create([
                'consultation_id' => $consultation->id,
                'sender_type'     => 'doctor',
                'sender_id'       => $doctor->id,
                'message'         => "Halo, saya dr. {$doctor->name}. Terima kasih telah bergabung dalam konsultasi online ini. Silakan ceritakan keluhan atau pertanyaan yang ingin Anda konsultasikan hari ini. Saya siap membantu dan memberikan informasi medis sesuai dengan kondisi yang Anda sampaikan.",
                'attachment_type' => 'none',
                'is_read'         => false,
            ]);
        }
        
        // Dispatch event so the doctor's dashboard updates in real-time
        broadcast(new \App\Events\DoctorAssigned($doctor));
    }

    /**
     * Manually end a consultation (by doctor or admin).
     */
    public function end(Consultation $consultation, string $by = 'doctor'): void
    {
        $consultation->update([
            'consultation_status' => 'completed',
            'ended_at'            => now(),
        ]);

        if ($consultation->doctor) {
            $consultation->doctor->update(['status' => 'online']);
        }

        $endedBy = match ($by) {
            'doctor' => 'dokter',
            'admin'  => 'admin',
            'timer'  => 'waktu konsultasi habis',
            default  => 'sistem',
        };

        $this->addSystemMessage($consultation, "Konsultasi telah diakhiri oleh {$endedBy}.");

        // Send History Code via Email
        try {
            Mail::to($consultation->patient->email)->send(new PatientHistoryCodeNotification($consultation));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Gagal mengirim email kode riwayat: ' . $e->getMessage());
        }
    }

    /**
     * Auto-expire consultations whose timer has run out.
     */
    public function expireOverdue(): int
    {
        $overdue = Consultation::where('consultation_status', 'active')
            ->where('expires_at', '<=', now())
            ->get();

        foreach ($overdue as $consultation) {
            $this->end($consultation, 'timer');
        }

        return $overdue->count();
    }

    private function addSystemMessage(Consultation $consultation, string $text): void
    {
        Message::create([
            'consultation_id' => $consultation->id,
            'sender_type'     => 'system',
            'sender_id'       => null,
            'message'         => $text,
            'attachment_type' => 'none',
            'is_read'         => false,
        ]);
    }
}
