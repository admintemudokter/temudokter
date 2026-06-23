<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kwitansi - {{ $consultation->invoice_number }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 1cm;
        }
        body {
            font-family: Arial, sans-serif;
            color: #333;
            font-size: 13px;
            margin: 0;
            padding: 0;
        }
        .text-teal { color: #559194; }
        .text-dark-teal { color: #1a4a4d; }
        .font-bold { font-weight: bold; }
        
        /* Layouts */
        .w-full { width: 100%; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .text-center { text-align: center; }
        .align-top { vertical-align: top; }
        
        /* Header */
        table.header {
            width: 100%;
            margin-bottom: 20px;
        }
        .brand-name {
            font-size: 28px;
            font-weight: bold;
            color: #1a4a4d;
            vertical-align: middle;
        }
        .receipt-title {
            font-size: 18px;
            font-weight: bold;
            color: #1a4a4d;
            text-align: right;
            margin-bottom: 20px;
        }
        
        table.info-right {
            width: 100%;
            font-size: 11px;
            font-weight: bold;
        }
        table.info-right td {
            padding: 4px 0;
        }
        .info-label { width: 80px; }
        .info-separator { width: 15px; text-align: center; }
        .info-value { color: #559194; text-align: right; }
        
        .contact-info {
            font-size: 12px;
            margin-top: 15px;
            line-height: 1.6;
            font-weight: bold;
        }
        
        /* Patient info */
        .patient-info {
            margin-bottom: 20px;
            font-size: 12px;
            line-height: 1.6;
            font-weight: bold;
            color: #555;
        }
        .patient-info .title {
            font-weight: normal;
            margin-bottom: 5px;
        }
        .patient-info .name {
            font-size: 13px;
            color: #333;
        }
        
        /* Main Table */
        table.items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.items th {
            background-color: #87b0b0;
            color: #fff;
            padding: 12px 15px;
            text-align: left;
            font-size: 12px;
        }
        table.items th:first-child {
            border-top-left-radius: 8px;
            border-bottom-left-radius: 8px;
        }
        table.items th:last-child {
            border-top-right-radius: 8px;
            border-bottom-right-radius: 8px;
        }
        table.items td {
            padding: 20px 15px;
            vertical-align: top;
            font-weight: bold;
            font-size: 13px;
        }
        
        .totals-table {
            width: 100%;
            margin-top: 15px;
        }
        .totals-table td {
            vertical-align: top;
        }
        .terbilang {
            color: #559194;
            font-style: italic;
            font-weight: bold;
            font-size: 12px;
        }
        .total-bayar-lbl {
            color: #559194;
            font-weight: bold;
            text-align: right;
            padding-right: 15px;
            font-size: 13px;
        }
        .total-bayar-val {
            font-weight: bold;
            font-size: 14px;
            text-align: right;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 11px;
            font-weight: bold;
            color: #559194;
        }
        .footer-section {
            width: 100%;
            margin-top: 50px;
        }
        .footer-left {
            width: 65%;
            font-size: 12px;
            color: #1a4a4d;
            line-height: 1.6;
            vertical-align: middle;
        }
        .footer-right {
            width: 35%;
            vertical-align: middle;
        }
        .verification-box {
            border: 2px solid #559194;
            border-radius: 8px;
            background-color: #f4f7f7;
            padding: 15px;
            position: relative;
            min-height: 80px;
        }
        .verification-title {
            font-weight: bold;
            font-size: 11px;
            color: #1a4a4d;
        }
        .verification-date {
            font-weight: bold;
            font-size: 10px;
            color: #1a4a4d;
        }
        
        .page-bottom-line {
            position: fixed;
            bottom: -1cm;
            left: -1cm;
            right: -1cm;
            height: 40px;
            background-color: #1a4a4d;
        }
    </style>
</head>
<body>
    <div class="page-bottom-line"></div>
    @php
        $imagePath = public_path('images/logo.png');
        $imageData = file_exists($imagePath) ? base64_encode(file_get_contents($imagePath)) : '';
        $src = $imageData ? 'data:image/png;base64,'.$imageData : '';

        // Generate QR Code
        $verifyUrl = route('verify.document', ['type' => 'receipt', 'invoice' => $consultation->invoice_number]);
        $qrCode = base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(80)->margin(0)->generate($verifyUrl));
        $qrSrc = 'data:image/svg+xml;base64,'.$qrCode;

        function terbilang($angka) {
            $angka = abs($angka);
            $baca = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
            $terbilang = "";
            if ($angka < 12) {
                $terbilang = " " . $baca[$angka];
            } else if ($angka < 20) {
                $terbilang = terbilang($angka - 10) . " Belas";
            } else if ($angka < 100) {
                $terbilang = terbilang($angka / 10) . " Puluh " . terbilang($angka % 10);
            } else if ($angka < 200) {
                $terbilang = " Seratus " . terbilang($angka - 100);
            } else if ($angka < 1000) {
                $terbilang = terbilang($angka / 100) . " Ratus " . terbilang($angka % 100);
            } else if ($angka < 2000) {
                $terbilang = " Seribu " . terbilang($angka - 1000);
            } else if ($angka < 1000000) {
                $terbilang = terbilang($angka / 1000) . " Ribu " . terbilang($angka % 1000);
            } else if ($angka < 1000000000) {
                $terbilang = terbilang($angka / 1000000) . " Juta " . terbilang($angka % 1000000);
            }
            return $terbilang;
        }
        $amountWord = trim(terbilang($consultation->price)) . " Rupiah";
        $price = $consultation->price ?: 0;
        
        // Asumsi pajak 11% dari total, atau harga yang ditampilkan adalah total. 
        // Jika harga total adalah X, dan itu sudah termasuk pajak atau tidak ada pajak di sistem, kita bisa asumsikan pajak 0 untuk kesederhanaan,
        // tapi di gambar ada kolom Pajak. Jika tidak ada sistem pajak, kita set 0.
        $tax = 0; 
        $priceBeforeTax = $price - $tax;
    @endphp

    <table class="header align-top">
        <tr>
            <td style="width: 50%;">
                <table>
                    <tr>
                        @if($src)
                        <td style="padding-right: 15px;"><img src="{{ $src }}" alt="Logo" style="height: 45px;"></td>
                        @endif
                        <td class="brand-name">Temu Dokter</td>
                    </tr>
                </table>
            </td>
            <td style="width: 50%; padding-top: 15px;">
                <div class="receipt-title">RECEIPT</div>
                <table class="info-right">
                    <tr>
                        <td class="info-label">No Invoice</td>
                        <td class="info-separator">:</td>
                        <td class="info-value">{{ $consultation->invoice_number }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Tanggal</td>
                        <td class="info-separator">:</td>
                        <td class="info-value">{{ $consultation->created_at->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Pembayaran</td>
                        <td class="info-separator">:</td>
                        <td class="info-value">Transfer / Cashless</td>
                    </tr>
                    <tr>
                        <td class="info-label">Status</td>
                        <td class="info-separator">:</td>
                        <td class="info-value">Lunas</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div class="patient-info">
        <div class="title">Kepada :</div>
        <div class="name">{{ $consultation->patient->full_name }}</div>
        <div>{{ $consultation->patient->whatsapp_number }}</div>
        <div>-</div>
    </div>

    <table class="items">
        <thead>
            <tr>
                <th style="width: 55%;">Nama Produk</th>
                <th style="width: 10%; text-align: center;">Qty</th>
                <th style="width: 15%; text-align: right;">Harga Satuan</th>
                <th style="width: 20%; text-align: right;">Harga Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    Chat Dokter Spesialis :<br>
                    <span style="font-weight: normal; display: inline-block; margin-top: 5px;">dr. {{ $consultation->doctor->name }}</span>
                </td>
                <td style="text-align: center;">1</td>
                <td style="text-align: right;">Rp {{ number_format($price, 0, ',', '.') }}.</td>
                <td style="text-align: right;">Rp {{ number_format($price, 0, ',', '.') }}.</td>
            </tr>
        </tbody>
    </table>

    <table class="totals-table">
        <tr>
            <td style="width: 60%;">
                <div class="terbilang">{{ $amountWord }}</div>
            </td>
            <td style="width: 20%;" class="total-bayar-lbl">
                Total Bayar :
            </td>
            <td style="width: 20%;" class="total-bayar-val">
                Rp {{ number_format($price, 0, ',', '.') }}.
            </td>
        </tr>
    </table>

    <table class="footer-section">
        <tr>
            <td class="footer-left">
                Demikian Surat Keterangan ini Dibuat untuk dapat dipergunakan<br>
                sebagaimana mestinya<br><br><br>
                <b>surat ini sudah diverifikasi secara elektronik,<br>
                tandatangan Petugas tidak diperlukan dan dianggap Sah.</b>
            </td>
            <td class="footer-right">
                <div class="verification-box">
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <td style="vertical-align: top; padding: 0;">
                                <div class="verification-title">Terverifikasi :</div>
                                <div class="verification-date" style="margin-top: 30px;">
                                    {{ $consultation->created_at->format('d/m/Y') }}<br>
                                    {{ $consultation->created_at->format('H:i') }} WIB
                                </div>
                            </td>
                            <td style="vertical-align: top; text-align: right; width: 70px; padding: 0;">
                                <img src="{{ $qrSrc }}" style="width: 65px; height: 65px;" alt="QR Code">
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>
</body>
</html>
