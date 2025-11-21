<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kecurangan', function (Blueprint $table) {

            // Primary key
            $table->bigIncrements('ID');

            // Columns
            $table->string('ID_SALES', 10);
            $table->string('ID_ASS', 10);
            $table->char('ID_SPC_MANAGER', 8)->nullable();
            $table->string('DISTRIBUTOR', 100);

            $table->string('JENIS_SANKSI', 100)->nullable();
            $table->text('KETERANGAN_SANKSI')->nullable();
            $table->decimal('NILAI_SANKSI', 15, 2)->default(0);

            $table->string('TOKO', 100);
            $table->string('KUNJUNGAN', 100);
            $table->date('TANGGAL');

            $table->text('KETERANGAN')->nullable();
            $table->string('KUARTAL', 255)->nullable();

            $table->tinyInteger('VALIDASI')->default(0);

            // timestamps
            $table->timestamp('CREATED_AT')->nullable();
            $table->timestamp('UPDATED_AT')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kecurangan');
    }
};
