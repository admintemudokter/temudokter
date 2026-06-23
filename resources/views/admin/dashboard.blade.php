@extends('layouts.admin')
@section('title', 'Dashboard')
@section('page_title', 'Dashboard')
@section('page_subtitle', 'Ringkasan operasional TemuDokter')

@section('content')
<div x-data="adminDashboard()" x-init="startPolling()">

    {{-- ===== STAT CARDS ===== --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 xl:grid-cols-8 gap-4 mb-8">

        {{-- Waiting Payments --}}
        <div class="card card-body col-span-1">
            <div class="stat-icon bg-amber-100 mb-3">
                <svg class="w-6 h-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="stat-value text-amber-600" x-text="stats.waiting_payments ?? '{{ $stats['waiting_payments'] }}'">{{ $stats['waiting_payments'] }}</div>
            <div class="stat-label">Menunggu Verifikasi</div>
        </div>

        {{-- Active Consultations --}}
        <div class="card card-body col-span-1">
            <div class="stat-icon bg-emerald-100 mb-3">
                <svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
            </div>
            <div class="stat-value text-emerald-600" x-text="stats.active_consultations ?? '{{ $stats['active_consultations'] }}'">{{ $stats['active_consultations'] }}</div>
            <div class="stat-label">Konsultasi Aktif</div>
        </div>

        {{-- Online Doctors --}}
        <div class="card card-body col-span-1">
            <div class="stat-icon bg-brand-100 mb-3">
                <svg class="w-6 h-6 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="stat-value text-brand-600" x-text="stats.online_doctors ?? '{{ $stats['online_doctors'] }}'">{{ $stats['online_doctors'] }}</div>
            <div class="stat-label">Dokter Online</div>
        </div>

        {{-- Waiting Assignment --}}
        <div class="card card-body col-span-1">
            <div class="stat-icon bg-purple-100 mb-3">
                <svg class="w-6 h-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <div class="stat-value text-purple-600" x-text="stats.waiting_assignment ?? '{{ $stats['waiting_assignment'] }}'">{{ $stats['waiting_assignment'] }}</div>
            <div class="stat-label">Menunggu Dokter</div>
        </div>

        {{-- Completed Today --}}
        <div class="card card-body col-span-1">
            <div class="stat-icon bg-teal-100 mb-3">
                <svg class="w-6 h-6 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="stat-value text-teal-600">{{ $stats['completed_today'] }}</div>
            <div class="stat-label">Selesai Hari Ini</div>
        </div>

        {{-- Revenue Today --}}
        <div class="card card-body col-span-1">
            <div class="stat-icon bg-green-100 mb-3">
                <svg class="w-6 h-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="text-xl font-bold text-green-700 font-heading truncate">Rp {{ number_format($stats['revenue_today'], 0, ',', '.') }}</div>
            <div class="stat-label truncate">Pendapatan Hari Ini</div>
        </div>

        {{-- Revenue Yesterday --}}
        <div class="card card-body col-span-1">
            <div class="stat-icon bg-blue-100 mb-3">
                <svg class="w-6 h-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="text-xl font-bold text-blue-700 font-heading truncate">Rp {{ number_format($stats['revenue_yesterday'], 0, ',', '.') }}</div>
            <div class="stat-label truncate">Pendapatan Kemarin</div>
        </div>

        {{-- Revenue Total --}}
        <div class="card card-body col-span-1">
            <div class="stat-icon bg-emerald-100 mb-3">
                <svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="text-xl font-bold text-emerald-700 font-heading truncate">Rp {{ number_format($stats['revenue_total'], 0, ',', '.') }}</div>
            <div class="stat-label truncate">Total Pendapatan</div>
        </div>

    </div>

    {{-- ===== CHARTS ===== --}}
    <div class="card mb-6">
        <div class="card-body border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-heading font-bold text-slate-800">Statistik Pendapatan & Konsultasi (7 Hari Terakhir)</h3>
        </div>
        <div class="p-4 sm:p-6 overflow-hidden">
            <div id="dashboard-chart" class="w-full"></div>
        </div>
    </div>

    {{-- ===== MAIN CONTENT GRID ===== --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- Activity Feed --}}
        <div class="xl:col-span-2 card">
            <div class="card-body border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="font-heading font-bold text-slate-800">Aktivitas Terkini</h3>
                    <p class="text-slate-500 text-xs mt-0.5">Update otomatis setiap 5 detik</p>
                </div>
                <span class="flex items-center gap-1.5 text-xs text-emerald-600 font-medium">
                    <span class="status-dot-online"></span> Live
                </span>
            </div>
            <div class="divide-y divide-slate-50 max-h-96 overflow-y-auto scrollbar-hide">
                <template x-if="activities.length === 0">
                    <div class="px-6 py-8 text-center text-slate-400 text-sm">Belum ada aktivitas.</div>
                </template>
                <template x-for="item in activities" :key="item.id">
                    <div class="px-6 py-4 flex items-start gap-4 hover:bg-slate-50 transition-colors">
                        <div class="w-8 h-8 rounded-full flex-shrink-0 flex items-center justify-center"
                             :class="{
                                 'bg-emerald-100': item.color === 'emerald',
                                 'bg-amber-100': item.color === 'amber',
                                 'bg-brand-100': item.color === 'brand' || item.color === 'teal',
                                 'bg-purple-100': item.color === 'purple',
                                 'bg-slate-100': item.color === 'slate',
                                 'bg-red-100': item.color === 'red',
                             }">
                            <svg class="w-4 h-4"
                                 :class="{
                                     'text-emerald-600': item.color === 'emerald',
                                     'text-amber-600': item.color === 'amber',
                                     'text-brand-600': item.color === 'brand' || item.color === 'teal',
                                     'text-purple-600': item.color === 'purple',
                                     'text-slate-500': item.color === 'slate',
                                     'text-red-600': item.color === 'red',
                                 }"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1 sm:gap-2">
                                <p class="text-sm font-semibold text-slate-800 break-words" x-text="item.patient"></p>
                                <span class="text-xs text-slate-400 flex-shrink-0" x-text="item.created_at"></span>
                            </div>
                            <div class="flex flex-wrap items-center gap-2 mt-1">
                                <span class="text-xs text-slate-500" x-text="item.invoice"></span>
                                <span class="w-1 h-1 bg-slate-300 rounded-full flex-shrink-0"></span>
                                <span class="badge text-xs"
                                      :class="{
                                          'badge-emerald': item.color === 'emerald' || item.color === 'teal',
                                          'badge-amber': item.color === 'amber',
                                          'badge-blue': item.color === 'brand',
                                          'badge-purple': item.color === 'purple',
                                          'badge-slate': item.color === 'slate',
                                          'badge-red': item.color === 'red',
                                      }"
                                      x-text="item.label"></span>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        {{-- Pending Payments --}}
        <div class="card">
            <div class="card-body border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-heading font-bold text-slate-800">Perlu Diverifikasi</h3>
                <a href="{{ route('admin.payment.index') }}" class="text-xs text-brand-600 hover:underline font-medium">Lihat Semua</a>
            </div>
            <div class="divide-y divide-slate-50">
                @forelse($pendingProofs as $proof)
                <div class="px-5 py-4">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-slate-800 truncate">
                                {{ $proof->transaction->consultation->patient->full_name }}
                            </p>
                            <p class="text-xs text-slate-500 mt-0.5">{{ $proof->transaction->invoice_number }}</p>
                            <p class="text-xs text-slate-400 mt-0.5">{{ $proof->created_at->diffForHumans() }}</p>
                        </div>
                        <a href="{{ route('admin.payment.show', $proof->id) }}"
                           class="btn-sm btn bg-amber-50 text-amber-700 border border-amber-200 hover:bg-amber-100 flex-shrink-0 text-xs">
                            Review
                        </a>
                    </div>
                </div>
                @empty
                <div class="px-5 py-8 text-center text-slate-400 text-sm">
                    <svg class="w-8 h-8 mx-auto mb-2 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Tidak ada pembayaran pending
                </div>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
