<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('salesman', function (Blueprint $table) {
            
            $table->char('ID_SALESMAN', 8)->primary();
            $table->char('ID_DISTRIBUTOR', 5);
            $table->integer('ID_USER')->nullable();
            $table->char('ID_JUNIOR_SPV', 11)->nullable();
            $table->char('ID_SPC_MANAGER', 8)->nullable();
            $table->string('NAMA_SALESMAN', 100);
            
            $table->char('TYPE_SALESMAN', 1)->nullable()
                ->comment("1 : SE, 2 : SPU, 3 : MIX, 4 : MT, 5 : CANVASSER 6 : OFFICE, 7 : ASS");

            $table->string('ALAMAT', 200)->nullable();
            $table->string('ALAMAT_DARI_ASS', 200)->nullable();
            $table->string('ALAMAT_GEOLOC', 200)->nullable();

            $table->double('LATITUDE')->nullable();
            $table->double('LONGITUDE')->nullable();

            // relasi ke tabel distributor bila diperlukan (opsional)
            // $table->foreign('ID_DISTRIBUTOR')->references('ID_DISTRIBUTOR')->on('distributor')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salesman');
    }
};