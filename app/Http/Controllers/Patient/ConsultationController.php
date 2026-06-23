<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\Message;
use App\Models\Patient;
use App\Models\Setting;
use App\Services\ConsultationService;
use App\Services\FileStorageService;
use App\Services\InvoiceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use App\Mail\ConsultationSummaryMail;

class ConsultationController extends Controller
{
    public function __construct(
        private InvoiceService       $invoiceService,
        private FileStorageService   $fileStorage,
        private ConsultationService  $consultationService,
    ) {}

    /**
     * Show the multi-step intake form.
     */
    public function create()
    {
        return view('patient.create');
    }

    /**
     * Handle form submission → create patient, invoice, consultation.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'full_name'            => 'required|string|max:150',
            'email'                => 'nullable|email|max:255',
            'whatsapp_number'      => 'required|string|max:20',
            'age'                  => 'required|integer|min:1|max:120',
            'gender'               => 'required|in:laki-laki,perempuan',
            'occupation'           => 'nullable|string|max:100',
            'province'             => 'required|string|max:100',
            'city'                 => 'required|string|max:100',
            'district'             => 'required|string|max:100',
            'village'              => 'required|string|max:100',
            'rt_rw'                => 'required|string|max:20',
            'complaint_description'=> 'required|string|min:20',
            'drug_allergies'       => 'required|string|max:255',
            'medical_image'        => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'medical_document'     => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // Store optional files
        $medicalImage    = null;
        $medicalDocument = null;

        if ($request->hasFile('medical_image')) {
            $stored = $this->fileStorage->store($request->file('medical_image'), 'medical-documents');
            $medicalImage = $stored['path'];
        }
        if ($request->hasFile('medical_document')) {
            $stored = $this->fileStorage->store($request->file('medical_document'), 'medical-documents');
            $medicalDocument = $stored['path'];
        }

        // Create patient with a session token
        $patient = Patient::create([
            'full_name'             => strip_tags($data['full_name']),
            'email'                 => $data['email'] ?? null,
            'whatsapp_number'       => $data['whatsapp_number'],
            'age'                   => $data['age'],
            'gender'                => $data['gender'],
            'occupation'            => strip_tags($data['occupation'] ?? null) ?: '-',
            'province'              => strip_tags($data['province']),
            'city'                  => strip_tags($data['city']),
            'district'              => strip_tags($data['district']),
            'village'               => strip_tags($data['village']),
            'rt_rw'                 => strip_tags($data['rt_rw']),
            'complaint_description' => strip_tags($data['complaint_description']),
            'drug_allergies'        => strip_tags($data['drug_allergies']),
            'medical_image'         => $medicalImage,
            'medical_document'      => $medicalDocument,
            'session_token'         => Str::uuid(),
        ]);

        // Generate invoice
        $invoiceNumber = $this->invoiceService->generate();

        $basePrice = (int) Setting::getValue('online_price', 25000);
        $discount = (int) Setting::getValue('online_discount', 0);
        $finalPrice = max(0, $basePrice - $discount);

        // Create consultation
        $consultation = Consultation::create([
            'patient_id'          => $patient->id,
            'invoice_number'      => $invoiceNumber,
            'history_code'        => strtoupper(Str::random(8)),
            'consultation_status' => 'waiting_payment',
            'duration_minutes'    => (int) env('CONSULTATION_DURATION_MINUTES', 15),
            'price'               => $finalPrice,
        ]);

        // Bind session
        session([
            'patient_token'    => $patient->session_token,
            'consultation_id'  => $consultation->id,
        ]);

        return redirect()->route('patient.invoice', $invoiceNumber)
            ->with('success', 'Data berhasil disimpan. Silakan lanjutkan pembayaran.');
    }

    /**
     * Show the multi-step intake form for Homecare.
     */
    public function createHomecare()
    {
        return view('patient.homecare_create');
    }

