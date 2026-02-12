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
        Schema::table('ac_history_images', function (Blueprint $table) {
            $table->unsignedBigInteger('log_service_unit_id')->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ac_history_images', function (Blueprint $table) {
            $table->dropColumn('log_service_unit_id');
        });
    }
};
