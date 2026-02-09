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
            $table->unique(['log_service_id', 'acdetail_id'], 'unique_spk_ac_history');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ac_history_images', function (Blueprint $table) {
            $table->dropUnique('unique_spk_ac_history');
        });
    }
};
