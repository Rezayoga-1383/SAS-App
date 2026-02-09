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
        Schema::rename('ac_history_image_table_', 'ac_history_images');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('ac_history_images', 'ac_history_image_table_');
    }
};