function adminDashboard() {
    return {
        stats: {
            waiting_payments:     {{ $stats['waiting_payments'] }},
            active_consultations: {{ $stats['active_consultations'] }},
            online_doctors:       {{ $stats['online_doctors'] }},
            waiting_assignment:   {{ $stats['waiting_assignment'] }},
        },
        activities: {!! $recentActivity->map(fn($c) => [
            'id'         => $c->id,
            'invoice'    => $c->invoice_number,
            'patient'    => $c->patient->full_name,
            'doctor'     => $c->doctor?->name,
            'status'     => $c->consultation_status,
            'label'      => $c->status_label,
            'color'      => $c->status_color,
            'created_at' => $c->created_at->diffForHumans(),
        ])->toJson() !!},
        startPolling() {
            setInterval(async () => {
                try {
                    const res = await getJson('{{ route('admin.api.activity') }}');
                    if (res.ok) {
                        const data = await res.json();
                        this.activities = data.activities;
                        this.stats = { ...this.stats, ...data.stats };
                    }
                } catch(e) {}
            }, 5000);
        },
    };
}

// Chart Initialization
document.addEventListener('alpine:init', () => {
    const chartData = @json($chartData);
    
    const options = {
        series: [{
            name: 'Pendapatan (Rp)',
            type: 'column',
            data: chartData.revenues
        }, {
            name: 'Skala Konsultasi',
            type: 'line',
            data: chartData.consultations
        }],
        chart: {
            height: 350,
            type: 'line',
            toolbar: { show: false },
            fontFamily: 'inherit'
        },
        stroke: {
            width: [0, 4],
            curve: 'smooth'
        },
        colors: ['#10b981', '#0ea5e9'],
        fill: {
            opacity: [0.85, 1],
        },
        labels: chartData.dates,
        xaxis: {
            type: 'category',
            labels: {
                style: { cssClass: 'text-xs text-slate-500 font-sans' }
            }
        },
        yaxis: [{
            title: {
                text: 'Pendapatan (Rp)',
                style: { cssClass: 'font-semibold text-slate-600 font-sans text-xs' }
            },
            labels: {
                formatter: (value) => { return 'Rp ' + value.toLocaleString('id-ID'); },
                style: { cssClass: 'text-xs text-slate-500 font-sans' }
            }
        }, {
            opposite: true,
            title: {
                text: 'Jumlah Konsultasi',
                style: { cssClass: 'font-semibold text-slate-600 font-sans text-xs' }
            },
            labels: {
                style: { cssClass: 'text-xs text-slate-500 font-sans' }
            }
        }],
        tooltip: {
            shared: true,
            intersect: false,
            y: {
                formatter: function (y, { seriesIndex }) {
                    if(typeof y !== "undefined") {
                        if (seriesIndex === 0) return 'Rp ' + y.toLocaleString('id-ID');
                        return y.toFixed(0) + " konsultasi";
                    }
                    return y;
                }
            }
        },
        legend: {
            position: 'top',
            horizontalAlign: 'right'
        }
    };

    const chart = new ApexCharts(document.querySelector("#dashboard-chart"), options);
    chart.render();
});
</script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
@endpush
