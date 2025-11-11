<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
<<<<<<< HEAD
    public function up()
    {
        Schema::create('kecurangan', function (Blueprint $table) {
            $table->id();
            $table->string('id_sales', 10);
            $table->string('nama_sales', 100);
=======
    public function up(): void
    {
        Schema::create('kecurangan', function (Blueprint $table) {
            $table->id();

            $table->string('id_sales', 10);
            $table->string('nama_sales', 100);

            // 🔹 Tambahan baru: Asisten Manager
            $table->string('id_asisten_manager', 10)->nullable()->after('nama_sales');
            $table->string('nama_asisten_manager', 100)->nullable()->after('id_asisten_manager');

>>>>>>> recovery-branch
            $table->string('distributor', 100);
            $table->string('toko', 100);
            $table->string('kunjungan', 100);
            $table->date('tanggal');
            $table->text('keterangan')->nullable();
<<<<<<< HEAD
            $table->timestamps();
        });
    }


=======
            $table->string('kuartal')->nullable();
            $table->timestamps();

            // 🔹 Tambahan kolom validasi (default 0 = belum divalidasi)
            $table->boolean('validasi')->default(false);
        });
    }

>>>>>>> recovery-branch
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kecurangan');
    }
};