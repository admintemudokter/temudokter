<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('meta_description', 'Temu Dokter – Platform konsultasi medis online untuk wilayah Bekasi. Konsultasi cepat, dokter terverifikasi, resep digital.')">
    <title>@yield('title', 'Temu Dokter') | Platform Konsultasi Medis Online Bekasi</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Inline styles for page-specific overrides --}}
    @stack('styles')
</head>
<body class="min-h-screen">

    {{-- Flash Messages --}}
    @if(session('success') || session('error') || session('info'))
    <div id="flash-message"
         class="fixed top-4 right-4 z-50 max-w-sm transition-all duration-400"
         style="transition: opacity 0.4s ease, transform 0.4s ease;">
        @if(session('success'))
        <div class="alert-success shadow-lg rounded-2xl">
            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            <span>{{ session('success') }}</span>
        </div>
        @elseif(session('error'))
        <div class="alert-danger shadow-lg rounded-2xl">
            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
            <span>{{ session('error') }}</span>
        </div>
        @elseif(session('info'))
        <div class="alert-info shadow-lg rounded-2xl">
            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
            <span>{{ session('info') }}</span>
        </div>
        @endif
    </div>
    @endif

    @yield('content')

    @stack('scripts')
    @include('components.anti-inspect')
</body>
</html>
