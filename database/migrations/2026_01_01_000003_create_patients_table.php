<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('whatsapp_number');
            $table->unsignedTinyInteger('age');
            $table->enum('gender', ['laki-laki', 'perempuan']);
            $table->string('bekasi_area');
            $table->text('complaint_description');
            $table->string('medical_image')->nullable();
            $table->string('medical_document')->nullable();
            $table->string('session_token')->unique()->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
