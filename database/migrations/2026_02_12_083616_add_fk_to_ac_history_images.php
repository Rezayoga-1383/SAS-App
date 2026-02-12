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
            $table->foreign('log_service_unit_id')
                  ->references('id')
                  ->on('log_service_unit')
                  ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ac_history_images', function (Blueprint $table) {
            $table->dropForeign(['log_service_unit_id']);
        });
    }
};
