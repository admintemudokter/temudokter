@extends('layouts.app')

@section('title', 'Temu Dokter – Konsultasi Dokter Online ')
@section('meta_description', 'Konsultasi dengan dokter online mudah, cepat, dan aman. Khusus wilayah Bekasi. Mulai konsultasi sekarang.')

@section('content')

{{-- ===== NAVBAR ===== --}}
<nav class="fixed top-0 left-0 right-0 z-50 bg-white/90 backdrop-blur-md border-b border-slate-200/50 shadow-sm transition-all duration-300"
     x-data="{ open: false, scrolled: false }"
     @scroll.window="scrolled = (window.pageYOffset > 20)"
     :class="{'py-1': scrolled, 'py-2': !scrolled}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="flex items-center justify-between h-20 transition-all duration-300">
            {{-- Logo (Kiri) --}}
            <a href="{{ route('home') }}" class="flex-shrink-0 flex items-center gap-3 group">
                <img src="{{ asset('images/logo.png') }}?v={{ time() }}" alt="Temu Dokter Logo" class="h-16 w-auto rounded-xl shadow-sm group-hover:scale-105 transition-transform duration-300">
                <div class="hidden sm:flex flex-col">
                    <span class="font-heading font-extrabold text-xl tracking-tight text-slate-800 leading-none">Temu Dokter</span>
                </div>
            </a>

            {{-- Navigasi Desktop (Kanan) --}}
            <div class="hidden lg:flex items-center gap-1">
                <a href="{{ route('home') }}" class="px-4 py-2 rounded-full text-[14px] font-bold text-slate-700 hover:text-brand-600 hover:bg-brand-50 transition-all">Beranda</a>
                <a href="#tentang" class="px-4 py-2 rounded-full text-[14px] font-bold text-slate-700 hover:text-brand-600 hover:bg-brand-50 transition-all">Tentang</a>
                <a href="#area" class="px-4 py-2 rounded-full text-[14px] font-bold text-slate-700 hover:text-brand-600 hover:bg-brand-50 transition-all">Area</a>
                <a href="#faq" class="px-4 py-2 rounded-full text-[14px] font-bold text-slate-700 hover:text-brand-600 hover:bg-brand-50 transition-all mr-2">FAQ</a>
                
                {{-- Divider --}}
                <div class="w-px h-6 bg-slate-200 mx-1"></div>

                <a href="{{ route('history.form') }}" class="px-4 py-2 rounded-full text-[14px] font-bold text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Riwayat
                </a>
                <a href="{{ route('patient.create') }}" class="ml-2 px-5 py-2 rounded-full text-[14px] font-bold text-white bg-rose-600 hover:bg-rose-700 shadow-md hover:shadow-lg transition-all flex items-center gap-2">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    <div class="flex flex-col text-left leading-tight">
                        <span>Chat Dokter</span>
                        <span class="text-[10px] font-medium text-rose-100 inline-block transform scale-[0.65] origin-left mt-0.5">(08.00 - 20.00)</span>
                    </div>
                </a>
                <a href="{{ route('patient.homecare.create') }}" class="ml-2 px-5 py-2.5 rounded-full text-[14px] font-bold text-purple-600 bg-white hover:bg-purple-50 border border-purple-200 shadow-sm hover:shadow-md transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Homecare
                </a>
            </div>

            {{-- Mobile hamburger (Kanan) --}}
            <div class="lg:hidden flex items-center">
                <button @click="open = !open" class="p-2.5 rounded-xl hover:bg-slate-100 transition-colors">
                    <svg x-show="!open" class="w-6 h-6 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg x-show="open" class="w-6 h-6 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Mobile menu --}}
        <div x-show="open" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-4"
             style="display: none;" 
             class="lg:hidden pb-6 border-t border-slate-100 pt-4 space-y-2">
            <a href="{{ route('home') }}" class="block px-4 py-2.5 text-[15px] font-bold text-slate-600 hover:text-brand-600 hover:bg-brand-50 rounded-xl transition-colors">Beranda</a>
            <a href="#tentang" class="block px-4 py-2.5 text-[15px] font-bold text-slate-600 hover:text-brand-600 hover:bg-brand-50 rounded-xl transition-colors">Tentang Kami</a>
            <a href="#area" class="block px-4 py-2.5 text-[15px] font-bold text-slate-600 hover:text-brand-600 hover:bg-brand-50 rounded-xl transition-colors">Area Layanan</a>
            <a href="#faq" class="block px-4 py-2.5 text-[15px] font-bold text-slate-600 hover:text-brand-600 hover:bg-brand-50 rounded-xl transition-colors mb-4">FAQ</a>
            
            <div class="h-px bg-slate-100 my-4 mx-4"></div>
            
            <a href="{{ route('history.form') }}" class="flex items-center gap-2 mx-4 px-4 py-3 text-[15px] text-slate-700 font-bold bg-slate-50 hover:bg-slate-100 rounded-xl transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Cek Riwayat
            </a>
            <a href="{{ route('patient.create') }}" class="flex justify-center items-center gap-2 mx-4 mt-2 px-4 py-3 text-[15px] text-white font-bold bg-rose-600 hover:bg-rose-700 rounded-xl shadow-md transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                Chat Dokter Online
            </a>
            <a href="{{ route('patient.homecare.create') }}" class="flex justify-center items-center gap-2 mx-4 mt-2 px-4 py-3 text-[15px] text-purple-600 font-bold bg-white hover:bg-purple-50 rounded-xl shadow-sm border border-purple-200 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Pesan Layanan Homecare
            </a>
        </div>
    </div>
</nav>

{{-- ===== HERO ===== --}}
<section class="relative min-h-screen flex items-center overflow-hidden bg-gradient-to-br from-[#183931] to-[#2A5C50] pt-16">
    <div class="relative max-w-6xl mx-auto px-4 sm:px-6 py-24 grid lg:grid-cols-2 gap-16 items-center">
        {{-- Left: Copy --}}
        <div class="animate-fade-in">
            {{-- Trust badge --}}
            <div class="inline-flex items-center gap-2 bg-white/10 border border-white/20 rounded-full px-4 py-2 text-sm text-white mb-6">
                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                <span>Dokter Online Sekarang</span>
            </div>

            <h1 class="font-heading text-5xl lg:text-6xl font-bold text-white leading-tight mb-6 tracking-tight">
                Konsultasi<br>Dokter
                <span class="text-[#6EE7B7]">Online</span><br>
                Seluruh Indonesia
            </h1>

            <p class="text-white/80 text-lg leading-relaxed mb-10 max-w-lg">
                Konsultasi dengan dokter terverifikasi dari rumah. Cepat, aman, dan terjangkau untuk seluruh wilayah Indonesia. Khusus layanan Homecare tersedia untuk area Bekasi.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 mb-12">
                <a href="{{ route('patient.create') }}"
                   class="btn bg-rose-600 text-white hover:bg-rose-700 border-0 btn-lg font-bold rounded-2xl flex items-center gap-2 px-6">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    Chat Dokter
                </a>
                <a href="{{ route('patient.homecare.create') }}"
                   class="btn bg-white text-purple-600 hover:bg-purple-50 border-0 btn-lg font-bold rounded-2xl flex items-center gap-2 px-6">
                    <svg class="w-5 h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Pesan Homecare
                </a>
            </div>

            {{-- Trust indicators --}}
            <div class="flex flex-wrap gap-6 text-white/80 text-sm font-medium">
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded-full bg-[#6EE7B7] text-[#183931] flex items-center justify-center">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    Data Terenkripsi
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded-full bg-[#6EE7B7] text-[#183931] flex items-center justify-center">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    Dokter Berlisensi
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 rounded-full bg-[#6EE7B7] text-[#183931] flex items-center justify-center">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    Resep Digital
                </div>
            </div>
        </div>

        {{-- Right: Floating UI card --}}
        <div class="relative hidden lg:flex justify-center items-center animate-slide-up pl-10">
            <div class="relative">
                {{-- Main card --}}
                <div class="bg-[#E4EBE9] rounded-3xl p-6 w-[340px] shadow-2xl relative z-10">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-white/40">
                        <div class="w-10 h-10 bg-[#C5D7D3] rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-[#377A6A]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[11px] text-[#557F76] font-medium uppercase tracking-wider">Konsultasi Aktif</p>
                            <p class="text-sm font-bold text-[#183931]">Dr. Andi Wijaya, Sp.PD</p>
                        </div>
                    </div>
                    
                    {{-- Chat preview --}}
                    <div class="space-y-4 mb-6 relative">
                        <div class="flex justify-end pr-2">
                            <div class="bg-[#3A7B6C] text-white px-4 py-3 rounded-2xl rounded-br-sm text-xs shadow-sm max-w-[85%]">Dok, saya batuk sudah 3 hari...</div>
                        </div>
                        <div class="flex items-end gap-2">
                            <div class="w-6 h-6 bg-[#C5D7D3] rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-3 h-3 text-[#377A6A]" fill="currentColor" viewBox="0 0 20 20"><path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/></svg>
                            </div>
                            <div class="bg-white text-[#183931] px-4 py-3 rounded-2xl rounded-bl-sm text-xs shadow-sm max-w-[85%]">Baik, saya akan periksa lebih lanjut. Apakah ada demam?</div>
                        </div>
                        <div class="flex justify-center pt-2">
                            <div class="px-4 py-1.5 bg-white/50 rounded-full text-[11px] font-medium text-[#557F76]">⏱ Sisa waktu: 12:34</div>
                        </div>
                    </div>
                    
                    {{-- Input preview --}}
                    <div class="flex gap-2">
                        <div class="flex-1 bg-white rounded-xl px-4 py-3 text-xs text-slate-400 shadow-sm flex items-center">Ketik pesan...</div>
                        <div class="w-10 h-10 bg-[#3A7B6C] rounded-xl flex items-center justify-center shadow-sm">
                            <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Floating badge: Invoice --}}
                <div class="absolute -top-10 -right-8 bg-white/80 backdrop-blur-md rounded-2xl shadow-xl p-4 w-36 z-20 animate-pulse-slow">
                    <div class="text-[10px] text-slate-500 mb-1">Invoice</div>
                    <div class="font-bold text-[#183931] text-xs mb-2">DK270526001</div>
                    <div class="inline-flex items-center gap-1 bg-[#E0F2EC] text-[#226852] px-2 py-0.5 rounded-full text-[10px] font-bold">
                        Verified
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <div class="absolute -bottom-3 left-4 text-[10px] font-bold text-[#488E7B]">Online</div>
                </div>

                {{-- Floating badge: Prescription --}}
                <div class="absolute -bottom-6 -left-12 bg-white rounded-2xl shadow-xl p-3 flex items-center gap-3 z-20">
                    <div class="w-8 h-8 bg-[#E0F2EC] rounded-xl flex items-center justify-center">
                        <svg class="w-4 h-4 text-[#377A6A]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-[10px] text-slate-500">Resep Digital</div>
                        <div class="font-bold text-[#183931] text-xs">Siap Diunduh</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Wave bottom --}}
    <div class="absolute bottom-0 left-0 right-0 overflow-hidden leading-none z-0">
        <svg class="relative block w-[calc(100%+1.3px)] h-[60px]" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
            <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V120H0V0C71.55,16,145.45,46.7,214.3,60.84C251.13,68.42,286.9,62.87,321.39,56.44Z" fill="#ffffff"></path>
        </svg>
    </div>
