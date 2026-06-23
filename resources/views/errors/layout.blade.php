<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') – Temu Dokter</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .error-blob {
            position: absolute;
            filter: blur(80px);
            z-index: -1;
            opacity: 0.6;
        }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4 relative overflow-hidden font-sans">
    
    {{-- Decorative Background Blobs --}}
    <div class="error-blob w-96 h-96 bg-brand-300 rounded-full top-[-10%] left-[-10%]"></div>
    <div class="error-blob w-96 h-96 bg-rose-200 rounded-full bottom-[-10%] right-[-10%]"></div>

    <div class="max-w-md w-full bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl border border-slate-100 p-8 text-center relative z-10">
        
        <div class="mb-6 flex justify-center">
            <img src="{{ asset('images/logo.png') }}" alt="Temu Dokter" class="h-12 w-auto">
        </div>

        <div class="mb-8">
            <h1 class="text-7xl font-black text-slate-800 mb-2 font-heading">@yield('code')</h1>
            <h2 class="text-xl font-bold text-slate-700 mb-3">@yield('message')</h2>
            <p class="text-slate-500 text-sm leading-relaxed">@yield('description')</p>
        </div>

        <a href="{{ url('/') }}" class="inline-flex items-center justify-center w-full px-6 py-3 bg-brand-600 text-white font-semibold rounded-xl hover:bg-brand-700 transition-all shadow-md hover:shadow-lg focus:ring-4 focus:ring-brand-500/30 gap-2">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Beranda
        </a>

        @if(env('APP_DEBUG') == true && View::hasSection('debug'))
            <div class="mt-8 text-left bg-slate-900 rounded-xl p-4 overflow-x-auto">
                <p class="text-rose-400 text-xs font-bold mb-2">DEBUG MODE (Only visible in local/dev):</p>
                <code class="text-slate-300 text-xs whitespace-pre-wrap">@yield('debug')</code>
            </div>
        @endif
    </div>

</body>
</html>
