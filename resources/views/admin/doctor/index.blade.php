@extends('layouts.admin')
@section('title', 'Manajemen Dokter')
@section('page_title', 'Manajemen Dokter')
@section('page_subtitle', 'Kelola data dan akun dokter')

@section('content')
<div class="flex justify-end mb-6">
    <a href="{{ route('admin.doctor.create') }}" class="btn-primary">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Dokter
    </a>
</div>

<div class="card">
    <div class="divide-y divide-slate-50">
        @foreach($doctors as $doc)
        <div class="px-4 py-4 sm:px-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4 min-w-0">
                <div class="w-10 h-10 bg-brand-100 rounded-full flex items-center justify-center text-brand-700 font-bold flex-shrink-0">
                    {{ substr($doc->name, 0, 1) }}
                </div>
                <div class="min-w-0">
                    <p class="font-semibold text-slate-800 truncate">{{ $doc->name }}</p>
                    <div class="flex flex-wrap items-center gap-2 text-xs text-slate-500 mt-1">
                        <span class="{{ 'status-dot-' . $doc->status }}"></span>
                        <span>{{ $doc->status_label }}</span>
                        <span>•</span>
                        <span>{{ $doc->specialization }}</span>
                        <span>•</span>
                        <span>{{ $doc->consultations_count }} total konsultasi</span>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-between sm:justify-end gap-2 w-full sm:w-auto mt-1 sm:mt-0 pt-3 sm:pt-0 border-t border-slate-100 sm:border-0 flex-shrink-0">
                <span class="badge-{{ $doc->status_color }} text-xs">{{ $doc->active_consultations_count }} aktif</span>
                <div class="flex gap-2">
                    <a href="{{ route('admin.doctor.edit', $doc->id) }}" class="btn-secondary btn-sm">Edit</a>
                    <form action="{{ route('admin.doctor.destroy', $doc->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus dokter ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-rose-100 text-rose-600 hover:bg-rose-200 px-3 py-1.5 rounded-lg text-sm font-semibold transition-colors">Delete</button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
