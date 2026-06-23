@extends('layouts.admin')
@section('title', 'Laporan Pendapatan')
@section('page_title', 'Laporan Pendapatan')
@section('page_subtitle', 'Ringkasan dan riwayat pendapatan finansial KonsulKU')

@section('content')

{{-- ===== STAT CARDS ===== --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    
    {{-- Total Revenue --}}
    <div class="card card-body">
        <div class="stat-icon bg-green-100 mb-3">
            <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div class="text-2xl font-bold text-green-700 font-heading">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
        <div class="stat-label mt-1 text-sm">Total Keseluruhan</div>
    </div>

    {{-- Today Revenue --}}
    <div class="card card-body">
        <div class="stat-icon bg-emerald-100 mb-3">
            <svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
        <div class="text-2xl font-bold text-emerald-700 font-heading">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</div>
        <div class="stat-label mt-1 text-sm">Hari Ini ({{ \Carbon\Carbon::today()->format('d M Y') }})</div>
    </div>

    {{-- Yesterday Revenue --}}
    <div class="card card-body">
        <div class="stat-icon bg-slate-100 mb-3">
            <svg class="w-6 h-6 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div class="text-2xl font-bold text-slate-700 font-heading">Rp {{ number_format($yesterdayRevenue, 0, ',', '.') }}</div>
        <div class="stat-label mt-1 text-sm">Kemarin ({{ \Carbon\Carbon::yesterday()->format('d M Y') }})</div>
    </div>

</div>

{{-- ===== REVENUE TABLE ===== --}}
<div class="card mb-8">
    <div class="card-body border-b border-slate-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <h3 class="font-heading font-bold text-slate-800">Grafik Pendapatan Bulan Ini</h3>
    </div>
    <div class="p-6">
        <canvas id="revenueChart" height="100"></canvas>
    </div>
</div>

<div class="card">
    <div class="card-body border-b border-slate-100 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <h3 class="font-heading font-bold text-slate-800">Riwayat Pendapatan Harian</h3>
        
        <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
            <form method="GET" action="{{ route('admin.revenue.index') }}" class="flex items-center space-x-2 w-full sm:w-auto">
                <select name="month" class="form-select text-sm border-slate-200 rounded-lg py-2 pl-3 pr-8 focus:border-brand-500 focus:ring-brand-500 w-full sm:w-auto">
                    @for($i=1; $i<=12; $i++)
                        <option value="{{ sprintf('%02d', $i) }}" {{ $month == sprintf('%02d', $i) ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                        </option>
                    @endfor
                </select>
                
                <select name="year" class="form-select text-sm border-slate-200 rounded-lg py-2 pl-3 pr-8 focus:border-brand-500 focus:ring-brand-500 w-full sm:w-auto">
                    @for($i=date('Y'); $i>=2024; $i--)
                        <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
                
                <button type="submit" class="bg-slate-100 text-slate-700 px-4 py-2 rounded-lg text-sm font-medium hover:bg-slate-200 transition-colors">
                    Filter
                </button>
            </form>

            <div class="flex items-center gap-2">
                <a href="{{ route('admin.revenue.export', ['format' => 'csv', 'month' => $month, 'year' => $year]) }}" class="bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-emerald-700 transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    CSV
                </a>
                <a href="{{ route('admin.revenue.export', ['format' => 'pdf', 'month' => $month, 'year' => $year]) }}" class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-red-700 transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    PDF
                </a>
            </div>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100">
                    <th class="py-3 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider">Tanggal</th>
                    <th class="py-3 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Total Transaksi</th>
                    <th class="py-3 px-6 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Pendapatan Bersih</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($dailyRevenues as $daily)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="py-4 px-6">
                        <div class="font-medium text-slate-800">{{ \Carbon\Carbon::parse($daily->date)->translatedFormat('l, d F Y') }}</div>
                    </td>
                    <td class="py-4 px-6 text-center">
                        <span class="inline-flex items-center justify-center bg-brand-50 text-brand-700 text-xs font-bold px-2.5 py-1 rounded-full">
                            {{ $daily->total_transactions }}
                        </span>
                    </td>
                    <td class="py-4 px-6 text-right">
                        <div class="font-bold text-green-600">Rp {{ number_format($daily->total_revenue, 0, ',', '.') }}</div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="py-12 px-6 text-center text-slate-400">
                        <svg class="w-12 h-12 mx-auto mb-3 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="font-medium">Belum ada data pendapatan tercatat.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($dailyRevenues->hasPages())
    <div class="px-6 py-4 border-t border-slate-100">
        {{ $dailyRevenues->links() }}
    </div>
    @endif
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('revenueChart').getContext('2d');
        
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(16, 185, 129, 0.2)'); // brand/emerald color
        gradient.addColorStop(1, 'rgba(16, 185, 129, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartLabels) !!},
                datasets: [{
                    label: 'Pendapatan Bersih (Rp)',
                    data: {!! json_encode($chartValues) !!},
                    borderColor: '#10b981', // emerald-500
                    backgroundColor: gradient,
                    borderWidth: 2,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#10b981',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 12,
                        titleFont: { size: 13, family: "'Inter', sans-serif" },
                        bodyFont: { size: 14, family: "'Inter', sans-serif", weight: 'bold' },
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f1f5f9',
                            drawBorder: false,
                        },
                        ticks: {
                            color: '#64748b',
                            font: { family: "'Inter', sans-serif" },
                            callback: function(value, index, values) {
                                if (value >= 1000000) return 'Rp ' + (value / 1000000) + ' Jt';
                                if (value >= 1000) return 'Rp ' + (value / 1000) + ' Rb';
                                return 'Rp ' + value;
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false,
                        },
                        ticks: {
                            color: '#64748b',
                            font: { family: "'Inter', sans-serif" },
                            maxTicksLimit: 15
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection
