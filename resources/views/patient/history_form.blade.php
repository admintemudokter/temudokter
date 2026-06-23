@extends('layouts.app')
@section('title', 'Cek Riwayat Konsultasi – TemuDokter')

@section('content')
<div class="min-h-screen bg-slate-50 flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <a href="{{ route('home') }}" class="flex justify-center items-center gap-2 mb-6 hover:opacity-80 transition-opacity">
            <img src="{{ asset('images/logo.png') }}" alt="TemuDokter Logo" class="h-16 w-auto">
        </a>
        <h2 class="mt-6 text-center text-3xl font-extrabold text-slate-900 font-heading">
            Cek Riwayat Konsultasi
        </h2>
        <p class="mt-2 text-center text-sm text-slate-600">
            Masukkan Kode Riwayat yang Anda terima melalui email untuk melihat riwayat chat dan dokumen medis Anda.
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10 border border-slate-100">
            @if ($errors->any())
            <div class="mb-4 bg-rose-50 text-rose-600 p-4 rounded-xl text-sm border border-rose-100 flex gap-3 items-start">
                <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <ul class="list-disc pl-4 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form class="space-y-6" action="{{ route('history.check') }}" method="POST">
                @csrf
                
                <div>
                    <label for="whatsapp_number" class="block text-sm font-medium text-slate-700">No. WhatsApp <span class="text-rose-500">*</span></label>
                    <div class="mt-1">
                        <input id="whatsapp_number" name="whatsapp_number" type="tel" required value="{{ old('whatsapp_number') }}"
                            class="appearance-none block w-full px-3 py-2 border border-slate-300 rounded-md shadow-sm placeholder-slate-400 focus:outline-none focus:ring-brand-500 focus:border-brand-500 sm:text-sm"
                            placeholder="Contoh: 08123456789">
                    </div>
                </div>

                <div>
                    <label for="history_code" class="block text-sm font-medium text-slate-700">Kode Riwayat <span class="text-rose-500">*</span></label>
                    <div class="mt-1">
                        <input id="history_code" name="history_code" type="text" required value="{{ old('history_code') }}"
                            class="appearance-none block w-full px-3 py-2 border border-slate-300 rounded-md shadow-sm placeholder-slate-400 focus:outline-none focus:ring-brand-500 focus:border-brand-500 sm:text-sm"
                            placeholder="Contoh: A7B9X2">
                    </div>
                </div>

                <div>
                    <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-brand-600 hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500">
                        Cek Riwayat
                    </button>
                </div>
                
                <div class="mt-4">
                    <a href="{{ route('home') }}" class="w-full flex justify-center py-2 px-4 border border-slate-300 rounded-md shadow-sm text-sm font-medium text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500">
                        Kembali ke Beranda
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
