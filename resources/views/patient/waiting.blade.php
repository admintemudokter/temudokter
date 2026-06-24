@extends('layouts.app')
@section('title', 'Menunggu Verifikasi – Temu Dokter')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-brand-950 via-brand-900 to-emerald-900 flex flex-col items-center justify-center p-6"
     x-data="waitingRoom('{{ $token }}', '{{ route('api.patient.status', $token) }}')"
     x-init="startPolling()">

    <div class="w-full max-w-md text-center">
        {{-- Logo --}}
        <div class="flex items-center justify-center gap-2 mb-10">
            <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
            </div>
            <span class="font-heading font-bold text-white text-lg">Temu<span class="text-teal-300">Dokter</span></span>
        </div>

        {{-- Animated ring --}}
        <div class="relative w-28 h-28 mx-auto mb-8">
            <div class="absolute inset-0 rounded-full border-4 border-white/10"></div>
            <div class="absolute inset-0 rounded-full border-4 border-t-teal-400 border-r-transparent border-b-transparent border-l-transparent animate-spin"></div>
            <div class="absolute inset-3 rounded-full bg-white/10 flex items-center justify-center">
                <svg class="w-10 h-10 text-teal-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>

        {{-- Status --}}
        <h2 class="font-heading text-2xl font-bold text-white mb-3" x-text="statusLabel">Menunggu Verifikasi</h2>
        <p class="text-white/60 text-sm mb-8" x-show="currentStatus !== 'payment_rejected' && currentStatus !== 'waiting_upload'">
            Halaman ini akan otomatis memperbarui status Anda. Jangan tutup jendela ini.
        </p>
        <p class="text-rose-400 text-sm mb-8 font-medium animate-pulse" x-show="currentStatus === 'payment_rejected'" style="display: none;">
            Halaman ini akan dialihkan kembali dalam beberapa detik...
        </p>
        <p class="text-amber-400 text-sm mb-8 font-medium animate-pulse" x-show="currentStatus === 'waiting_upload'" style="display: none;">
            Halaman ini akan dialihkan ke halaman pembayaran untuk upload ulang...
        </p>

        {{-- Status Steps --}}
        <div class="space-y-3 mb-10">
            @php
            $steps = [
                ['key'=>'waiting_admin_confirmation','label'=>'Pembayaran sedang diverifikasi admin'],
                ['key'=>'waiting_assignment','label'=>'Admin memilihkan dokter untuk Anda'],
                ['key'=>'active','label'=>'Masuk ke ruang konsultasi'],
            ];
            @endphp
            @foreach($steps as $i => $step)
            <div class="flex items-center gap-3 text-left">
                <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 text-xs font-bold transition-all"
                     :class="{
                         'bg-emerald-500 text-white': statusDone('{{ $step['key'] }}'),
                         'bg-white/30 text-white animate-pulse': statusCurrent('{{ $step['key'] }}'),
                         'bg-white/10 text-white/40': statusPending('{{ $step['key'] }}'),
                     }">
                    <span x-show="statusDone('{{ $step['key'] }}')">✓</span>
                    <span x-show="!statusDone('{{ $step['key'] }}')">{{ $i + 1 }}</span>
                </div>
                <span class="text-sm transition-colors"
                      :class="{
                          'text-white font-semibold': statusCurrent('{{ $step['key'] }}'),
                          'text-white/70': statusDone('{{ $step['key'] }}'),
                          'text-white/40': statusPending('{{ $step['key'] }}'),
                      }">
                    {{ $step['label'] }}
                </span>
            </div>
            @endforeach
        </div>

        <div class="card-glass p-5 text-sm text-white/70">
            <p><strong class="text-white">Invoice:</strong> {{ $consultation->invoice_number }}</p>
            <p class="mt-1"><strong class="text-white">Pasien:</strong> {{ $consultation->patient->full_name }}</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function waitingRoom(token, statusUrl) {
    const statusOrder = ['waiting_admin_confirmation', 'waiting_assignment', 'active', 'completed'];

    return {
        currentStatus: '{{ $consultation->consultation_status }}',
        statusLabel: '{{ $consultation->status_label }}',

        statusDone(key) {
            const ci = statusOrder.indexOf(this.currentStatus);
            const ki = statusOrder.indexOf(key);
            return ci > ki;
        },
        statusCurrent(key) { return this.currentStatus === key; },
        statusPending(key) {
            const ci = statusOrder.indexOf(this.currentStatus);
            const ki = statusOrder.indexOf(key);
            return ki > ci;
        },

        async startPolling() {
            if (window.Echo) {
                window.Echo.channel(`consultation.{{ $consultation->patient->session_token }}`)
                    .listen('ConsultationStatusUpdated', (e) => {
                        this.currentStatus = e.status;
                        if (e.status_label) {
                            this.statusLabel = e.status_label;
                        }
                        
                        if (e.status === 'active' || e.status === 'waiting_payment') {
                            window.location.reload();
                        }
                        
                        if (e.status === 'payment_rejected') {
                            setTimeout(() => {
                                window.location.href = '{{ $consultation->type === 'homecare' ? route('patient.homecare.create') : route('patient.create') }}';
                            }, 10000);
                        }
                        
                        if (e.status === 'waiting_upload') {
                            setTimeout(() => {
                                window.location.href = '{{ route('patient.invoice', $consultation->invoice_number) }}';
                            }, 10000);
                        }
                    });
            }
            
            // Check initial status in case it was already rejected before page load
            if (this.currentStatus === 'payment_rejected') {
                setTimeout(() => {
                    window.location.href = '{{ $consultation->type === 'homecare' ? route('patient.homecare.create') : route('patient.create') }}';
                }, 10000);
            }
            
            if (this.currentStatus === 'waiting_upload') {
                setTimeout(() => {
                    window.location.href = '{{ route('patient.invoice', $consultation->invoice_number) }}';
                }, 10000);
            }
        },
    };
}
</script>
@endpush
