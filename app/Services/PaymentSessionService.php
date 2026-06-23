<?php

namespace App\Services;

use App\Models\PaymentSession;
use App\Models\Transaction;
use Carbon\Carbon;

class PaymentSessionService
{
    /**
     * Create a new payment session (QRIS / VA / E-wallet simulation).
     */
    public function create(Transaction $transaction): PaymentSession
    {
        // Expire any existing active sessions
        $transaction->paymentSessions()
            ->where('status', 'active')
            ->update(['status' => 'expired']);

        $expiryMinutes = (int) env('PAYMENT_SESSION_MINUTES', 60);
        $simulatedNumber = $this->generateSimulatedNumber($transaction);

        return $transaction->paymentSessions()->create([
            'method'           => $transaction->payment_method,
            'provider'         => $transaction->payment_provider,
            'simulated_number' => $simulatedNumber,
            'expires_at'       => now()->addMinutes($expiryMinutes),
            'status'           => 'active',
        ]);
    }

    /**
     * Refresh / regenerate a QRIS session.
     */
    public function refresh(Transaction $transaction): PaymentSession
    {
        return $this->create($transaction);
    }

    /**
     * Mark a session as used.
     */
    public function markUsed(PaymentSession $session): void
    {
        $session->update(['status' => 'used']);
    }

    private function generateSimulatedNumber(Transaction $transaction): string
    {
        return match ($transaction->payment_method) {
            'virtual_account' => $this->generateVA($transaction->payment_provider),
            'qris'            => 'QRIS-' . strtoupper(substr(md5(uniqid()), 0, 8)),
            'ewallet'         => 'EW-' . strtoupper(substr(md5(uniqid()), 0, 10)),
            default           => strtoupper(substr(md5(uniqid()), 0, 12)),
        };
    }

    private function generateVA(string $provider): string
    {
        $prefixes = [
            'BCA'        => '8808',
            'Mandiri'    => '8886',
            'BRI'        => '8835',
            'BTN'        => '8868',
            'CIMB Niaga' => '8855',
            'BNI'        => '8820',
            'Permata'    => '8877',
        ];

        $prefix = $prefixes[$provider] ?? '8800';
        return $prefix . str_pad(rand(10000000, 99999999), 8, '0');
    }
}
