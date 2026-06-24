<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ruang Konsultasi {{ $consultation->invoice_number }} – Temu Dokter</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-100 overflow-hidden h-screen"
      x-data="consultationRoom({{ $consultation->id }}, '{{ $consultation->consultation_status }}', {{ $consultation->remaining_seconds }})"
      x-init="init()">

<div class="h-screen flex flex-col">

    {{-- ===== TOP HEADER ===== --}}
    <header class="bg-white border-b border-slate-200 px-4 py-3 flex items-center justify-between flex-shrink-0 shadow-sm relative z-20">
        <div class="flex items-center gap-3">
            <img src="{{ asset('images/logo.png') }}" alt="Temu Dokter Logo" class="h-10 w-auto">
            <div>
                <p class="text-xs text-slate-500 font-medium">{{ $consultation->invoice_number }}</p>
            </div>
        </div>

        {{-- Status + Timer --}}
        <div class="flex items-center gap-4">
            @if($consultation->doctor)
            <button @click="showInfoModal = true" class="hidden sm:flex items-center gap-2 text-sm text-left hover:bg-slate-50 p-1.5 rounded-xl transition-colors border border-transparent hover:border-slate-100">
                @if($consultation->doctor->photo)
                    <img src="{{ asset('storage/' . $consultation->doctor->photo) }}" class="w-8 h-8 rounded-full object-cover shadow-sm">
                @else
                    <div class="w-8 h-8 bg-brand-100 rounded-full flex items-center justify-center text-brand-700 font-bold text-xs shadow-sm">
                        {{ substr($consultation->doctor->name, 0, 1) }}
                    </div>
                @endif
                <div>
                    <p class="font-semibold text-slate-800 text-xs">{{ $consultation->doctor->name }}</p>
                    <p class="text-slate-500 text-[10px]">{{ $consultation->doctor->specialization }}</p>
                </div>
                <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </button>
            
            {{-- Mobile Info Button --}}
            <button @click="showInfoModal = true" class="sm:hidden p-2 text-slate-500 hover:text-brand-600 hover:bg-brand-50 rounded-lg">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </button>
            @endif

            {{-- Timer --}}
            <div x-show="status === 'active'"
                 class="flex items-center gap-2 px-3 py-1.5 rounded-xl text-sm font-bold"
                 :class="timeLeft <= 60 ? 'bg-rose-100 text-rose-700' : timeLeft <= 300 ? 'bg-amber-100 text-amber-700' : 'bg-brand-100 text-brand-700'">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span x-text="formatTime(timeLeft)"></span>
            </div>

            <div x-show="status !== 'active'" class="badge-slate text-xs" x-text="statusLabel"></div>
        </div>
    </header>

    {{-- ===== WARNING BANNERS ===== --}}
    <div x-show="timeLeft <= 300 && timeLeft > 60 && status === 'active'" class="bg-amber-50 border-b border-amber-200 px-4 py-2 text-center text-amber-800 text-xs font-semibold flex-shrink-0 relative z-10">
        ⚠️ Sisa waktu konsultasi <span x-text="formatTime(timeLeft)"></span>. Segera sampaikan pertanyaan Anda.
    </div>
    <div x-show="timeLeft <= 60 && timeLeft > 0 && status === 'active'" class="bg-rose-50 border-b border-rose-200 px-4 py-2 text-center text-rose-800 text-xs font-semibold flex-shrink-0 animate-pulse relative z-10">
        🚨 Kurang dari 1 menit! Konsultasi akan segera berakhir.
    </div>

    {{-- ===== MAIN CONTENT ===== --}}
    <div class="flex-1 flex overflow-hidden">

        {{-- Center: Chat Area --}}
        <div class="flex-1 flex flex-col min-w-0">

            {{-- Prescription Banner (Mobile only) --}}
            @if($consultation->prescription)
            <div class="bg-emerald-50 border-b border-emerald-100 px-4 py-2 flex items-center justify-between xl:hidden flex-shrink-0 relative z-10">
                <div class="flex items-center gap-2 text-emerald-800">
                    <span class="text-sm font-semibold">✅ Resep Tersedia</span>
                </div>
                <a href="{{ \Illuminate\Support\Facades\URL::signedRoute('files.prescription', $consultation->prescription->id) }}" target="_blank" class="btn-sm bg-emerald-600 text-white hover:bg-emerald-700 rounded-lg px-3 py-1.5 text-xs font-medium">
                    Lihat Resep
                </a>
            </div>
            @endif

            {{-- Messages --}}
            <div class="flex-1 overflow-y-auto p-4 space-y-3 scrollbar-hide relative z-0" id="chat-messages" x-ref="messages">

                {{-- Consultation not active banner --}}
                <div x-show="status !== 'active'" class="chat-bubble-system my-4">
                    <span x-text="status === 'completed' ? '✅ Konsultasi telah selesai.' : '⏳ Menunggu konsultasi dimulai...'"></span>
                </div>

                {{-- Messages --}}
                <template x-for="msg in messages" :key="msg.id">
                    <div class="flex"
                         :class="msg.sender_type === 'patient' ? 'justify-end' : msg.sender_type === 'system' ? 'justify-center' : 'justify-start'">

                        {{-- System message --}}
                        <div x-show="msg.sender_type === 'system'" class="chat-bubble-system" x-text="msg.message"></div>

                        {{-- Doctor message --}}
                        <div x-show="msg.sender_type === 'doctor'" class="flex items-end gap-2 max-w-sm md:max-w-md">
                            <div class="w-7 h-7 bg-brand-100 rounded-full flex items-center justify-center text-brand-700 font-bold text-xs flex-shrink-0">
                                D
                            </div>
                            <div>
                                <div class="chat-bubble-doctor">
                                    <p x-text="msg.message" x-show="msg.message"></p>
                                    <div x-show="msg.attachment_type === 'image'" class="mt-2">
                                        <a :href="msg.attachment_url" target="_blank" class="text-xs text-brand-600 hover:underline inline-flex items-center gap-1">📎 Lihat Gambar</a>
                                    </div>
                                    <div x-show="msg.attachment_type === 'pdf'" class="mt-2">
                                        <a :href="msg.attachment_url" target="_blank" class="text-xs text-brand-600 hover:underline inline-flex items-center gap-1">📄 Lihat PDF</a>
                                    </div>
                                </div>
                                <p class="text-xs text-slate-400 mt-1 ml-1" x-text="msg.created_at"></p>
                            </div>
                        </div>

                        {{-- Patient message --}}
                        <div x-show="msg.sender_type === 'patient'" class="max-w-sm md:max-w-md">
                            <div class="chat-bubble-patient">
                                <p x-text="msg.message" x-show="msg.message"></p>
                                <div x-show="msg.attachment_type !== 'none' && msg.attachment_url" class="mt-2 text-right">
                                    <a :href="msg.attachment_url" target="_blank" class="text-xs text-teal-200 hover:underline">📎 Lihat Lampiran</a>
                                </div>
                            </div>
                            <p class="text-xs text-slate-400 mt-1 text-right mr-1" x-text="msg.created_at"></p>
                        </div>
                    </div>
                </template>

                {{-- Typing indicator --}}
                <div x-show="typing" class="flex items-end gap-2">
                    <div class="w-7 h-7 bg-brand-100 rounded-full flex items-center justify-center flex-shrink-0">D</div>
                    <div class="chat-bubble-doctor py-3">
                        <div class="flex gap-1">
                            <span class="w-2 h-2 bg-slate-400 rounded-full animate-bounce" style="animation-delay:0ms"></span>
                            <span class="w-2 h-2 bg-slate-400 rounded-full animate-bounce" style="animation-delay:150ms"></span>
                            <span class="w-2 h-2 bg-slate-400 rounded-full animate-bounce" style="animation-delay:300ms"></span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Input area --}}
            <div class="bg-white border-t border-slate-200 p-4 flex-shrink-0 relative z-20">
                <div x-show="status === 'active'">
                    <div class="flex items-end gap-2">
                        {{-- Attachment button --}}
                        <label class="cursor-pointer btn-ghost btn-sm !px-2.5 !py-2.5 flex-shrink-0">
                            <svg class="w-5 h-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                            </svg>
                            <input type="file" class="hidden" accept=".jpg,.jpeg,.png,.pdf" @change="pendingAttachment = $event.target.files[0]">
                        </label>

                        {{-- Pending attachment indicator --}}
                        <div x-show="pendingAttachment" class="flex items-center gap-1 px-3 py-1.5 bg-brand-50 border border-brand-200 rounded-xl text-xs text-brand-700 flex-shrink-0">
                            📎 <span x-text="pendingAttachment?.name"></span>
                            <button @click="pendingAttachment = null" class="ml-1 text-brand-400 hover:text-brand-700">×</button>
                        </div>

                        {{-- Text input --}}
                        <textarea x-model="newMessage"
                                  @keydown.enter.prevent="!$event.shiftKey ? sendMessage() : null"
                                  class="flex-1 form-textarea resize-none py-2.5 text-sm rounded-xl focus:ring-brand-500"
                                  rows="1"
                                  placeholder="Ketik pesan... (Enter untuk kirim)"
                                  :disabled="sending"></textarea>

                        {{-- Send button --}}
                        <button @click="sendMessage()"
                                :disabled="(!newMessage.trim() && !pendingAttachment) || sending"
                                class="btn-primary flex-shrink-0 !px-3 !py-2.5 rounded-xl shadow-md disabled:shadow-none disabled:opacity-60">
                            <svg x-show="!sending" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                            <svg x-show="sending" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Closed state --}}
                <div x-show="status !== 'active'" class="text-center py-4">
                    <div x-show="status === 'completed'">
                        <p class="text-slate-500 text-sm mb-3">Konsultasi telah selesai.</p>
                        <a href="{{ route('patient.summary', session('patient_token')) }}"
                           class="btn-primary btn-sm rounded-xl">Isi Survei & Lihat Ringkasan →</a>
                    </div>
                    <div x-show="status !== 'completed'">
                        <p class="text-slate-400 text-sm">Menunggu konsultasi dimulai...</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Panel: Patient Info (desktop) --}}
        <aside class="hidden xl:flex w-80 border-l border-slate-200 bg-slate-50 flex-col flex-shrink-0 relative z-10">
            <div class="p-5 border-b border-slate-200 bg-white">
                <h3 class="font-heading font-bold text-slate-800 text-sm flex items-center gap-2">
                    <svg class="w-4 h-4 text-brand-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Informasi Pasien
                </h3>
            </div>
            <div class="p-5 space-y-5 flex-1 overflow-y-auto">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Nama Pasien</p>
                    <p class="font-bold text-slate-800 text-sm">{{ $consultation->patient->full_name }}</p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Usia</p>
                        <p class="font-bold text-slate-800 text-sm">{{ $consultation->patient->age }} tahun</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Gender</p>
                        <p class="font-bold text-slate-800 text-sm capitalize">{{ $consultation->patient->gender }}</p>
                    </div>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Wilayah</p>
                    <p class="font-bold text-slate-800 text-sm">{{ $consultation->patient->bekasi_area }}</p>
                </div>
                <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Keluhan Medis</p>
                    <p class="text-sm text-slate-700 leading-relaxed">{{ $consultation->patient->complaint_description }}</p>
                </div>
                <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Alergi Obat</p>
                    <p class="text-sm text-slate-700 leading-relaxed">{{ $consultation->patient->drug_allergies }}</p>
                </div>

                @if($consultation->patient->medical_image || $consultation->patient->medical_document)
                <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Lampiran Medis</p>
                    <div class="space-y-2 mt-2">
                        @if($consultation->patient->medical_image)
                        <a href="{{ \Illuminate\Support\Facades\URL::signedRoute('files.medical', ['patient' => $consultation->patient->id, 'field' => 'medical_image']) }}" target="_blank" class="w-full flex items-center justify-between p-2 rounded-lg border border-slate-200 hover:bg-slate-50 transition-colors">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded bg-blue-50 text-blue-500 flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <span class="text-xs font-medium text-slate-700">Foto Medis</span>
                            </div>
                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                        @endif
                        @if($consultation->patient->medical_document)
                        <a href="{{ \Illuminate\Support\Facades\URL::signedRoute('files.medical', ['patient' => $consultation->patient->id, 'field' => 'medical_document']) }}" target="_blank" class="w-full flex items-center justify-between p-2 rounded-lg border border-slate-200 hover:bg-slate-50 transition-colors">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded bg-rose-50 text-rose-500 flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <span class="text-xs font-medium text-slate-700">Dokumen Medis</span>
                            </div>
                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                        @endif
                    </div>
                </div>
                @endif

                @if($consultation->prescription)
                <div class="bg-emerald-50 rounded-2xl p-4 border border-emerald-100 mt-4">
                    <p class="text-xs font-semibold text-emerald-600 uppercase tracking-wider mb-2 flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Resep Dokter
                    </p>
                    @if($consultation->prescription->notes)
                    <p class="text-xs text-emerald-800 mb-3 leading-relaxed">{{ $consultation->prescription->notes }}</p>
                    @endif
                    <a href="{{ \Illuminate\Support\Facades\URL::signedRoute('files.prescription', $consultation->prescription->id) }}" target="_blank" class="w-full btn bg-white text-emerald-700 border border-emerald-200 hover:bg-emerald-100 text-xs py-2 shadow-sm rounded-xl flex items-center justify-center gap-2">
                        Unduh PDF Resep
                    </a>
                </div>
                @endif
            </div>
        </aside>
    </div>