    /**
     * Handle homecare form submission
     */
    public function storeHomecare(Request $request)
    {
        $data = $request->validate([
            'full_name'            => 'required|string|max:150',
            'email'                => 'nullable|email|max:255',
            'whatsapp_number'      => 'required|string|max:20',
            'age'                  => 'required|integer|min:1|max:120',
            'gender'               => 'required|in:laki-laki,perempuan',
            'occupation'           => 'nullable|string|max:100',
            'province'             => 'required|string|max:100',
            'city'                 => 'required|string|max:100',
            'district'             => 'required|in:Bekasi Barat,Bekasi Timur,Bekasi Selatan',
            'village'              => 'required|string|max:100',
            'address'              => 'required|string|max:500',
            'complaint_description'=> 'required|string|min:20',
            'drug_allergies'       => 'required|string|max:255',
            'medical_image'        => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'medical_document'     => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // Validate scheduling logic
        // This is simplified. In a real app, you'd check schedule availability here.
        
        $medicalImage    = null;
        $medicalDocument = null;

        if ($request->hasFile('medical_image')) {
            $stored = $this->fileStorage->store($request->file('medical_image'), 'medical-documents');
            $medicalImage = $stored['path'];
        }
        if ($request->hasFile('medical_document')) {
            $stored = $this->fileStorage->store($request->file('medical_document'), 'medical-documents');
            $medicalDocument = $stored['path'];
        }

        // Create patient with a session token
        $patient = Patient::create([
            'full_name'             => strip_tags($data['full_name']),
            'email'                 => $data['email'] ?? null,
            'whatsapp_number'       => $data['whatsapp_number'],
            'age'                   => $data['age'],
            'gender'                => $data['gender'],
            'occupation'            => strip_tags($data['occupation'] ?? null) ?: '-',
            'province'              => strip_tags($data['province']),
            'city'                  => strip_tags($data['city']),
            'district'              => strip_tags($data['district']),
            'village'               => strip_tags($data['village']),
            'rt_rw'                 => '-', // Adjust if needed
            'bekasi_area'           => strip_tags($data['district']),
            'full_address'          => strip_tags($data['address']),
            'complaint_description' => strip_tags($data['complaint_description']),
            'drug_allergies'        => strip_tags($data['drug_allergies']),
            'medical_image'         => $medicalImage,
            'medical_document'      => $medicalDocument,
            'session_token'         => Str::uuid(),
        ]);

        $invoiceNumber = $this->invoiceService->generate();

        $basePrice = (int) Setting::getValue('homecare_price', 150000);
        $discount = (int) Setting::getValue('homecare_discount', 0);
        $finalPrice = max(0, $basePrice - $discount);

        $consultation = Consultation::create([
            'patient_id'          => $patient->id,
            'invoice_number'      => $invoiceNumber,
            'history_code'        => strtoupper(Str::random(8)),
            'consultation_status' => 'waiting_payment',
            'type'                => 'homecare',
            'address'             => $data['address'],
            'price'               => $finalPrice,
            'duration_minutes'    => 60,
        ]);

        session([
            'patient_token'    => $patient->session_token,
            'consultation_id'  => $consultation->id,
        ]);

        return redirect()->route('patient.invoice', $invoiceNumber)
            ->with('success', 'Permintaan Homecare berhasil dibuat. Menunggu konfirmasi admin.');
    }

    /**
     * Invoice + payment method selection page.
     */
    public function invoice(string $invoice)
    {
        $consultation = Consultation::with(['patient', 'transaction.latestPaymentSession'])
            ->where('invoice_number', $invoice)
            ->firstOrFail();

        $this->authorizePatient($consultation);

        if (in_array($consultation->consultation_status, ['waiting_payment', 'waiting_upload']) && $consultation->created_at->diffInHours(now()) >= 3) {
            if ($consultation->transaction) {
                $consultation->transaction->paymentProofs()->delete();
                $consultation->transaction->paymentSessions()->delete();
                $consultation->transaction->delete();
            }
            $consultation->delete();
            session()->forget(['patient_token', 'consultation_id']);
            return redirect()->route('home')->with('error', 'Sesi pembayaran telah kedaluwarsa (lebih dari 3 jam). Silakan isi ulang formulir pendaftaran.');
        }

        $openedDates = \App\Models\HomecareBlock::where('type', 'open')
            ->whereDate('date', '>=', today())
            ->get(['date', 'reason']);

        return view('patient.invoice', compact('consultation', 'openedDates'));
    }

    /**
     * Waiting room — patient polls for status updates.
     */
    public function waiting(string $token)
    {
        $patient = Patient::where('session_token', $token)->firstOrFail();
        $consultation = $patient->latestConsultation;

        if (!$consultation) {
            abort(404);
        }

        // If consultation is active, redirect to room
        if ($consultation->consultation_status === 'active') {
            return redirect()->to(
                URL::signedRoute('patient.room', ['id' => $consultation->id])
            );
        }

        return view('patient.waiting', compact('patient', 'consultation', 'token'));
    }

    /**
     * Consultation room (signed URL required).
     */
    public function room(Request $request, int $id)
    {
        $consultation = Consultation::with(['patient', 'doctor', 'messages', 'prescription'])
            ->find($id);

        if (!$consultation) {
            return redirect('/')->with('error', 'Sesi konsultasi tidak ditemukan.');
        }

        $this->authorizePatient($consultation);

        // Redirect to summary if completed
        if ($consultation->isCompleted()) {
            return redirect()->route('patient.summary', session('patient_token'));
        }

        return view('patient.room', compact('consultation'));
    }

    /**
     * Consultation summary page.
     */
    public function summary(string $token)
    {
        $patient = Patient::where('session_token', $token)->first();
        if (!$patient) {
            return redirect('/')->with('error', 'Sesi konsultasi tidak ditemukan atau sudah kadaluarsa.');
        }
        
        // Force survey if the latest consultation is unrated
        $latestConsultation = $patient->latestConsultation;
        if ($latestConsultation && $latestConsultation->isCompleted() && is_null($latestConsultation->rating)) {
            return redirect()->route('patient.survey', $patient->session_token)
                ->with('error', 'Silakan isi survei kepuasan terlebih dahulu untuk melihat riwayat konsultasi Anda.');
        }

        $consultations = $patient->consultations()
            ->with(['doctor', 'prescription'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        $consultation = $consultations->first();

        return view('patient.summary', compact('patient', 'consultations', 'consultation', 'token'));
    }

    /**
     * Show satisfaction survey form.
     */
    public function survey(string $token)
    {
        $patient = Patient::where('session_token', $token)->first();
        if (!$patient) {
            return redirect('/')->with('error', 'Sesi konsultasi tidak ditemukan atau sudah kadaluarsa.');
        }

        $consultation = $patient->latestConsultation;
        if (!$consultation) {
            return redirect('/')->with('error', 'Data konsultasi tidak ditemukan.');
        }

        if (!is_null($consultation->rating)) {
            return redirect()->route('patient.summary', $token);
        }

        return view('patient.survey', compact('patient', 'consultation', 'token'));
    }

    /**
     * Store satisfaction survey results.
     */
    public function storeSurvey(Request $request, string $token)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
        ]);

        $patient = Patient::where('session_token', $token)->first();
        if (!$patient) {
            return redirect('/')->with('error', 'Sesi konsultasi tidak ditemukan atau sudah kadaluarsa.');
        }

        $consultation = $patient->latestConsultation;
        if (!$consultation) {
            return redirect('/')->with('error', 'Data konsultasi tidak ditemukan.');
        }

        $consultation->update([
            'rating' => $request->rating,
            'review' => $request->review,
        ]);

        // Send email notification after survey is completed
        if ($patient->email) {
            Mail::to($patient->email)->queue(new ConsultationSummaryMail($consultation));
        }

        return redirect()->route('patient.summary', $token)
            ->with('success', 'Terima kasih! Survei kepuasan Anda telah berhasil disimpan, dan riwayat konsultasi telah dikirim ke email Anda.');
    }

    // ----------------------------------------------------------------
    // AJAX POLLING ENDPOINTS
    // ----------------------------------------------------------------

    /**
     * Poll consultation status (for waiting room).
     */
    public function pollStatus(string $token)
    {
        $patient = Patient::where('session_token', $token)->first();
        if (!$patient) {
            return response()->json(['error' => 'Not found'], 404);
        }

        $consultation = $patient->latestConsultation;

        $status = $consultation?->consultation_status;

        $room_url = null;
        $redirect_url = null;

        if ($status === 'active') {
            $room_url = URL::signedRoute('patient.room', ['id' => $consultation->id]);
        } elseif (in_array($status, ['waiting_upload', 'waiting_payment', 'payment_rejected'])) {
            $redirect_url = route('patient.invoice', $consultation->invoice_number);
        }

        return response()->json([
            'status'       => $status,
            'label'        => $consultation?->status_label,
            'room_url'     => $room_url,
            'redirect_url' => $redirect_url,
        ]);
    }

    /**
     * Poll new messages (AJAX polling endpoint).
     */
    public function pollMessages(Request $request, int $id)
    {
        $consultation = Consultation::findOrFail($id);
        $this->authorizePatient($consultation);

        $afterId = (int) $request->query('after', 0);

        $messages = Message::where('consultation_id', $id)
            ->where('id', '>', $afterId)
            ->orderBy('id')
            ->get()
            ->map(fn($m) => $this->formatMessage($m));

        // Mark unread doctor messages as read
        Message::where('consultation_id', $id)
            ->where('sender_type', 'doctor')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'messages'      => $messages,
            'status'        => $consultation->fresh()->consultation_status,
            'remaining_sec' => $consultation->remaining_seconds,
        ]);
    }

    /**
     * Send a message from patient.
     */
    public function sendMessage(Request $request, int $id)
    {
        $consultation = Consultation::findOrFail($id);
        $this->authorizePatient($consultation);

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

        if (empty($data['message']) && !$attachmentPath) {
            return response()->json(['error' => 'Pesan tidak boleh kosong.'], 422);
        }

        $message = Message::create([
            'consultation_id' => $id,
            'sender_type'     => 'patient',
            'sender_id'       => null,
            'message'         => isset($data['message']) ? $data['message'] : null,
            'attachment'      => $attachmentPath,
            'attachment_type' => $attachmentType,
            'is_read'         => false,
        ]);

        return response()->json(['message' => $this->formatMessage($message)]);
    }

    // ----------------------------------------------------------------
    // HELPERS
    // ----------------------------------------------------------------

    private function authorizePatient(Consultation $consultation): void
    {
        $token = session('patient_token');
        if (!$token || $consultation->patient->session_token !== $token) {
            abort(403, 'Akses tidak diizinkan.');
        }
    }

    private function formatMessage(Message $m): array
    {
        return [
            'id'              => $m->id,
            'sender_type'     => $m->sender_type,
            'message'         => $m->message,
            'attachment'      => $m->attachment,
            'attachment_type' => $m->attachment_type,
            'attachment_url'  => $m->attachment ? URL::signedRoute('files.attachment', $m->id) : null,
            'is_read'         => $m->is_read,
            'created_at'      => $m->created_at->format('H:i'),
        ];
    }
}
