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
            $table->string('before_image')->after('file_spk');
            $table->string('after_image')->after('before_image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('log_service', function (Blueprint $table) {
            $table->dropColumn('before_image');
            $table->dropColumn('after_image');
        });
    }
};