</div>

{{-- ===== MODAL INFO DOKTER & PASIEN ===== --}}
<div x-show="showInfoModal" 
     style="display: none;" 
     class="fixed inset-0 z-50 overflow-y-auto" 
     aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        {{-- Backdrop --}}
        <div x-show="showInfoModal" 
             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" 
             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" 
             class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" 
             @click="showInfoModal = false" aria-hidden="true"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        {{-- Modal Content --}}
        <div x-show="showInfoModal" 
             x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
             x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
             class="inline-block w-full max-w-md overflow-hidden text-left align-middle transition-all transform bg-white rounded-3xl shadow-2xl sm:my-8 border border-slate-100">
            
            {{-- Tabs --}}
            <div class="flex border-b border-slate-100">
                <button @click="activeTab = 'doctor'" 
                        :class="activeTab === 'doctor' ? 'text-rose-500 border-rose-500 font-bold' : 'text-slate-400 border-transparent hover:text-slate-600 font-medium'" 
                        class="flex-1 py-4 text-sm text-center border-b-2 transition-colors">
                    Informasi Dokter
                </button>
                <button @click="activeTab = 'patient'" 
                        :class="activeTab === 'patient' ? 'text-rose-500 border-rose-500 font-bold' : 'text-slate-400 border-transparent hover:text-slate-600 font-medium'" 
                        class="flex-1 py-4 text-sm text-center border-b-2 transition-colors">
                    Informasi Pasien
                </button>
            </div>

            {{-- Tab Content: Doctor --}}
            @if($consultation->doctor)
            <div x-show="activeTab === 'doctor'" class="overflow-y-auto max-h-[70vh]">
                {{-- Header Pink Background --}}
                <div class="bg-rose-50/50 p-6 flex items-center gap-4 border-b border-rose-100/50">
                    @if($consultation->doctor->photo)
                        <button @click="showZoomModal = true" class="relative group outline-none rounded-full focus:ring-2 focus:ring-brand-500 focus:ring-offset-2">
                            <img src="{{ asset('storage/' . $consultation->doctor->photo) }}" class="w-16 h-16 rounded-full object-cover border-2 border-white shadow-sm transition-transform group-hover:scale-105">
                            <div class="absolute inset-0 bg-black/20 rounded-full opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <svg class="w-5 h-5 text-white drop-shadow-md" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg>
                            </div>
                        </button>
                    @else
                        <div class="w-16 h-16 bg-white border-2 border-rose-100 rounded-full flex items-center justify-center text-rose-500 font-bold text-2xl shadow-sm">
                            {{ substr($consultation->doctor->name, 0, 1) }}
                        </div>
                    @endif
                    <div>
                        <h3 class="font-bold text-slate-800 text-lg">{{ $consultation->doctor->name }}</h3>
                        <p class="text-slate-500 text-sm mb-1">{{ $consultation->doctor->specialization }}</p>
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-white border border-slate-200 rounded-full text-xs font-semibold text-slate-600 shadow-sm">
                            <svg class="w-3.5 h-3.5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $consultation->doctor->experience_years ?? 0 }} tahun pengalaman
                        </div>
                    </div>
                </div>

                {{-- Details --}}
                <div class="p-6 space-y-5">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Lokasi Praktek</p>
                        <p class="font-semibold text-slate-800 text-sm">{{ $consultation->doctor->practice_location ?? 'Praktek dr ' . $consultation->doctor->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Pendidikan</p>
                        <p class="font-semibold text-slate-800 text-sm">{{ $consultation->doctor->education ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Nomor STR</p>
                        <p class="font-semibold text-slate-800 text-sm">{{ $consultation->doctor->str_number ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Nomor SIP</p>
                        <p class="font-semibold text-slate-800 text-sm">{{ $consultation->doctor->sip_number ?? '-' }}</p>
                    </div>
                </div>
            </div>
            @endif

            {{-- Tab Content: Patient --}}
            <div x-show="activeTab === 'patient'" style="display:none;" class="p-6 overflow-y-auto max-h-[70vh]">
                <div class="space-y-5">
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Nama Pasien</p>
                        <p class="font-semibold text-slate-800 text-sm">{{ $consultation->patient->full_name }}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Usia</p>
                            <p class="font-semibold text-slate-800 text-sm">{{ $consultation->patient->age }} tahun</p>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Gender</p>
                            <p class="font-semibold text-slate-800 text-sm capitalize">{{ $consultation->patient->gender }}</p>
                        </div>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Wilayah</p>
                        <p class="font-semibold text-slate-800 text-sm">{{ $consultation->patient->bekasi_area }}</p>
                    </div>
                    <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Keluhan Medis</p>
                        <p class="text-sm text-slate-700 leading-relaxed">{{ $consultation->patient->complaint_description }}</p>
                    </div>
                    <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Alergi Obat</p>
                        <p class="text-sm text-slate-700 leading-relaxed">{{ $consultation->patient->drug_allergies }}</p>
                    </div>
                </div>
            </div>

            {{-- Footer / Button --}}
            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                <button @click="showInfoModal = false" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-colors shadow-sm">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

@if($consultation->doctor && $consultation->doctor->photo)
{{-- ===== ZOOM PHOTO MODAL ===== --}}
<div x-show="showZoomModal" 
     style="display: none;" 
     class="fixed inset-0 z-[60] flex items-center justify-center p-4 sm:p-6" 
     aria-labelledby="modal-title" role="dialog" aria-modal="true">
    
    {{-- Backdrop --}}
    <div x-show="showZoomModal" 
         x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" 
         x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" 
         class="fixed inset-0 transition-opacity bg-black/90 backdrop-blur-sm" 
         @click="showZoomModal = false" aria-hidden="true"></div>

    {{-- Close Button --}}
    <button @click="showZoomModal = false" class="absolute top-4 right-4 sm:top-8 sm:right-8 z-[70] p-2 text-white/70 hover:text-white bg-black/20 hover:bg-black/40 rounded-full backdrop-blur-md transition-all">
        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>

    {{-- Image Container --}}
    <div x-show="showZoomModal" 
         x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-90 translate-y-8" x-transition:enter-end="opacity-100 scale-100 translate-y-0" 
         x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-95 translate-y-4" 
         class="relative z-[60] max-w-4xl w-full max-h-[90vh] flex flex-col items-center justify-center pointer-events-none">
        
        <img src="{{ asset('storage/' . $consultation->doctor->photo) }}" 
             class="max-w-full max-h-[85vh] object-contain rounded-2xl shadow-2xl pointer-events-auto"
             alt="{{ $consultation->doctor->name }}">
             
        <p class="mt-4 text-white/90 text-lg font-medium drop-shadow-md text-center pointer-events-auto">{{ $consultation->doctor->name }}</p>
    </div>
</div>
@endif

<script>
function consultationRoom(id, initialStatus, initialRemaining) {
    return {
        messages: {!! $consultation->messages->map(fn($m) => [
            'id'              => $m->id,
            'sender_type'     => $m->sender_type,
            'message'         => $m->message,
            'attachment'      => $m->attachment,
            'attachment_type' => $m->attachment_type,
            'attachment_url'  => $m->attachment ? \Illuminate\Support\Facades\URL::signedRoute('files.attachment', $m->id) : null,
            'is_read'         => $m->is_read,
            'created_at'      => $m->created_at->format('H:i'),
        ])->toJson() !!},
        status: initialStatus,
        statusLabel: '{{ $consultation->status_label }}',
        timeLeft: initialRemaining,
        newMessage: '',
        pendingAttachment: null,
        sending: false,
        typing: false,
        lastId: 0,
        showInfoModal: false,
        showZoomModal: false,
        activeTab: 'doctor',

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
            // Set last message id
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
                        
                        if (this.status === 'completed') {
                            window.location.href = "{{ route('patient.summary', session('patient_token')) }}";
                        }
                    });
            }

            // Timer countdown
            if (this.status === 'active') {
                if (this.timeLeft <= 0) {
                    window.location.href = "{{ route('patient.summary', session('patient_token')) }}";
                } else {
                    setInterval(() => {
                        if (this.timeLeft > 0 && this.status === 'active') {
                            this.timeLeft--;
                            if (this.timeLeft === 0) {
                                // Tunggu 2 detik barangkali dokter mengakhiri dan mengirim resep
                                setTimeout(() => {
                                    window.location.href = "{{ route('patient.summary', session('patient_token')) }}";
                                }, 2000);
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

            // Optimistic UI
            const optimistic = {
                id: Date.now(),
                sender_type: 'patient',
                message: this.newMessage,
                attachment: null,
                attachment_type: this.pendingAttachment ? 'image' : 'none',
                is_read: false,
                created_at: new Date().toLocaleTimeString('id-ID', {hour:'2-digit',minute:'2-digit'}),
            };
            this.messages.push(optimistic);
            this.newMessage = '';
            this.pendingAttachment = null;
            this.scrollToBottom();

            try {
                await postForm(`/api/pasien/pesan/${id}`, fd);
            } catch(e) {}

            this.sending = false;
        },
    };
}
</script>
@include('components.anti-inspect')
</body>
</html>
