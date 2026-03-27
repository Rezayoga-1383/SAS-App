<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE pengguna MODIFY role ENUM('Superadmin', 'Admin', 'Teknisi') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE pengguna MODIFY role ENUM('Admin', 'Teknisi') NOT NULL");
    }
};
