<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

use App\Models\Consultation;

class ConsultationSummaryMail extends Mailable
{
    use Queueable, SerializesModels;

    public $consultation;

    /**
     * Create a new message instance.
     */
    public function __construct(Consultation $consultation)
    {
        $this->consultation = $consultation;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Ringkasan Konsultasi Anda - Temu Dokter',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.consultation_summary',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        $attachments = [];

        if ($this->consultation->prescription && $this->consultation->prescription->file_path) {
            $attachments[] = Attachment::fromStorageDisk('private', $this->consultation->prescription->file_path)
                                ->as('Resep_Obat_' . $this->consultation->invoice_number . '.pdf')
                                ->withMime('application/pdf');
        }

        if ($this->consultation->sickLeave && $this->consultation->sickLeave->file_path) {
            $attachments[] = Attachment::fromStorageDisk('private', $this->consultation->sickLeave->file_path)
                                ->as('Surat_Sakit_' . $this->consultation->invoice_number . '.pdf')
                                ->withMime('application/pdf');
        }

        return $attachments;
    }
}
