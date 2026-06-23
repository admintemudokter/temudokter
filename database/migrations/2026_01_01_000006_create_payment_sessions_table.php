<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained('transactions')->cascadeOnDelete();
            $table->enum('method', ['qris', 'virtual_account', 'ewallet']);
            $table->string('provider');
            $table->string('simulated_number')->nullable(); // VA number or QRIS ref
            $table->timestamp('expires_at');
            $table->enum('status', ['active', 'expired', 'used'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_sessions');
    }
};
