@extends('layouts.admin')
@section('title', 'Manajemen Telekonsultasi')
@section('page_title', 'Manajemen Telekonsultasi')
@section('page_subtitle', 'Kanban board status konsultasi hari ini')

@section('content')

<div class="mb-4 flex items-center justify-between">
    <div class="flex items-center gap-2">
        <label for="type_filter" class="text-sm text-slate-500 font-medium">Filter Layanan:</label>
        <select id="type_filter" class="form-select py-1.5 text-sm rounded-lg border-slate-300 w-48" onchange="window.location.href = '?type=' + this.value">
            <option value="all" {{ $type === 'all' ? 'selected' : '' }}>Semua Layanan</option>
            <option value="telemedicine" {{ $type === 'telemedicine' ? 'selected' : '' }}>Konsultasi Online</option>
            <option value="homecare" {{ $type === 'homecare' ? 'selected' : '' }}>Homecare</option>
        </select>
    </div>
</div>

<div class="flex overflow-x-auto snap-x snap-mandatory lg:grid lg:grid-cols-4 gap-4 min-h-96 pb-4 scrollbar-hide">

    @php
    $kanban = [
        ['key'=>'waiting_admin_confirmation', 'label'=>'Menunggu Verifikasi', 'color'=>'blue', 'emoji'=>'🔍'],
        ['key'=>'waiting_assignment', 'label'=>'Menunggu Dokter', 'color'=>'purple', 'emoji'=>'👨‍⚕️'],
        ['key'=>'active', 'label'=>'Sedang Aktif', 'color'=>'emerald', 'emoji'=>'💬'],
        ['key'=>'completed', 'label'=>'Selesai (Menunggu Diarsipkan)', 'color'=>'teal', 'emoji'=>'✅'],
    ];
    @endphp

    @foreach($kanban as $col)
    <div class="flex flex-col w-[85vw] max-w-sm flex-shrink-0 snap-center lg:w-auto lg:max-w-none lg:flex-shrink">
        {{-- Column header --}}
        <div @class([
            'flex items-center gap-2 px-3 py-2 rounded-xl mb-3 text-sm font-semibold',
            'bg-amber-100 text-amber-800' => $col['color'] === 'amber',
            'bg-blue-100 text-blue-800' => $col['color'] === 'blue',
            'bg-purple-100 text-purple-800' => $col['color'] === 'purple',
            'bg-emerald-100 text-emerald-800' => $col['color'] === 'emerald',
            'bg-teal-100 text-teal-800' => $col['color'] === 'teal',
        ])>
            <span>{{ $col['emoji'] }}</span>
            <span>{{ $col['label'] }}</span>
            <span class="ml-auto w-5 h-5 bg-white/60 rounded-full flex items-center justify-center text-xs font-bold">
                {{ $columns[$col['key']]->count() }}
            </span>
        </div>
        
        @if($col['key'] === 'completed' && $columns['completed']->count() > 0)
        <form action="{{ route('admin.consultation.archiveAll') }}" method="POST" class="mb-3" onsubmit="return confirm('Anda yakin ingin memindahkan semua data Selesai ke Riwayat Telekonsultasi?');">
            @csrf
            <button type="submit" class="w-full btn py-2 text-xs bg-amber-100 text-amber-800 hover:bg-amber-200 border-none flex justify-center items-center gap-1 font-semibold rounded-xl">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                Arsipkan Semua
            </button>
        </form>
        @endif

        {{-- Cards --}}
        <div class="space-y-2 flex-1">
            @forelse($columns[$col['key']] as $c)
            <div class="relative flex flex-col card card-body !p-4 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 cursor-pointer overflow-hidden group">
                <a href="{{ route('admin.consultation.show', $c->id) }}" class="absolute inset-0 z-10"></a>
                <div class="flex items-start justify-between gap-2 mb-2">
                    <p class="font-semibold text-slate-800 text-sm truncate">{{ $c->patient->full_name }}</p>
                </div>
                <div class="flex items-center gap-2 mb-2">
                    <p class="text-xs text-slate-500 font-mono">{{ $c->invoice_number }}</p>
                    <span class="px-1.5 py-0.5 rounded text-[10px] font-bold {{ $c->type === 'homecare' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                        {{ $c->type === 'homecare' ? 'HOMECARE' : 'KONSULTASI ONLINE' }}
                    </span>
                </div>
                @if($c->doctor)
                <div class="flex items-center gap-1.5 text-xs text-slate-600">
                    <span class="status-dot-online"></span>
                    <span class="truncate">{{ $c->doctor->name }}</span>
                </div>
                @endif
                @if($col['key'] === 'active' && $c->remaining_seconds > 0)
                <div class="mt-2 text-xs text-emerald-600 font-semibold">
                    ⏱ {{ gmdate('i:s', $c->remaining_seconds) }}
                </div>
                @endif
                <p class="text-xs text-slate-400 mt-1">{{ $c->created_at->format('d/m/Y H:i') }}</p>
                
                @if($col['key'] === 'completed')
                <div class="mt-3 pt-3 border-t border-slate-100 flex justify-end relative z-20">
                    <form action="{{ route('admin.consultation.archive', $c->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="text-[11px] font-medium px-3 py-1.5 rounded-lg bg-amber-50 text-amber-700 hover:bg-amber-100 transition-colors border border-amber-200">
                            Pindah ke Riwayat
                        </button>
                    </form>
                </div>
                @endif
            </div>
            @empty
            <div class="text-center py-6 text-slate-400 text-xs">Kosong</div>
            @endforelse
        </div>
    </div>
    @endforeach

</div>

@endsection
