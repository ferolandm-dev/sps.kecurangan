<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('kecurangan', function (Blueprint $table) {
            $table->id();
            $table->string('id_sales', 10);
            $table->string('nama_sales', 100);
            $table->string('distributor', 100);
            $table->string('toko', 100);
            $table->string('kunjungan', 100);
            $table->date('tanggal');
            $table->text('keterangan')->nullable();
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