</section>

{{-- ===== TENTANG KAMI ===== --}}
<section id="tentang" class="py-24 bg-white relative overflow-hidden">
    {{-- Decorative Background Elements --}}
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0 pointer-events-none">
        <div class="absolute top-[-10%] right-[-5%] w-[600px] h-[600px] rounded-full bg-brand-50 blur-[120px] opacity-70"></div>
        <div class="absolute bottom-[-10%] left-[-5%] w-[500px] h-[500px] rounded-full bg-emerald-50 blur-[100px] opacity-70"></div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 relative z-10">
        <div class="text-center mb-16 md:mb-24">
            <span class="inline-flex items-center gap-2 px-4 py-2 bg-brand-50 text-brand-600 rounded-full text-sm font-bold mb-6 shadow-sm border border-brand-100">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                Mengenal Kami
            </span>
            <h2 class="font-heading text-4xl sm:text-5xl md:text-6xl font-extrabold text-slate-900 mb-6 tracking-tight">
                Tentang <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#004945] to-emerald-500">Temu Dokter</span>
            </h2>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-20 items-start">
            {{-- Deskripsi --}}
            <div class="lg:col-span-6 relative">
                <div class="absolute -left-6 top-2 bottom-6 w-1.5 bg-gradient-to-b from-[#004945] to-emerald-200 rounded-full hidden md:block opacity-80"></div>
                
                <div class="space-y-6 text-slate-600 text-lg leading-relaxed md:pl-2">
                    <p class="text-xl md:text-2xl text-slate-800 font-semibold leading-relaxed tracking-tight mb-8">
                        <span class="text-[#004945]">Temu Dokter</span> merupakan platform layanan kesehatan digital yang didirikan secara perorangan oleh seorang Dokter Umum pada tanggal 26 Mei 2026.
                    </p>
                    
                    <p class="text-slate-600">
                        Platform ini lahir dari kepedulian terhadap kebutuhan masyarakat akan akses layanan kesehatan yang mudah, cepat, aman, dan terpercaya di era digital. Dengan memanfaatkan perkembangan teknologi informasi, Temu Dokter hadir untuk memfasilitasi masyarakat dalam memperoleh konsultasi kesehatan, informasi medis, serta layanan kesehatan lainnya secara lebih praktis melalui perangkat ponsel maupun komputer, tanpa terbatas oleh jarak dan waktu.
                    </p>
                    
                    <p class="text-slate-600">
                        Dalam menjalankan layanannya, Temu Dokter berkomitmen untuk menjunjung tinggi profesionalisme, etika, dan mutu pelayanan kesehatan. Seluruh layanan didukung oleh tenaga medis dan tenaga kesehatan yang memiliki kompetensi, registrasi, serta izin praktik yang sah sesuai dengan ketentuan peraturan perundang-undangan yang berlaku.
                    </p>
                    
                    <div class="p-6 bg-slate-50 rounded-2xl border border-slate-100 shadow-sm mt-8">
                        <p class="text-slate-700 italic font-medium leading-relaxed">
                            "Sebagai mitra kesehatan masyarakat, Temu Dokter senantiasa berupaya menghadirkan layanan yang responsif, mudah diakses, dan dapat diandalkan guna mendukung peningkatan kualitas kesehatan masyarakat serta memberikan pengalaman pelayanan kesehatan yang aman, nyaman, dan berorientasi pada kepuasan pasien."
                        </p>
                    </div>
                </div>
            </div>

            {{-- Visi & Misi --}}
            <div class="lg:col-span-6 space-y-8">
                {{-- Visi --}}
                <div class="bg-gradient-to-br from-[#004945] to-[#00706B] rounded-[2rem] p-8 md:p-10 shadow-2xl relative overflow-hidden text-white group hover:-translate-y-1 transition-transform duration-300">
                    {{-- Abstract decoration --}}
                    <div class="absolute -top-20 -right-20 w-64 h-64 bg-white/5 rounded-full blur-2xl group-hover:bg-white/10 transition-colors duration-500"></div>
                    <div class="absolute -bottom-10 -left-10 w-40 h-40 bg-emerald-400/20 rounded-full blur-xl"></div>
                    
                    <div class="absolute top-6 right-8 opacity-10 transform group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">
                        <svg class="w-32 h-32 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L1 12h3v9h6v-6h4v6h6v-9h3L12 2z"/></svg>
                    </div>
                    
                    <h3 class="font-heading text-3xl font-bold mb-6 flex items-center gap-4 relative z-10">
                        <span class="w-14 h-14 rounded-2xl bg-white/10 text-white flex items-center justify-center backdrop-blur-md border border-white/20 shadow-inner">
                            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </span>
                        Visi
                    </h3>
                    <p class="leading-relaxed font-medium relative z-10 text-white/95 text-lg">
                        Menjadi platform layanan kesehatan digital yang terpercaya, profesional, dan mudah diakses oleh seluruh lapisan masyarakat dalam mewujudkan pelayanan kesehatan yang berkualitas dan berkelanjutan.
                    </p>
                </div>

                {{-- Misi --}}
                <div class="bg-white rounded-[2rem] p-8 md:p-10 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.06)] relative overflow-hidden group hover:shadow-[0_8px_30px_rgb(0,0,0,0.12)] hover:-translate-y-1 transition-all duration-300">
                    <h3 class="font-heading text-3xl font-bold text-slate-900 mb-8 flex items-center gap-4 relative z-10">
                        <span class="w-14 h-14 rounded-2xl bg-emerald-50 text-[#004945] flex items-center justify-center border border-emerald-100 group-hover:scale-110 group-hover:bg-[#004945] group-hover:text-white transition-all duration-300 shadow-sm">
                            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </span>
                        Misi
                    </h3>
                    <ul class="space-y-5 relative z-10">
                        <li class="flex items-start gap-4">
                            <div class="w-7 h-7 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0 mt-0.5 shadow-sm border border-emerald-200">
                                <svg class="w-4 h-4 text-[#004945]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <span class="text-slate-600 leading-relaxed font-medium">Menyediakan layanan konsultasi kesehatan yang mudah, cepat, aman, dan terpercaya melalui teknologi digital.</span>
                        </li>
                        <li class="flex items-start gap-4">
                            <div class="w-7 h-7 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0 mt-0.5 shadow-sm border border-emerald-200">
                                <svg class="w-4 h-4 text-[#004945]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <span class="text-slate-600 leading-relaxed font-medium">Menghubungkan masyarakat dengan tenaga medis dan tenaga kesehatan yang kompeten serta memiliki izin praktik yang sah.</span>
                        </li>
                        <li class="flex items-start gap-4">
                            <div class="w-7 h-7 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0 mt-0.5 shadow-sm border border-emerald-200">
                                <svg class="w-4 h-4 text-[#004945]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <span class="text-slate-600 leading-relaxed font-medium">Meningkatkan literasi dan kesadaran masyarakat mengenai pentingnya kesehatan melalui edukasi yang akurat dan terpercaya.</span>
                        </li>
                        <li class="flex items-start gap-4">
                            <div class="w-7 h-7 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0 mt-0.5 shadow-sm border border-emerald-200">
                                <svg class="w-4 h-4 text-[#004945]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <span class="text-slate-600 leading-relaxed font-medium">Mengedepankan profesionalisme, etika, dan kualitas pelayanan dalam setiap layanan yang diberikan.</span>
                        </li>
                        <li class="flex items-start gap-4">
                            <div class="w-7 h-7 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0 mt-0.5 shadow-sm border border-emerald-200">
                                <svg class="w-4 h-4 text-[#004945]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <span class="text-slate-600 leading-relaxed font-medium">Mengembangkan inovasi layanan kesehatan yang berorientasi pada kebutuhan dan kepuasan pasien.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===== BENEFITS ===== --}}
