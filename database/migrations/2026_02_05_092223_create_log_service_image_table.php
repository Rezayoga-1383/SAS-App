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
        Schema::create('log_service_image', function (Blueprint $table) {
            $table->id();
            $table->foreignId('log_service_unit_id')->constrained('log_service_unit')->cascadeonDelete();
            $table->enum('kondisi', ['before', 'after']);
            $table->enum('posisi', ['indoor', 'outdoor', 'all'])->default('all');
            $table->string('image_path');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_service_image');
    }
};
