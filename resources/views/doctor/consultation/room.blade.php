<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ruang Konsultasi – {{ $consultation->patient->full_name }} | Temu Dokter</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-100 overflow-hidden h-screen"
      x-data="doctorRoom({{ $consultation->id }}, '{{ $consultation->consultation_status }}', {{ $consultation->remaining_seconds }})"
      x-init="init()">

<div class="h-screen flex flex-col">

    {{-- Header --}}
    <header class="bg-emerald-900 text-white px-4 py-3 flex items-center justify-between flex-shrink-0">
        <div class="flex items-center gap-3">
            <a href="{{ route('doctor.dashboard') }}" class="text-white/60 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <p class="font-semibold text-sm">{{ $consultation->patient->full_name }}</p>
                <p class="text-white/60 text-xs">{{ $consultation->invoice_number }} • {{ $consultation->patient->bekasi_area }}</p>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <div x-show="status === 'active'"
                 class="flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-sm font-bold"
                 :class="timeLeft <= 60 ? 'bg-rose-600' : timeLeft <= 300 ? 'bg-amber-500' : 'bg-emerald-700'">
                ⏱ <span x-text="formatTime(timeLeft)"></span>
            </div>
            <button class="lg:hidden btn btn-sm bg-white/20 hover:bg-white/30 text-white border-0" @click="showInfoModal = true">
                Info Pasien
            </button>
            <button x-show="status === 'active'" @click="showEndModal = true"
                    class="btn btn-sm bg-rose-500 text-white hover:bg-rose-600 border-0">
                Akhiri Konsultasi
            </button>
        </div>
    </header>

    <div class="flex-1 flex overflow-hidden">

        {{-- Chat --}}
        <div class="flex-1 flex flex-col min-w-0">
            {{-- Messages --}}
            <div class="flex-1 overflow-y-auto p-4 space-y-3 scrollbar-hide" x-ref="messages">
                <template x-for="msg in messages" :key="msg.id">
                    <div class="flex"
                         :class="msg.sender_type === 'doctor' ? 'justify-end' : msg.sender_type === 'system' ? 'justify-center' : 'justify-start'">
                        <div x-show="msg.sender_type === 'system'" class="chat-bubble-system" x-text="msg.message"></div>
                        <div x-show="msg.sender_type === 'patient'" class="chat-bubble-doctor max-w-md">
                            <p x-text="msg.message" x-show="msg.message"></p>
                            <div x-show="msg.attachment_type !== 'none' && msg.attachment_url" class="mt-2">
                                <a :href="msg.attachment_url" target="_blank" class="text-xs text-brand-600 hover:underline inline-flex items-center gap-1">📎 Lihat Lampiran</a>
                            </div>
                            <p class="text-xs text-slate-400 mt-1" x-text="msg.created_at"></p>
                        </div>
                        <div x-show="msg.sender_type === 'doctor'" class="max-w-md">
                            <div class="chat-bubble-patient">
                                <p x-text="msg.message" x-show="msg.message"></p>
                                <div x-show="msg.attachment_type === 'image'" class="mt-2 text-right">
                                    <a :href="msg.attachment_url" target="_blank" class="text-xs text-teal-200 hover:underline inline-flex items-center gap-1">📎 Lihat Gambar</a>
                                </div>
                                <div x-show="msg.attachment_type === 'pdf'" class="mt-2 text-right">
                                    <a :href="msg.attachment_url" target="_blank" class="text-xs text-teal-200 hover:underline inline-flex items-center gap-1">📄 Lihat PDF</a>
                                </div>
                            </div>
                            <p class="text-xs text-slate-400 mt-1 text-right" x-text="msg.created_at"></p>
                        </div>
                    </div>
                </template>
            </div>

            {{-- Input --}}
            <div class="bg-white border-t p-4 flex-shrink-0">
                @if($consultation->type === 'homecare')
                <div x-show="status === 'active'" class="mb-2 flex gap-2 overflow-x-auto pb-1 scrollbar-hide">
                    <button @click="sendQuickReply(`🚗 Halo, saya dr. {{ auth('doctor')->user()->name }} yang ditugaskan. Saya sedang bersiap menuju rumah Anda.`)" class="btn-ghost btn-sm whitespace-nowrap text-xs bg-purple-50 hover:bg-purple-100 text-purple-700 border border-purple-200">
                        🚗 Menuju Rumah Anda
                    </button>
                    <button @click="sendQuickReply(`📍 Pasien sedang di tindak lanjuti oleh dokter.`)" class="btn-ghost btn-sm whitespace-nowrap text-xs bg-purple-50 hover:bg-purple-100 text-purple-700 border border-purple-200">
                        📍 Sedang Ditindak lanjuti
                    </button>
                    <button @click="sendQuickReply(`✅ Tindakan medis telah selesai dilakukan. Terima kasih.`); showEndModal = true;" class="btn-ghost btn-sm whitespace-nowrap text-xs bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200">
                        ✅ Selesai
                    </button>
                </div>
                @endif
                <div x-show="status === 'active'" class="flex items-end gap-2">
                    <label class="cursor-pointer btn-ghost btn-sm !px-2.5">
                        <svg class="w-5 h-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                        </svg>
                        <input type="file" class="hidden" accept=".jpg,.jpeg,.png,.pdf" @change="pendingAttachment = $event.target.files[0]">
                    </label>
                    <textarea x-model="newMessage"
                              @keydown.enter.prevent="!$event.shiftKey ? sendMessage() : null"
                              class="flex-1 form-textarea resize-none py-2.5 text-sm" rows="1"
                              placeholder="Balas pasien..."></textarea>
                    <button @click="sendMessage()" :disabled="(!newMessage.trim() && !pendingAttachment) || sending"
                            class="btn flex-shrink-0 !px-3 !py-2.5 bg-emerald-600 text-white hover:bg-emerald-700 rounded-xl">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                    </button>
                </div>
                <div x-show="status !== 'active'" class="text-center py-4 text-slate-400 text-sm">
                    Konsultasi telah berakhir.
                </div>
            </div>
        </div>

        {{-- Right: Patient Info + Actions --}}
        <aside class="hidden lg:flex w-72 border-l border-slate-200 bg-white flex-col flex-shrink-0">
            <div class="p-5 space-y-4 flex-1 overflow-y-auto">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-3">Data Pasien</p>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-slate-500">Usia</span>
                            <span class="font-semibold">{{ $consultation->patient->age }} tahun</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Jenis Kelamin</span>
                            <span class="font-semibold capitalize">{{ $consultation->patient->gender }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Pekerjaan</span>
                            <span class="font-semibold">{{ $consultation->patient->occupation ?: '-' }}</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-slate-500 mb-1">Domisili</span>
                            <span class="font-semibold text-right">{{ $consultation->patient->full_address }}</span>
                        </div>
                        @if($consultation->type === 'homecare')
                        <div class="flex justify-between">
                            <span class="text-slate-500">Tipe Layanan</span>
                            <span class="font-semibold text-purple-600">Homecare</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-slate-500 mb-1">Alamat Lengkap</span>
                            <span class="font-semibold text-right">{{ $consultation->address }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-500">Jadwal Kunjungan</span>
                            <span class="font-semibold text-right">{{ $consultation->homecare_schedule_date?->format('d/m/Y') }} <br> {{ $consultation->homecare_schedule_time }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">Keluhan</p>
                    <div class="bg-slate-50 rounded-xl p-3 text-sm text-slate-700 leading-relaxed">
                        {{ $consultation->patient->complaint_description }}
                    </div>
                </div>

                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">Alergi Obat</p>
                    <div class="bg-slate-50 rounded-xl p-3 text-sm text-slate-700 leading-relaxed">
                        {{ $consultation->patient->drug_allergies }}
                    </div>
                </div>

                @if($consultation->patient->medical_image || $consultation->patient->medical_document)
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">Lampiran Medis</p>
                    <div class="space-y-2">
                        @if($consultation->patient->medical_image)
                        <a href="{{ \Illuminate\Support\Facades\URL::signedRoute('files.medical', ['patient' => $consultation->patient->id, 'field' => 'medical_image']) }}" target="_blank" class="w-full flex items-center justify-between p-2 rounded-xl bg-slate-50 hover:bg-slate-100 transition-colors border border-slate-100">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded bg-blue-100 text-blue-600 flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <span class="text-sm font-medium text-slate-700">Foto Medis</span>
                            </div>
                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                        @endif
                        @if($consultation->patient->medical_document)
                        <a href="{{ \Illuminate\Support\Facades\URL::signedRoute('files.medical', ['patient' => $consultation->patient->id, 'field' => 'medical_document']) }}" target="_blank" class="w-full flex items-center justify-between p-2 rounded-xl bg-slate-50 hover:bg-slate-100 transition-colors border border-slate-100">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded bg-rose-100 text-rose-600 flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <span class="text-sm font-medium text-slate-700">Dokumen Medis</span>
                            </div>
                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                        @endif
                    </div>
                </div>
                @endif

                {{-- Prescription Section --}}
                <div class="pt-4 border-t border-slate-100 space-y-2">
                    <button @click="showPrescriptionModal = true"
                            class="btn w-full bg-emerald-600 text-white hover:bg-emerald-700 border-0 text-sm">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Buat Resep Obat
                    </button>
                    <button @click="showSickLeaveModal = true"
                            class="btn w-full bg-blue-600 text-white hover:bg-blue-700 border-0 text-sm">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Buat Surat Sakit
                    </button>
                </div>

                @if($consultation->type === 'homecare')
                {{-- Homecare Report Section --}}
                <div class="pt-4 border-t border-slate-100">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-3">Laporan Kunjungan</p>

                    @if($consultation->homecare_report)
                    <div class="bg-purple-50 border border-purple-200 rounded-xl p-3 mb-3">
                        <p class="text-sm font-semibold text-purple-800 mb-1">✅ Laporan ditambahkan</p>
                        <p class="text-xs text-purple-700 mb-2">{{ $consultation->homecare_report }}</p>
                    </div>
                    @endif

                    <button @click="showReportModal = true"
                            class="btn w-full bg-purple-600 text-white hover:bg-purple-700 border-0 text-sm">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        {{ $consultation->homecare_report ? 'Update Laporan' : 'Tulis Laporan' }}
                    </button>
                </div>
                @endif
            </div>
        </aside>
    </div>
</div>

{{-- Patient Info Modal (Mobile) --}}
<div x-show="showInfoModal" x-transition class="modal-backdrop lg:hidden">
    <div class="modal-box max-h-[90vh] flex flex-col">
        <div class="modal-header">
            <h3 class="font-heading font-bold text-slate-800">Info Pasien & Resep</h3>
            <button @click="showInfoModal = false" class="btn-ghost btn-sm !px-2">✕</button>
        </div>
        <div class="modal-body space-y-4 overflow-y-auto">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-3">Data Pasien</p>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Usia</span>
                        <span class="font-semibold">{{ $consultation->patient->age }} tahun</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Jenis Kelamin</span>
                        <span class="font-semibold capitalize">{{ $consultation->patient->gender }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Pekerjaan</span>
                        <span class="font-semibold">{{ $consultation->patient->occupation ?: '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Wilayah</span>
                        <span class="font-semibold">{{ $consultation->patient->bekasi_area }}</span>
                    </div>
                </div>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">Keluhan</p>
                <div class="bg-slate-50 rounded-xl p-3 text-sm text-slate-700 leading-relaxed">
                    {{ $consultation->patient->complaint_description }}
                </div>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">Alergi Obat</p>
                <div class="bg-slate-50 rounded-xl p-3 text-sm text-slate-700 leading-relaxed">
                    {{ $consultation->patient->drug_allergies }}
                </div>
            </div>
            <div class="pt-4 border-t border-slate-100 space-y-2">
                <button @click="showInfoModal = false; showPrescriptionModal = true"
                        class="btn w-full bg-emerald-600 text-white hover:bg-emerald-700 border-0 text-sm">
                    Buat Resep Obat
                </button>
                <button @click="showInfoModal = false; showSickLeaveModal = true"
                        class="btn w-full bg-blue-600 text-white hover:bg-blue-700 border-0 text-sm">
                    Buat Surat Sakit
                </button>
            </div>
        </div>
    </div>
</div>

{{-- End Consultation Modal --}}
<div x-show="showEndModal" x-transition class="modal-backdrop">
    <div class="modal-box max-w-sm">
        <div class="modal-header">
            <h3 class="font-heading font-bold text-slate-800">Akhiri Konsultasi?</h3>
        </div>
        <div class="modal-body">
            <p class="text-slate-600 text-sm">Apakah Anda yakin ingin mengakhiri sesi konsultasi dengan <strong>{{ $consultation->patient->full_name }}</strong>? Tindakan ini tidak dapat dibatalkan.</p>
        </div>
        <div class="modal-footer">
            <button @click="showEndModal = false" class="btn-ghost">Batal</button>
            <button @click="endConsultation()" class="btn-danger">Ya, Akhiri</button>
        </div>
    </div>
</div>

{{-- Prescription Modal --}}
<div x-show="showPrescriptionModal" x-transition class="modal-backdrop">
    <div class="modal-box max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="modal-header">
            <h3 class="font-heading font-bold text-slate-800">Buat Resep Obat</h3>
            <button @click="showPrescriptionModal = false" class="btn-ghost btn-sm !px-2">✕</button>
        </div>
        <div class="modal-body space-y-4">
            <template x-for="(item, index) in prescriptionItems" :key="index">
                <div class="p-4 border border-slate-200 rounded-xl relative bg-slate-50">
                    <button type="button" @click="prescriptionItems.splice(index, 1)" x-show="prescriptionItems.length > 1" class="absolute top-2 right-2 text-rose-500 hover:text-rose-700 text-xs font-bold bg-white px-2 py-1 rounded shadow-sm">✕ Hapus</button>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Nama Obat <span class="text-rose-500">*</span></label>
                            <select x-model="item.medicine_id" class="form-select" required>
                                <option value="">Pilih Obat...</option>
                                <template x-for="med in medicines" :key="med.id">
                                    <option :value="med.id" x-text="med.name"></option>
                                </template>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Jumlah Obat <span class="text-rose-500">*</span></label>
                            <input type="number" x-model="item.quantity" class="form-input" min="1" placeholder="Contoh: 10" required>
                        </div>
                        <div class="md:col-span-2">
                            <label class="form-label">Kegunaan</label>
                            <input type="text" x-model="item.kegunaan" class="form-input" placeholder="Contoh: Meredakan sakit kepala">
                        </div>
                        <div class="md:col-span-2">
                            <label class="form-label">Instruksi Pemakaian <span class="text-rose-500">*</span></label>
                            <input type="text" x-model="item.instructions" class="form-input" placeholder="Contoh: 3x1 sehari setelah makan" required>
                        </div>
                        <div class="md:col-span-2">
                            <label class="form-label">Catatan Tambahan</label>
                            <textarea x-model="item.notes" class="form-textarea" rows="2" placeholder="Contoh: Habiskan obat antibiotik"></textarea>
                        </div>
                    </div>
                </div>
            </template>
            <button @click="prescriptionItems.push({ medicine_id: '', quantity: '', kegunaan: '', instructions: '', notes: '' })" type="button" class="btn-ghost w-full border border-dashed border-slate-300 text-brand-600 hover:bg-brand-50 hover:border-brand-300">+ Tambah Obat Lain</button>
        </div>
        <div class="modal-footer">
            <button @click="showPrescriptionModal = false" class="btn-ghost">Batal</button>
            <button @click="submitPrescription()" :disabled="uploadingPrescription" class="btn flex-shrink-0 bg-emerald-600 text-white hover:bg-emerald-700 border-0">
                <span x-text="uploadingPrescription ? 'Menyimpan...' : 'Kirim Resep'"></span>
            </button>
        </div>
    </div>
</div>

{{-- Sick Leave Modal --}}
<div x-show="showSickLeaveModal" x-transition class="modal-backdrop">
    <div class="modal-box max-w-md">
        <div class="modal-header">
            <h3 class="font-heading font-bold text-slate-800">Buat Surat Sakit</h3>
            <button @click="showSickLeaveModal = false" class="btn-ghost btn-sm !px-2">✕</button>
        </div>
        <div class="modal-body space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Dari Tanggal <span class="text-rose-500">*</span></label>
                    <input type="date" x-model="sickLeave.start_date" class="form-input" required>
                </div>
                <div>
                    <label class="form-label">Sampai Tanggal <span class="text-rose-500">*</span></label>
                    <input type="date" x-model="sickLeave.end_date" class="form-input" required>
                </div>
            </div>
            <div>
                <label class="form-label">Keterangan / Diagnosa <span class="text-rose-500">*</span></label>
                <textarea x-model="sickLeave.reason" class="form-textarea" rows="3" placeholder="Pasien membutuhkan istirahat karena..." required></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button @click="showSickLeaveModal = false" class="btn-ghost">Batal</button>
            <button @click="submitSickLeave()" :disabled="uploadingSickLeave" class="btn flex-shrink-0 bg-blue-600 text-white hover:bg-blue-700 border-0">
                <span x-text="uploadingSickLeave ? 'Menyimpan...' : 'Kirim Surat Sakit'"></span>
            </button>
        </div>
    </div>
</div>

{{-- Homecare Report Modal --}}
@if($consultation->type === 'homecare')
<div x-show="showReportModal" x-transition class="modal-backdrop">
    <div class="modal-box">
        <div class="modal-header">
            <h3 class="font-heading font-bold text-slate-800">Laporan Kunjungan Homecare</h3>
            <button @click="showReportModal = false" class="btn-ghost btn-sm !px-2">✕</button>
        </div>
        <div class="modal-body space-y-4">
            <div>
                <label class="form-label">Deskripsi Laporan Medis</label>
                <textarea x-model="homecareReport" class="form-textarea" rows="6"
                          placeholder="Tuliskan hasil pemeriksaan fisik, diagnosis sementara, atau catatan medis lainnya terkait kunjungan ke rumah pasien..."></textarea>
            </div>
        </div>
        <div class="modal-footer">
            <button @click="showReportModal = false" class="btn-ghost">Batal</button>
            <button @click="uploadHomecareReport()" :disabled="uploadingReport" class="btn flex-shrink-0 bg-purple-600 text-white hover:bg-purple-700 border-0">
                <span x-text="uploadingReport ? 'Menyimpan...' : 'Simpan Laporan'"></span>
            </button>
        </div>
    </div>
</div>
@endif

<script>
function doctorRoom(id, initialStatus, initialRemaining) {
    return {
        messages: {!! $consultation->messages->map(fn($m) => [
            'id'              => $m->id,
            'sender_type'     => $m->sender_type,
            'message'         => $m->message,
            'attachment'      => $m->attachment,
            'attachment_type' => $m->attachment_type,
            'attachment_url'  => $m->attachment ? \Illuminate\Support\Facades\URL::signedRoute('files.attachment', $m->id) : null,
            'created_at'      => $m->created_at->format('H:i'),
        ])->toJson() !!},
        status: initialStatus,
        timeLeft: initialRemaining,
        newMessage: '',
        pendingAttachment: null,
        sending: false,
        lastId: 0,
        showEndModal: false,
        showInfoModal: false,
        showPrescriptionModal: false,
        
        medicines: @js($medicines),
        prescriptionItems: [{ medicine_id: '', quantity: '', kegunaan: '', instructions: '', notes: '' }],
        uploadingPrescription: false,

        showSickLeaveModal: false,
        sickLeave: {
            start_date: '',
            end_date: '',
            reason: '',
        },
        uploadingSickLeave: false,
        
        showReportModal: false,
        homecareReport: @js($consultation->homecare_report ?? ''),
        uploadingReport: false,

        formatTime(sec) {
            const m = Math.floor(sec / 60).toString().padStart(2, '0');
            const s = (sec % 60).toString().padStart(2, '0');
            return m + ':' + s;
        },

        scrollToBottom() {
            this.$nextTick(() => {
                const el = this.$refs.messages;
                if (el) el.scrollTop = el.scrollHeight;
            });
        },

        async init() {
            if (this.messages.length > 0) {
                this.lastId = this.messages[this.messages.length - 1].id;
            }
            this.scrollToBottom();
            
            // Listen to Echo channel
            if (window.Echo) {
                window.Echo.channel(`consultation.{{ $consultation->patient->session_token }}`)
                    .listen('MessageSent', (e) => {
                        // Prevent duplicate if optimistic UI already added it
                        if (!this.messages.find(m => m.id === e.id)) {
                            this.messages.push({
                                id: e.id,
                                sender_type: e.sender_type,
                                message: e.message,
                                attachment: null,
                                attachment_type: e.attachment_type || (e.file_path ? 'image' : 'none'),
                                attachment_url: e.file_path,
                                is_read: false,
                                created_at: e.created_at
                            });
                            this.scrollToBottom();
                        }
                    })
                    .listen('ConsultationStatusUpdated', (e) => {
                        this.status = e.status;
                        if (e.status_label) this.statusLabel = e.status_label;
                    });
            }

            if (this.status === 'active') {
                if (this.timeLeft <= 0) {
                    this.endConsultation();
                } else {
                    setInterval(() => { 
                        if (this.timeLeft > 0 && this.status === 'active') {
                            this.timeLeft--;
                            if (this.timeLeft === 0) {
                                this.endConsultation();
                            }
                        }
                    }, 1000);
                }
            }
        },

        async sendMessage() {
            if ((!this.newMessage.trim() && !this.pendingAttachment) || this.sending) return;
            this.sending = true;
            const fd = new FormData();
            if (this.newMessage.trim()) fd.append('message', this.newMessage);
            if (this.pendingAttachment) fd.append('attachment', this.pendingAttachment);
            this.newMessage = '';
            this.pendingAttachment = null;
            try { await postForm(`/doctor/api/pesan/${id}`, fd); this.fetchMessages(); } catch(e) {}
            this.sending = false;
        },

        async fetchMessages() {
            try {
                const res = await fetch(`/doctor/api/pesan/${id}?after=${this.lastId}`);
                if (res.ok) {
                    const data = await res.json();
                    if (data.messages && data.messages.length > 0) {
                        data.messages.forEach(m => {
                            if (!this.messages.find(x => x.id === m.id)) {
                                this.messages.push(m);
                            }
                        });
                        this.lastId = data.messages[data.messages.length - 1].id;
                        this.scrollToBottom();
                    }
                }
            } catch(e) {}
        },

        async sendQuickReply(text) {
            if (this.sending) return;
            this.newMessage = text;
            await this.sendMessage();
        },

        async endConsultation() {
            try {
                await postJson(`/doctor/konsultasi/${id}/end`, {});
                this.status = 'completed';
                this.showEndModal = false;
            } catch(e) {}
        },

        async submitPrescription() {
            if (this.prescriptionItems.length === 0 || !this.prescriptionItems[0].medicine_id) {
                alert('Silakan pilih minimal 1 obat');
                return;
            }
            this.uploadingPrescription = true;
            try {
                const res = await postJson(`/doctor/konsultasi/${id}/prescription`, { items: this.prescriptionItems });
                const data = await res.json();
                if (data.success) {
                    this.showPrescriptionModal = false;
                    this.prescriptionItems = [{ medicine_id: '', quantity: '', instructions: '', notes: '' }];
                    this.fetchMessages();
                } else {
                    alert(data.error || 'Gagal membuat resep');
                }
            } catch (err) {
                alert('Terjadi kesalahan.');
            }
            this.uploadingPrescription = false;
        },

        async submitSickLeave() {
            if (!this.sickLeave.start_date || !this.sickLeave.end_date || !this.sickLeave.reason) {
                alert('Silakan lengkapi semua field surat sakit');
                return;
            }
            this.uploadingSickLeave = true;
            try {
                const res = await postJson(`/doctor/konsultasi/${id}/sick-leave`, this.sickLeave);
                const data = await res.json();
                if (data.success) {
                    this.showSickLeaveModal = false;
                    this.sickLeave = { start_date: '', end_date: '', reason: '' };
                    this.fetchMessages();
                } else {
                    alert(data.error || 'Gagal membuat surat sakit');
                }
            } catch (err) {
                alert('Terjadi kesalahan.');
            }
            this.uploadingSickLeave = false;
        },

        async uploadPrescription() {
            this.uploadingPrescription = true;
            const fd = new FormData();
            if (this.prescriptionNotes) fd.append('notes', this.prescriptionNotes);
            if (this.prescriptionFile) fd.append('file', this.prescriptionFile);
            try {
                const res = await postForm(`/doctor/konsultasi/${id}/prescription`, fd);
                const data = await res.json();
                if (data.success) {
                    this.showPrescriptionModal = false;
                    alert(data.message);
                }
            } catch(e) {}
            this.uploadingPrescription = false;
        },

        async uploadHomecareReport() {
            this.uploadingReport = true;
            const fd = new FormData();
            if (this.homecareReport) fd.append('homecare_report', this.homecareReport);
            try {
                const res = await postForm(`/doctor/konsultasi/${id}/report`, fd);
                const data = await res.json();
                if (data.success) {
                    this.showReportModal = false;
                    alert(data.message);
                    window.location.reload(); // Reload to show the updated report
                } else {
                    alert(data.message || data.error || 'Gagal menyimpan laporan. Pastikan teks tidak terlalu pendek.');
                }
            } catch(e) {
                alert('Terjadi kesalahan saat menyimpan laporan.');
            }
            this.uploadingReport = false;
        },
    };
}
</script>
@include('components.anti-inspect')
</body>
</html>
