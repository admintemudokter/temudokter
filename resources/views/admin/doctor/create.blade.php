@extends('layouts.admin')
@section('title', 'Tambah Dokter')
@section('page_title', 'Tambah Dokter Baru')

@section('content')
<div class="max-w-xl">
    <div class="card">
        <div class="card-body border-b border-slate-100">
            <h3 class="font-heading font-bold text-slate-800">Formulir Dokter Baru</h3>
        </div>
        <form action="{{ route('admin.doctor.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
            @csrf
            <div><label class="form-label" for="name">Nama Lengkap <span class="text-rose-500">*</span></label>
            <input type="text" id="name" name="name" class="form-input @error('name') border-rose-400 @enderror" value="{{ old('name') }}" required>
            @error('name') <p class="form-error">{{ $message }}</p> @enderror</div>

            <div><label class="form-label" for="email">Email <span class="text-rose-500">*</span></label>
            <input type="email" id="email" name="email" class="form-input @error('email') border-rose-400 @enderror" value="{{ old('email') }}" required>
            @error('email') <p class="form-error">{{ $message }}</p> @enderror</div>

            <div class="grid grid-cols-2 gap-4">
                <div><label class="form-label" for="password">Password <span class="text-rose-500">*</span></label>
                <input type="password" id="password" name="password" class="form-input" required>
                @error('password') <p class="form-error">{{ $message }}</p> @enderror</div>
                <div><label class="form-label" for="password_confirmation">Konfirmasi Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" required></div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div><label class="form-label" for="specialization">Spesialisasi <span class="text-rose-500">*</span></label>
                <input type="text" id="specialization" name="specialization" class="form-input" value="{{ old('specialization', 'Dokter Umum') }}" required>
                @error('specialization') <p class="form-error">{{ $message }}</p> @enderror</div>
                
                <div><label class="form-label" for="experience_years">Pengalaman (Tahun)</label>
                <input type="number" id="experience_years" name="experience_years" class="form-input" value="{{ old('experience_years', 0) }}" min="0">
                @error('experience_years') <p class="form-error">{{ $message }}</p> @enderror</div>
            </div>

            <div><label class="form-label" for="practice_location">Lokasi Praktek</label>
            <input type="text" id="practice_location" name="practice_location" class="form-input" value="{{ old('practice_location') }}" placeholder="Contoh: Klinik Pratama Sehat">
            @error('practice_location') <p class="form-error">{{ $message }}</p> @enderror</div>

            <div><label class="form-label" for="education">Pendidikan</label>
            <input type="text" id="education" name="education" class="form-input" value="{{ old('education') }}" placeholder="Contoh: FK UI">
            @error('education') <p class="form-error">{{ $message }}</p> @enderror</div>

            <div class="grid grid-cols-2 gap-4">
                <div><label class="form-label" for="str_number">Nomor STR</label>
                <input type="text" id="str_number" name="str_number" class="form-input" value="{{ old('str_number') }}">
                @error('str_number') <p class="form-error">{{ $message }}</p> @enderror</div>

                <div><label class="form-label" for="sip_number">Nomor SIP</label>
                <input type="text" id="sip_number" name="sip_number" class="form-input" value="{{ old('sip_number') }}">
                @error('sip_number') <p class="form-error">{{ $message }}</p> @enderror</div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div><label class="form-label" for="phone">Nomor HP</label>
                <input type="tel" id="phone" name="phone" class="form-input" value="{{ old('phone') }}"></div>

                <div><label class="form-label" for="photo">Foto Dokter</label>
                <input type="file" id="photo" name="photo" class="form-input !py-1.5" accept="image/*">
                @error('photo') <p class="form-error">{{ $message }}</p> @enderror</div>
            </div>

            <div><label class="form-label" for="bio">Bio</label>
            <textarea id="bio" name="bio" class="form-textarea" rows="3">{{ old('bio') }}</textarea></div>

            <div class="flex gap-3 pt-2">
                <a href="{{ route('admin.doctor.index') }}" class="btn-ghost flex-1 text-center">Batal</a>
                <button type="submit" class="btn-primary flex-1">Simpan Dokter</button>
            </div>
        </form>
    </div>
</div>
@endsection
