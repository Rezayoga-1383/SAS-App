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
        Schema::create('ac_history_image_table_', function (Blueprint $table) {
            $table->id();
            $table->foreignId('acdetail_id')->constrained('acdetail')->cascadeOnDelete();
            $table->foreignId('log_service_id')->constrained('log_service')->cascadeOnDelete();
            $table->string('image_path');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ac_history_image_table_');
    }
};
