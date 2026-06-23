<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function historyForm()
    {
        return view('patient.history_form');
    }

    public function checkHistory(Request $request)
    {
        $request->validate([
            'whatsapp_number' => 'required|string',
            'history_code' => 'required|string',
        ]);

        $consultation = Consultation::with(['patient', 'doctor', 'transaction'])
            ->whereHas('patient', function ($q) use ($request) {
                $q->where('whatsapp_number', $request->whatsapp_number);
            })
            ->where('history_code', $request->history_code)
            ->where('consultation_status', 'completed')
            ->first();

        if (!$consultation) {
            return back()->withErrors(['history_code' => 'No. WhatsApp atau Kode Riwayat tidak valid, atau konsultasi belum selesai.'])->withInput();
        }

        // Redirect directly to the consultation view, no list needed since code is unique per consultation
        return redirect()->signedRoute('history.show', ['id' => $consultation->id]);
    }

    public function showHistory($id)
    {
        $consultation = Consultation::with(['patient', 'doctor', 'messages', 'prescription', 'transaction'])->findOrFail($id);

        if (is_null($consultation->rating) && $consultation->isCompleted()) {
            return redirect()->route('patient.survey', $consultation->patient->session_token)
                ->with('error', 'Silakan isi survei kepuasan terlebih dahulu untuk mengakses riwayat konsultasi.');
        }

        return view('patient.history_view', compact('consultation'));
    }
}
