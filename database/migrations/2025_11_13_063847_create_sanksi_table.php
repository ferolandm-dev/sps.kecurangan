<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nama_tabel_kamu', function (Blueprint $table) {

            // Primary Key
            $table->bigIncrements('ID');

            // Columns
            $table->string('JENIS', 100);
            $table->text('KETERANGAN')->nullable();
            $table->decimal('NILAI', 15, 2)->default(0);

            // Timestamps
            $table->timestamp('CREATED_AT')->nullable();
            $table->timestamp('UPDATED_AT')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nama_tabel_kamu');
    }
};