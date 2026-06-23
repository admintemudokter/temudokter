<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consultation_id')->constrained('consultations')->cascadeOnDelete();
            $table->string('invoice_number');
            $table->enum('payment_method', ['qris', 'virtual_account', 'ewallet']);
            $table->string('payment_provider'); // BCA, GoPay, etc.
            $table->unsignedInteger('amount');
            $table->enum('payment_status', [
                'pending_payment',
                'waiting_upload',
                'waiting_admin_confirmation',
                'approved',
                'rejected',
                'request_reupload',
                'expired',
            ])->default('pending_payment');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
