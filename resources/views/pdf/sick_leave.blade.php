<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Keterangan Dokter</title>
    <style>
        @page {
            margin: 40px 40px 60px 40px;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            color: #1a3c40;
            font-size: 12px;
            margin: 0;
            padding: 0;
            line-height: 1.4;
        }

        /* Variables */
        :root {
            --dark-teal: #1a4a4d;
            --mid-teal: #87b0b0;
            --light-bg: #eff4f4;
        }

        .dark-text { color: #1a4a4d; font-weight: bold; }
        .mid-bg { background-color: #87b0b0; }
        .light-bg { background-color: #eff4f4; }
        
        .box-container {
            background-color: #eff4f4;
            border-radius: 8px;
            padding: 15px 20px;
            margin-bottom: 20px;
        }

        .section-header {
            background-color: #87b0b0;
            color: #ffffff;
            font-weight: bold;
            font-size: 13px;
            padding: 8px 15px;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            margin-bottom: 0;
            text-transform: uppercase;
        }

        .section-body {
            background-color: #eff4f4;
            border-bottom-left-radius: 8px;
            border-bottom-right-radius: 8px;
            padding: 15px 20px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }
        td {
            vertical-align: bottom;
            padding-bottom: 5px;
            padding-top: 10px;
        }

        .input-line {
            border-bottom: 1.5px solid #87b0b0;
            color: #000;
            font-weight: normal;
        }

        .lbl {
            font-weight: bold;
            color: #1a4a4d;
            font-size: 12px;
        }
        
        .val-text {
            color: #000;
            padding-left: 5px;
            font-weight: normal;
        }

        .title-text {
            text-align: center;
            font-weight: bold;
            color: #1a4a4d;
            font-size: 14px;
            margin: 10px 0 20px 0;
        }

        .paragraph-text {
            font-size: 11px;
            color: #1a4a4d;
            line-height: 1.5;
            margin-bottom: 15px;
            text-align: justify;
        }

        .footer-table {
            margin-top: 30px;
        }

        .verify-box {
            border: 1.5px solid #87b0b0;
            border-radius: 8px;
            background-color: #eff4f4;
            width: 220px;
            height: 120px;
            position: relative;
            float: right;
            padding: 10px;
            box-sizing: border-box;
        }
        
        .qr-placeholder {
            border: 1.5px solid #1a4a4d;
            border-radius: 6px;
            width: 70px;
            height: 70px;
            position: absolute;
            right: 15px;
            top: 25px;
            background-color: #eff4f4;
        }
        
        .clear { clear: both; }

        .bg-bottom {
            position: fixed;
            bottom: -60px;
            left: -40px;
            width: calc(100% + 80px);
            height: 18px;
            background-color: #1a4a4d;
            border-bottom: 4px solid #000000;
            z-index: -100;
        }

    </style>
</head>
<body>

    <div class="bg-bottom"></div>

    @php
        $days = \Carbon\Carbon::parse($sickLeave->start_date)->diffInDays(\Carbon\Carbon::parse($sickLeave->end_date)) + 1;
        $start = \Carbon\Carbon::parse($sickLeave->start_date)->translatedFormat('d F Y');
        $end = \Carbon\Carbon::parse($sickLeave->end_date)->translatedFormat('d F Y');
        
        $verifyUrl = route('verify.document', ['type' => 'sick-leave', 'invoice' => $consultation->invoice_number]);
        $qrCode = base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(60)->margin(0)->generate($verifyUrl));
        $qrSrc = 'data:image/svg+xml;base64,'.$qrCode;
    @endphp

    @php
        $logoPath = public_path('images/logo.png');
        $logoData = file_exists($logoPath) ? base64_encode(file_get_contents($logoPath)) : '';
        $logoSrc = $logoData ? 'data:image/png;base64,'.$logoData : '';
    @endphp

    <!-- NEW HEADER -->
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 10px;">
        <tr>
            <td style="width: 30%; vertical-align: bottom; padding-bottom: 5px;">
                @if($logoSrc)
                    <img src="{{ $logoSrc }}" alt="Logo Temu Dokter" style="width: 140px;">
                @endif
            </td>
            <td style="width: 70%; vertical-align: bottom; padding-bottom: 5px;">
                <div style="text-align: right; color: #1a4a4d; font-weight: bold; font-size: 20px; margin-bottom: 15px;">Temu Dokter</div>
                
                <table style="width: 100%; font-size: 11px; color: #000; border-collapse: collapse;">
                    <tr>
                        <td style="width: 15%;"></td>
                        <td style="text-align: right; width: 40%; font-weight: bold; padding-bottom: 8px;">Nama Dokter :</td>
                        <td style="text-align: left; width: 45%; padding-left: 10px; padding-bottom: 8px; white-space: nowrap;">{{ $consultation->doctor->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td style="text-align: right; font-weight: bold;">No SIP :</td>
                        <td style="text-align: left; padding-left: 10px; white-space: nowrap;">{{ $consultation->doctor->sip_number ?? '-' }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div style="border-bottom: 3px solid #1a4a4d; margin-bottom: 20px;"></div>

    <div style="text-align: center; font-weight: bold; font-size: 18px; color: #1a4a4d; letter-spacing: 1px; margin-bottom: 20px;">
        SURAT KETERANGAN SAKIT
    </div>

    <div class="title-text" style="font-size: 13px;">
        Yang Bertanda Tangan Dibawah ini Menerangkan Bahwa :
    </div>

    <!-- IDENTITAS PASIEN -->
    <div class="section-header">
        IDENTITAS PASIEN
    </div>
    <div class="section-body">
        <table>
            <tr>
                <td style="width: 45%; padding-right: 15px;">
                    <div class="lbl">Nama :</div>
                    <div class="input-line val-text">{{ $consultation->patient->full_name }}</div>
                </td>
                <td style="width: 30%; padding-right: 15px;">
                    <div class="lbl">Tanggal Lahir / Umur :</div>
                    <div class="input-line val-text">{{ \Carbon\Carbon::parse($consultation->patient->date_of_birth)->translatedFormat('d M Y') }} / {{ $consultation->patient->age }} Thn</div>
                </td>
                <td style="width: 25%;">
                    <div class="lbl">Jenis Kelamin :</div>
                    <div class="input-line val-text" style="text-align: center; font-weight: bold; letter-spacing: 2px;">
                        @if(strtolower($consultation->patient->gender) == 'laki-laki') L @else P @endif
                    </div>
                </td>
            </tr>
            <tr>
                <td style="padding-right: 15px; padding-top: 15px;">
                    <div class="lbl">Alamat :</div>
                    <div class="input-line val-text">{{ $consultation->address ?: ($consultation->patient->full_address ?: '-') }}</div>
                </td>
                <td style="padding-right: 15px; padding-top: 15px;">
                    <div class="lbl">Pekerjaan :</div>
                    <div class="input-line val-text">{{ $consultation->patient->occupation ?: '-' }}</div>
                </td>
                <td style="padding-top: 15px;">
                    <div class="lbl">Telp :</div>
                    <div class="input-line val-text">{{ $consultation->patient->whatsapp_number ?: '-' }}</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- KETERANGAN MEDIS -->
    <div class="section-header">
        KETERANGAN MEDIS
    </div>
    <div class="section-body">
        <div class="paragraph-text">
            Berdasarkan hasil pemeriksaan klinis, pasien tersebut dinyatakan memerlukan istirahat karena kondisi kesehatan yang dinilai oleh dokter pemeriksa. Keterangan diagnosis/keluhan dan durasi istirahat diisi sesuai hasil pemeriksaan serta ketentuan klinik yang berlaku.
        </div>
        
        <table>
            <tr>
                <td colspan="3" style="padding-bottom: 15px;">
                    <div class="lbl">Diagnosis / Keluhan</div>
                    <div class="input-line val-text" style="width: 50%;">{{ $sickLeave->reason ?? '-' }}</div>
                </td>
            </tr>
            <tr>
                <td style="width: 33%; padding-right: 15px;">
                    <div class="lbl" style="margin-bottom: 5px;">Perlu istirahat selama</div>
                    <table style="width: 100%;">
                        <tr>
                            <td class="input-line val-text" style="text-align: center; padding-top: 0; padding-bottom: 2px;">{{ $days }}</td>
                            <td style="width: 30px; text-align: right; padding-top: 0; padding-bottom: 2px; font-weight: bold; color: #1a4a4d;">Hari</td>
                        </tr>
                    </table>
                </td>
                <td style="width: 33%; padding-right: 15px;">
                    <div class="lbl" style="margin-bottom: 5px;">Mulai Tanggal</div>
                    <div class="input-line val-text" style="text-align: center;">{{ $start }}</div>
                </td>
                <td style="width: 33%;">
                    <div class="lbl" style="margin-bottom: 5px;">Sampai Tanggal</div>
                    <div class="input-line val-text" style="text-align: center;">{{ $end }}</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Footer -->
    <table class="footer-table">
        <tr>
            <td style="width: 50%; vertical-align: top;">
                <div style="font-size: 11px; color: #1a4a4d; line-height: 1.4; margin-bottom: 40px;">
                    Demikian Surat Keterangan ini Dibuat untuk dapat dipergunakan<br>sebagaimana mestinya
                </div>
                
                <div style="font-size: 10px; color: #1a4a4d; font-weight: bold; line-height: 1.3;">
                    surat ini sudah diverifikasi secara elektronik,<br>
                    tandatangan Petugas tidak diperlukan dan dianggap Sah.
                </div>
            </td>
            <td style="width: 50%; vertical-align: top;">
                <div class="verify-box">
                    <div class="lbl" style="font-size: 11px;">Terverifikasi :</div>
                    
                    <div style="position: absolute; bottom: 10px; left: 10px; font-weight: bold; color: #1a4a4d; font-size: 9px; line-height: 1.3;">
                        {{ now()->translatedFormat('d/m/Y') }}<br>
                        {{ now()->format('H:i') }} WIB
                    </div>
                    
                    <div class="qr-placeholder">
                        <img src="{{ $qrSrc }}" style="width: 60px; height: 60px; margin: 4px;" alt="QR">
                    </div>
                </div>
                <div class="clear"></div>
            </td>
        </tr>
    </table>

</body>
</html>