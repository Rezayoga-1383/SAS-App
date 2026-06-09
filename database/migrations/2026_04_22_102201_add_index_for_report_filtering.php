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
            $table->index('tanggal', 'idx_log_service_tanggal');
        });

        Schema::table('log_service_detail', function (Blueprint $table) {
            $table->index('kategori_pekerjaan', 'idx_lsd_kategori');

            $table->index(
                ['kategori_pekerjaan', 'acdetail_id'],
                'idx_lsd_kategori_acdetail'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('log_service', function (Blueprint $table) {
            $table->dropIndex('idx_log_service_tanggal');
        });

        Schema::table('log_service_detail', function (Blueprint $table) {
            $table->dropIndex('idx_lsd_kategori');
            $table->dropIndex('idx_lsd_kategori_acdetail');
        });
    }
};