<section id="keunggulan" class="py-24 bg-slate-50 relative overflow-hidden">
    {{-- Decorative Background Elements --}}
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[400px] bg-emerald-500/5 blur-[120px] rounded-full pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 relative z-10">
        <div class="text-center mb-20">
            <span class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-100 text-emerald-700 rounded-full text-sm font-bold mb-6 shadow-sm border border-emerald-200">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.381z" clip-rule="evenodd"/></svg>
                Keunggulan Kami
            </span>
            <h2 class="font-heading text-4xl sm:text-5xl font-extrabold text-slate-900 mb-6 tracking-tight">Lebih dari Sekadar<br/><span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-500">Konsultasi Online</span></h2>
            <p class="text-slate-500 text-lg sm:text-xl max-w-2xl mx-auto leading-relaxed">Platform yang dirancang khusus dengan teknologi terkini untuk memastikan kenyamanan, kecepatan, dan keamanan data pasien.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            {{-- Benefit 1 --}}
            <div class="bg-white rounded-3xl p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] hover:-translate-y-1 transition-all duration-300 border border-slate-100 group">
                <div class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center mb-6 group-hover:scale-110 group-hover:bg-amber-500 group-hover:text-white transition-all duration-300 shadow-sm">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <h3 class="font-heading font-bold text-slate-900 text-xl mb-3">Respon Seketika</h3>
                <p class="text-slate-500 leading-relaxed text-justify">Terhubung dengan dokter dalam hitungan menit. Tidak perlu menghabiskan waktu antri panjang seperti di klinik atau rumah sakit.</p>
            </div>

            {{-- Benefit 2 --}}
            <div class="bg-white rounded-3xl p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] hover:-translate-y-1 transition-all duration-300 border border-slate-100 group">
                <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-500 flex items-center justify-center mb-6 group-hover:scale-110 group-hover:bg-blue-500 group-hover:text-white transition-all duration-300 shadow-sm">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <h3 class="font-heading font-bold text-slate-900 text-xl mb-3">Dokter Terverifikasi</h3>
                <p class="text-slate-500 leading-relaxed text-justify">Semua mitra dokter kami memiliki Surat Tanda Registrasi (STR) aktif dan telah melalui proses verifikasi kredensial yang ketat.</p>
            </div>

            {{-- Benefit 3 --}}
            <div class="bg-white rounded-3xl p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] hover:-translate-y-1 transition-all duration-300 border border-slate-100 group">
                <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-500 flex items-center justify-center mb-6 group-hover:scale-110 group-hover:bg-emerald-500 group-hover:text-white transition-all duration-300 shadow-sm">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <h3 class="font-heading font-bold text-slate-900 text-xl mb-3">Privasi Terjamin</h3>
                <p class="text-slate-500 leading-relaxed text-justify">Seluruh data medis, percakapan, dan dokumen Anda disimpan dalam penyimpanan privat dengan standar enkripsi keamanan tinggi.</p>
            </div>

            {{-- Benefit 4 --}}
            <div class="bg-white rounded-3xl p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] hover:-translate-y-1 transition-all duration-300 border border-slate-100 group">
                <div class="w-14 h-14 rounded-2xl bg-teal-50 text-teal-500 flex items-center justify-center mb-6 group-hover:scale-110 group-hover:bg-teal-500 group-hover:text-white transition-all duration-300 shadow-sm">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <h3 class="font-heading font-bold text-slate-900 text-xl mb-3">E-Resep Praktis</h3>
                <p class="text-slate-500 leading-relaxed text-justify">Dapatkan resep digital resmi dari dokter langsung setelah sesi konsultasi selesai. Dapat diunduh kapan saja Anda butuhkan.</p>
            </div>

            {{-- Benefit 5 --}}
            <div class="bg-white rounded-3xl p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] hover:-translate-y-1 transition-all duration-300 border border-slate-100 group">
                <div class="w-14 h-14 rounded-2xl bg-purple-50 text-purple-500 flex items-center justify-center mb-6 group-hover:scale-110 group-hover:bg-purple-500 group-hover:text-white transition-all duration-300 shadow-sm">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                </div>
                <h3 class="font-heading font-bold text-slate-900 text-xl mb-3">Berbagi Dokumen</h3>
                <p class="text-slate-500 leading-relaxed text-justify">Unggah foto gejala kulit, hasil tes laboratorium, atau rekam medis lainnya langsung dari ponsel Anda dengan format yang didukung penuh.</p>
            </div>

            {{-- Benefit 6 --}}
            <div class="bg-white rounded-3xl p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] hover:-translate-y-1 transition-all duration-300 border border-slate-100 group">
                <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-500 flex items-center justify-center mb-6 group-hover:scale-110 group-hover:bg-rose-500 group-hover:text-white transition-all duration-300 shadow-sm">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/></svg>
                </div>
                <h3 class="font-heading font-bold text-slate-900 text-xl mb-3">Live Chat Interaktif</h3>
                <p class="text-slate-500 leading-relaxed text-justify">Ruang konsultasi dengan teknologi modern memberikan pengalaman chat medis yang mengalir lancar dan responsif secara real-time.</p>
            </div>
        </div>
    </div>
