<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->cascadeOnDelete();
            $table->foreignId('doctor_id')->nullable()->constrained('doctors')->nullOnDelete();
            $table->string('invoice_number')->unique();
            $table->enum('consultation_status', [
                'waiting_payment',
                'waiting_upload',
                'waiting_admin_confirmation',
                'payment_rejected',
                'waiting_assignment',
                'active',
                'completed',
                'cancelled',
                'expired',
            ])->default('waiting_payment');
            $table->unsignedSmallInteger('duration_minutes')->default(15);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consultations');
    }
};
