@extends('layouts.app')
@section('title', 'Pilih Riwayat Konsultasi – Temu Dokter')

@section('content')
<div class="min-h-screen bg-slate-50 py-10">
    <div class="max-w-4xl mx-auto px-4 sm:px-6">
        
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-bold font-heading text-slate-800">Daftar Riwayat Konsultasi</h1>
            <a href="{{ route('history.form') }}" class="btn-ghost btn-sm text-slate-500 hover:text-slate-800">
                &larr; Ganti Nomor
            </a>
        </div>

        <div class="bg-white p-4 rounded-xl border border-brand-100 mb-8 inline-block shadow-sm">
            <p class="text-sm text-slate-600">Menampilkan riwayat untuk pasien: <strong class="text-slate-800">{{ $consultations->first()->patient->full_name }}</strong></p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @foreach($consultations as $consult)
            <a href="{{ URL::signedRoute('history.show', ['id' => $consult->id]) }}" class="block card bg-white hover:border-brand-300 hover:shadow-md transition-all duration-200">
                <div class="p-5 border-l-4 {{ $consult->type === 'homecare' ? 'border-purple-500' : 'border-brand-500' }} rounded-l-none">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <span class="text-xs font-bold px-2 py-1 rounded-md {{ $consult->type === 'homecare' ? 'bg-purple-100 text-purple-700' : 'bg-brand-100 text-brand-700' }} uppercase">
                                {{ $consult->type === 'homecare' ? 'Homecare' : 'Konsultasi Online' }}
                            </span>
                        </div>
                        <div>
                            @if($consult->consultation_status === 'completed')
                                <span class="badge-emerald text-xs">Selesai</span>
                            @else
                                <span class="badge-blue text-xs capitalize">{{ str_replace('_', ' ', $consult->consultation_status) }}</span>
                            @endif
                        </div>
                    </div>
                    
                    <h3 class="font-bold text-slate-800 text-lg mb-1">{{ $consult->created_at->format('d F Y') }}</h3>
                    <p class="text-sm text-slate-500 mb-3 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Jam: {{ $consult->created_at->format('H:i') }}
                    </p>

                    <div class="text-sm">
                        <p class="text-slate-600 truncate"><span class="font-medium text-slate-500">Dokter:</span> {{ $consult->doctor->name ?? 'Belum ada' }}</p>
                        <p class="text-slate-600 truncate mt-1"><span class="font-medium text-slate-500">Keluhan:</span> {{ \Illuminate\Support\Str::limit($consult->patient->complaint_description, 50) }}</p>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

    </div>
</div>
@endsection
