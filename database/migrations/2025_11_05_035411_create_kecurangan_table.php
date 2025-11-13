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

            $table->string('id_sales', 10);
            $table->string('nama_sales', 100);
            $table->string('id_asisten_manager', 10)->nullable();
            $table->string('nama_asisten_manager', 100)->nullable();
            $table->string('distributor', 100);
            $table->string('jenis_sanksi', 100)->nullable();
            $table->text('keterangan_sanksi')->nullable();
            $table->decimal('nilai_sanksi', 15, 2)->nullable()->default(0);
            $table->string('toko', 100);
            $table->string('kunjungan', 100);
            $table->date('tanggal');
            $table->text('keterangan')->nullable();
            $table->string('kuartal')->nullable();
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