<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::create('kecurangan_foto', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel kecurangan
            $table->unsignedBigInteger('id_kecurangan');

            // Path atau lokasi file di storage
            $table->string('path', 255);

            // Informasi tambahan (opsional)
            $table->string('nama_file', 150)->nullable();

            $table->timestamps();

            // Foreign key
            $table->foreign('id_kecurangan')
                  ->references('id')
                  ->on('kecurangan')
                  ->onDelete('cascade');
        });
    }

    /**
     * Batalkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('kecurangan_foto');
    }
};