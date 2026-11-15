<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            ALTER TABLE log_service_detail
            MODIFY kategori_pekerjaan ENUM(
                'Cuci AC',
                'Perbaikan',
                'Cek AC',
                'Ganti Unit',
                'Survey',
                'Bongkar Pasang',
                'Lanjutan Pekerjaan'
            ) NOT NULL
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("
            ALTER TABLE log_service_detail
            MODIFY kategori_pekerjaan ENUM(
                'Cuci AC',
                'Perbaikan',
                'Cek AC',
                'Ganti Unit',
                'Bongkar Pasang',
                'Lanjutan Pekerjaan'
            ) NOT NULL
        ");
    }
};
