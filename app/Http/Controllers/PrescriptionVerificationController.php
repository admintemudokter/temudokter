<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\Prescription;
use Illuminate\Http\Request;

class PrescriptionVerificationController extends Controller
{
    /**
     * Tampilkan halaman verifikasi resep untuk publik.
     */
    public function show(string $invoice_number)
    {
        $consultation = Consultation::where('invoice_number', $invoice_number)
            ->with(['patient', 'doctor', 'prescription.items'])
            ->firstOrFail();

        $prescription = $consultation->prescription;

        if (!$prescription) {
            abort(404, 'Resep tidak ditemukan');
        }

        return view('verify.prescription', compact('consultation', 'prescription'));
    }
}
