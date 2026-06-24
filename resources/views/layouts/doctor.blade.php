<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') – Temu Dokter Dokter</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-slate-100 min-h-screen" x-data="{ sidebarOpen: false }">

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

        {{-- Sidebar --}}
        <aside class="absolute z-50 md:relative h-full w-64 bg-emerald-950 flex-shrink-0 flex flex-col"
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'"
               style="transition: transform 0.3s ease;">
            <div class="flex items-center gap-2.5 px-6 py-5 border-b border-white/10">
                <img src="{{ asset('images/logo.png') }}?v={{ time() }}" alt="Temu Dokter Logo" class="h-10 w-auto filter brightness-0 invert">
                <div>
                    <div class="font-heading font-bold text-white">Portal Dokter</div>
                </div>
            </div>

            <nav class="flex-1 px-3 py-4 space-y-1">
                <a href="{{ route('doctor.dashboard') }}"
                   class="{{ request()->routeIs('doctor.dashboard') ? 'bg-emerald-600 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }} flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Dashboard
                </a>
            </nav>

            {{-- Availability toggle --}}
            <div class="px-4 py-3 border-t border-white/10" x-data="doctorStatus('{{ auth('doctor')->user()->status }}', '{{ route('doctor.status') }}')">
                <p class="text-white/40 text-xs mb-2">Status Ketersediaan</p>
                <div class="flex gap-2">
                    <button @click="setStatus('online')"
                            :class="status === 'online' ? 'bg-emerald-500 text-white' : 'bg-white/10 text-white/60'"
                            class="flex-1 py-2 rounded-xl text-xs font-semibold transition-all">
                        ● Online
                    </button>
                    <button @click="setStatus('offline')"
                            :class="status === 'offline' ? 'bg-slate-500 text-white' : 'bg-white/10 text-white/60'"
                            class="flex-1 py-2 rounded-xl text-xs font-semibold transition-all">
                        ○ Offline
                    </button>
                </div>
            </div>

            <div class="px-4 pb-4 border-t border-white/10 pt-3">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-emerald-600 rounded-full flex items-center justify-center text-white text-xs font-bold">
                        {{ substr(auth('doctor')->user()->name, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-white text-xs font-medium truncate">{{ auth('doctor')->user()->name }}</p>
                        <p class="text-white/40 text-xs truncate">{{ auth('doctor')->user()->specialization }}</p>
                    </div>
                    <form action="{{ route('doctor.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-white/50 hover:text-white">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="bg-white border-b border-slate-200 px-6 py-4 flex items-center gap-4 flex-shrink-0">
                <button @click="sidebarOpen = !sidebarOpen" class="md:hidden p-2 -ml-2 rounded-lg hover:bg-slate-100 transition-colors">
                    <svg class="w-5 h-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <div>
                    <h1 class="font-heading font-bold text-slate-800">@yield('page_title', 'Dashboard')</h1>
                    <p class="text-xs text-slate-500">@yield('page_subtitle', '')</p>
                </div>
            </header>
            <main class="flex-1 overflow-y-auto p-6">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
    <script>
    function doctorStatus(initial, url) {
        return {
            status: initial,
            async setStatus(s) {
                try {
                    const res = await postJson(url, { status: s });
                    const data = await res.json();
                    if (data.success) this.status = data.status;
                    else alert(data.error);
                } catch(e) {}
            },
        };
    }
    </script>
    @include('components.anti-inspect')
</body>
</html>
