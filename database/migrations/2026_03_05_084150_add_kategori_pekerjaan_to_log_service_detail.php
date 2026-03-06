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
        Schema::table('log_service_detail', function (Blueprint $table) {
            $table->enum('kategori_pekerjaan', [
                'Cuci AC',
                'Perbaikan',
                'Cek AC',
                'Ganti Unit'
            ])->after('acdetail_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('log_service_detail', function (Blueprint $table) {
            $table->dropColumn('kategori_pekerjaan');
        });
    }
};
