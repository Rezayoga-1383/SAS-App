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
        Schema::table('log_service', function (Blueprint $table) {
            $table->text('catatan_spk')->nullable()->after('keterangan_spk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('log_service', function (Blueprint $table) {
            $table->dropColumn('catatan_spk');
        });
    }
};
