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
        Schema::create('kecurangan', function (Blueprint $table) {
            $table->id();

            // Data Sales
            $table->string('id_sales', 10);
            $table->string('nama_sales', 100);

            // Data Asisten Manager (opsional)
            $table->string('id_asisten_manager', 10)->nullable();
            $table->string('nama_asisten_manager', 100)->nullable();

            // Data lainnya
            $table->string('distributor', 100);
            $table->string('toko', 100);
            $table->string('kunjungan', 100);
            $table->date('tanggal');
            $table->text('keterangan')->nullable();
            $table->string('kuartal')->nullable();

            // Status Validasi
            $table->boolean('validasi')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kecurangan');
    }
};