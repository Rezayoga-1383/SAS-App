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
        Schema::table('log_service_image', function (Blueprint $table) {
            $table->dropColumn(['kondisi', 'posisi']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('log_service_image', function (Blueprint $table){
            $table->enum('kondisi', ['before', 'after']);
            $table->enum('posisi', ['indoor', 'outdoor', 'all"']);
        });
    }
};
