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
        Schema::create('acdetail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_merkac');
            $table->unsignedBigInteger('id_jenisac');
            $table->unsignedBigInteger('id_ruangan');
            $table->string('no_ac', 30);
            $table->string('no_seri_indoor', 50);
            $table->string('no_seri_outdoor', 50);
            $table->decimal('pk_ac', 5, 1);
            $table->integer('jumlah_ac');
            $table->year('tahun_ac');
            $table->date('tanggal_pemasangan');
            $table->date('tanggal_habis_garansi');
            $table->timestamps();

            $table->foreign('id_merkac')->references('id')->on('merkac');
            $table->foreign('id_jenisac')->references('id')->on('jenisac');
            $table->foreign('id_ruangan')->references('id')->on('ruangan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acdetail');
    }
};
