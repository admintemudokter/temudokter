<?php

namespace App\Services;

use App\Models\Consultation;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Support\Str;

class InvoiceService
{
    /**
     * Generate a unique invoice number in format: DK + DDMMYY + 4-digit sequence
     * Example: DK270526001
     */
    public function generate(): string
    {
        $prefix = env('INVOICE_PREFIX', 'DK');
        $datePart = now()->format('dmy'); // e.g., 270526

        // Find highest sequence today
        $todayPattern = $prefix . now()->format('dmy') . '%';
        $lastInvoice = Consultation::withTrashed()
            ->where('invoice_number', 'like', $todayPattern)
            ->orderBy('invoice_number', 'desc')
            ->value('invoice_number');

        if ($lastInvoice) {
            $seq = (int) substr($lastInvoice, -4) + 1;
        } else {
            $seq = 1;
        }

        return $prefix . $datePart . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }
}
