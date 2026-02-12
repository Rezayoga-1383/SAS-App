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
        DB::statement("
            UPDATE ac_history_images h
            JOIN log_service_unit u 
              ON h.log_service_id = u.log_service_id 
             AND h.acdetail_id = u.acdetail_id
            SET h.log_service_unit_id = u.id
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("
            UPDATE ac_history_images 
            SET log_service_unit_id = NULL
        ");
    }
};
