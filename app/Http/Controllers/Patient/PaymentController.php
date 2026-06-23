<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\PaymentProof;
use App\Models\Transaction;
use App\Services\FileStorageService;
use App\Services\PaymentSessionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdminPaymentNotification;

class PaymentController extends Controller
{
    public function __construct(
        private PaymentSessionService $paymentSessionService,
        private FileStorageService    $fileStorage,
    ) {}

    /**
     * Select payment method and create transaction + session.
     */
    public function select(Request $request, string $invoice)
    {
        $consultation = Consultation::with('transaction')
            ->where('invoice_number', $invoice)
            ->firstOrFail();

        $this->authorizePatient($consultation);

        $data = $request->validate([
            'payment_method'   => 'required|in:qris,bank_transfer',
            'payment_provider' => 'required|string|max:100',
        ]);

        // Create or update transaction
        $transaction = $consultation->transaction ?? Transaction::create([
            'consultation_id'  => $consultation->id,
            'invoice_number'   => $invoice,
            'payment_method'   => $data['payment_method'],
            'payment_provider' => $data['payment_provider'],
            'amount'           => $consultation->price ?? (int) env('DEFAULT_CONSULTATION_PRICE', 25000),
            'payment_status'   => 'pending_payment',
        ]);

        if ($consultation->transaction) {
            // Update existing transaction
            $transaction->update([
                'payment_method'   => $data['payment_method'],
                'payment_provider' => $data['payment_provider'],
                'payment_status'   => 'pending_payment',
            ]);
        }

        // Update consultation status
        $consultation->update(['consultation_status' => 'waiting_payment']);

        // Create payment session (simulated VA/QRIS)
        $session = $this->paymentSessionService->create($transaction);

        return response()->json([
            'success'          => true,
            'payment_method'   => $data['payment_method'],
            'provider'         => $data['payment_provider'],
            'simulated_number' => $session->simulated_number,
            'expires_at'       => $session->expires_at->toIso8601String(),
            'remaining_sec'    => $session->remaining_seconds,
        ]);
    }

    /**
     * Refresh a QRIS / VA session.
     */
    public function refresh(Request $request, string $invoice)
    {
        $consultation = Consultation::with('transaction')
            ->where('invoice_number', $invoice)
            ->firstOrFail();

        $this->authorizePatient($consultation);

        if (!$consultation->transaction) {
            return response()->json(['error' => 'Tidak ada transaksi aktif.'], 404);
        }

        $session = $this->paymentSessionService->refresh($consultation->transaction);

        return response()->json([
            'simulated_number' => $session->simulated_number,
            'expires_at'       => $session->expires_at->toIso8601String(),
            'remaining_sec'    => $session->remaining_seconds,
        ]);
    }

    /**
     * Upload payment proof.
     */
    public function uploadProof(Request $request, string $invoice)
    {
        $consultation = Consultation::with('transaction')
            ->where('invoice_number', $invoice)
            ->firstOrFail();

        $this->authorizePatient($consultation);

        $data = $request->validate([
            'proof_file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'notes'      => 'nullable|string|max:500',
            'homecare_date' => 'nullable|date',
            'homecare_time' => 'nullable|string',
        ]);

        if ($consultation->type === 'homecare') {
            if (empty($data['homecare_date']) || empty($data['homecare_time'])) {
                return response()->json(['error' => 'Silakan lengkapi jadwal kunjungan Homecare.'], 422);
            }

            // Validasi Blokir Admin
            $fullDayBlock = \App\Models\HomecareBlock::where('date', $data['homecare_date'])->whereNull('time')->exists();
            if ($fullDayBlock) {
                return response()->json(['error' => 'Maaf, seluruh jadwal di tanggal tersebut telah ditutup.'], 422);
            }
            $timeBlock = \App\Models\HomecareBlock::where('date', $data['homecare_date'])->where('time', 'like', substr($data['homecare_time'], 0, 5) . '%')->exists();
            if ($timeBlock) {
                return response()->json(['error' => 'Maaf, jadwal di jam tersebut telah ditutup oleh Admin.'], 422);
            }

            // Validasi Booking Orang Lain
            $alreadyBooked = \App\Models\Consultation::where('type', 'homecare')
                ->where('id', '!=', $consultation->id)
                ->whereDate('homecare_schedule_date', $data['homecare_date'])
                ->where('homecare_schedule_time', 'like', substr($data['homecare_time'], 0, 5) . '%')
                ->whereNotIn('consultation_status', ['rejected', 'payment_rejected'])
                ->exists();
            if ($alreadyBooked) {
                return response()->json(['error' => 'Maaf, jadwal di jam tersebut baru saja dipesan pasien lain. Silakan pilih jadwal lain.'], 422);
            }

            $consultation->update([
                'homecare_schedule_date' => $data['homecare_date'],
                'homecare_schedule_time' => $data['homecare_time'],
            ]);
        }

        $stored = $this->fileStorage->store($request->file('proof_file'), 'payment-proofs');

        // Ensure transaction exists, if not, create a manual one
        if (!$consultation->transaction) {
            $consultation->transaction()->create([
                'invoice_number'   => $consultation->invoice_number,
                'payment_method'   => 'manual_transfer',
                'payment_provider' => 'manual',
                'amount'           => $consultation->price,
                'payment_status'   => 'waiting_admin_confirmation',
            ]);
            $consultation->refresh();
        }

        // Update existing proof or create new one
        PaymentProof::updateOrCreate(
            ['transaction_id' => $consultation->transaction->id],
            [
                'file_path'        => $stored['path'],
                'file_type'        => $stored['type'],
                'notes'            => $data['notes'] ?? null,
                'status'           => 'pending',
                'rejection_reason' => null,
            ]
        );

        // Update statuses
        $consultation->transaction->update(['payment_status' => 'waiting_admin_confirmation']);
        $consultation->update(['consultation_status' => 'waiting_admin_confirmation']);

        // Kirim email notifikasi ke admin
        try {
            Mail::to('admintemudokter@gmail.com')->send(new AdminPaymentNotification($consultation));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Gagal mengirim email notifikasi pembayaran: ' . $e->getMessage());
        }

        // Beri tahu Admin Dashboard secara Real-time
        event(new \App\Events\AdminDashboardUpdated('Pasien telah mengunggah bukti pembayaran baru.'));

        return response()->json([
            'success'      => true,
            'token'        => $consultation->patient->session_token,
            'waiting_url'  => route('patient.waiting', $consultation->patient->session_token),
        ]);
    }

    private function authorizePatient(Consultation $consultation): void
    {
        $token = session('patient_token');
        if (!$token || $consultation->patient->session_token !== $token) {
            abort(403, 'Akses tidak diizinkan.');
        }
    }
}