</section>

{{-- ===== COVERAGE AREA ===== --}}
<section id="area" class="py-24 bg-gradient-to-br from-brand-950 to-brand-800 relative overflow-hidden">
    <div class="absolute inset-0 opacity-5"
         style="background-image: radial-gradient(circle, white 1px, transparent 1px); background-size: 30px 30px;"></div>

    <div class="relative max-w-6xl mx-auto px-4 sm:px-6">
        <div class="text-center mb-16">
            <span class="inline-block px-4 py-1.5 bg-white/10 text-white/90 border border-white/20 rounded-full text-sm font-semibold mb-4">Area Layanan</span>
            <h2 class="font-heading text-3xl sm:text-4xl font-bold text-white mb-4">Konsultasi Online Seluruh Indonesia</h2>
            <p class="text-white/70 text-lg max-w-xl mx-auto">Temu Dokter siap melayani Anda di mana saja. Khusus untuk layanan kunjungan dokter ke rumah (Homecare), saat ini hanya mencakup wilayah Bekasi.</p>
        </div>

        <div class="flex flex-wrap justify-center gap-3">
            @php
            $areas = [
                'Bekasi Timur','Bekasi Barat','Bekasi Selatan','Bekasi Utara',
                'Tambun','Babelan','Cikarang','Setu','Rawalumbu','Pondok Gede',
                'Jatiasih','Mustika Jaya','Bantargebang','Medan Satria',
                'Bekasi Kota','Rawa Lumbu','Jati Sampurna',
            ];
            @endphp
            @foreach($areas as $area)
            <div class="flex items-center gap-2 px-4 py-2.5 bg-white/10 backdrop-blur-sm border border-white/20 rounded-full text-white text-sm font-medium hover:bg-white/20 transition-colors cursor-default">
                <svg class="w-3.5 h-3.5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                </svg>
                {{ $area }}
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== PAYMENT METHODS ===== --}}
<section class="py-24 bg-white relative overflow-hidden">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 relative z-10">
        <div class="text-center mb-16">
            <span class="inline-block px-4 py-1.5 bg-blue-50 text-blue-600 rounded-full text-sm font-bold mb-4 shadow-sm border border-blue-100">💳 Metode Pembayaran</span>
            <h2 class="font-heading text-4xl sm:text-5xl font-extrabold text-slate-900 mb-6 tracking-tight leading-tight">Berbagai Pilihan <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">Pembayaran</span></h2>
            <p class="text-slate-500 text-lg max-w-xl mx-auto leading-relaxed">Bayar dengan cara yang paling nyaman untuk Anda. Transaksi diproses seketika dan 100% aman.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-10">
            {{-- QRIS --}}
            <div class="bg-white rounded-[2rem] p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.12)] hover:-translate-y-2 transition-all duration-300 border border-slate-100 group relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-teal-500/5 rounded-bl-full -mr-10 -mt-10 transition-transform group-hover:scale-110"></div>
                <div class="w-16 h-16 bg-gradient-to-br from-teal-50 to-teal-100 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 shadow-sm border border-teal-100">
                    <svg class="w-8 h-8 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                    </svg>
                </div>
                <h3 class="font-heading font-extrabold text-slate-900 text-xl mb-3 relative z-10">QRIS</h3>
                <p class="text-slate-500 text-sm leading-relaxed mb-6 relative z-10">Scan QR Code dari aplikasi mobile banking atau e-wallet manapun secara instan.</p>
                <div class="flex flex-wrap gap-2 text-xs font-bold relative z-10">
                    <span class="px-3 py-1.5 bg-teal-50 text-teal-700 rounded-lg border border-teal-100/50">GoPay</span>
                    <span class="px-3 py-1.5 bg-teal-50 text-teal-700 rounded-lg border border-teal-100/50">DANA</span>
                    <span class="px-3 py-1.5 bg-teal-50 text-teal-700 rounded-lg border border-teal-100/50">OVO</span>
                    <span class="px-3 py-1.5 bg-teal-50 text-teal-700 rounded-lg border border-teal-100/50">+Lainnya</span>
                </div>
            </div>

            {{-- Manual Bank Transfer --}}
            <div class="bg-white rounded-[2rem] p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.12)] hover:-translate-y-2 transition-all duration-300 border border-slate-100 group relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-blue-500/5 rounded-bl-full -mr-10 -mt-10 transition-transform group-hover:scale-110"></div>
                <div class="w-16 h-16 bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 shadow-sm border border-blue-100">
                    <svg class="w-8 h-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                </div>
                <h3 class="font-heading font-extrabold text-slate-900 text-xl mb-3 relative z-10">Transfer Bank</h3>
                <p class="text-slate-500 text-sm leading-relaxed mb-6 relative z-10">Transfer manual antar bank, lalu unggah bukti transfer untuk diverifikasi oleh admin.</p>
                <div class="flex flex-wrap gap-2 text-xs font-bold relative z-10">
                    <span class="px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg border border-blue-100/50">BCA</span>
                    <span class="px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg border border-blue-100/50">Mandiri</span>
                    <span class="px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg border border-blue-100/50">BNI</span>
                    <span class="px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg border border-blue-100/50">BRI</span>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===== FAQ ===== --}}
