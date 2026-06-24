@extends('layouts.admin')
@section('title', 'Riwayat Pendapatan')
@section('page_title', 'Riwayat Pendapatan')
@section('page_subtitle', 'Data transaksi historis yang telah di-reset')

@section('content')
<div class="card mb-6">
    <div class="px-6 py-5 border-b border-slate-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h2 class="font-heading font-bold text-lg text-slate-800">Arsip Transaksi</h2>
            <p class="text-slate-500 text-sm mt-1">Menampilkan data pendapatan yang telah di-reset sebelumnya.</p>
        </div>
        
        <div class="flex flex-wrap items-center gap-2">
            <form action="{{ route('admin.revenue.history.index') }}" method="GET" class="flex flex-wrap items-center gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari invoice/pasien..." class="form-input text-sm w-40">
                <select name="month" class="form-input text-sm w-32">
                    @foreach($months as $num => $name)
                    <option value="{{ $num }}" {{ $month == $num ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
                <select name="year" class="form-input text-sm w-24">
                    @foreach($years as $y)
                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn-secondary btn-sm">Filter</button>
            </form>

            <a href="{{ route('admin.revenue.history.export_csv', request()->all()) }}" class="btn-primary bg-emerald-600 hover:bg-emerald-700 btn-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                CSV
            </a>
            <a href="{{ route('admin.revenue.history.export_pdf', request()->all()) }}" class="btn-primary bg-rose-600 hover:bg-rose-700 btn-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                PDF
            </a>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50/50 border-b border-slate-100">
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Tanggal Hapus</th>
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Invoice</th>
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Pasien</th>
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Metode</th>
                    <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Nominal</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($transactions as $t)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-slate-800">{{ $t->deleted_at->format('d M Y') }}</div>
                        <div class="text-xs text-slate-500">{{ $t->deleted_at->format('H:i') }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-semibold text-slate-700">{{ $t->invoice_number }}</div>
                        <div class="text-xs text-slate-500">{{ $t->created_at->format('d/m/y H:i') }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-slate-700">{{ $t->consultation->patient->name ?? '-' }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2.5 py-1 bg-slate-100 text-slate-600 rounded-lg text-xs font-medium border border-slate-200">
                            {{ strtoupper($t->payment_provider) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="text-sm font-bold text-emerald-600">Rp {{ number_format($t->amount, 0, ',', '.') }}</div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                        <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-400">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        Belum ada riwayat pendapatan yang tercatat.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($transactions->hasPages())
    <div class="px-6 py-4 border-t border-slate-100">
        {{ $transactions->links() }}
    </div>
    @endif
</div>
@endsection
