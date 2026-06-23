@extends('layouts.admin')
@section('title', 'Review Pembayaran – ' . $consultation->invoice_number)
@section('page_title', 'Review Bukti Pembayaran')
@section('page_subtitle', $consultation->invoice_number)

@section('content')
<div class="grid lg:grid-cols-5 gap-6" x-data="{ rejectModal: false, reuploadModal: false, reason: '' }">

    {{-- Left: Proof Preview --}}
    <div class="lg:col-span-3 card">
        <div class="card-body border-b border-slate-100">
            <h3 class="font-heading font-bold text-slate-800">Bukti Pembayaran</h3>
        </div>
        <div class="p-6">
            @if($proof->file_type === 'image')
            <div class="bg-slate-100 rounded-2xl overflow-hidden">
                <img src="{{ \Illuminate\Support\Facades\URL::signedRoute('files.proof', ['proof' => $proof->id]) }}"
                     alt="Bukti Pembayaran"
                     class="w-full max-h-[500px] object-contain"
                     onerror="this.src=''; this.alt='Gagal memuat gambar';">
            </div>
            @else
            <div class="bg-slate-50 border-2 border-dashed border-slate-200 rounded-2xl p-12 text-center">
                <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-slate-500 font-medium mb-1">File PDF</p>
                <p class="text-slate-400 text-sm mb-4">Klik tombol di bawah untuk membuka file</p>
                <a href="{{ \Illuminate\Support\Facades\URL::signedRoute('files.proof', ['proof' => $proof->id]) }}" target="_blank" class="btn-secondary btn-sm">
                    Buka PDF →
                </a>
            </div>
            @endif

            @if($proof->notes)
            <div class="mt-4 p-4 bg-slate-50 rounded-xl">
                <p class="text-xs text-slate-500 mb-1">Catatan dari pasien:</p>
                <p class="text-sm text-slate-700">{{ $proof->notes }}</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Right: Transaction Details + Actions --}}
    <div class="lg:col-span-2 space-y-4">

        {{-- Transaction Detail --}}
        <div class="card card-body">
            <h3 class="font-heading font-bold text-slate-800 mb-4">Detail Transaksi</h3>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-slate-500">Invoice</span>
                    <span class="font-semibold font-heading text-brand-700">{{ $consultation->invoice_number }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Pasien</span>
                    <span class="font-semibold">{{ $consultation->patient->full_name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">WhatsApp</span>
                    <span class="font-semibold">{{ $consultation->patient->whatsapp_number }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Metode</span>
                    <span class="font-semibold">{{ $proof->transaction->method_label }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Provider</span>
                    <span class="font-semibold">{{ $proof->transaction->payment_provider }}</span>
                </div>
                <div class="flex justify-between pt-3 border-t border-slate-100">
                    <span class="font-semibold text-slate-700">Total</span>
                    <span class="text-xl font-bold text-brand-700 font-heading">{{ $proof->transaction->formatted_amount }}</span>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        @if($proof->status === 'pending')
        <div class="card card-body space-y-3">
            <h3 class="font-heading font-bold text-slate-800 mb-2">Tindakan</h3>

            {{-- Approve --}}
            <form action="{{ route('admin.payment.approve', $proof->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn w-full bg-emerald-600 text-white hover:bg-emerald-700 border-0"
                        onclick="return confirm('Setujui pembayaran ini?')">
                    ✅ Setujui Pembayaran
                </button>
            </form>

            {{-- Request Reupload --}}
            <button @click="reuploadModal = true" class="btn-secondary w-full">
                🔄 Minta Upload Ulang
            </button>

            {{-- Reject --}}
            <button @click="rejectModal = true" class="btn-danger w-full">
                ❌ Tolak Pembayaran
            </button>
        </div>
        @else
        <div class="card card-body">
            <div class="badge-{{ $proof->status === 'approved' ? 'emerald' : ($proof->status === 'rejected' ? 'red' : 'blue') }} text-sm">
                Status: {{ ucfirst(str_replace('_', ' ', $proof->status)) }}
            </div>
            @if($proof->rejection_reason)
            <p class="text-sm text-slate-600 mt-3">{{ $proof->rejection_reason }}</p>
            @endif
        </div>
        @endif

        <a href="{{ route('admin.payment.index') }}" class="btn-ghost w-full text-center block">← Kembali</a>
    </div>

{{-- Reject Modal --}}
<div x-show="rejectModal" x-transition class="modal-backdrop">
    <div class="modal-box max-w-md">
        <div class="modal-header">
            <h3 class="font-heading font-bold text-slate-800">Tolak Pembayaran</h3>
        </div>
        <form action="{{ route('admin.payment.reject', $proof->id) }}" method="POST">
            @csrf
            <div class="modal-body">
                <label class="form-label">Alasan Penolakan <span class="text-rose-500">*</span></label>
                <textarea name="reason" x-model="reason" class="form-textarea" rows="3"
                          placeholder="Jelaskan alasan penolakan..." required></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" @click="rejectModal = false" class="btn-ghost">Batal</button>
                <button type="submit" class="btn-danger">Tolak</button>
            </div>
        </form>
    </div>
</div>

{{-- Reupload Modal --}}
<div x-show="reuploadModal" x-transition class="modal-backdrop">
    <div class="modal-box max-w-md">
        <div class="modal-header">
            <h3 class="font-heading font-bold text-slate-800">Minta Upload Ulang</h3>
        </div>
        <form action="{{ route('admin.payment.reupload', $proof->id) }}" method="POST">
            @csrf
            <div class="modal-body">
                <label class="form-label">Alasan Permintaan <span class="text-rose-500">*</span></label>
                <textarea name="reason" x-model="reason" class="form-textarea" rows="3"
                          placeholder="Jelaskan apa yang perlu diperbaiki..." required></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" @click="reuploadModal = false" class="btn-ghost">Batal</button>
                <button type="submit" class="btn-primary">Kirim Permintaan</button>
            </div>
        </form>
    </div>
</div>

</div>

@endsection
