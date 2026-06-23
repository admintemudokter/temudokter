<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $doctor = auth('doctor')->user();
        $type = $request->query('type', 'all');

        $activeQuery = $doctor->activeConsultations();
        $waitingQuery = Consultation::waitingAssignment();
        $completedQuery = $doctor->consultations()->completed()->today();

        if ($type !== 'all') {
            $activeQuery->where('type', $type);
            $waitingQuery->where('type', $type);
            $completedQuery->where('type', $type);
        }

        $stats = [
            'active'    => $activeQuery->count(),
            'waiting'   => $waitingQuery->count(),
            'completed' => $completedQuery->count(),
        ];

        $activeConsultations = (clone $activeQuery)
            ->with('patient')
            ->latest('started_at')
            ->get();

        $recentConsultations = $doctor->consultations()
            ->when($type !== 'all', fn($q) => $q->where('type', $type))
            ->with('patient')
            ->completed()
            ->latest()
            ->take(5)
            ->get();

        return view('doctor.dashboard', compact('doctor', 'stats', 'activeConsultations', 'recentConsultations', 'type'));
    }

    /**
     * Toggle doctor availability status.
     */
    public function toggleStatus(Request $request)
    {
        $doctor = auth('doctor')->user();

        $data = $request->validate([
            'status' => 'required|in:online,offline',
        ]);

        // Don't allow going offline during active consultation
        if ($data['status'] === 'offline' && $doctor->activeConsultations()->exists()) {
            return response()->json([
                'error' => 'Anda tidak dapat offline saat masih ada konsultasi aktif.',
            ], 422);
        }

        $doctor->update(['status' => $data['status']]);

        return response()->json([
            'success' => true,
            'status'  => $doctor->status,
            'label'   => $doctor->status_label,
        ]);
    }
}
