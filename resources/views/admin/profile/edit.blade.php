@extends('layouts.admin')
@section('title', 'Edit Profil Admin')
@section('page_title', 'Edit Profil')
@section('page_subtitle', 'Sesuaikan informasi profil dan pengaturan akun Anda')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="card p-6">
        @if(session('success'))
            <div class="bg-emerald-50 text-emerald-600 p-4 rounded-xl mb-6 font-medium border border-emerald-100 flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="mb-8 flex items-start gap-6">
                <div class="shrink-0 relative group">
                    <img id="photo-preview" src="{{ $admin->photo_url }}" alt="Foto Admin" class="w-24 h-24 rounded-2xl object-cover shadow-sm border-2 border-slate-100">
                    <label for="photo" class="absolute inset-0 bg-slate-900/50 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center cursor-pointer">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </label>
                    <input type="file" id="photo" name="photo" class="hidden" accept="image/*" onchange="previewImage(this)">
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-slate-800">Foto Profil</h3>
                    <p class="text-sm text-slate-500 mb-2">Pilih foto persegi dengan resolusi minimal 500x500px untuk hasil terbaik.</p>
                    <label for="photo" class="btn-secondary btn-sm cursor-pointer">Ubah Foto</label>
                    @error('photo')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <hr class="border-slate-100 mb-8">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" class="form-input" value="{{ old('name', $admin->name) }}" required>
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label">Alamat Email</label>
                    <input type="email" name="email" class="form-input" value="{{ old('email', $admin->email) }}" required>
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mb-8">
                <h3 class="text-base font-semibold text-slate-800 mb-4">Ganti Password <span class="text-sm font-normal text-slate-400">(Biarkan kosong jika tidak ingin mengubah)</span></h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="form-label">Password Baru</label>
                        <input type="password" name="password" class="form-input" placeholder="Minimal 8 karakter">
                        @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" class="form-input" placeholder="Ketik ulang password baru">
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-4 border-t border-slate-100">
                <button type="submit" class="btn-primary">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('photo-preview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
@endsection
