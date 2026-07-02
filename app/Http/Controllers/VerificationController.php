<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function show($type, $invoice)
    {
        $consultation = Consultation::with(['patient', 'doctor', 'sickLeave', 'prescription.items', 'treatments'])->where('invoice_number', $invoice)->firstOrFail();

        if ($type === 'sick-leave' && !$consultation->sickLeave) {
            abort(404, 'Surat Sakit tidak ditemukan.');
        }

        if ($type === 'prescription' && !$consultation->prescription) {
            abort(404, 'Resep Obat tidak ditemukan.');
        }

        if ($type === 'treatment' && $consultation->treatments->isEmpty()) {
            abort(404, 'Laporan Tindakan tidak ditemukan.');
        }

        return view('verify.document', compact('consultation', 'type'));
    }
}
