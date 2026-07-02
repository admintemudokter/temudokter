<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Patient;
use App\Models\PaymentProof;
use App\Models\Prescription;
use App\Models\SickLeave;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class FileController extends Controller
{
    /**
     * Serve private payment proof images/PDFs (admin only).
     */
    public function paymentProof(PaymentProof $proof)
    {
        // Only authenticated admins can access payment proofs
        if (!auth('admin')->check()) {
            abort(403);
        }

        return $this->serveFile($proof->file_path);
    }

    /**
     * Serve patient medical images/documents.
     * Access: patient's own session OR admin/doctor.
     */
    public function medicalFile(Patient $patient, string $field)
    {
        $allowedFields = ['medical_image', 'medical_document'];
        if (!in_array($field, $allowedFields)) {
            abort(404);
        }

        // Authorize: patient's own session, admin, or assigned doctor
        $this->authorizeMedicalAccess($patient);

        $path = $patient->{$field};
        if (!$path) abort(404);

        return $this->serveFile($path);
    }

    /**
     * Serve chat attachment (patient or doctor in same consultation).
     */
    public function chatAttachment(Message $message)
    {
        $consultation = $message->consultation;

        // Authorize: valid signature, or patient session, admin, or assigned doctor
        $hasSignature = request()->hasValidSignature();
        $isAdmin = auth('admin')->check();
        $isDoctor = auth('doctor')->check() && auth('doctor')->id() === $consultation->doctor_id;
        $isPatient = session('patient_token') === $consultation->patient->session_token;

        if (!$hasSignature && !$isAdmin && !$isDoctor && !$isPatient) {
            abort(403);
        }

        if (!$message->attachment) abort(404);

        return $this->serveFile($message->attachment);
    }

    /**
     * Serve prescription files (patient via signed URL or admin/doctor).
     */
    public function prescription(Prescription $prescription)
    {
        $consultation = $prescription->consultation;

        $hasSignature = request()->hasValidSignature();
        $isAdmin = auth('admin')->check();
        $isDoctor = auth('doctor')->check() && auth('doctor')->id() === $consultation->doctor_id;
        $isPatient = session('patient_token') === $consultation->patient->session_token;

        if (!$hasSignature && !$isAdmin && !$isDoctor && !$isPatient) {
            abort(403);
        }

        if (!$prescription->file_path) abort(404);

        return $this->serveFile($prescription->file_path);
    }

    /**
     * Serve sick leave files (patient via signed URL or admin/doctor).
     */
    public function sickLeave(SickLeave $sick_leave)
    {
        $consultation = $sick_leave->consultation;

        $hasSignature = request()->hasValidSignature();
        $isAdmin = auth('admin')->check();
        $isDoctor = auth('doctor')->check() && auth('doctor')->id() === $consultation->doctor_id;
        $isPatient = session('patient_token') === $consultation->patient->session_token;

        if (!$hasSignature && !$isAdmin && !$isDoctor && !$isPatient) {
            abort(403);
        }

        if (!$sick_leave->file_path) abort(404);

        return $this->serveFile($sick_leave->file_path);
    }

    /**
     * Generate and serve payment receipt on the fly.
     */
    public function receipt(Transaction $transaction)
    {
        $consultation = $transaction->consultation;

        $hasSignature = request()->hasValidSignature();
        $isAdmin = auth('admin')->check();
        $isDoctor = auth('doctor')->check() && auth('doctor')->id() === $consultation->doctor_id;
        $isPatient = session('patient_token') === $consultation->patient->session_token;

        if (!$hasSignature && !$isAdmin && !$isDoctor && !$isPatient) {
            abort(403);
        }

        $pdf = Pdf::loadView('pdf.receipt', compact('transaction', 'consultation'))->setPaper('a5', 'landscape');
        return $pdf->stream('Kwitansi_' . $consultation->invoice_number . '.pdf');
    }

    /**
     * Stream a file from private storage.
     */
    private function serveFile(string $path)
    {
        $disk = config('filesystems.private_disk');
        if (!Storage::disk($disk)->exists($path)) {
            abort(404, 'File tidak ditemukan.');
        }

        $content  = Storage::disk($disk)->get($path);
        $mimeType = Storage::disk($disk)->mimeType($path);
        $size     = Storage::disk($disk)->size($path);
        $filename = basename($path);

        return response($content, 200)
            ->header('Content-Type', $mimeType)
            ->header('Content-Length', $size)
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"')
            ->header('Cache-Control', 'private, max-age=3600');
    }

    private function authorizeMedicalAccess(Patient $patient): void
    {
        $isAdmin   = auth('admin')->check();
        $isDoctor  = auth('doctor')->check();
        $isPatient = session('patient_token') === $patient->session_token;

        if (!$isAdmin && !$isDoctor && !$isPatient) {
            abort(403);
        }
    }
    
    /**
     * Serve treatment files.
     */
    public function treatment($invoice)
    {
        $path = 'treatments/tindakan_' . $invoice . '.pdf';
        
        $disk = config('filesystems.private_disk');
        if (!Storage::disk($disk)->exists($path)) {
            abort(404, 'File Laporan Tindakan tidak ditemukan.');
        }

        return $this->serveFile($path);
    }
}
