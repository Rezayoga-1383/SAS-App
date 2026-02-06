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
        Schema::create('log_service_unit', function (Blueprint $table) {
            $table->id();
            $table->foreignId('log_service_id')->constrained('log_service')->cascadeOnDelete();
            $table->foreignId('acdetail_id')->constrained('acdetail')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_service_unit');
    }
};
