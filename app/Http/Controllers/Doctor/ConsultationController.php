<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\Message;
use App\Models\Medicine;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use App\Models\SickLeave;
use App\Services\ConsultationService;
use App\Services\FileStorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class ConsultationController extends Controller
{
    public function __construct(
        private ConsultationService $consultationService,
        private FileStorageService  $fileStorage,
    ) {}

    public function show(Consultation $consultation)
    {
        $doctor = auth('doctor')->user();

        // Ensure doctor owns this consultation
        if ($consultation->doctor_id != $doctor->id) {
            abort(403);
        }

        $consultation->load(['patient', 'doctor', 'messages', 'prescription']);

        $medicines = Medicine::orderBy('name')->get();
        $treatments = \App\Models\Treatment::orderBy('name')->get();

        return view('doctor.consultation.room', compact('consultation', 'doctor', 'medicines', 'treatments'));
    }

    public function end(Request $request, Consultation $consultation)
    {
        $doctor = auth('doctor')->user();
        if ($consultation->doctor_id != $doctor->id) abort(403);

        $this->consultationService->end($consultation, 'doctor');

        return response()->json(['success' => true]);
    }

    public function uploadPrescription(Request $request, Consultation $consultation)
    {
        $doctor = auth('doctor')->user();
        if ($consultation->doctor_id != $doctor->id) abort(403);

        $data = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.medicine_id' => 'required|exists:medicines,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.instructions' => 'required|string|max:255',
            'items.*.kegunaan' => 'nullable|string',
            'items.*.notes' => 'nullable|string',
        ]);

        // Create prescription record
        $prescription = Prescription::updateOrCreate(
            ['consultation_id' => $consultation->id],
            ['doctor_id' => $doctor->id]
        );

        // Delete old items
        $prescription->items()->delete();

        // Add new items
        foreach ($data['items'] as $item) {
            $medicine = Medicine::find($item['medicine_id']);
            $prescription->items()->create([
                'medicine_id' => $medicine->id,
                'medicine_name' => $medicine->name,
                'kegunaan' => $item['kegunaan'] ?? null,
                'quantity' => $item['quantity'],
                'instructions' => $item['instructions'],
                'notes' => $item['notes'] ?? null,
            ]);
        }

        // Generate PDF
        $prescription->load('items');
        $pdf = Pdf::loadView('pdf.prescription', compact('consultation', 'doctor', 'prescription'));
        
        $fileName = 'resep_' . $consultation->invoice_number . '.pdf';
        $path = 'prescriptions/' . $fileName;
        Storage::disk('private')->put($path, $pdf->output());

        $prescription->update([
            'file_path' => $path,
            'file_type' => 'pdf'
        ]);

        // Send a message with the prescription as an attachment to the chat
        Message::create([
            'consultation_id' => $consultation->id,
            'sender_type'     => 'doctor',
            'sender_id'       => $doctor->id,
            'message'         => "Resep obat telah diterbitkan. Silakan unduh resep obat Anda.",
            'attachment'      => $path,
            'attachment_type' => 'pdf',
            'is_read'         => false,
        ]);

        return response()->json(['success' => true, 'message' => 'Resep berhasil dibuat.']);
    }

    public function uploadSickLeave(Request $request, Consultation $consultation)
    {
        $doctor = auth('doctor')->user();
        if ($consultation->doctor_id != $doctor->id) abort(403);

        $data = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:1000',
        ]);

        // Generate PDF
        $sickLeave = new SickLeave([
            'consultation_id' => $consultation->id,
            'doctor_id' => $doctor->id,
            'patient_id' => $consultation->patient_id,
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'reason' => $data['reason'],
        ]);

        $pdf = Pdf::loadView('pdf.sick_leave', compact('consultation', 'doctor', 'sickLeave'));
        
        $fileName = 'surat_sakit_' . $consultation->invoice_number . '.pdf';
        $path = 'sick_leaves/' . $fileName;
        Storage::disk('private')->put($path, $pdf->output());

        $sickLeave->file_path = $path;
        $sickLeave->save();

        // Send a message with the sick leave as an attachment to the chat
        Message::create([
            'consultation_id' => $consultation->id,
            'sender_type'     => 'doctor',
            'sender_id'       => $doctor->id,
            'message'         => "Surat keterangan sakit telah diterbitkan. Silakan unduh surat sakit Anda.",
            'attachment'      => $path,
            'attachment_type' => 'pdf',
            'is_read'         => false,
        ]);

        return response()->json(['success' => true, 'message' => 'Surat sakit berhasil dibuat.']);
    }

    public function uploadHomecareReport(Request $request, Consultation $consultation)
    {
        $doctor = auth('doctor')->user();
        if ($consultation->doctor_id != $doctor->id) abort(403);
        if ($consultation->type !== 'homecare') abort(403, 'Bukan layanan homecare');

        $data = $request->validate([
            'homecare_report' => 'required|string|min:3',
        ]);

        $consultation->update([
            'homecare_report' => $data['homecare_report'],
        ]);

        // Send a message to chat
        Message::create([
            'consultation_id' => $consultation->id,
            'sender_type'     => 'doctor',
            'sender_id'       => $doctor->id,
            'message'         => "Laporan Kunjungan Homecare telah ditambahkan oleh dokter.",
            'is_read'         => false,
        ]);

        return response()->json(['success' => true, 'message' => 'Laporan kunjungan berhasil disimpan.']);
    }

    public function uploadTreatment(Request $request, Consultation $consultation)
    {
        $doctor = auth('doctor')->user();
        if ($consultation->doctor_id != $doctor->id) abort(403);
        if ($consultation->type !== 'homecare') abort(403, 'Bukan layanan homecare');

        $data = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.treatment_id' => 'required|exists:treatments,id',
        ]);

        // Delete old items for this consultation (if they want to replace it)
        \App\Models\ConsultationTreatment::where('consultation_id', $consultation->id)->delete();

        // Add new items
        foreach ($data['items'] as $item) {
            $treatment = \App\Models\Treatment::find($item['treatment_id']);
            \App\Models\ConsultationTreatment::create([
                'consultation_id' => $consultation->id,
                'treatment_id' => $treatment->id,
                'treatment_name' => $treatment->name,
                'bentuk_sediaan' => $treatment->bentuk_sediaan,
                'price' => $treatment->price,
            ]);
        }

        // Generate PDF
        $consultation->load(['patient', 'doctor', 'treatments']);
        $pdf = Pdf::loadView('pdf.treatment', compact('consultation', 'doctor'));
        
        $fileName = 'tindakan_' . $consultation->invoice_number . '.pdf';
        $path = 'treatments/' . $fileName;
        Storage::disk('private')->put($path, $pdf->output());

        // Send a message to chat
        Message::create([
            'consultation_id' => $consultation->id,
            'sender_type'     => 'doctor',
            'sender_id'       => $doctor->id,
            'message'         => "Laporan Tindakan telah diterbitkan. Silakan unduh laporan tindakan Anda.",
            'attachment'      => $path,
            'attachment_type' => 'pdf',
            'is_read'         => false,
        ]);

        return response()->json(['success' => true, 'message' => 'Tindakan berhasil disimpan.']);
    }

    // ----------------------------------------------------------------
    // AJAX POLLING
    // ----------------------------------------------------------------

    public function pollMessages(Request $request, int $id)
    {
        $consultation = Consultation::findOrFail($id);
        $doctor = auth('doctor')->user();
        if ($consultation->doctor_id != $doctor->id) abort(403);

        $afterId = (int) $request->query('after', 0);

        $messages = Message::where('consultation_id', $id)
            ->where('id', '>', $afterId)
            ->orderBy('id')
            ->get()
            ->map(fn($m) => [
                'id'              => $m->id,
                'sender_type'     => $m->sender_type,
                'message'         => $m->message,
                'attachment'      => $m->attachment,
                'attachment_type' => $m->attachment_type,
                'attachment_url'  => $m->attachment ? URL::signedRoute('files.attachment', $m->id) : null,
                'is_read'         => $m->is_read,
                'created_at'      => $m->created_at->format('H:i'),
            ]);

        // Mark patient messages as read
        Message::where('consultation_id', $id)
            ->where('sender_type', 'patient')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'messages'      => $messages,
            'status'        => $consultation->fresh()->consultation_status,
            'remaining_sec' => $consultation->remaining_seconds,
        ]);
    }

    public function sendMessage(Request $request, int $id)
    {
        $consultation = Consultation::findOrFail($id);
        $doctor = auth('doctor')->user();
        if ($consultation->doctor_id != $doctor->id) abort(403);

        if (!$consultation->isActive()) {
            return response()->json(['error' => 'Konsultasi sudah berakhir.'], 403);
        }

        $data = $request->validate([
            'message'    => 'nullable|string|max:2000',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $attachmentPath = null;
        $attachmentType = 'none';

        if ($request->hasFile('attachment')) {
            $stored = $this->fileStorage->store($request->file('attachment'), 'chat-attachments');
            $attachmentPath = $stored['path'];
            $attachmentType = $stored['type'];
        }

        $message = Message::create([
            'consultation_id' => $id,
            'sender_type'     => 'doctor',
            'sender_id'       => $doctor->id,
            'message'         => isset($data['message']) ? $data['message'] : null,
            'attachment'      => $attachmentPath,
            'attachment_type' => $attachmentType,
            'is_read'         => false,
        ]);

        return response()->json([
            'message' => [
                'id'              => $message->id,
                'sender_type'     => $message->sender_type,
                'message'         => $message->message,
                'attachment'      => $message->attachment,
                'attachment_type' => $message->attachment_type,
                'attachment_url'  => $message->attachment ? URL::signedRoute('files.attachment', $message->id) : null,
                'is_read'         => $message->is_read,
                'created_at'      => $message->created_at->format('H:i'),
            ],
        ]);
    }
}
