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
        Schema::table('prescription_items', function (Blueprint $table) {
            if (!Schema::hasColumn('prescription_items', 'quantity')) {
                $table->integer('quantity')->default(1);
            }
            if (!Schema::hasColumn('prescription_items', 'instructions')) {
                $table->string('instructions')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
