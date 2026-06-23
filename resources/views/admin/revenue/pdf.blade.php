<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pendapatan</title>
    <style>
        body { font-family: sans-serif; color: #333; margin: 0; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #10b981; padding-bottom: 20px; }
        .title { font-size: 24px; font-weight: bold; color: #1e293b; margin: 0; }
        .subtitle { font-size: 14px; color: #64748b; margin-top: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #e2e8f0; padding: 12px; text-align: left; }
        th { background-color: #f8fafc; font-weight: bold; color: #475569; text-transform: uppercase; font-size: 12px; }
        td { font-size: 14px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .total-row { background-color: #ecfdf5; font-weight: bold; }
        .footer { margin-top: 40px; font-size: 12px; color: #94a3b8; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="title">Laporan Pendapatan - Temu Dokter</h1>
        <div class="subtitle">Bulan: {{ \Carbon\Carbon::createFromDate($year, $month, 1)->translatedFormat('F Y') }}</div>
        <div class="subtitle" style="margin-top:2px; font-size:11px;">Dicetak pada: {{ now()->translatedFormat('d F Y H:i') }} WIB</div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="35%">Tanggal</th>
                <th width="25%" class="text-center">Total Transaksi</th>
                <th width="35%" class="text-right">Pendapatan Bersih</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $totalTransactions = 0;
                $totalRevenue = 0;
            @endphp
            
            @forelse($data as $index => $row)
                @php 
                    $totalTransactions += $row->total_transactions;
                    $totalRevenue += $row->total_revenue;
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($row->date)->translatedFormat('l, d F Y') }}</td>
                    <td class="text-center">{{ $row->total_transactions }}</td>
                    <td class="text-right">Rp {{ number_format($row->total_revenue, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">Belum ada data pendapatan tercatat pada bulan ini.</td>
                </tr>
            @endforelse
            
            @if(count($data) > 0)
            <tr class="total-row">
                <td colspan="2" class="text-right">TOTAL KESELURUHAN</td>
                <td class="text-center">{{ $totalTransactions }}</td>
                <td class="text-right">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
            </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        Dokumen ini dibuat secara otomatis oleh sistem komputer Temu Dokter dan sah tanpa tanda tangan fisik.
    </div>
</body>
</html>
