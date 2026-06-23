<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\Doctor;
use App\Models\PaymentProof;
use App\Services\ConsultationService;
use Illuminate\Http\Request;

class PaymentVerificationController extends Controller
{
    public function __construct(private ConsultationService $consultationService) {}

    public function index(Request $request)
    {
        $search = $request->input('search');
        $type = $request->input('type');

        $query = Consultation::with(['patient', 'transaction.latestProof'])
            ->whereHas('transaction.paymentProofs');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->whereHas('patient', function ($q2) use ($search) {
                    $q2->where('full_name', 'like', "%{$search}%");
                })->orWhere('invoice_number', 'like', "%{$search}%");
            });
        }
        
        if ($type === 'homecare') {
            $query->where('type', 'homecare');
        } elseif ($type === 'online') {
            $query->where(function($q) {
                $q->whereNull('type')->orWhere('type', '!=', 'homecare');
            });
        }

        $consultations = $query->orderByRaw("CASE consultation_status WHEN 'waiting_admin_confirmation' THEN 0 WHEN 'waiting_upload' THEN 1 WHEN 'waiting_payment' THEN 2 ELSE 3 END")
            ->latest()
            ->paginate(20)
            ->appends(['search' => $search, 'type' => $type]);

        return view('admin.payment.index', compact('consultations', 'search', 'type'));
    }

    public function show(PaymentProof $proof)
    {
        $proof->load(['transaction.consultation.patient']);
        $consultation = $proof->transaction->consultation;
        return view('admin.payment.show', compact('proof', 'consultation'));
    }

    public function approve(Request $request, PaymentProof $proof)
    {
        $proof->update(['status' => 'approved']);
        $proof->transaction->update(['payment_status' => 'approved']);

        // Activate consultation
        $this->consultationService->activate($proof->transaction->consultation);

        return redirect()->route('admin.payment.index')
            ->with('success', 'Pembayaran disetujui. Konsultasi siap ditugaskan ke dokter.');
    }

    public function reject(Request $request, PaymentProof $proof)
    {
        $request->validate(['reason' => 'required|string|max:500']);

        $proof->update([
            'status'           => 'rejected',
            'rejection_reason' => $request->reason,
        ]);
        $proof->transaction->update(['payment_status' => 'rejected']);
        $proof->transaction->consultation->update(['consultation_status' => 'payment_rejected']);

        return redirect()->route('admin.payment.index')
            ->with('success', 'Pembayaran ditolak dan pasien telah diberitahu.');
    }

    public function requestReupload(Request $request, PaymentProof $proof)
    {
        $request->validate(['reason' => 'required|string|max:500']);

        $proof->update([
            'status'           => 'request_reupload',
            'rejection_reason' => $request->reason,
        ]);
        $proof->transaction->update(['payment_status' => 'request_reupload']);
        $proof->transaction->consultation->update(['consultation_status' => 'waiting_upload']);

        return redirect()->route('admin.payment.index')
            ->with('success', 'Permintaan upload ulang bukti pembayaran telah dikirim.');
    }

    public function destroyConsultation(Consultation $consultation)
    {
        if ($consultation->transaction) {
            $consultation->transaction->paymentProofs()->delete();
            $consultation->transaction->paymentSessions()->delete();
            $consultation->transaction->delete();
        }
        // Delete messages if any
        $consultation->messages()->delete();
        $consultation->delete();
        
        return redirect()->route('admin.payment.index')
            ->with('success', 'Konsultasi berhasil dihapus.');
    }
}
