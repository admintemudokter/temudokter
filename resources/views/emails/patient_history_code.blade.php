<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kode Akses Riwayat Medis Anda - Temu Dokter</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f4f4f5; margin: 0; padding: 20px; color: #334155; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        .header { background-color: #0f766e; padding: 30px 20px; text-align: center; color: #ffffff; }
        .header h1 { margin: 0; font-size: 24px; font-weight: 700; }
        .content { padding: 30px 40px; }
        .content p { line-height: 1.6; margin-bottom: 20px; }
        .code-box { background-color: #e0e7ff; border: 2px dashed #6366f1; border-radius: 8px; padding: 20px; text-align: center; margin: 30px 0; }
        .code-title { font-size: 12px; font-weight: bold; color: #4338ca; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 10px; }
        .code-value { font-size: 32px; font-weight: 900; color: #312e81; letter-spacing: 4px; font-family: monospace; }
        .footer { background-color: #f8fafc; padding: 20px; text-align: center; font-size: 12px; color: #94a3b8; border-top: 1px solid #f1f5f9; }
        .button { display: inline-block; padding: 12px 24px; background-color: #0f766e; color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: bold; margin-top: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Konsultasi Selesai!</h1>
        </div>
        <div class="content">
            <p>Halo <strong>{{ $consultation->patient->full_name }}</strong>,</p>
            <p>Terima kasih telah menggunakan layanan Temu Dokter. Konsultasi medis Anda dengan <strong>{{ $consultation->doctor->name }}</strong> (Invoice: {{ $consultation->invoice_number }}) telah selesai.</p>
            
            <p>Berikut adalah kode unik untuk mengakses riwayat percakapan, resep obat, dan surat medis Anda di kemudian hari:</p>
            
            <div class="code-box">
                <div class="code-title">KODE RIWAYAT ANDA</div>
                <div class="code-value">{{ $consultation->patient->session_token }}</div>
            </div>
            
            <p><strong>Penting:</strong> Simpan kode ini baik-baik. Anda dapat menggunakan kode tersebut di halaman <em>Cek Riwayat</em> pada website kami kapan saja Anda membutuhkannya.</p>
            
            <div style="text-align: center;">
                <a href="{{ url('/') }}" class="button">Kunjungi Website</a>
            </div>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Temu Dokter. Semua Hak Dilindungi.<br>
            Pesan ini dikirim secara otomatis oleh sistem. Mohon tidak membalas email ini.
        </div>
    </div>
</body>
</html>
