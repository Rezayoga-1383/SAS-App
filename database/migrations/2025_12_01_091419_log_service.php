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
        Schema::create('log_service', function (Blueprint $table) {
            $table->id();

            $table->string('no_spk', 10);
            $table->date('tanggal');
            $table->time('waktu_mulai');
            $table->time('waktu_selesai');
            $table->integer('jumlah_orang');

            $table->string('kepada', 100);
            $table->string('mengetahui', 100);
            $table->string('hormat_kami', 100);

            $table->unsignedBigInteger('pelaksana_ttd')->nullable();
            $table->string('file_spk');
            
            $table->timestamps();

            $table->foreign('pelaksana_ttd')->references('id')->on('pengguna')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_service');
    }
};
