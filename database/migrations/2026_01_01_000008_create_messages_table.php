<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consultation_id')->constrained('consultations')->cascadeOnDelete();
            $table->enum('sender_type', ['patient', 'doctor', 'admin', 'system']);
            $table->unsignedBigInteger('sender_id')->nullable(); // null for system/patient
            $table->text('message')->nullable();
            $table->string('attachment')->nullable();
            $table->enum('attachment_type', ['image', 'pdf', 'none'])->default('none');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
