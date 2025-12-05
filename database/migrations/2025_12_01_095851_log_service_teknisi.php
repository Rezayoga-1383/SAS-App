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
        Schema::create('log_service_teknisi', function (Blueprint $table) {
            $table->id();
    
            $table->unsignedBigInteger('log_service_id');
            $table->unsignedBigInteger('id_pengguna');
    
            $table->foreign('log_service_id')->references('id')->on('log_service')->onDelete('cascade');
            $table->foreign('id_pengguna')->references('id')->on('pengguna')->onDelete('cascade');
    
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_service_teknisi');
    }
};
