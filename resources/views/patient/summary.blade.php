@extends('layouts.app')
@section('title', 'Ringkasan Konsultasi – TemuDokter')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-brand-950 via-brand-900 to-emerald-900 flex items-center justify-center p-6">
    <div class="w-full max-w-lg animate-slide-up">

        {{-- Success animation --}}
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-emerald-500 rounded-full flex items-center justify-center mx-auto mb-4 shadow-2xl shadow-emerald-900/50">
                <svg class="w-10 h-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h1 class="font-heading text-2xl font-bold text-white mb-2">Konsultasi Selesai!</h1>
            <p class="text-white/60">Terima kasih telah menggunakan TemuDokter.</p>
        </div>

        <div class="card">
            <div class="p-6 border-b border-slate-100">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-xs text-slate-500 mb-1">Invoice</p>
                        <p class="font-heading font-bold text-brand-700">{{ $consultation->invoice_number }}</p>
                    </div>
                    <span class="badge-teal">Selesai</span>
                </div>
            </div>

            <div class="p-6 pb-0">
                <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-4 text-center">
                    <p class="text-xs text-indigo-600 font-semibold uppercase tracking-wider mb-1">Kode Riwayat Anda</p>
                    <p class="font-heading text-3xl font-bold text-indigo-800 tracking-widest">{{ $consultation->history_code }}</p>
                    <p class="text-xs text-indigo-500 mt-2">Simpan kode ini. Gunakan untuk mengakses riwayat chat, resep, dan surat medis Anda di halaman Cek Riwayat.</p>
                </div>
            </div>

            <div class="p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-slate-500 text-xs mb-1">Nama Pasien</p>
                        <p class="font-semibold text-slate-800">{{ $patient->full_name }}</p>
                    </div>
                    @if($consultation->doctor)
                    <div>
                        <p class="text-slate-500 text-xs mb-1">Dokter</p>
                        <p class="font-semibold text-slate-800">{{ $consultation->doctor->name }}</p>
                    </div>
                    @endif
                    @if($consultation->started_at && $consultation->ended_at)
                    <div>
                        <p class="text-slate-500 text-xs mb-1">Durasi</p>
                        <p class="font-semibold text-slate-800">{{ $consultation->started_at->diffInMinutes($consultation->ended_at) }} menit</p>
                    </div>
                    @endif
                    <div>
                        <p class="text-slate-500 text-xs mb-1">Tanggal</p>
                        <p class="font-semibold text-slate-800">{{ ($consultation->ended_at ?? $consultation->created_at)->format('d M Y') }}</p>
                    </div>
                </div>

                @if($consultation->transaction)
                <div class="mt-4 p-4 bg-slate-50 border border-slate-200 rounded-2xl">
                    <p class="font-semibold text-slate-800 text-sm mb-3">Detail Pembayaran</p>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Total</span>
                            <span class="font-bold text-brand-700">{{ $consultation->transaction->formatted_amount }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Metode</span>
                            <span class="font-semibold text-slate-800">
                                @if($consultation->transaction->payment_method === 'qris')
                                    QRIS
                                @elseif(in_array($consultation->transaction->payment_method, ['bank_transfer', 'manual_transfer']))
                                    @if($consultation->transaction->payment_provider && strtolower($consultation->transaction->payment_provider) !== 'manual')
                                        Transfer Bank ({{ strtoupper($consultation->transaction->payment_provider) }})
                                    @else
                                        Transfer Manual / QRIS
                                    @endif
                                @else
                                    {{ strtoupper($consultation->transaction->payment_method) }}
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Status</span>
                            @if($consultation->transaction->payment_status === 'paid')
                                <span class="text-emerald-600 font-semibold">Lunas</span>
                            @elseif($consultation->transaction->payment_status === 'pending' || $consultation->transaction->payment_status === 'waiting_verification')
                                <span class="text-amber-600 font-semibold">Menunggu Verifikasi</span>
                            @else
                                <span class="text-rose-600 font-semibold capitalize">{{ $consultation->transaction->payment_status }}</span>
                            @endif
                        </div>
                    </div>
                    @if($consultation->transaction->payment_status === 'paid' && $consultation->transaction->latestProof)
                    <div class="mt-4 border-t border-slate-200 pt-4 flex flex-col gap-2">
                        <a href="{{ URL::signedRoute('files.receipt', ['transaction' => $consultation->transaction->id]) }}" target="_blank" class="btn w-full bg-brand-600 text-white hover:bg-brand-700 border-0 btn-sm">
                            📥 Unduh Kwitansi
                        </a>
                    </div>
                    @endif
                </div>
                @endif

                @if($consultation->prescription)
                <div class="mt-4 p-4 bg-emerald-50 border border-emerald-200 rounded-2xl">
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-5 h-5 text-emerald-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="font-semibold text-emerald-800 text-sm">Resep Dokter Tersedia</p>
                    </div>
                    @if($consultation->prescription->notes)
                    <p class="text-emerald-700 text-sm leading-relaxed mb-3">{{ $consultation->prescription->notes }}</p>
                    @endif
                    @if($consultation->prescription->file_path)
                    <a href="{{ URL::signedRoute('files.prescription', ['prescription' => $consultation->prescription->id]) }}"
                       target="_blank"
                       class="btn w-full bg-emerald-600 text-white hover:bg-emerald-700 border-0 btn-sm">
                        📥 Unduh Resep
                    </a>
                    @endif
                </div>
                @else
                <div class="p-4 bg-amber-50 border border-amber-200 rounded-2xl text-sm text-amber-800">
                    ⏳ Dokter sedang menyiapkan resep. Refresh halaman ini untuk mengecek.
                </div>
                @endif

                @if($consultation->sickLeave)
                <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-2xl">
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-5 h-5 text-blue-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="font-semibold text-blue-800 text-sm">Surat Keterangan Sakit Tersedia</p>
                    </div>
                    <p class="text-blue-700 text-sm leading-relaxed mb-3">
                        Pasien disarankan istirahat dari {{ \Carbon\Carbon::parse($consultation->sickLeave->start_date)->format('d M') }} 
                        s/d {{ \Carbon\Carbon::parse($consultation->sickLeave->end_date)->format('d M Y') }}.
                    </p>
                    @if($consultation->sickLeave->file_path)
                    <a href="{{ URL::signedRoute('files.sick_leave', ['sick_leave' => $consultation->sickLeave->id]) }}"
                       target="_blank"
                       class="btn w-full bg-blue-600 text-white hover:bg-blue-700 border-0 btn-sm">
                        📥 Unduh Surat Sakit
                    </a>
                    @endif
                </div>
                @endif
            </div>

            <div class="p-6 pt-0">
                <a href="{{ route('home') }}" class="btn-primary w-full text-center">
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
