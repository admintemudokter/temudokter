@extends('layouts.app')
@section('title', 'Login Dokter – Temu Dokter')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-emerald-950 via-teal-900 to-slate-900 flex items-center justify-center p-4">
    <div class="w-full max-w-md animate-slide-up">
        <div class="text-center mb-8">
            <div class="inline-flex items-center gap-2.5">
                <div class="w-10 h-10 bg-emerald-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="font-heading font-bold text-xl text-white">Portal <span class="text-emerald-400">Dokter</span></span>
            </div>
            <p class="text-white/50 text-sm mt-2">Temu Dokter Doctor Dashboard</p>
        </div>

        <div class="card">
            <div class="px-8 pt-8 pb-6 border-b border-slate-100">
                <h2 class="font-heading font-bold text-xl text-slate-800">Masuk Dokter</h2>
                <p class="text-slate-500 text-sm mt-1">Login untuk mengakses dashboard konsultasi Anda</p>
            </div>
            <div class="px-8 py-6">
                @if($errors->any())
                <div class="alert-danger mb-5">
                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    <span>{{ $errors->first() }}</span>
                </div>
                @endif

                <form action="{{ route('doctor.login.post') }}" method="POST" class="space-y-5">
                    @csrf
                    <div>
                        <label class="form-label" for="email">Email</label>
                        <input type="email" id="email" name="email" autocomplete="email"
                               class="form-input @error('email') border-rose-400 @enderror"
                               value="{{ old('email') }}" placeholder="dokter@temudokter.id" required autofocus>
                    </div>
                    <div>
                        <label class="form-label" for="password">Password</label>
                        <input type="password" id="password" name="password"
                               class="form-input" placeholder="••••••••" required>
                    </div>
                    <button type="submit" class="btn w-full btn-lg bg-emerald-600 text-white hover:bg-emerald-700 focus:ring-emerald-500 shadow-sm">
                        Masuk
                    </button>
                </form>
            </div>
        </div>

        <p class="text-center text-white/40 text-xs mt-6">
            <a href="{{ route('home') }}" class="hover:text-white/60 transition-colors">← Kembali ke halaman utama</a>
        </p>
    </div>
</div>
@endsection
