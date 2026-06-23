@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    /* Styling Flatpickr to match the purple theme */
    .flatpickr-day.selected {
        background: #9333ea !important;
        border-color: #9333ea !important;
    }
</style>
@endpush

@section('title', 'Invoice {{ $consultation->invoice_number }} – TemuDokter')

@section('content')
<div class="min-h-screen bg-slate-50" x-data="invoicePage('{{ $consultation->invoice_number }}', '{{ route('patient.payment.select', $consultation->invoice_number) }}', '{{ route('patient.payment.refresh', $consultation->invoice_number) }}', '{{ route('patient.payment.proof', $consultation->invoice_number) }}', {{ ($consultation->transaction && $consultation->transaction->payment_status !== 'rejected') ? 'true' : 'false' }}, '{{ $consultation->transaction?->payment_method ?? '' }}', '{{ $consultation->transaction?->payment_provider ?? '' }}', '{{ $consultation->transaction?->latestPaymentSession?->simulated_number ?? '' }}', {{ $consultation->transaction?->latestPaymentSession?->remaining_seconds ?? 3600 }}, '{{ $consultation->created_at->toIso8601String() }}')">

    {{-- Header --}}
    <div class="bg-white border-b border-slate-200">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <img src="{{ asset('images/logo.png') }}" alt="TemuDokter Logo" class="h-10 w-auto">
            </div>
            <div class="text-sm text-slate-500">Invoice Pembayaran</div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 py-10">
        <div class="grid lg:grid-cols-5 gap-8">

            {{-- Left: Invoice + Payment Method --}}
            <div class="lg:col-span-3 space-y-6">

                {{-- Invoice Card --}}
                <div class="card">
                    <div class="p-6 bg-gradient-to-br from-brand-50 to-teal-50 border-b border-brand-100 rounded-t-2xl">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-xs text-brand-600 font-semibold uppercase tracking-wider mb-1">Invoice</p>
                                <h1 class="font-heading text-2xl font-bold text-brand-800">{{ $consultation->invoice_number }}</h1>
                                <p class="text-slate-500 text-sm mt-1">{{ $consultation->created_at->format('d F Y, H:i') }}</p>
                            </div>
                            <div class="text-right">
                                <div class="badge-{{ $consultation->status_color }} text-xs">
                                    {{ $consultation->status_label }}
                                </div>
                                <div class="mt-2 text-xs font-semibold text-rose-500" x-show="invoiceTimeLeft > 0 && '{{ $consultation->consultation_status }}' === 'waiting_payment'" style="display: none;">
                                    Sisa waktu: <span x-text="formatTime(invoiceTimeLeft)"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-slate-500">Pasien</span>
                                <span class="font-semibold text-slate-800">{{ $consultation->patient->full_name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Wilayah</span>
                                <span class="font-semibold text-slate-800">{{ $consultation->patient->bekasi_area }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Durasi Konsultasi</span>
                                <span class="font-semibold text-slate-800">{{ $consultation->duration_minutes }} menit</span>
                            </div>
                            @php
                                $isHomecare = $consultation->type === 'homecare';
                                $basePrice = $isHomecare 
                                    ? \App\Models\Setting::getValue('homecare_price', 150000) 
                                    : \App\Models\Setting::getValue('online_price', 25000);
                                $discount = $isHomecare 
                                    ? \App\Models\Setting::getValue('homecare_discount', 0) 
                                    : \App\Models\Setting::getValue('online_discount', 0);
                            @endphp

                            @if($discount > 0 && !($isHomecare && is_null($consultation->price)))
                                @php
                                    $discountPercentage = $basePrice > 0 ? round(($discount / $basePrice) * 100) : 0;
                                @endphp
                                <div class="mt-4 mb-2 p-3 bg-emerald-50 border border-emerald-100 rounded-xl flex gap-3 items-start shadow-sm">
                                    <div class="bg-emerald-200/60 p-1.5 rounded-lg text-emerald-700 mt-0.5">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                    </div>
                                    <div>
                                        <h4 class="text-xs font-bold text-emerald-800">Status: Sedang Promo! 🎉</h4>
                                        <p class="text-xs text-emerald-600 mt-0.5 leading-relaxed">Catatan: Anda sedang mendapatkan potongan harga khusus sebesar {{ $discountPercentage }}%. Segera selesaikan pembayaran sebelum promo ini berakhir.</p>
                                    </div>
                                </div>
                                <div class="pt-3 border-t border-slate-100 flex justify-between items-center">
                                    <span class="text-slate-500 text-sm">Harga Dasar</span>
                                    <span class="text-slate-500 text-sm line-through">Rp {{ number_format($basePrice, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between items-center pt-1">
                                    <span class="text-emerald-600 text-sm font-medium">Diskon Khusus ({{ $discountPercentage }}%)</span>
                                    <span class="text-emerald-600 text-sm font-bold">- Rp {{ number_format($discount, 0, ',', '.') }}</span>
                                </div>
                                <div class="pt-2 flex justify-between items-center">
                            @else
                                <div class="pt-3 border-t border-slate-100 flex justify-between items-center">
                            @endif
                                    <span class="font-semibold text-slate-700">Total Pembayaran</span>
                                    <span class="text-2xl font-bold text-brand-700 font-heading">
                                        @if($isHomecare && is_null($consultation->price))
                                            <span class="text-lg text-slate-500">Menunggu Admin</span>
                                        @else
                                            Rp {{ number_format($consultation->price, 0, ',', '.') }}
                                        @endif
                                    </span>
                                </div>
                        </div>
                    </div>
                </div>

                @if($consultation->type === 'homecare' && is_null($consultation->price))
                {{-- Waiting Admin Pricing for Homecare --}}
                <div class="card card-body bg-purple-50 border-purple-100 text-center">
                    <div class="w-16 h-16 bg-purple-200 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-purple-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-heading text-xl font-bold text-purple-800 mb-2">Menunggu Konfirmasi Admin</h3>
                    <p class="text-purple-600 text-sm max-w-md mx-auto">Permintaan Homecare Anda telah diterima. Admin kami sedang memproses dan akan segera menentukan jadwal kunjungan serta biaya layanannya. Silakan *refresh* halaman ini nanti untuk melihat biayanya.</p>
                </div>
                @else
                @if($consultation->type === 'homecare')
                {{-- Homecare Scheduling Section --}}
                <div class="card mb-6">
                    <div class="p-6 border-b border-slate-100 bg-purple-50 rounded-t-2xl">
                        <h2 class="font-heading font-bold text-purple-800">Jadwal Kunjungan Homecare</h2>
                        <p class="text-purple-600 text-sm mt-1">Tentukan tanggal dan jam kunjungan sebelum melanjutkan pembayaran.</p>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="form-label text-purple-700">Pilih Tanggal (Hanya Sabtu & Minggu)</label>
                            <input type="text" x-ref="datePicker" class="form-input border-purple-200 focus:border-purple-500 focus:ring-purple-500 bg-white" placeholder="Pilih tanggal...">
                            <p x-show="dateError" class="text-rose-500 text-sm mt-1" x-text="dateError"></p>
                            
                            {{-- Admin Note --}}
                            <div x-show="dateNote" class="mt-3 p-3 bg-emerald-50 border border-emerald-100 rounded-xl flex gap-3 items-start" style="display: none;">
                                <div class="bg-emerald-200/50 p-1.5 rounded-lg text-emerald-600 mt-0.5">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div>
                                    <h4 class="text-xs font-bold text-emerald-800">Dibuka oleh Admin</h4>
                                    <p class="text-xs text-emerald-600 mt-0.5" x-text="dateNote"></p>
                                </div>
                            </div>
                        </div>
                        <div x-show="homecareDate && !dateError">
                            <label class="form-label text-purple-700">Pilih Jam Kunjungan</label>
                            <div class="flex gap-3 flex-wrap">
                                <template x-for="slot in availableSlots" :key="slot.time">
                                    <button @click="if(!slot.booked) homecareTime = slot.time"
                                            :class="slot.booked ? 'bg-slate-200 text-slate-400 cursor-not-allowed border-slate-200' : (homecareTime === slot.time ? 'bg-purple-600 text-white border-purple-600' : 'bg-white text-purple-700 hover:bg-purple-100 border-purple-200')"
                                            class="px-4 py-2 rounded-xl text-sm font-medium border transition-colors flex flex-col items-center min-w-[80px]"
                                            :disabled="slot.booked">
                                        <span class="text-base" x-text="slot.time"></span>
                                        <span x-show="slot.booked" class="text-[10px] font-bold text-rose-500 mt-0.5">CLOSED</span>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Payment Method Selection --}}
                <div class="card">
                    <div class="p-6 border-b border-slate-100">
                        <h2 class="font-heading font-bold text-slate-800">Pilih Metode Pembayaran</h2>
                        <p class="text-slate-500 text-sm mt-1">Pilih metode yang Anda inginkan.</p>
                    </div>
                    <div class="p-6 space-y-4">
                        {{-- Method tabs --}}
                        <div class="flex gap-2 rounded-xl bg-slate-100 p-1 mb-6">
                            <button @click="method='qris'; provider='QRIS'; createSession()" :class="method==='qris' ? 'bg-white shadow font-semibold text-brand-700' : 'text-slate-500'"
                                    class="flex-1 py-2 px-3 rounded-lg text-sm transition-all">QRIS</button>
                            <button @click="method='bank_transfer'; provider='Transfer Bank'; createSession()" :class="method==='bank_transfer' ? 'bg-white shadow font-semibold text-brand-700' : 'text-slate-500'"
                                    class="flex-1 py-2 px-3 rounded-lg text-sm transition-all">Pembayaran Manual Bank</button>
                        </div>

                        <div class="bg-slate-50 rounded-2xl p-6 mb-6 text-center relative overflow-hidden">
                            <!-- Loader overlay -->
                            <div x-show="loading" class="absolute inset-0 bg-slate-50/80 backdrop-blur-sm flex items-center justify-center z-10">
                                <svg class="w-8 h-8 text-brand-600 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>

                            <template x-if="method === 'qris'">
                                <div>
                                    <p class="text-xs text-slate-500 mb-2">Scan QR Code di bawah ini</p>
                                    <img src="{{ asset('images/qris-merchant.png') }}" alt="QRIS" class="mx-auto max-w-[250px] border border-slate-200 rounded-xl mb-3 shadow-sm">
                                    <a href="{{ asset('images/qris-merchant.png') }}" download="QRIS_Temu_Dokter.png" class="inline-flex items-center gap-2 text-xs px-4 py-2 border border-slate-200 rounded-xl bg-white hover:bg-slate-50 text-slate-700 font-semibold mb-4 shadow-sm transition-colors cursor-pointer">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                        Unduh QRIS
                                    </a>
                                </div>
                            </template>
                            <template x-if="method === 'bank_transfer'">
                                <div>
                                    <p class="text-xs text-slate-500 mb-2">Transfer ke salah satu rekening berikut:</p>
                                    <div class="text-left bg-white p-4 rounded-xl border border-slate-200 inline-block mb-4 shadow-sm">
                                        <p class="font-bold text-slate-800 text-sm">Rekening an. Rizkianna Narwiningtyas</p>
                                        <p class="text-sm text-slate-600 mt-2">Bank Mandiri : <span class="font-semibold text-brand-700">1670003323929</span></p>
                                        <p class="text-sm text-slate-600 mt-1">Bank BNI : <span class="font-semibold text-brand-700">1860258283</span></p>
                                    </div>
                                </div>
                            </template>
                            
                            <p class="text-xs text-slate-500 mt-2">Jumlah: <strong class="text-slate-800">Rp {{ number_format($consultation->price ?? env('DEFAULT_CONSULTATION_PRICE', 25000), 0, ',', '.') }}</strong></p>
                        </div>
                        
                        <div class="flex gap-3">
                            <button @click="showProofModal = true" class="btn-primary w-full" :disabled="loading">
                                ✅ Saya Sudah Bayar
                            </button>
                        </div>
                    </div>
                </div>


                @endif

            </div>

            {{-- Right: Info Panel --}}
            <div class="lg:col-span-2 space-y-4">
                @if($consultation->consultation_status === 'payment_rejected')
                <div class="card card-body bg-rose-50 border-rose-100 text-center">
                    <h3 class="font-heading font-bold text-rose-800 mb-2">Pembayaran Ditolak</h3>
                    <p class="text-sm text-rose-600 mb-4">Mohon maaf, bukti pembayaran Anda ditolak oleh Admin. Silakan pilih ulang metode pembayaran, atau kembali ke halaman utama.</p>
                    <a href="{{ route('home') }}" class="btn-secondary w-full border-rose-200 text-rose-700 hover:bg-rose-100">← Kembali ke Beranda</a>
                </div>
                @endif

                <div class="card card-body">
                    <h3 class="font-heading font-bold text-slate-800 mb-4">Langkah Selanjutnya</h3>
                    @if($consultation->type === 'homecare' && is_null($consultation->price))
                    <ol class="space-y-3 text-sm">
                        <li class="flex gap-3">
                            <span class="w-6 h-6 bg-purple-600 text-white rounded-full flex items-center justify-center text-xs flex-shrink-0 font-bold">1</span>
                            <span class="text-slate-600">Admin menentukan jadwal dan biaya</span>
                        </li>
                        <li class="flex gap-3">
                            <span class="w-6 h-6 bg-slate-300 text-white rounded-full flex items-center justify-center text-xs flex-shrink-0 font-bold">2</span>
                            <span class="text-slate-400">Pilih metode pembayaran dan lanjutkan</span>
                        </li>
                        <li class="flex gap-3">
                            <span class="w-6 h-6 bg-slate-300 text-white rounded-full flex items-center justify-center text-xs flex-shrink-0 font-bold">3</span>
                            <span class="text-slate-400">Transfer ke nomor yang ditampilkan</span>
                        </li>
                        <li class="flex gap-3">
                            <span class="w-6 h-6 bg-slate-300 text-white rounded-full flex items-center justify-center text-xs flex-shrink-0 font-bold">4</span>
                            <span class="text-slate-400">Upload bukti pembayaran</span>
                        </li>
                    </ol>
                    @else
                    <ol class="space-y-3 text-sm">
                        <li class="flex gap-3">
                            <span class="w-6 h-6 bg-brand-600 text-white rounded-full flex items-center justify-center text-xs flex-shrink-0 font-bold">1</span>
                            <span class="text-slate-600">Pilih metode pembayaran dan lanjutkan</span>
                        </li>
                        <li class="flex gap-3">
                            <span class="w-6 h-6 bg-brand-600 text-white rounded-full flex items-center justify-center text-xs flex-shrink-0 font-bold">2</span>
                            <span class="text-slate-600">Transfer ke nomor yang ditampilkan</span>
                        </li>
                        <li class="flex gap-3">
                            <span class="w-6 h-6 bg-brand-600 text-white rounded-full flex items-center justify-center text-xs flex-shrink-0 font-bold">3</span>
                            <span class="text-slate-600">Upload bukti pembayaran</span>
                        </li>
                        <li class="flex gap-3">
                            <span class="w-6 h-6 bg-slate-300 text-white rounded-full flex items-center justify-center text-xs flex-shrink-0 font-bold">4</span>
                            <span class="text-slate-400">Admin memverifikasi (5–15 menit)</span>
                        </li>
                    </ol>
                    @endif
                </div>

                <div class="alert-info">
                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                    <span class="text-xs">Simpan nomor invoice <strong>{{ $consultation->invoice_number }}</strong> sebagai referensi pembayaran Anda.</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== PROOF UPLOAD MODAL ===== --}}
    <div x-show="showProofModal" x-transition class="modal-backdrop" @click.self="showProofModal = false">
        <div class="modal-box" @click.stop>
            <div class="modal-header">
                <h3 class="font-heading font-bold text-slate-800">Upload Bukti Pembayaran</h3>
                <button @click="showProofModal = false" class="btn-ghost btn-sm !px-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="modal-body space-y-4">
                <div class="upload-zone" @click="$refs.fileInput.click()">
                    <svg class="w-10 h-10 text-slate-300 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                    <p class="text-slate-500 text-sm font-medium" x-text="proofFileName || 'Klik untuk pilih file'"></p>
                    <p class="text-slate-400 text-xs mt-1">JPG, PNG, PDF – Maks. 2MB</p>
                </div>
                <input type="file" id="proof_file" name="proof_file" class="hidden" accept=".jpg,.jpeg,.png,.pdf" 
                       x-ref="fileInput" 
                       @change="
                            let file = $event.target.files[0];
                            if (file && file.size > 2 * 1024 * 1024) {
                                alert('Ukuran file maksimal 2MB!');
                                $event.target.value = '';
                                proofFileName = '';
                                proofFile = null;
                            } else {
                                proofFile = file;
                                proofFileName = file?.name ?? '';
                            }
                       ">
                <p x-show="uploadError" x-text="uploadError" class="text-rose-500 text-xs mt-2 font-medium" style="display: none;"></p>
                <div>
                    <label class="form-label">Catatan (Opsional)</label>
                    <textarea x-model="proofNotes" class="form-textarea" rows="2" placeholder="Contoh: Transfer dari BCA a.n. Budi Santoso"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button @click="showProofModal = false" class="btn-ghost">Batal</button>
                <button @click="uploadProof()" :disabled="!proofFile || uploadingProof" class="btn-primary">
                    <svg x-show="uploadingProof" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    <span x-text="uploadingProof ? 'Mengupload...' : 'Upload Bukti'"></span>
                </button>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>
<script>
function invoicePage(inv, selectUrl, refreshUrl, proofUrl, initActive, initMethod, initProvider, initNumber, initTimeLeft, createdAtISO) {
    return {
        method: '',
        provider: '',
        
        sessionActive: initActive,
        selectedMethod: initMethod,
        selectedProvider: initProvider,
        simulatedNumber: initNumber,
        timeLeft: initTimeLeft,
        timerInterval: null,
        
        invoiceExpiresAt: new Date(new Date(createdAtISO).getTime() + 3 * 60 * 60 * 1000),
        invoiceTimeLeft: 0,
        invoiceTimerInterval: null,
        
        loading: false,
        expired: initActive && initTimeLeft <= 0,
        
        // Modal
        showProofModal: false,
        proofFile: null,
        proofFileName: null,
        proofNotes: '',
        uploadingProof: false,

        // Homecare
        homecareDate: '',
        homecareTime: '',
        availableSlots: [],
        dateError: '',
        dateNote: '',
        openedDates: @js($openedDates ?? []),
        
        init() {
            if (this.method === '') {
                this.method = 'qris';
                this.provider = 'QRIS';
                this.createSession();
            }
            
            // Start 3-hour global invoice timer
            this.invoiceTimerInterval = setInterval(() => {
                const now = new Date().getTime();
                this.invoiceTimeLeft = Math.max(0, Math.floor((this.invoiceExpiresAt - now) / 1000));
                if (this.invoiceTimeLeft === 0 && '{{ $consultation->consultation_status }}' === 'waiting_payment') {
                    clearInterval(this.invoiceTimerInterval);
                    window.location.reload(); // Will hit controller and redirect
                }
            }, 1000);

            // Initialize Flatpickr if homecare
            if ('{{ $consultation->type }}' === 'homecare' && !'{{ $consultation->price }}') {
                // Not priced yet, don't show picker? The picker is inside the section where price is present
            }
            
            if (this.$refs.datePicker) {
                const self = this;
                const openedDateStrings = this.openedDates.map(o => o.date);
                
                flatpickr(this.$refs.datePicker, {
                    locale: "id",
                    dateFormat: "Y-m-d",
                    altInput: true,
                    altFormat: "l, j F Y",
                    minDate: "today",
                    disable: [
                        function(date) {
                            // Check if this date is specifically opened by admin
                            const d = new Date(date.getTime() - (date.getTimezoneOffset() * 60000));
                            const dateStr = d.toISOString().split('T')[0];
                            if (openedDateStrings.includes(dateStr)) {
                                return false; // Do not disable
                            }
                            // Otherwise, disable if day is not Saturday (6) and not Sunday (0)
                            return (date.getDay() !== 0 && date.getDay() !== 6);
                        }
                    ],
                    onChange: (selectedDates, dateStr, instance) => {
                        self.homecareDate = dateStr;
                        
                        // Check if selected date has a note
                        const opened = self.openedDates.find(o => o.date === dateStr);
                        if (opened) {
                            self.dateNote = opened.reason || 'Jadwal hari biasa ini telah dibuka khusus oleh admin.';
                        } else {
                            self.dateNote = '';
                        }
                        
                        self.fetchSlots();
                    }
                });
            }
        },

        formatTime(s) {
            const h = Math.floor(s / 3600);
            const m = Math.floor((s % 3600) / 60);
            const sec = s % 60;
            return [h, m, sec].map(v => v.toString().padStart(2, '0')).join(':');
        },

        async createSession() {
            this.loading = true;
            try {
                const res = await postJson(selectUrl, { payment_method: this.method, payment_provider: this.provider });
                const data = await res.json();
                if (data.success) {
                    this.sessionActive = true;
                    this.selectedMethod = data.payment_method;
                    this.selectedProvider = data.provider;
                    this.simulatedNumber = data.simulated_number;
                }
            } catch(e) { console.error(e); }
            this.loading = false;
        },

        refreshSession() {},

        startTimer() {},

        copyNumber() {
            navigator.clipboard.writeText(this.simulatedNumber);
        },

        async fetchSlots() {
            this.dateError = '';
            this.homecareTime = '';
            this.availableSlots = [];
            
            if (!this.homecareDate) return;
            
            const openedDateStrings = this.openedDates.map(o => o.date);
            const isOpened = openedDateStrings.includes(this.homecareDate);
            
            const d = new Date(this.homecareDate);
            const day = d.getDay(); // 0 is Sunday, 6 is Saturday
            
            if (day !== 0 && day !== 6 && !isOpened) {
                this.dateError = 'Kunjungan Homecare hanya tersedia di hari Sabtu dan Minggu.';
                return;
            }

            try {
                const res = await getJson(`/api/homecare/slots?date=${this.homecareDate}`);
                const data = await res.json();
                this.availableSlots = data.slots;
            } catch(e) {
                console.error(e);
            }
        },

        async uploadProof() {
            if (!this.proofFile) return;
            if ('{{ $consultation->type }}' === 'homecare') {
                if (!this.homecareDate || !this.homecareTime) {
                    alert('Silakan pilih jadwal kunjungan Homecare terlebih dahulu.');
                    return;
                }
            }
            
            this.uploadingProof = true;
            const fd = new FormData();
            fd.append('proof_file', this.proofFile);
            fd.append('notes', this.proofNotes);
            if ('{{ $consultation->type }}' === 'homecare') {
                fd.append('homecare_date', this.homecareDate);
                fd.append('homecare_time', this.homecareTime);
            }
            try {
                const res = await postForm(proofUrl, fd);
                const data = await res.json();
                if (data.success) {
                    window.location.href = data.waiting_url;
                } else if (data.error) {
                    alert(data.error);
                } else if (data.message) {
                    alert(data.message);
                }
            } catch(e) { 
                console.error(e); 
                alert('Terjadi kesalahan sistem saat mengupload bukti.');
            }
            this.uploadingProof = false;
        },
    };
}
</script>
@endpush
