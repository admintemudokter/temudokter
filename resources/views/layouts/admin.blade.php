<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') – TemuDokter Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-slate-100 min-h-screen" x-data="{ sidebarOpen: false }">

    {{-- Flash --}}
    @if(session('success') || session('error'))
    <div id="flash-message" class="fixed top-4 right-4 z-50 max-w-sm transition-all duration-400">
        @if(session('success'))
        <div class="alert-success shadow-lg rounded-2xl">
            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            <span>{{ session('success') }}</span>
        </div>
        @else
        <div class="alert-danger shadow-lg rounded-2xl">
            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
            <span>{{ session('error') }}</span>
        </div>
        @endif
    </div>
    @endif

    <div class="flex h-screen overflow-hidden">
        {{-- Mobile Overlay Backdrop --}}
        <div x-show="sidebarOpen" class="fixed inset-0 bg-slate-900/50 z-40 md:hidden" @click="sidebarOpen = false" x-transition.opacity></div>

        {{-- ===== SIDEBAR ===== --}}
        <aside class="absolute z-50 md:relative h-full w-60 bg-brand-950 flex-shrink-0 flex flex-col"
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
               style="transition: transform 0.3s ease;">
            {{-- Logo --}}
            <div class="flex items-center gap-2.5 px-6 py-5 border-b border-white/10">
                <img src="{{ asset('images/logo.png') }}?v={{ time() }}" alt="TemuDokter Logo" class="h-10 w-auto rounded-lg shadow-sm">
                <div>
                    <div class="text-xs text-white/40">Admin Panel</div>
                </div>
            </div>

            {{-- Nav --}}
            <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
                <a href="{{ route('admin.dashboard') }}"
                   class="{{ request()->routeIs('admin.dashboard') ? 'bg-brand-600 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }} flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2zm0 0V5a2 2 0 012-2h6l2 2h6a2 2 0 012 2v2M7 13h10M7 17h6"/></svg>
                    Dashboard
                </a>
                <a href="{{ route('admin.revenue.index') }}"
                   class="{{ request()->routeIs('admin.revenue.*') ? 'bg-brand-600 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }} flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Pendapatan
                </a>
                <a href="{{ route('admin.payment.index') }}"
                   class="{{ request()->routeIs('admin.payment.*') ? 'bg-brand-600 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }} flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    Verifikasi Pembayaran
                </a>
                <a href="{{ route('admin.medicine.index') }}"
                   class="{{ request()->routeIs('admin.medicine.*') ? 'bg-brand-600 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }} flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                    Master Data Obat
                </a>
                <a href="{{ route('admin.homecare.schedule.index') }}"
                   class="{{ request()->routeIs('admin.homecare.schedule.*') ? 'bg-brand-600 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }} flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Jadwal Homecare
                </a>
                <a href="{{ route('admin.consultation.index') }}"
                   class="{{ request()->routeIs('admin.consultation.*') ? 'bg-brand-600 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }} flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    Telekonsultasi
                </a>
                <a href="{{ route('admin.doctor.index') }}"
                   class="{{ request()->routeIs('admin.doctor.*') ? 'bg-brand-600 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }} flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Manajemen Dokter
                </a>
                <a href="{{ route('admin.settings.pricing') }}"
                   class="{{ request()->routeIs('admin.settings.pricing*') ? 'bg-brand-600 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }} flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Harga & Diskon
                </a>
            </nav>

            {{-- User info --}}
            <div class="px-4 py-4 border-t border-white/10">
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.profile.edit') }}" class="flex-shrink-0 group relative cursor-pointer">
                        <img src="{{ auth('admin')->user()->photo_url }}" alt="Profile" class="w-9 h-9 rounded-full object-cover border border-white/20 group-hover:border-white/50 transition-colors">
                        <div class="absolute inset-0 bg-black/40 rounded-full opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        </div>
                    </a>
                    <div class="flex-1 min-w-0">
                        <a href="{{ route('admin.profile.edit') }}" class="text-white text-sm font-medium truncate hover:underline">{{ auth('admin')->user()->name }}</a>
                        <p class="text-white/40 text-xs truncate">Administrator</p>
                    </div>
                    <form action="{{ route('admin.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-white/50 hover:text-white transition-colors" title="Logout">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- ===== MAIN CONTENT ===== --}}
        <div class="flex-1 flex flex-col overflow-hidden">
            {{-- Top bar --}}
            <header class="bg-white border-b border-slate-200 px-6 py-4 flex items-center justify-between flex-shrink-0">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="md:hidden p-2 rounded-lg hover:bg-slate-100 transition-colors">
                        <svg class="w-5 h-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    <div>
                        <h1 class="font-heading font-bold text-slate-800">@yield('page_title', 'Dashboard')</h1>
                        <p class="text-xs text-slate-500">@yield('page_subtitle', 'TemuDokter Admin Panel')</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 text-sm text-slate-500">
                    <span class="status-dot-online"></span>
                    <span class="hidden sm:inline">Sistem Aktif</span>
                </div>
            </header>

            {{-- Page content --}}
            <main class="flex-1 overflow-y-auto p-6">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
    @include('components.anti-inspect')
</body>
</html>
