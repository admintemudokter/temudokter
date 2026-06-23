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
        Schema::table('doctors', function (Blueprint $table) {
            $table->integer('experience_years')->default(0)->after('specialization');
            $table->string('practice_location')->nullable()->after('experience_years');
            $table->string('education')->nullable()->after('practice_location');
            $table->string('str_number')->nullable()->after('education');
            $table->string('sip_number')->nullable()->after('str_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->dropColumn([
                'experience_years',
                'practice_location',
                'education',
                'str_number',
                'sip_number'
            ]);
        });
    }
};
