@extends('layouts.admin')
@section('title', 'Edit Dokter – ' . $doctor->name)
@section('page_title', 'Edit Dokter')

@section('content')
<div class="max-w-xl">
    <div class="card">
        <div class="card-body border-b border-slate-100">
            <h3 class="font-heading font-bold text-slate-800">Edit: {{ $doctor->name }}</h3>
        </div>
        <form action="{{ route('admin.doctor.update', $doctor->id) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
            @csrf @method('PUT')
            <div><label class="form-label">Nama</label>
            <input type="text" name="name" class="form-input" value="{{ old('name', $doctor->name) }}" required></div>
            <div><label class="form-label">Email</label>
            <input type="email" name="email" class="form-input" value="{{ old('email', $doctor->email) }}" required></div>
            <div class="grid grid-cols-2 gap-4">
                <div><label class="form-label">Password Baru <span class="text-slate-400 font-normal">(kosongkan jika tidak berubah)</span></label>
                <input type="password" name="password" class="form-input"></div>
                <div><label class="form-label">Konfirmasi</label>
                <input type="password" name="password_confirmation" class="form-input"></div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div><label class="form-label">Spesialisasi</label>
                <input type="text" name="specialization" class="form-input" value="{{ old('specialization', $doctor->specialization) }}" required></div>
                
                <div><label class="form-label">Pengalaman (Tahun)</label>
                <input type="number" name="experience_years" class="form-input" value="{{ old('experience_years', $doctor->experience_years) }}" min="0"></div>
            </div>

            <div><label class="form-label">Lokasi Praktek</label>
            <input type="text" name="practice_location" class="form-input" value="{{ old('practice_location', $doctor->practice_location) }}" placeholder="Contoh: Klinik Pratama Sehat"></div>

            <div><label class="form-label">Pendidikan</label>
            <input type="text" name="education" class="form-input" value="{{ old('education', $doctor->education) }}" placeholder="Contoh: FK UI"></div>

            <div class="grid grid-cols-2 gap-4">
                <div><label class="form-label">Nomor STR</label>
                <input type="text" name="str_number" class="form-input" value="{{ old('str_number', $doctor->str_number) }}"></div>

                <div><label class="form-label">Nomor SIP</label>
                <input type="text" name="sip_number" class="form-input" value="{{ old('sip_number', $doctor->sip_number) }}"></div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div><label class="form-label">Nomor HP</label>
                <input type="tel" name="phone" class="form-input" value="{{ old('phone', $doctor->phone) }}"></div>

                <div>
                    <label class="form-label">Foto Dokter</label>
                    <input type="file" name="photo" class="form-input !py-1.5" accept="image/*">
                    @if($doctor->photo)
                        <div class="mt-2 text-xs text-slate-500 flex items-center gap-2">
                            <img src="{{ asset('storage/' . $doctor->photo) }}" class="w-8 h-8 rounded object-cover shadow-sm">
                            <span>Foto saat ini</span>
                        </div>
                    @endif
                </div>
            </div>

            <div><label class="form-label">Status</label>
            <select name="status" class="form-select" required>
                <option value="online" {{ $doctor->status === 'online' ? 'selected' : '' }}>Online</option>
                <option value="offline" {{ $doctor->status === 'offline' ? 'selected' : '' }}>Offline</option>
                <option value="inactive" {{ $doctor->status === 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
            </select></div>

            <div><label class="form-label">Bio</label>
            <textarea name="bio" class="form-textarea" rows="3">{{ old('bio', $doctor->bio) }}</textarea></div>
            <div class="flex gap-3 pt-2">
                <a href="{{ route('admin.doctor.index') }}" class="btn-ghost flex-1 text-center">Batal</a>
                <button type="submit" class="btn-primary flex-1">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
