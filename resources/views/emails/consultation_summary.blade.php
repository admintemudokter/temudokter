<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ringkasan Konsultasi</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">

    <div style="max-w: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 8px;">
        <h2 style="color: #00766c; text-align: center;">Klinik Temu Dokter</h2>
        
        <p>Halo, <strong>{{ $consultation->patient->full_name }}</strong>,</p>
        
        <p>Terima kasih telah menggunakan layanan TemuDokter. Konsultasi Anda telah selesai.</p>

        <div style="background-color: #f9f9f9; padding: 15px; border-radius: 8px; margin: 20px 0;">
            <p style="margin: 0 0 10px;"><strong>Detail Konsultasi:</strong></p>
            <ul style="margin: 0; padding-left: 20px;">
                <li><strong>No. Invoice:</strong> {{ $consultation->invoice_number }}</li>
                <li><strong>Kode Riwayat (Akses Web):</strong> <span style="background: #e2e8f0; padding: 2px 6px; border-radius: 4px; font-weight: bold;">{{ $consultation->history_code }}</span></li>
                <li><strong>Tanggal:</strong> {{ $consultation->created_at->format('d M Y') }}</li>
                <li><strong>Tipe Layanan:</strong> {{ $consultation->type === 'homecare' ? 'Homecare' : 'Konsultasi Online' }}</li>
                <li><strong>Dokter:</strong> {{ $consultation->doctor ? $consultation->doctor->name : '-' }}</li>
            </ul>
        </div>

        @if($consultation->type === 'homecare' && $consultation->homecare_report)
        <div style="background-color: #f0fdfa; padding: 15px; border: 1px solid #ccfbf1; border-radius: 8px; margin: 20px 0;">
            <p style="margin: 0 0 10px; color: #0f766e;"><strong>Laporan Kunjungan Homecare:</strong></p>
            <p style="margin: 0; white-space: pre-wrap; font-size: 14px; color: #0f766e;">{{ $consultation->homecare_report }}</p>
        </div>
        @endif

        <div style="margin: 20px 0;">
            <p style="margin: 0 0 10px;"><strong>Riwayat Pesan:</strong></p>
            <div style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 15px; max-height: 400px; overflow-y: auto; background-color: #ffffff;">
                @forelse($consultation->messages as $msg)
                    @if($msg->sender_type !== 'system')
                        <div style="margin-bottom: 10px;">
                            <strong style="color: {{ $msg->sender_type === 'doctor' ? '#00766c' : '#334155' }};">{{ $msg->sender_type === 'doctor' ? ($consultation->doctor->name ?? 'Dokter') : 'Anda' }}:</strong>
                            <span style="font-size: 14px; color: #475569;">{{ $msg->message }}</span>
                            @if($msg->attachment)
                            <br><span style="font-size: 12px; color: #64748b;">[Terdapat lampiran file]</span>
                            @endif
                        </div>
                    @endif
                @empty
                    <p style="font-size: 14px; color: #64748b; margin: 0;">Tidak ada riwayat pesan.</p>
                @endforelse
            </div>
        </div>

        @if($consultation->prescription || $consultation->sickLeave)
        <p>Dokter Anda telah meresepkan/menerbitkan dokumen medis. Dokumen tersebut (Resep Obat / Surat Sakit) telah kami lampirkan dalam email ini berformat PDF.</p>
        @else
        <p>Tidak ada resep atau dokumen medis tambahan yang dilampirkan oleh dokter pada konsultasi ini.</p>
        @endif

        <br>
        <p>Semoga lekas sembuh!<br>
        <strong>Tim Temu Dokter</strong></p>
    </div>

</body>
</html>
