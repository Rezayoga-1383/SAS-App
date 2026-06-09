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
        Schema::create('absensi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengguna_id')->constrained('pengguna')->onDelete('cascade');
            $table->enum('jenis', ['Hadir', 'Pulang', 'Izin', 'Sakit']);
            $table->time('waktu');
            $table->date('tanggal');
            $table->decimal('latitude', 10, 6);
            $table->decimal('longitude', 10, 6);
            $table->text('alamat');
            $table->string('foto');
            $table->text('catatan')->nullable();
            $table->enum('status', ['Tepat Waktu', 'Terlambat'])->default('Tepat Waktu');
            $table->integer('menit_terlambat')->default(0);
            $table->timestamps();

            // Index
            $table->index(['pengguna_id', 'tanggal']);
            $table->index(['tanggal', 'jenis']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi');
    }
};
