<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use App\Models\Doctor;
use App\Models\PaymentProof;
use App\Models\Transaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'waiting_payments'   => PaymentProof::where('status', 'pending')->count(),
            'active_consultations' => Consultation::active()->count(),
            'online_doctors'     => Doctor::online()->count(),
            'waiting_assignment' => Consultation::waitingAssignment()->count(),
            'completed_today'    => Consultation::completed()->today()->count(),
            'revenue_today'      => Transaction::where('payment_status', 'approved')
                                        ->whereDate('created_at', today())
                                        ->sum('amount'),
            'revenue_yesterday'  => Transaction::where('payment_status', 'approved')
                                        ->whereDate('created_at', \Carbon\Carbon::yesterday())
                                        ->sum('amount'),
            'revenue_total'      => Transaction::where('payment_status', 'approved')
                                        ->sum('amount'),
        ];

        $recentActivity = Consultation::with(['patient', 'doctor'])
            ->where('consultation_status', '!=', 'waiting_payment')
            ->latest()
            ->take(10)
            ->get();

        $pendingProofs = PaymentProof::with(['transaction.consultation.patient'])
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        // Chart data for 7 days
        $chartData = [
            'dates' => [],
            'revenues' => [],
            'consultations' => [],
        ];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = \Carbon\Carbon::today()->subDays($i);
            $chartData['dates'][] = $date->format('d M');
            
            $chartData['revenues'][] = (float) Transaction::where('payment_status', 'approved')
                ->whereDate('created_at', $date)
                ->sum('amount');
                
            $chartData['consultations'][] = Consultation::whereDate('created_at', $date)
                ->where('consultation_status', '!=', 'waiting_payment')
                ->count();
        }

        return view('admin.dashboard', compact('stats', 'recentActivity', 'pendingProofs', 'chartData'));
    }

    /**
     * Live activity feed for AJAX polling.
     */
    public function activityFeed()
    {
        $activities = Consultation::with(['patient', 'doctor'])
            ->latest()
            ->take(15)
            ->get()
            ->map(fn($c) => [
                'id'         => $c->id,
                'invoice'    => $c->invoice_number,
                'patient'    => $c->patient->full_name,
                'doctor'     => $c->doctor?->name,
                'status'     => $c->consultation_status,
                'label'      => $c->status_label,
                'color'      => $c->status_color,
                'created_at' => $c->created_at->diffForHumans(),
            ]);

        $stats = [
            'waiting_payments'     => PaymentProof::where('status', 'pending')->count(),
            'active_consultations' => Consultation::active()->count(),
            'online_doctors'       => Doctor::online()->count(),
            'waiting_assignment'   => Consultation::waitingAssignment()->count(),
        ];

        return response()->json(compact('activities', 'stats'));
    }
}
