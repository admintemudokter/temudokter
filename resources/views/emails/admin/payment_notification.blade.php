<x-mail::message>
# Bukti Pembayaran Baru

Halo Admin,

Terdapat pasien yang baru saja mengunggah bukti pembayaran. Berikut rincian transaksinya:

- **No. Invoice:** {{ $consultation->invoice_number }}
- **Nama Pasien:** {{ $consultation->patient->full_name ?? '-' }}
- **Layanan:** {{ $consultation->type === 'homecare' ? 'Homecare' : 'Konsultasi Online' }}
- **Total Pembayaran:** Rp {{ number_format($consultation->price ?? 150000, 0, ',', '.') }}

Silakan login ke panel admin untuk memverifikasi bukti pembayaran tersebut.

<x-mail::button :url="route('admin.payment.index')">
Cek Pembayaran
</x-mail::button>

Terima kasih,<br>
Sistem TemuDokter
</x-mail::message>
