<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('consultations', function (Blueprint $table) {
            $table->enum('type', ['telemedicine', 'homecare'])->default('telemedicine');
            $table->text('address')->nullable();
            $table->date('homecare_schedule_date')->nullable();
            $table->time('homecare_schedule_time')->nullable();
            $table->unsignedInteger('price')->nullable();
            $table->text('homecare_report')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('consultations', function (Blueprint $table) {
            $table->dropColumn([
                'type',
                'address',
                'homecare_schedule_date',
                'homecare_schedule_time',
                'price',
                'homecare_report'
            ]);
        });
    }
};
