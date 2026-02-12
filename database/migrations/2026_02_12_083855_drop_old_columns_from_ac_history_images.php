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
            $table->dropForeign('ac_history_image_table__acdetail_id_foreign');
            $table->dropForeign('ac_history_image_table__log_service_id_foreign');
            $table->dropUnique('unique_spk_ac_history');

            $table->dropColumn(['acdetail_id', 'log_service_id']);

            $table->unsignedBigInteger('log_service_unit_id')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ac_history_images', function (Blueprint $table) {
            //
        });
    }
};
