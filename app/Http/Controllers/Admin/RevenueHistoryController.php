<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;
use PDF; // Assuming barryvdh/laravel-dompdf is installed as in RevenueController

class RevenueHistoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::onlyTrashed()->with(['consultation' => function ($q) {
            $q->withTrashed()->with('patient');
        }])->where('payment_status', 'approved');

        // Filter Month
        $month = $request->input('month', date('n'));
        // Filter Year
        $year = $request->input('year', date('Y'));
        
        $query->whereMonth('created_at', $month)
              ->whereYear('created_at', $year);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('consultation.patient', function($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $transactions = $query->orderBy('deleted_at', 'desc')->paginate(20);

        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        // Get available years from trashed transactions
        $years = Transaction::onlyTrashed()
            ->selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();
            
        if (empty($years)) {
            $years = [date('Y')];
        }

        return view('admin.revenue.history', compact('transactions', 'month', 'year', 'months', 'years'));
    }

    public function exportCsv(Request $request)
    {
        $month = $request->input('month', date('n'));
        $year = $request->input('year', date('Y'));
        
        $transactions = Transaction::onlyTrashed()->with(['consultation' => function ($q) {
            $q->withTrashed()->with('patient');
        }])
        ->where('payment_status', 'approved')
        ->whereMonth('created_at', $month)
        ->whereYear('created_at', $year)
        ->orderBy('deleted_at', 'desc')
        ->get();

        $filename = "riwayat_pendapatan_{$year}_{$month}.csv";
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use ($transactions) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Tanggal Hapus', 'Invoice', 'Tanggal Transaksi', 'Pasien', 'Metode Bayar', 'Provider', 'Nominal']);

            foreach ($transactions as $t) {
                fputcsv($file, [
                    $t->deleted_at->format('d/m/Y H:i'),
                    $t->invoice_number,
                    $t->created_at->format('d/m/Y H:i'),
                    $t->consultation->patient->name ?? '-',
                    strtoupper(str_replace('_', ' ', $t->payment_method)),
                    strtoupper($t->payment_provider),
                    'Rp ' . number_format($t->amount, 0, ',', '.')
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf(Request $request)
    {
        $month = $request->input('month', date('n'));
        $year = $request->input('year', date('Y'));
        
        $transactions = Transaction::onlyTrashed()->with(['consultation' => function ($q) {
            $q->withTrashed()->with('patient');
        }])
        ->where('payment_status', 'approved')
        ->whereMonth('created_at', $month)
        ->whereYear('created_at', $year)
        ->orderBy('deleted_at', 'desc')
        ->get();

        $monthName = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ][$month];

        // Resusing the revenue PDF view but passing different variables or 
        // we can create a dedicated history PDF view. Let's reuse admin.revenue.pdf and pass history flag or create a new one.
        // It's safer to create admin.revenue.history_pdf
        $pdf = PDF::loadView('admin.revenue.history_pdf', compact('transactions', 'monthName', 'year'));
        return $pdf->download("riwayat_pendapatan_{$year}_{$month}.pdf");
    }
}
