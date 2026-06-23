<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\Doctor;
use App\Services\ConsultationService;
use Illuminate\Http\Request;

class ConsultationController extends Controller
{
    public function __construct(private ConsultationService $consultationService) {}

    public function index(Request $request)
    {
        $type = $request->query('type', 'all');

        $query = function() use ($type) {
            if ($type === 'all') {
                return Consultation::query();
            }
            return Consultation::where('type', $type);
        };

        $columns = [
            'waiting_admin_confirmation' => $query()->where('consultation_status', 'waiting_admin_confirmation')->with('patient')->latest()->get(),
            'waiting_assignment'         => $query()->waitingAssignment()->with('patient')->latest()->get(),
            'active'                     => $query()->active()->with(['patient', 'doctor'])->latest()->get(),
            'completed'                  => $query()->completed()->with(['patient', 'doctor'])->today()->latest()->get(),
        ];

        return view('admin.consultation.index', compact('columns', 'type'));
    }

    public function show(Consultation $consultation)
    {
        $consultation->load(['patient', 'doctor', 'transaction.latestProof', 'messages', 'prescription']);
        $availableDoctors = Doctor::available()->withCount(['activeConsultations'])->orderBy('name')->get();
        return view('admin.consultation.show', compact('consultation', 'availableDoctors'));
    }

    public function assign(Request $request, Consultation $consultation)
    {
        $request->validate(['doctor_id' => 'required|exists:doctors,id']);

        $doctor = Doctor::findOrFail($request->doctor_id);
        $this->consultationService->assignDoctor($consultation, $doctor);

        return redirect()->route('admin.consultation.show', $consultation)
            ->with('success', "Konsultasi berhasil ditugaskan ke {$doctor->name}.");
    }

    public function forceClose(Consultation $consultation)
    {
        $this->consultationService->end($consultation, 'admin');
        return redirect()->route('admin.consultation.index')
            ->with('success', 'Konsultasi telah diakhiri oleh admin.');
    }

}
