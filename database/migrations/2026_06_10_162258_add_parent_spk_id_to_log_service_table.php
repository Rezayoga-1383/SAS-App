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
        Schema::table('log_service', function (Blueprint $table) {
            
            $table->unsignedBigInteger('parent_spk_id')
                ->nullable()
                ->after('status');
            
            $table->index('parent_spk_id');

            $table->foreign('parent_spk_id')
                ->references('id')
                ->on('log_service')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('log_service', function (Blueprint $table) {
            
            $table->dropForeign(['parent_spk_id']);

            $table->dropIndex(['parent_spk_id']);

            $table->dropColumn('parent_spk_id');
        });
    }
};
