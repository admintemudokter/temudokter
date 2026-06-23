@extends('layouts.admin')
@section('title', 'Jadwal Homecare')
@section('page_title', 'Kelola Jadwal Homecare')
@section('page_subtitle', 'Tutup (block) atau buka paksa jadwal hari biasa')

@section('content')
<div class="grid lg:grid-cols-12 gap-8">

    {{-- Form Tambah Blokir --}}
    <div class="lg:col-span-4">
        <div class="card shadow-sm border border-slate-100">
            <div class="p-6 border-b border-slate-100 bg-brand-50/50 rounded-t-2xl">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-brand-100 flex items-center justify-center text-brand-600 flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-heading font-bold text-slate-800 text-lg">Kelola Status Jadwal</h3>
                        <p class="text-xs text-slate-500">Pilih tanggal untuk diblokir atau dibuka</p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <form action="{{ route('admin.homecare.schedule.store') }}" method="POST" class="space-y-5">
                    @csrf
                    <div>
                        <label class="form-label text-slate-700 font-medium" for="type">Aksi <span class="text-rose-500">*</span></label>
                        <select name="type" id="type" class="form-select mt-1 bg-slate-50 border-slate-200">
                            <option value="block">Tutup Jadwal (Blokir)</option>
                            <option value="open">Buka Jadwal Hari Biasa</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label text-slate-700 font-medium" for="date">Tanggal Kunjungan <span class="text-rose-500">*</span></label>
                        <input type="date" name="date" id="date" class="form-input mt-1" required min="{{ date('Y-m-d') }}">
                    </div>
                    <div>
                        <label class="form-label text-slate-700 font-medium" for="time">Pilih Jam</label>
                        <select name="time" id="time" class="form-select mt-1 bg-slate-50">
                            <option value="">Tutup 1 Hari Penuh</option>
                            <option value="09:00">09:00</option>
                            <option value="11:00">11:00</option>
                            <option value="13:00">13:00</option>
                            <option value="15:00">15:00</option>
                            <option value="17:00">17:00</option>
                        </select>
                        <div class="alert-info mt-2 !py-2 !px-3">
                            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                            <span class="text-[11px] leading-tight">Biarkan "1 Hari Penuh" jika layanan libur di tanggal tersebut.</span>
                        </div>
                    </div>
                    <div>
                        <label class="form-label text-slate-700 font-medium" for="reason">Keterangan Tambahan <span class="text-slate-400 font-normal">(Opsional)</span></label>
                        <input type="text" name="reason" id="reason" class="form-input mt-1 placeholder-slate-300" placeholder="Cth: Cuti Tahunan, Libur Nasional">
                    </div>
                    <button type="submit" class="btn-primary w-full py-3 shadow-md hover:shadow-lg transition-all mt-2">
                        <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8V7a4 4 0 00-8 0v4h8z"/></svg>
                        Simpan Jadwal
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Daftar Blokir --}}
    <div class="lg:col-span-8">
        <div class="card shadow-sm border border-slate-100">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-white rounded-t-2xl">
                <div>
                    <h3 class="font-heading font-bold text-slate-800 text-lg">Daftar Jadwal yang Dikelola</h3>
                    <p class="text-sm text-slate-500 mt-1">Daftar jadwal yang ditutup atau dibuka paksa oleh admin.</p>
                </div>
            </div>
            
            <div class="p-0 overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 text-slate-500 text-xs uppercase tracking-wider">
                            <th class="py-4 px-6 font-semibold border-b border-slate-100">Tanggal</th>
                            <th class="py-4 px-6 font-semibold border-b border-slate-100">Jam / Status</th>
                            <th class="py-4 px-6 font-semibold border-b border-slate-100">Keterangan</th>
                            <th class="py-4 px-6 font-semibold border-b border-slate-100 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-slate-100">
                        @forelse($blocks as $block)
                        <tr class="hover:bg-brand-50/30 transition-colors">
                            <td class="py-4 px-6 text-slate-800 font-medium">
                                {{ \Carbon\Carbon::parse($block->date)->translatedFormat('l, d F Y') }}
                            </td>
                            <td class="py-4 px-6">
                                @if($block->type === 'open')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-emerald-50 border border-emerald-100 text-emerald-600 text-xs font-bold whitespace-nowrap">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        Dibuka (Bebas Jam)
                                    </span>
                                @elseif(is_null($block->time))
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-rose-50 border border-rose-100 text-rose-600 text-xs font-bold whitespace-nowrap">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Ditutup (1 Hari Penuh)
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-slate-100 border border-slate-200 text-slate-700 text-xs font-bold font-mono">
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Ditutup ({{ substr($block->time, 0, 5) }})
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-slate-500">
                                {{ $block->reason ?: '-' }}
                            </td>
                            <td class="py-4 px-6 text-right">
                                <form action="{{ route('admin.homecare.schedule.destroy', $block) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membuka kembali jadwal ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 rounded-lg text-rose-500 hover:bg-rose-50 hover:text-rose-600 transition-colors tooltip tooltip-left" data-tip="Hapus Data">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-3">
                                        <svg class="w-8 h-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                    <h4 class="text-slate-700 font-medium text-sm">Belum ada jadwal yang ditutup</h4>
                                    <p class="text-slate-400 text-xs mt-1">Anda dapat menutup jadwal baru melalui form di sebelah kiri.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
