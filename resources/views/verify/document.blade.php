<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Dokumen - Temu Dokter</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-900 text-slate-200 font-sans min-h-screen flex justify-center p-4">

    <div class="max-w-md w-full bg-slate-950 rounded-xl shadow-2xl border border-slate-800 p-6 sm:p-8 mt-10 mb-10 h-max">
        
        <!-- Checkmark Icon -->
        <div class="flex justify-center mb-6">
            <div class="w-16 h-16 rounded-full bg-brand-600 border-4 border-slate-900 flex items-center justify-center shadow-lg ring-4 ring-brand-900/30">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
        </div>

        @php
            $title = '';
            if ($type === 'sick-leave') $title = 'SURAT SAKIT';
            elseif ($type === 'prescription') $title = 'RESEP OBAT';
            elseif ($type === 'receipt') $title = 'KUITANSI';

            // Mask name (e.g. Budi Santoso -> BU** *******)
            $nameParts = explode(' ', $consultation->patient->full_name);
            $maskedName = '';
            foreach ($nameParts as $part) {
                if (strlen($part) > 2) {
                    $maskedName .= substr($part, 0, 2) . str_repeat('*', strlen($part) - 2) . ' ';
                } else {
                    $maskedName .= $part . ' ';
                }
            }
            $maskedName = trim($maskedName);
        @endphp

        <!-- Title -->
        <h1 class="text-2xl font-light text-center text-white tracking-widest mb-6">
            {{ $title }}
        </h1>

        <hr class="border-slate-800 mb-6">

        <!-- Info Grid -->
        <div class="space-y-3 text-sm">
            <div class="grid grid-cols-[140px_auto] gap-2">
                <div class="text-slate-400 text-right uppercase tracking-wider text-xs font-semibold pt-0.5">Klinik :</div>
                <div class="text-white font-medium">TEMU DOKTER</div>
            </div>
            <div class="grid grid-cols-[140px_auto] gap-2">
                <div class="text-slate-400 text-right uppercase tracking-wider text-xs font-semibold pt-0.5">Nama :</div>
                <div class="text-white font-medium">{{ strtoupper($maskedName) }}</div>
            </div>
            <div class="grid grid-cols-[140px_auto] gap-2">
                <div class="text-slate-400 text-right uppercase tracking-wider text-xs font-semibold pt-0.5">Umur :</div>
                <div class="text-white font-medium">{{ $consultation->patient->age }} Tahun</div>
            </div>
            <div class="grid grid-cols-[140px_auto] gap-2">
                <div class="text-slate-400 text-right uppercase tracking-wider text-xs font-semibold pt-0.5">ID MR :</div>
                <div class="text-white font-medium">{{ sprintf('%06d', $consultation->patient->id) }}</div>
            </div>
            <div class="grid grid-cols-[140px_auto] gap-2">
                <div class="text-slate-400 text-right uppercase tracking-wider text-xs font-semibold pt-0.5">Tanggal Dokumen :</div>
                <div class="text-white font-medium">{{ $consultation->created_at->format('d-m-Y') }}</div>
            </div>
            <div class="grid grid-cols-[140px_auto] gap-2">
                <div class="text-slate-400 text-right uppercase tracking-wider text-xs font-semibold pt-0.5">Penanggung Jawab :</div>
                <div class="text-white font-medium">{{ $consultation->doctor->name }}</div>
            </div>
            <div class="grid grid-cols-[140px_auto] gap-2">
                <div class="text-slate-400 text-right uppercase tracking-wider text-xs font-semibold pt-0.5">Status :</div>
                <div class="text-emerald-400 font-bold">Valid</div>
            </div>
        </div>

        <hr class="border-slate-800 my-6">

        <!-- Document Specific Content -->
        <div class="text-sm text-center leading-relaxed text-slate-300">
            @if ($type === 'sick-leave')
                @php
                    $start = \Carbon\Carbon::parse($consultation->sickLeave->start_date);
                    $end = \Carbon\Carbon::parse($consultation->sickLeave->end_date);
                    $days = $start->diffInDays($end) + 1;
                @endphp
                <p class="mb-4">
                    Dalam keadaan <b class="text-white">SAKIT</b> dan memerlukan istirahat selama {{ $days }} hari
                </p>
                <p>Terhitung mulai tanggal : <b>{{ $start->translatedFormat('d F Y') }}</b></p>
                <p>Sampai dengan tanggal : <b>{{ $end->translatedFormat('d F Y') }}</b></p>
                <p class="mt-4">Diagnosa : <b>{{ $consultation->sickLeave->reason }}</b></p>

            @elseif ($type === 'prescription')
                <p class="mb-4">Dokumen ini berisi <b class="text-white">Resep Obat</b> elektronik yang sah untuk pasien di atas.</p>
                <div class="bg-slate-900 rounded-lg p-4 border border-slate-800 text-left">
                    <ul class="list-disc pl-4 space-y-2">
                        @foreach($consultation->prescription->items as $item)
                            <li>
                                <span class="font-bold text-white">{{ $item->medicine_name }} {{ optional($item->medicine)->bentuk_sediaan ? '(' . optional($item->medicine)->bentuk_sediaan . ')' : '' }}</span><br>
                                <span class="text-xs text-slate-400">Aturan: {{ $item->instructions }} {{ $item->notes ? '| ' . $item->notes : '' }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            
            @elseif ($type === 'receipt')
                @php
                    $finalPrice = $consultation->price ?: 0;
                    if ($consultation->type === 'homecare' && $consultation->treatments) {
                        foreach ($consultation->treatments as $treatment) {
                            $finalPrice += $treatment->price;
                        }
                    }
                @endphp
                <p class="mb-4">Dokumen ini merupakan <b class="text-white">Kuitansi/Bukti Pembayaran</b> yang sah.</p>
                <div class="bg-slate-900 rounded-lg p-4 border border-slate-800 text-center">
                    <div class="text-xs text-slate-400 uppercase tracking-wider mb-1">Total Pembayaran</div>
                    <div class="text-xl font-bold text-emerald-400">Rp {{ number_format($finalPrice, 0, ',', '.') }}</div>
                    <div class="text-xs mt-2 text-slate-400">No. Invoice: {{ $consultation->invoice_number }}</div>
                </div>
            
            @elseif ($type === 'treatment')
                <p class="mb-4">Dokumen ini berisi <b class="text-white">Laporan Tindakan Medis</b> elektronik yang sah untuk pasien di atas.</p>
                <div class="bg-slate-900 rounded-lg p-4 border border-slate-800 text-left">
                    <ul class="list-disc pl-4 space-y-2">
                        @foreach($consultation->treatments as $item)
                            <li>
                                <span class="font-bold text-white">{{ $item->treatment_name }} {{ $item->bentuk_sediaan ? '(' . $item->bentuk_sediaan . ')' : '' }}</span><br>
                                <span class="text-xs text-slate-400">Biaya: Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

    </div>

</body>
</html>
