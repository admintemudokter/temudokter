@extends('layouts.admin')
@section('title', 'Pengaturan Harga & Diskon')
@section('page_title', 'Harga & Diskon')

@section('content')
<div class="max-w-4xl space-y-6">
    <div class="card">
        <div class="card-body border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-heading font-bold text-slate-800">Pengaturan Layanan</h3>
                <p class="text-slate-500 text-sm mt-1">Atur harga dasar dan diskon untuk setiap layanan yang tersedia.</p>
            </div>
        </div>
        
        <form action="{{ route('admin.settings.pricing.update') }}" method="POST" class="p-6 space-y-8">
            @csrf
            @method('PUT')

            {{-- Konsultasi Online Section --}}
            <div>
                <h4 class="font-bold text-brand-600 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    Konsultasi Online
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-slate-50 p-5 rounded-2xl border border-slate-100">
                    <div>
                        <label class="form-label">Harga Dasar (Rp) <span class="text-rose-500">*</span></label>
                        <input type="number" name="online_price" class="form-input @error('online_price') border-rose-400 @enderror" value="{{ old('online_price', $onlinePrice) }}" required min="0">
                        @error('online_price') <p class="form-error">{{ $message }}</p> @enderror
                        <p class="text-xs text-slate-400 mt-1">Harga normal sebelum diskon.</p>
                    </div>
                    <div>
                        <label class="form-label">Diskon (Rp)</label>
                        <input type="number" name="online_discount" class="form-input @error('online_discount') border-rose-400 @enderror" value="{{ old('online_discount', $onlineDiscount) }}" min="0">
                        @error('online_discount') <p class="form-error">{{ $message }}</p> @enderror
                        <p class="text-xs text-slate-400 mt-1">Isi 0 jika tidak ada diskon.</p>
                    </div>
                </div>
            </div>

            {{-- Homecare Section --}}
            <div>
                <h4 class="font-bold text-emerald-600 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Layanan Homecare
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-slate-50 p-5 rounded-2xl border border-slate-100">
                    <div>
                        <label class="form-label">Harga Dasar (Rp) <span class="text-rose-500">*</span></label>
                        <input type="number" name="homecare_price" class="form-input @error('homecare_price') border-rose-400 @enderror" value="{{ old('homecare_price', $homecarePrice) }}" required min="0">
                        @error('homecare_price') <p class="form-error">{{ $message }}</p> @enderror
                        <p class="text-xs text-slate-400 mt-1">Berlaku sebagai harga dasar untuk semua area.</p>
                    </div>
                    <div>
                        <label class="form-label">Diskon (Rp)</label>
                        <input type="number" name="homecare_discount" class="form-input @error('homecare_discount') border-rose-400 @enderror" value="{{ old('homecare_discount', $homecareDiscount) }}" min="0">
                        @error('homecare_discount') <p class="form-error">{{ $message }}</p> @enderror
                        <p class="text-xs text-slate-400 mt-1">Isi 0 jika tidak ada diskon.</p>
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end">
                <button type="submit" class="btn-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
