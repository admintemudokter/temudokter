<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RevenueController extends Controller
{
    public function index(Request $request)
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));

        // Hitung statistik
        $totalRevenue = Transaction::where('payment_status', 'approved')->sum('amount');
        
        $todayRevenue = Transaction::where('payment_status', 'approved')
                                   ->whereDate('created_at', $today)
                                   ->sum('amount');
                                   
        $yesterdayRevenue = Transaction::where('payment_status', 'approved')
                                       ->whereDate('created_at', $yesterday)
                                       ->sum('amount');

        // Dapatkan rincian pendapatan harian (Group by Date)
        $dailyRevenues = Transaction::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(id) as total_transactions'),
                DB::raw('SUM(amount) as total_revenue')
            )
            ->where('payment_status', 'approved')
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->paginate(15)
            ->withQueryString();

        $chartDataRaw = Transaction::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(amount) as total_revenue')
            )
            ->where('payment_status', 'approved')
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $chartLabels = [];
        $chartValues = [];

        $daysInMonth = Carbon::createFromDate($year, $month, 1)->daysInMonth;
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $dateString = sprintf('%04d-%02d-%02d', $year, $month, $i);
            $chartLabels[] = $i . ' ' . Carbon::createFromDate($year, $month, 1)->translatedFormat('M');
            
            $match = $chartDataRaw->firstWhere('date', $dateString);
            $chartValues[] = $match ? $match->total_revenue : 0;
        }

        return view('admin.revenue.index', compact('totalRevenue', 'todayRevenue', 'yesterdayRevenue', 'dailyRevenues', 'month', 'year', 'chartLabels', 'chartValues'));
    }

    public function export(Request $request, $format)
    {
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));

        $data = Transaction::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(id) as total_transactions'),
                DB::raw('SUM(amount) as total_revenue')
            )
            ->where('payment_status', 'approved')
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();

        if ($format === 'csv') {
            $filename = "Laporan_Pendapatan_{$year}_{$month}.csv";
            $headers = [
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=$filename",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            ];

            $callback = function() use($data) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['Tanggal', 'Total Transaksi', 'Pendapatan Bersih (Rp)']);
                foreach ($data as $row) {
                    fputcsv($file, [
                        Carbon::parse($row->date)->translatedFormat('d F Y'),
                        $row->total_transactions,
                        $row->total_revenue
                    ]);
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        } elseif ($format === 'pdf') {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.revenue.pdf', compact('data', 'month', 'year'));
            return $pdf->download("Laporan_Pendapatan_{$year}_{$month}.pdf");
        }

        abort(404);
    }
}
