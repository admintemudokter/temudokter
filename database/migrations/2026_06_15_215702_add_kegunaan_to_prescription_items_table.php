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
            if (!Schema::hasColumn('prescription_items', 'kegunaan')) {
                $table->string('kegunaan')->nullable()->after('medicine_name');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prescription_items', function (Blueprint $table) {
            if (Schema::hasColumn('prescription_items', 'kegunaan')) {
                $table->dropColumn('kegunaan');
            }
        });
    }
};
