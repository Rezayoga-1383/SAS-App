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
        Schema::create('log_service_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('log_service_id');
            $table->unsignedBigInteger('acdetail_id');

            $table->text('keluhan');
            $table->text('jenis_pekerjaan');
            
            $table->timestamps();

            $table->foreign('log_service_id')->references('id')->on('log_service')->onDelete('cascade');
            $table->foreign('acdetail_id')->references('id')->on('acdetail')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_service_detail');
    }
};
