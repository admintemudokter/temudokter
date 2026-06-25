<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use Illuminate\Http\Request;

class ConsultationHistoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Consultation::archived()
            ->with(['patient', 'doctor', 'transaction']);

        // Filter by Type
        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        // Filter by Date Range
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        // Search by Patient Name, Doctor Name, or Invoice/History Code
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->whereHas('patient', function($pq) use ($searchTerm) {
                    $pq->where('full_name', 'like', "%{$searchTerm}%")
                       ->orWhere('whatsapp_number', 'like', "%{$searchTerm}%");
                })
                ->orWhereHas('doctor', function($dq) use ($searchTerm) {
                    $dq->where('name', 'like', "%{$searchTerm}%");
                })
                ->orWhere('invoice_number', 'like', "%{$searchTerm}%")
                ->orWhere('history_code', 'like', "%{$searchTerm}%");
            });
        }

        $histories = $query->latest()->paginate(15)->withQueryString();

        return view('admin.consultation.history', compact('histories'));
    }
}