<section id="faq" class="py-24 bg-slate-50" x-data="{ open: null }">
    <div class="max-w-3xl mx-auto px-4 sm:px-6">
        <div class="text-center mb-16">
            <span class="inline-block px-4 py-1.5 bg-slate-200 text-slate-700 rounded-full text-sm font-semibold mb-4">FAQ</span>
            <h2 class="font-heading text-3xl sm:text-4xl font-bold text-slate-800 mb-4">Pertanyaan yang Sering Diajukan</h2>
        </div>

        @php
        $faqs = [
            ['q'=>'Bagaimana cara konsultasi di Temu Dokter?','a'=>'Cukup isi formulir konsultasi dengan data diri dan keluhan Anda, pilih metode pembayaran, upload bukti bayar, lalu tunggu admin memverifikasi. Setelah terverifikasi, Anda akan dihubungkan dengan dokter dan dapat langsung berkonsultasi melalui ruang chat.'],
            ['q'=>'Berapa lama proses verifikasi pembayaran?','a'=>'Proses verifikasi pembayaran dilakukan oleh admin kami biasanya dalam waktu 5–15 menit di jam kerja. Anda dapat memantau status pembayaran secara real-time di halaman tunggu.'],
            ['q'=>'Apakah data dan dokumen medis saya aman?','a'=>'Ya, sepenuhnya aman. Semua dokumen medis, bukti pembayaran, dan resep disimpan di penyimpanan privat yang tidak dapat diakses publik. Akses file menggunakan tautan terenkripsi yang unik untuk setiap pengguna.'],
            ['q'=>'Bagaimana cara menerima resep dari dokter?','a'=>'Setelah konsultasi selesai, dokter akan mengupload resep digital (PDF atau gambar). Anda dapat mengunduh resep tersebut dari halaman ringkasan konsultasi menggunakan tautan aman yang hanya bisa diakses oleh Anda.'],
            ['q'=>'Berapa biaya konsultasi?','a'=>'Biaya konsultasi adalah Rp ' . number_format(\App\Models\Setting::getValue('online_price', 25000), 0, ',', '.') . ' per sesi konsultasi selama 15 menit. Tidak ada biaya tambahan tersembunyi.'],
            ['q'=>'Apa yang terjadi jika waktu konsultasi habis?','a'=>'Sistem akan memberikan peringatan 5 menit sebelum waktu habis dan 1 menit sebelum berakhir. Setelah 15 menit, sesi konsultasi otomatis berakhir dan Anda dapat mengunduh resep jika sudah disiapkan dokter.'],
            ['q'=>'Apakah perlu membuat akun untuk konsultasi?','a'=>'Tidak perlu. Pasien tidak perlu membuat akun. Cukup isi formulir konsultasi dan Anda akan mendapatkan akses ke ruang konsultasi melalui tautan yang aman dan unik.'],
        ];
        @endphp

        <div class="space-y-3">
            @foreach($faqs as $i => $faq)
            <div class="card overflow-hidden">
                <button @click="open = open === {{ $i }} ? null : {{ $i }}"
                        class="w-full flex items-center justify-between px-6 py-4 text-left hover:bg-slate-50 transition-colors">
                    <span class="font-semibold text-slate-800 pr-4">{{ $faq['q'] }}</span>
                    <svg class="w-5 h-5 text-brand-500 flex-shrink-0 transition-transform duration-200"
                         :class="open === {{ $i }} ? 'rotate-180' : ''"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open === {{ $i }}"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 -translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-2"
                     class="px-6 pb-5 text-slate-600 text-sm leading-relaxed border-t border-slate-100 pt-4">
                    {{ $faq['a'] }}
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== CTA BANNER ===== --}}
<section class="relative py-24 bg-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="relative rounded-[3rem] overflow-hidden bg-[#004945] shadow-2xl">
            {{-- Decorative Background --}}
            <div class="absolute inset-0 z-0">
                <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-gradient-to-br from-emerald-500/30 to-brand-600/30 blur-[80px] rounded-full -translate-y-1/2 translate-x-1/3"></div>
                <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-gradient-to-tr from-purple-500/20 to-blue-500/20 blur-[80px] rounded-full translate-y-1/3 -translate-x-1/3"></div>
                <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMSIgY3k9IjEiIHI9IjEiIGZpbGw9InJnYmEoMjU1LDI1NSwyNTUsMC4wNSkiLz48L3N2Zz4=')] [mask-image:linear-gradient(to_bottom,white,transparent)]"></div>
            </div>

            <div class="relative z-10 px-6 py-16 sm:py-20 md:p-24 text-center flex flex-col items-center">
                <span class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 text-white/90 rounded-full text-sm font-bold mb-6 backdrop-blur-md border border-white/20">
                    <span class="relative flex h-2.5 w-2.5">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                    </span>
                    Dokter Online 24/7
                </span>
                
                <h2 class="font-heading text-4xl sm:text-5xl md:text-6xl font-extrabold text-white mb-6 tracking-tight leading-tight max-w-3xl">
                    Kesehatan Anda, <br class="hidden sm:block"> Prioritas Utama Kami
                </h2>
                
                <p class="text-slate-300 text-lg sm:text-xl max-w-2xl mb-10 leading-relaxed">
                    Jangan tunda kesehatan Anda. Konsultasikan keluhan medis Anda dengan dokter terverifikasi sekarang juga. Cepat, aman, dan tanpa ribet.
                </p>
                
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 sm:gap-6 w-full sm:w-auto">
                    <a href="{{ route('patient.create') }}"
                       class="group relative inline-flex items-center justify-center w-full sm:w-auto px-8 py-4 bg-emerald-500 text-white font-bold rounded-2xl hover:bg-emerald-400 transition-all duration-300 hover:scale-105 hover:shadow-[0_0_40px_rgba(16,185,129,0.4)]">
                        <span class="flex items-center gap-2 text-lg">
                            Mulai Chat Dokter
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </span>
                    </a>
                    
                    <a href="{{ route('patient.homecare.create') }}"
                       class="group relative inline-flex items-center justify-center w-full sm:w-auto px-8 py-4 bg-white/10 text-white font-bold rounded-2xl backdrop-blur-md border border-white/20 hover:bg-white/20 transition-all duration-300 hover:scale-105">
                        <span class="flex items-center gap-2 text-lg">
                            <svg class="w-5 h-5 text-purple-400 group-hover:-translate-y-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            Pesan Homecare
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===== FOOTER ===== --}}
<footer class="bg-[#004945] border-t border-slate-800 text-slate-300 py-16 relative overflow-hidden">
    {{-- Decorative Background --}}
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0 opacity-10 pointer-events-none">
        <div class="absolute -top-[20%] -right-[10%] w-[500px] h-[500px] rounded-full bg-brand-500 blur-[120px]"></div>
        <div class="absolute -bottom-[20%] -left-[10%] w-[400px] h-[400px] rounded-full bg-emerald-500 blur-[100px]"></div>
    </div>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-10 lg:gap-16 mb-12">
            
            {{-- Brand Column --}}
            <div class="md:col-span-5 lg:col-span-4">
                <a href="{{ route('home') }}" class="inline-block mb-6 bg-white p-2.5 rounded-xl shadow-lg">
                    <img src="{{ asset('images/logo.png') }}?v={{ time() }}" alt="Temu Dokter Logo" class="h-12 w-auto rounded-lg">
                </a>
                <p class="text-sm leading-relaxed text-slate-400 mb-6">
                    Temu Dokter adalah platform layanan kesehatan digital yang memudahkan masyarakat untuk berkonsultasi dengan dokter secara online maupun pemesanan layanan Homecare langsung ke rumah Anda.
                </p>
                <div class="flex gap-3">
                    <a href="#" class="w-9 h-9 rounded-full bg-slate-800 flex items-center justify-center text-slate-400 hover:bg-brand-600 hover:text-white transition-all shadow-sm">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                    </a>
                    <a href="#" class="w-9 h-9 rounded-full bg-slate-800 flex items-center justify-center text-slate-400 hover:bg-brand-600 hover:text-white transition-all shadow-sm">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </a>
                    <a href="#" class="w-9 h-9 rounded-full bg-slate-800 flex items-center justify-center text-slate-400 hover:bg-brand-600 hover:text-white transition-all shadow-sm">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/></svg>
                    </a>
                </div>
            </div>

            {{-- Links --}}
            <div class="md:col-span-2 lg:col-span-2">
                <h4 class="text-white font-bold mb-4 uppercase tracking-wider text-xs">Layanan</h4>
                <ul class="space-y-3 text-sm">
                    <li><a href="{{ route('patient.create') }}" class="hover:text-brand-400 transition-colors">Konsultasi Online</a></li>
                    <li><a href="{{ route('patient.homecare.create') }}" class="hover:text-brand-400 transition-colors">Homecare</a></li>
                    <li><a href="{{ route('history.form') }}" class="hover:text-brand-400 transition-colors">Riwayat Medis</a></li>
                </ul>
            </div>

            <div class="md:col-span-2 lg:col-span-2">
                <h4 class="text-white font-bold mb-4 uppercase tracking-wider text-xs">Informasi</h4>
                <ul class="space-y-3 text-sm">
                    <li><a href="#keunggulan" class="hover:text-brand-400 transition-colors">Keunggulan</a></li>
                    <li><a href="#area" class="hover:text-brand-400 transition-colors">Area Layanan</a></li>
                    <li><a href="#faq" class="hover:text-brand-400 transition-colors">FAQ</a></li>
                </ul>
            </div>

            {{-- Contact info --}}
            <div class="md:col-span-3 lg:col-span-4">
                <h4 class="text-white font-bold mb-4 uppercase tracking-wider text-xs">Hubungi Kami</h4>
                <ul class="space-y-4 text-sm">
                    <li class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-brand-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </div>
                        <span class="text-slate-400 mt-1">+62 812 3456 7890<br><span class="text-xs text-slate-500">(Senin - Minggu: 08.00 - 20.00 WIB)</span></span>
                    </li>
                    <li class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-brand-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <span class="text-slate-400 mt-1.5">admintemudokter@gmail.com</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-slate-800 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-brand-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <span class="text-slate-400 mt-0.5 leading-relaxed"> Kota Bekasi, Jawa Barat 17143</span>
                    </li>
                </ul>
            </div>

        </div>

        {{-- Bottom Copyright --}}
        <div class="pt-8 border-t border-slate-800 flex flex-col md:flex-row items-center justify-between gap-4">
            <p class="text-sm text-slate-500">© {{ date('Y') }} Temu Dokter (Klinik Temu Dokter). Seluruh hak cipta dilindungi.</p>
            <div class="flex flex-wrap items-center gap-3 text-sm font-medium">
                <a href="{{ route('admin.login') }}" class="text-slate-500 hover:text-white transition-colors bg-slate-800/50 hover:bg-slate-700 px-4 py-2 rounded-lg border border-slate-700/50 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    Portal Admin
                </a>
                <a href="{{ route('doctor.login') }}" class="text-slate-500 hover:text-white transition-colors bg-slate-800/50 hover:bg-slate-700 px-4 py-2 rounded-lg border border-slate-700/50 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Portal Dokter
                </a>
            </div>
        </div>
    </div>
</footer>

@endsection
