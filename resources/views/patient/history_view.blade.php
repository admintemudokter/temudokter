@extends('layouts.app')
@section('title', 'Riwayat Konsultasi – Temu Dokter')

@section('content')
<div class="min-h-screen bg-slate-50 py-10">
    <div class="max-w-4xl mx-auto px-4 sm:px-6">
        
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-bold font-heading text-slate-800">Riwayat Konsultasi</h1>
            <a href="{{ route('home') }}" class="btn-ghost btn-sm text-slate-500 hover:text-slate-800">
                &larr; Kembali
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            {{-- Left Column: Details & Prescription --}}
            <div class="space-y-6">
                {{-- Detail Card --}}
                <div class="card bg-white p-5 border border-slate-200">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-4">Detail Konsultasi</p>
                    
                    <div class="space-y-4 text-sm">
                        <div>
                            <p class="text-slate-500 mb-1">Invoice</p>
                            <p class="font-bold text-brand-700">{{ $consultation->invoice_number }}</p>
                        </div>
                        <div>
                            <p class="text-slate-500 mb-1">Tanggal</p>
                            <p class="font-semibold text-slate-800">{{ $consultation->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-slate-500 mb-1">Dokter</p>
                            <p class="font-semibold text-slate-800">{{ $consultation->doctor ? $consultation->doctor->name : 'Belum/Tidak ada dokter' }}</p>
                        </div>
                        <div>
                            <p class="text-slate-500 mb-1">Status</p>
                            @if($consultation->consultation_status === 'completed')
                                <span class="badge-emerald">Selesai</span>
                            @else
                                <span class="badge-blue capitalize">{{ str_replace('_', ' ', $consultation->consultation_status) }}</span>
                            @endif
                        </div>
                        <div>
                            <p class="text-slate-500 mb-2">Keluhan</p>
                            <div class="bg-slate-50 rounded-xl p-3 text-slate-700 leading-relaxed border border-slate-100">
                                {{ $consultation->patient->complaint_description }}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Payment Detail Card --}}
                @if($consultation->transaction)
                <div class="card bg-white p-5 border border-slate-200">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-4">Detail Pembayaran</p>
                    <div class="space-y-4 text-sm">
                        <div>
                            <p class="text-slate-500 mb-1">Total Tagihan</p>
                            <p class="font-bold text-brand-700">{{ $consultation->transaction->formatted_amount }}</p>
                        </div>
                        <div>
                            <p class="text-slate-500 mb-1">Metode Pembayaran</p>
                            <p class="font-semibold text-slate-800">
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
                            </p>
                        </div>
                        <div>
                            <p class="text-slate-500 mb-1">Status Pembayaran</p>
                            @if(in_array($consultation->transaction->payment_status, ['paid', 'approved']))
                                <span class="badge-emerald">Lunas</span>
                            @elseif($consultation->transaction->payment_status === 'pending' || $consultation->transaction->payment_status === 'waiting_verification')
                                <span class="badge-amber">Menunggu Verifikasi</span>
                            @else
                                <span class="badge-rose capitalize">{{ $consultation->transaction->payment_status }}</span>
                            @endif
                        </div>
                        @if(in_array($consultation->transaction->payment_status, ['paid', 'approved']))
                        <div class="pt-4 mt-4 border-t border-slate-100 flex flex-col gap-2">
                            <a href="{{ URL::signedRoute('files.receipt', ['transaction' => $consultation->transaction->id]) }}" target="_blank" class="btn w-full bg-brand-600 text-white hover:bg-brand-700 border-0 btn-sm shadow-sm">
                                📥 Unduh Kuitansi
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                {{-- Homecare Report Card --}}
                @if($consultation->type === 'homecare' && $consultation->homecare_report)
                <div class="card bg-purple-50 p-5 border border-purple-200 mt-6">
                    <div class="flex items-center gap-2 mb-3">
                        <svg class="w-5 h-5 text-purple-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="text-sm font-semibold text-purple-800 uppercase tracking-wide">Laporan Kunjungan</p>
                    </div>
                    
                    <div class="bg-white rounded-xl p-4 text-purple-900 text-sm leading-relaxed border border-purple-100 shadow-sm">
                        {{ $consultation->homecare_report }}
                    </div>
                </div>
                @endif

                {{-- Prescription Card --}}
                @if($consultation->prescription)
                <div class="card bg-emerald-50 p-5 border border-emerald-200">
                    <div class="flex items-center gap-2 mb-3">
                        <svg class="w-5 h-5 text-emerald-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="text-sm font-semibold text-emerald-800 uppercase tracking-wide">Resep Dokter</p>
                    </div>
                    
                    @if($consultation->prescription->notes)
                    <p class="text-emerald-700 text-sm leading-relaxed mb-4">{{ $consultation->prescription->notes }}</p>
                    @endif
                    
                    @if($consultation->prescription->file_path)
                    <a href="{{ URL::signedRoute('files.prescription', ['prescription' => $consultation->prescription->id]) }}"
                       target="_blank"
                       class="btn w-full bg-emerald-600 text-white hover:bg-emerald-700 border-0 btn-sm shadow-sm">
                        📥 Unduh File Resep
                    </a>
                    @endif
                </div>
                @else
                <div class="card bg-white p-5 border border-slate-200 text-center">
                    <p class="text-slate-500 text-sm">Tidak ada resep yang dilampirkan oleh dokter pada konsultasi ini.</p>
                </div>
                @endif

                {{-- Sick Leave Card --}}
                @if($consultation->sickLeave)
                <div class="card bg-blue-50 p-5 border border-blue-200 mt-6">
                    <div class="flex items-center gap-2 mb-3">
                        <svg class="w-5 h-5 text-blue-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="text-sm font-semibold text-blue-800 uppercase tracking-wide">Surat Keterangan Sakit</p>
                    </div>
                    
                    <p class="text-blue-700 text-sm leading-relaxed mb-4">
                        Pasien disarankan beristirahat dari tanggal 
                        <strong>{{ \Carbon\Carbon::parse($consultation->sickLeave->start_date)->format('d M Y') }}</strong> 
                        sampai <strong>{{ \Carbon\Carbon::parse($consultation->sickLeave->end_date)->format('d M Y') }}</strong>
                        karena <em>{{ $consultation->sickLeave->reason }}</em>.
                    </p>
                    
                    @if($consultation->sickLeave->file_path)
                    <a href="{{ URL::signedRoute('files.sick_leave', ['sick_leave' => $consultation->sickLeave->id]) }}"
                       target="_blank"
                       class="btn w-full bg-blue-600 text-white hover:bg-blue-700 border-0 btn-sm shadow-sm">
                        📥 Unduh Surat Sakit
                    </a>
                    @endif
                </div>
                @endif
            </div>

            {{-- Right Column: Chat History --}}
            <div class="md:col-span-2">
                <div class="card bg-white border border-slate-200 flex flex-col h-[600px]">
                    <div class="p-4 border-b border-slate-100 bg-slate-50/50 rounded-t-2xl flex items-center gap-3">
                        <div class="w-10 h-10 bg-brand-100 rounded-full flex items-center justify-center text-brand-600">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="font-bold text-slate-800">Riwayat Chat</h2>
                            <p class="text-xs text-slate-500">Percakapan telah berakhir</p>
                        </div>
                    </div>

                    <div class="flex-1 overflow-y-auto p-4 space-y-4">
                        @forelse($consultation->messages as $msg)
                            <div class="flex {{ $msg->sender_type === 'patient' ? 'justify-end' : ($msg->sender_type === 'system' ? 'justify-center' : 'justify-start') }}">
                                
                                @if($msg->sender_type === 'system')
                                    <div class="chat-bubble-system text-xs max-w-sm text-center">
                                        {{ $msg->message }}
                                    </div>
                                @elseif($msg->sender_type === 'patient')
                                    <div class="max-w-md">
                                        <div class="chat-bubble-patient">
                                            @if($msg->message)
                                                <p>{{ $msg->message }}</p>
                                            @endif
                                            @if($msg->attachment && $msg->attachment_type === 'image')
                                                <img src="{{ URL::signedRoute('files.attachment', ['message' => $msg->id]) }}" class="mt-2 rounded-lg max-w-full h-auto" alt="Attachment">
                                            @elseif($msg->attachment)
                                                <a href="{{ URL::signedRoute('files.attachment', ['message' => $msg->id]) }}" target="_blank" class="text-white underline text-sm mt-1 block">Unduh Lampiran</a>
                                            @endif
                                        </div>
                                        <p class="text-xs text-slate-400 mt-1 text-right">{{ $msg->created_at->format('H:i') }}</p>
                                    </div>
                                @else
                                    <div class="flex items-end gap-2 max-w-md">
                                        <div class="w-8 h-8 bg-brand-100 rounded-full flex items-center justify-center flex-shrink-0 mb-5">
                                            <svg class="w-4 h-4 text-brand-600" fill="currentColor" viewBox="0 0 20 20"><path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/></svg>
                                        </div>
                                        <div>
                                            <div class="chat-bubble-doctor">
                                                @if($msg->message)
                                                    <p>{{ $msg->message }}</p>
                                                @endif
                                                @if($msg->attachment && $msg->attachment_type === 'image')
                                                    <img src="{{ URL::signedRoute('files.attachment', ['message' => $msg->id]) }}" class="mt-2 rounded-lg max-w-full h-auto" alt="Attachment">
                                                @elseif($msg->attachment)
                                                    <a href="{{ URL::signedRoute('files.attachment', ['message' => $msg->id]) }}" target="_blank" class="text-brand-700 underline text-sm mt-1 block">Unduh Lampiran</a>
                                                @endif
                                            </div>
                                            <p class="text-xs text-slate-400 mt-1">{{ $msg->created_at->format('H:i') }}</p>
                                        </div>
                                    </div>
                                @endif

                            </div>
                        @empty
                            <div class="h-full flex flex-col items-center justify-center text-slate-400">
                                <svg class="w-12 h-12 mb-3 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                                <p>Belum ada riwayat pesan</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
