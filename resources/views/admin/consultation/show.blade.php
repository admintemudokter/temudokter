@extends('layouts.admin')
@section('title', 'Detail Konsultasi – ' . $consultation->invoice_number)
@section('page_title', 'Detail Konsultasi')
@section('page_subtitle', $consultation->invoice_number)

@section('content')
<div class="grid lg:grid-cols-3 gap-6">

    {{-- Info --}}
    <div class="lg:col-span-2 space-y-4">
        <div class="card card-body">
            <h3 class="font-heading font-bold text-slate-800 mb-4">Informasi Pasien</h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div><p class="text-slate-500 text-xs mb-1">Nama</p><p class="font-semibold">{{ $consultation->patient->full_name }}</p></div>
                <div><p class="text-slate-500 text-xs mb-1">WhatsApp</p><p class="font-semibold">{{ $consultation->patient->whatsapp_number }}</p></div>
                <div><p class="text-slate-500 text-xs mb-1">Usia / Kelamin</p><p class="font-semibold">{{ $consultation->patient->age }} tahun / {{ $consultation->patient->gender_label }}</p></div>
                <div class="col-span-2"><p class="text-slate-500 text-xs mb-1">Alamat Domisili</p><p class="font-semibold">{{ $consultation->patient->full_address }}</p></div>
                <div class="col-span-2"><p class="text-slate-500 text-xs mb-1">Keluhan</p><p class="text-slate-700">{{ $consultation->patient->complaint_description }}</p></div>
                <div class="col-span-2"><p class="text-slate-500 text-xs mb-1">Alergi Obat</p><p class="text-slate-700">{{ $consultation->patient->drug_allergies }}</p></div>
            </div>
        </div>

        {{-- Log Komunikasi & Pemberitahuan --}}
        <div class="card card-body">
            <h3 class="font-heading font-bold text-slate-800 mb-4">Log Komunikasi & Pemberitahuan</h3>
            <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 h-96 overflow-y-auto space-y-3">
                @forelse($consultation->messages as $msg)
                    <div class="flex {{ $msg->sender_type === 'patient' ? 'justify-start' : ($msg->sender_type === 'system' ? 'justify-center' : 'justify-end') }}">
                        @if($msg->sender_type === 'system')
                            <div class="bg-slate-200 text-slate-600 text-xs px-3 py-1 rounded-full">{{ $msg->message }}</div>
                        @else
                            <div class="max-w-md {{ $msg->sender_type === 'patient' ? 'bg-white border border-slate-200 text-slate-700' : 'bg-brand-600 text-white' }} rounded-xl p-3 text-sm">
                                <p class="font-bold text-xs mb-1 opacity-70">{{ $msg->sender_type === 'patient' ? 'Pasien' : 'Dokter' }}</p>
                                <p>{{ $msg->message }}</p>
                                @if($msg->attachment)
                                    <div class="mt-2 text-right">
                                        <a href="{{ route('files.attachment', $msg->id) }}" target="_blank" class="text-xs hover:underline text-brand-200">📎 Lihat Lampiran</a>
                                    </div>
                                @endif
                                <p class="text-xs mt-1 text-right opacity-60">{{ $msg->created_at->format('H:i') }}</p>
                            </div>
                        @endif
                    </div>
                @empty
                    <p class="text-center text-slate-400 text-sm py-4">Belum ada log komunikasi.</p>
                @endforelse
            </div>
        </div>

        {{-- Doctor Assignment --}}
        @if($consultation->consultation_status === 'waiting_assignment')
        <div class="card card-body">
            <h3 class="font-heading font-bold text-slate-800 mb-4">Tugaskan Dokter</h3>
            <div class="space-y-2 mb-4">
                @forelse($availableDoctors as $doctor)
                <form action="{{ route('admin.consultation.assign', $consultation->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">
                    <div class="flex items-center justify-between p-4 border border-slate-200 rounded-xl hover:border-brand-300 hover:bg-brand-50 transition-all">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 bg-brand-100 rounded-full flex items-center justify-center text-brand-700 font-bold text-sm">
                                {{ substr($doctor->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-semibold text-slate-800 text-sm">{{ $doctor->name }}</p>
                                <div class="flex items-center gap-2 text-xs text-slate-500">
                                    <span class="{{ 'status-dot-' . $doctor->status }}"></span>
                                    <span>{{ $doctor->specialization }}</span>
                                    <span>•</span>
                                    <span>{{ $doctor->active_consultations_count }} aktif</span>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn-primary btn-sm">Tugaskan</button>
                    </div>
                </form>
                @empty
                <p class="text-slate-400 text-sm text-center py-4">Tidak ada dokter yang tersedia saat ini.</p>
                @endforelse
            </div>
        </div>
        @endif
    </div>

    {{-- Right sidebar --}}
    <div class="space-y-4">
        <div class="card card-body">
            <h3 class="font-heading font-bold text-slate-800 mb-4">Status Konsultasi</h3>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-slate-500">Status</span>
                    <span class="badge-{{ $consultation->status_color }}">{{ $consultation->status_label }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Tipe Layanan</span>
                    <span class="font-semibold">{{ $consultation->type === 'homecare' ? 'Homecare' : 'Konsultasi Online' }}</span>
                </div>
                @if($consultation->type === 'homecare')
                <div class="flex justify-between">
                    <span class="text-slate-500">Biaya</span>
                    <span class="font-semibold">{{ $consultation->price ? 'Rp ' . number_format($consultation->price, 0, ',', '.') : 'Belum Ditentukan' }}</span>
                </div>
                @if($consultation->homecare_schedule_date)
                <div class="flex justify-between">
                    <span class="text-slate-500">Jadwal</span>
                    <span class="font-semibold text-right">{{ $consultation->homecare_schedule_date->format('d/m/Y') }} <br> {{ $consultation->homecare_schedule_time }}</span>
                </div>
                @endif
                @endif
                @if($consultation->doctor)
                <div class="flex justify-between">
                    <span class="text-slate-500">Dokter</span>
                    <span class="font-semibold">{{ $consultation->doctor->name }}</span>
                </div>
                @endif
                @if($consultation->started_at)
                <div class="flex justify-between">
                    <span class="text-slate-500">Dimulai</span>
                    <span class="font-semibold">{{ $consultation->started_at->format('H:i') }}</span>
                </div>
                @endif
                @if($consultation->ended_at)
                <div class="flex justify-between">
                    <span class="text-slate-500">Berakhir</span>
                    <span class="font-semibold">{{ $consultation->ended_at->format('H:i') }}</span>
                </div>
                @endif
            </div>
        </div>

        @if(in_array($consultation->consultation_status, ['active']))
        <form action="{{ route('admin.consultation.close', $consultation->id) }}" method="POST">
            @csrf
            <button type="submit" class="btn-danger w-full" onclick="return confirm('Force close konsultasi ini?')">
                Force Close Konsultasi
            </button>
        </form>
        @endif

        <a href="{{ route('admin.consultation.index') }}" class="btn-ghost w-full text-center block">← Kembali</a>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (window.Echo) {
            window.Echo.channel(`consultation.{{ $consultation->patient->session_token }}`)
                .listen('MessageSent', () => {
                    window.location.reload();
                })
                .listen('ConsultationStatusUpdated', () => {
                    window.location.reload();
                });
        }
    });
</script>
@endpush
@endsection
