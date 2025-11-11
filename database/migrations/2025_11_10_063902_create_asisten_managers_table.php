<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asisten_managers', function (Blueprint $table) {
            $table->string('id', 12)->primary();
            $table->string('nama');
            $table->string('id_distributor', 6);
            $table->enum('status', ['Aktif', 'Nonaktif'])->default('Aktif');
            $table->timestamps();

            // Relasi ke tabel distributors
            $table->foreign('id_distributor')
                ->references('id')
                ->on('distributors')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asisten_managers');
    }
};