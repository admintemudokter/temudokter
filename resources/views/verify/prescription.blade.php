<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Resep - Temu Dokter</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-900 text-slate-300 min-h-screen flex items-center justify-center p-4 font-sans">

    <div class="max-w-md w-full bg-slate-950 rounded-2xl shadow-2xl border border-slate-800 overflow-hidden relative">
        
        {{-- Blue Top Accent --}}
        <div class="h-2 bg-blue-600 w-full"></div>

        <div class="p-8">
            {{-- Checkmark Icon --}}
            <div class="flex justify-center mb-6">
                <div class="w-16 h-16 rounded-full bg-slate-800 border-2 border-slate-600 flex items-center justify-center text-blue-400">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            </div>

            <h1 class="text-center text-2xl font-semibold tracking-widest text-slate-100 mb-8">RESEP OBAT</h1>

            <div class="space-y-3 text-sm border-b border-slate-800 pb-6 mb-6">
                <div class="flex">
                    <div class="w-32 text-slate-500 font-medium">KLINIK</div>
                    <div class="text-slate-200">: KONSULKU</div>
                </div>
                <div class="flex">
                    <div class="w-32 text-slate-500 font-medium">NAMA PASIEN</div>
                    <div class="text-slate-200">: {{ $consultation->patient->full_name }}</div>
                </div>
                <div class="flex">
                    <div class="w-32 text-slate-500 font-medium">UMUR</div>
                    <div class="text-slate-200">: {{ $consultation->patient->age }} Tahun</div>
                </div>
                <div class="flex">
                    <div class="w-32 text-slate-500 font-medium">NO. INVOICE</div>
                    <div class="text-slate-200">: {{ $consultation->invoice_number }}</div>
                </div>
                <div class="flex">
                    <div class="w-32 text-slate-500 font-medium">TANGGAL RESEP</div>
                    <div class="text-slate-200">: {{ $prescription->created_at->format('d-m-Y H:i') }}</div>
                </div>
                <div class="flex">
                    <div class="w-32 text-slate-500 font-medium">DOKTER</div>
                    <div class="text-slate-200">: dr. {{ $consultation->doctor->name }}</div>
                </div>
                <div class="flex">
                    <div class="w-32 text-slate-500 font-medium">STATUS</div>
                    <div class="text-emerald-400 font-bold">: Valid</div>
                </div>
            </div>

            <div class="text-sm space-y-4">
                <div class="text-center text-slate-400 mb-4">Daftar Obat yang Diresepkan:</div>
                
                @foreach($prescription->items as $item)
                <div class="bg-slate-900 rounded-lg p-3 border border-slate-800">
                    <div class="font-bold text-slate-200">{{ $item->medicine_name }}</div>
                    <div class="text-slate-400 mt-1">Jumlah: {{ $item->quantity }}</div>
                    <div class="text-slate-400">Aturan: {{ $item->instructions }}</div>
                    @if($item->notes)
                    <div class="text-slate-500 italic mt-1">{{ $item->notes }}</div>
                    @endif
                </div>
                @endforeach
            </div>

        </div>

        <div class="p-6 bg-slate-900 border-t border-slate-800 text-center">
            <a href="{{ route('home') }}" class="inline-block px-6 py-2 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-lg transition-colors border border-slate-700 text-sm">
                Kembali ke Beranda
            </a>
        </div>
    </div>

</body>
</html>
