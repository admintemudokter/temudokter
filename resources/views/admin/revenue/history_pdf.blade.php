<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Pendapatan - {{ $monthName }} {{ $year }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #004945; padding-bottom: 10px; }
        .logo { max-width: 150px; margin-bottom: 10px; }
        h2 { margin: 0 0 5px 0; color: #004945; }
        p { margin: 0; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f8fafc; color: #475569; font-weight: bold; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .footer { margin-top: 30px; text-align: center; font-size: 10px; color: #999; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Temu Dokter - Riwayat Pendapatan</h2>
        <p>Arsip Transaksi yang Telah Di-reset</p>
        <p>Periode: {{ $monthName }} {{ $year }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal Transaksi</th>
                <th>Invoice</th>
                <th>Pasien</th>
                <th>Metode Bayar</th>
                <th class="text-right">Nominal</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @forelse($transactions as $index => $t)
                @php $total += $t->amount; @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $t->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $t->invoice_number }}</td>
                    <td>{{ $t->consultation->patient->name ?? '-' }}</td>
                    <td>{{ strtoupper($t->payment_provider) }}</td>
                    <td class="text-right">Rp {{ number_format($t->amount, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada riwayat pendapatan pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
        @if($transactions->isNotEmpty())
        <tfoot>
            <tr>
                <th colspan="5" class="text-right">Total Pendapatan Terarsip</th>
                <th class="text-right">Rp {{ number_format($total, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
        @endif
    </table>

    <div class="footer">
        Dicetak pada: {{ now()->format('d/m/Y H:i') }} oleh Sistem Temu Dokter.
    </div>
</body>
</html>
