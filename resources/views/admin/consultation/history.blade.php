@extends('layouts.admin')
@section('title', 'Riwayat Telekonsultasi')
@section('page_title', 'Riwayat Telekonsultasi')
@section('page_subtitle', 'Data pasien yang telah selesai dan diarsipkan')

@section('content')

<div class="card p-6 border-slate-200">
    {{-- Filter & Search Form --}}
    <form method="GET" action="{{ route('admin.consultation.history') }}" class="flex flex-col lg:flex-row items-end gap-4 mb-6 bg-slate-50 p-4 rounded-xl border border-slate-100">
        
        <div class="w-full lg:w-1/4">
            <label for="search" class="form-label">Pencarian</label>
            <div class="relative">
                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                    class="form-input pl-10 bg-white" placeholder="Cari Pasien, Dokter, Invoice...">
                <svg class="w-5 h-5 text-slate-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>

        <div class="w-full lg:w-1/5">
            <label for="type" class="form-label">Layanan</label>
            <select name="type" id="type" class="form-select bg-white">
                <option value="all">Semua Layanan</option>
                <option value="telemedicine" {{ request('type') == 'telemedicine' ? 'selected' : '' }}>Konsultasi Online</option>
                <option value="homecare" {{ request('type') == 'homecare' ? 'selected' : '' }}>Homecare</option>
            </select>
        </div>

        <div class="w-full lg:w-1/5">
            <label for="start_date" class="form-label">Tanggal Mulai</label>
            <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}" class="form-input bg-white">
        </div>

        <div class="w-full lg:w-1/5">
            <label for="end_date" class="form-label">Tanggal Akhir</label>
            <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}" class="form-input bg-white">
        </div>

        <div class="w-full lg:w-auto flex gap-2">
            <button type="submit" class="btn btn-primary px-6 h-[42px] whitespace-nowrap">Filter</button>
            <a href="{{ route('admin.consultation.history') }}" class="btn btn-outline border-slate-300 text-slate-600 h-[42px] px-4 whitespace-nowrap bg-white hover:bg-slate-50">Reset</a>
        </div>
    </form>

    {{-- Table --}}
    <div class="overflow-x-auto">
        <table class="table w-full text-sm text-left">
            <thead>
                <tr class="bg-slate-50 text-slate-500 uppercase text-xs">
                    <th class="px-4 py-3 rounded-l-xl">Tgl. Selesai</th>
                    <th class="px-4 py-3">Invoice</th>
                    <th class="px-4 py-3">Layanan</th>
                    <th class="px-4 py-3">Pasien</th>
                    <th class="px-4 py-3">Dokter</th>
                    <th class="px-4 py-3">Harga</th>
                    <th class="px-4 py-3 text-center rounded-r-xl">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($histories as $c)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-4 py-3 whitespace-nowrap text-slate-500">
                        {{ $c->created_at->format('d M Y') }}<br>
                        <span class="text-xs">{{ $c->created_at->format('H:i') }} WIB</span>
                    </td>
                    <td class="px-4 py-3 font-mono text-slate-600 text-xs">
                        {{ $c->invoice_number }}<br>
                        <span class="text-[10px] text-slate-400">Kode: {{ $c->history_code }}</span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded-md text-[10px] font-bold uppercase {{ $c->type === 'homecare' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                            {{ $c->type === 'homecare' ? 'HOMECARE' : 'ONLINE' }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="font-medium text-slate-800">{{ $c->patient->full_name }}</div>
                        <div class="text-xs text-slate-500">{{ $c->patient->whatsapp_number }}</div>
                    </td>
                    <td class="px-4 py-3 text-slate-600">
                        {{ $c->doctor ? $c->doctor->name : '-' }}
                    </td>
                    <td class="px-4 py-3 font-semibold text-slate-700">
                        Rp {{ number_format($c->price ?? 0, 0, ',', '.') }}
                    </td>
                    <td class="px-4 py-3 text-center">
                        <a href="{{ route('admin.consultation.show', $c->id) }}" class="btn py-1.5 px-3 text-xs bg-slate-100 text-slate-700 hover:bg-slate-200 border-none">
                            Lihat Detail & Roomchat
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center text-slate-400">
                        <div class="flex flex-col items-center justify-center">
                            <svg class="w-12 h-12 mb-3 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                            <p>Belum ada data riwayat telekonsultasi.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $histories->links() }}
    </div>
</div>

@endsection
