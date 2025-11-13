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
        Schema::create('sanksi', function (Blueprint $table) {
            $table->id();
            $table->string('jenis', 100); // contoh: "Teguran", "Denda", dll
            $table->text('keterangan')->nullable(); // deskripsi atau alasan sanksi
            $table->decimal('nilai', 15, 2)->default(0); // nilai dalam rupiah
            $table->timestamps();
        });
    }

    /**
     * Batalkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('sanksi');
    }
};