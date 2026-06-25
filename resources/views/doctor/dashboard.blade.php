@extends('layouts.doctor')
@section('title', 'Dashboard Dokter')
@section('page_title', 'Selamat Datang, ' . auth('doctor')->user()->name)
@section('page_subtitle', auth('doctor')->user()->specialization)

@section('content')

<div class="mb-6 flex items-center justify-between">
    <div class="flex items-center gap-2">
        <label for="type_filter" class="text-sm text-slate-500 font-medium">Filter Layanan:</label>
        <select id="type_filter" class="form-select py-1.5 text-sm rounded-lg border-slate-300 w-48" onchange="window.location.href = '?type=' + this.value">
            <option value="all" {{ $type === 'all' ? 'selected' : '' }}>Semua Layanan</option>
            <option value="telemedicine" {{ $type === 'telemedicine' ? 'selected' : '' }}>Konsultasi Online</option>
            <option value="homecare" {{ $type === 'homecare' ? 'selected' : '' }}>Homecare</option>
        </select>
    </div>
</div>

{{-- Stats --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
    <div class="card card-body text-center">
        <div class="stat-icon bg-emerald-100 mx-auto mb-3">
            <svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
        </svg>
        </div>
        <div class="stat-value text-emerald-600">{{ $stats['active'] }}</div>
        <div class="stat-label">Konsultasi Aktif</div>
    </div>
    <div class="card card-body text-center">
        <div class="stat-icon bg-teal-100 mx-auto mb-3">
            <svg class="w-6 h-6 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div class="stat-value text-teal-600">{{ $stats['completed'] }}</div>
        <div class="stat-label">Selesai Hari Ini</div>
    </div>
</div>

{{-- Active Consultations --}}
<div class="card mb-6">
    <div class="card-body border-b border-slate-100">
        <h3 class="font-heading font-bold text-slate-800">Konsultasi Aktif Saya</h3>
    </div>
    @forelse($activeConsultations as $c)
    <div class="px-6 py-4 border-b border-slate-50 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
        <div>
            <div class="flex items-center gap-2">
                <p class="font-semibold text-slate-800">{{ $c->patient->full_name }}</p>
                <span class="px-1.5 py-0.5 rounded text-[10px] font-bold {{ $c->type === 'homecare' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                    {{ $c->type === 'homecare' ? 'HOMECARE' : 'KONSULTASI ONLINE' }}
                </span>
            </div>
            <p class="text-sm text-slate-500">{{ $c->invoice_number }} • Sisa {{ $c->remaining_seconds }} detik</p>
        </div>
        <a href="{{ route('doctor.consultation.show', $c->id) }}" class="btn-primary btn-sm">
            Buka Ruang Konsultasi →
        </a>
    </div>
    @empty
    <div class="px-6 py-8 text-center text-slate-400 text-sm">
        Tidak ada konsultasi aktif saat ini.
    </div>
    @endforelse
</div>

{{-- Recent Consultations --}}
@if($recentConsultations->isNotEmpty())
<div class="card">
    <div class="card-body border-b border-slate-100">
        <h3 class="font-heading font-bold text-slate-800">Riwayat Konsultasi</h3>
    </div>
    @foreach($recentConsultations as $c)
    <div class="px-6 py-3 border-b border-slate-50 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2 text-sm">
        <div>
            <div class="flex items-center gap-2">
                <p class="font-medium text-slate-800">{{ $c->patient->full_name }}</p>
                <span class="px-1.5 py-0.5 rounded text-[10px] font-bold {{ $c->type === 'homecare' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                    {{ $c->type === 'homecare' ? 'HOMECARE' : 'KONSULTASI ONLINE' }}
                </span>
            </div>
            <p class="text-slate-500 text-xs">{{ $c->ended_at?->format('d M Y H:i') }}</p>
        </div>
        <span class="badge-teal">Selesai</span>
    </div>
    @endforeach
</div>
@endif

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (window.Echo) {
            window.Echo.private(`doctor.{{ auth('doctor')->id() }}`)
                .listen('DoctorAssigned', (e) => {
                    // Reload the dashboard to show the new consultation
                    window.location.reload();
                });
        }
    });
</script>
@endpush
