@extends('layouts.admin')
@section('title', 'Verifikasi Pembayaran')
@section('page_title', 'Verifikasi Pembayaran')
@section('page_subtitle', 'Review dan verifikasi bukti pembayaran pasien')

@section('content')

{{-- Search & Filter Form --}}
<div class="mb-6 flex flex-col sm:flex-row items-center justify-between gap-4">
    <form action="{{ route('admin.payment.index') }}" method="GET" class="w-full flex flex-col sm:flex-row gap-4">
        {{-- Search --}}
        <div class="relative flex-1">
            <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama pasien atau no invoice..."
                   class="form-input pl-10 w-full bg-white border-slate-200">
            @if(request('search'))
            <a href="{{ route('admin.payment.index', ['type' => request('type')]) }}" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </a>
            @endif
        </div>
        
        {{-- Filter Type --}}
        <div class="w-full sm:w-48">
            <select name="type" class="form-select bg-white border-slate-200" onchange="this.form.submit()">
                <option value="">Semua Layanan</option>
                <option value="online" {{ request('type') === 'online' ? 'selected' : '' }}>Konsultasi Online</option>
                <option value="homecare" {{ request('type') === 'homecare' ? 'selected' : '' }}>Homecare</option>
            </select>
        </div>
    </form>
</div>

<div class="card">
    <div class="divide-y divide-slate-50">
        @forelse($consultations as $c)
        @php 
            $proof = $c->transaction?->latestProof; 
            $status = $proof ? $proof->status : ($c->consultation_status === 'waiting_payment' ? 'menunggu_bayar' : $c->consultation_status);
        @endphp
        <div class="px-4 py-5 sm:px-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4 min-w-0">
                {{-- Status dot --}}
                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                     @class([
                         'bg-slate-100' => $status === 'menunggu_bayar' || $status === 'waiting_upload',
                         'bg-amber-100' => $status === 'pending',
                         'bg-emerald-100' => $status === 'approved' || in_array($status, ['active', 'completed', 'waiting_assignment']),
                         'bg-rose-100' => $status === 'rejected',
                         'bg-blue-100' => $status === 'request_reupload',
                     ])>
                    <span class="text-lg">
                        @if($status === 'pending') 🕐
                        @elseif($status === 'approved' || in_array($status, ['active', 'completed', 'waiting_assignment'])) ✅
                        @elseif($status === 'rejected') ❌
                        @elseif($status === 'menunggu_bayar' || $status === 'waiting_upload') ⏳
                        @else 🔄
                        @endif
                    </span>
                </div>

                <div class="min-w-0">
                    <p class="font-semibold text-slate-800">{{ $c->patient->full_name }} <span class="ml-2 text-xs font-normal text-slate-500 uppercase px-2 py-0.5 bg-slate-100 rounded">{{ $c->type === 'homecare' ? 'HOMECARE' : 'ONLINE' }}</span></p>
                    <div class="flex flex-wrap items-center gap-2 mt-0.5">
                        <span class="text-xs text-slate-500">{{ $c->invoice_number }}</span>
                        @if($c->transaction)
                        <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                        <span class="text-xs text-slate-500">{{ $c->transaction->method_label }}</span>
                        @if($c->transaction->payment_provider && !in_array(strtolower($c->transaction->payment_provider), ['qris', 'transfer bank', 'manual transfer bank']))
                        <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                        <span class="text-xs text-slate-500">{{ $c->transaction->payment_provider }}</span>
                        @endif
                        <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                        <span class="text-xs font-semibold text-slate-700">{{ $c->transaction->formatted_amount }}</span>
                        @endif
                    </div>
                    <p class="text-xs text-slate-400 mt-0.5">{{ $proof ? $proof->created_at->diffForHumans() : $c->created_at->diffForHumans() }}</p>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 w-full sm:w-auto mt-2 sm:mt-0 flex-shrink-0 border-t border-slate-100 pt-3 sm:border-0 sm:pt-0">
                <span @class([
                    'badge-slate' => $status === 'menunggu_bayar' || $status === 'waiting_upload',
                    'badge-amber' => $status === 'pending',
                    'badge-emerald' => $status === 'approved' || in_array($status, ['active', 'completed', 'waiting_assignment']),
                    'badge-red' => $status === 'rejected',
                    'badge-blue' => $status === 'request_reupload',
                ])>{{ 
                    $status === 'menunggu_bayar' ? 'Menunggu Bayar' : 
                    ($status === 'waiting_upload' ? 'Menunggu Upload Bukti' : 
                    (in_array($status, ['active', 'completed', 'waiting_assignment']) ? 'Telah Diverifikasi' : 
                    ucfirst(str_replace('_', ' ', $status)))) 
                }}</span>

                @if($status === 'pending' && $proof)
                <a href="{{ route('admin.payment.show', $proof->id) }}" class="btn-primary btn-sm">
                    Review →
                </a>
                @endif
                
                {{-- Delete Button --}}
                <form action="{{ route('admin.payment.destroy_consultation', $c->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data konsultasi dan pembayaran ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-ghost btn-sm text-rose-500 hover:bg-rose-50 hover:text-rose-700 !px-2" title="Hapus Konsultasi">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="px-6 py-16 text-center text-slate-400">
            <svg class="w-12 h-12 mx-auto mb-3 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="font-medium">Tidak ada pembayaran yang perlu diverifikasi</p>
        </div>
        @endforelse
    </div>
    @if($consultations->hasPages())
    <div class="px-6 py-4 border-t border-slate-100">{{ $consultations->links() }}</div>
    @endif
</div>

@endsection

@push('scripts')
<script>
    if (window.Echo) {
        window.Echo.channel('admin.dashboard')
            .listen('AdminDashboardUpdated', (e) => {
                // Tampilkan notifikasi kecil lalu reload halaman otomatis
                const toast = document.createElement('div');
                toast.className = 'fixed bottom-4 right-4 bg-emerald-600 text-white px-4 py-3 rounded-xl shadow-lg font-medium text-sm z-50 flex items-center gap-2 animate-bounce';
                toast.innerHTML = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> ${e.message}`;
                document.body.appendChild(toast);
                
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            });
    }
</script>
@endpush
