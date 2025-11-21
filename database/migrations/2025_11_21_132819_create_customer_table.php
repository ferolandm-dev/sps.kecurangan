<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customer', function (Blueprint $table) {

            $table->char('ID_CUST', 12);
            $table->integer('ID_KEC')->nullable();
            $table->integer('ID_KEL');
            $table->integer('ID_POS');
            $table->integer('ID_KEL_NOO')->nullable();
            $table->integer('ID_POS_NOO')->nullable();
            $table->char('ID_PASAR_NOO', 8)->nullable();
            $table->char('ID_TIPE', 2);
            $table->integer('ID_PROV')->nullable();
            $table->integer('ID_KOTA')->nullable();
            $table->char('ID_DISTRIBUTOR', 5);
            $table->char('ID_SALESMAN', 8)->nullable();
            $table->char('ID_JENIS_PL', 5)->nullable();
            $table->string('NAMA_CUST', 100)->nullable();
            $table->string('PASAR', 50)->nullable();
            $table->string('ALAMAT1', 200)->nullable();
            $table->string('ALAMAT2', 100)->nullable();
            $table->string('ALAMAT_TOKO', 200)->nullable();
            $table->string('NO_TELP', 13)->nullable();
            $table->double('LATITUDE')->nullable();
            $table->double('LONGITUDE')->nullable();
            $table->double('ACCURACY')->nullable();
            $table->string('ALAMAT_GEOLOC', 200)->nullable();
            $table->string('ALAMAT_GEOLOC_NOO', 200)->nullable();
            $table->string('AREA', 6)->nullable();
            $table->string('TOP', 10)->nullable();
            $table->string('NIK', 16)->nullable();
            $table->string('NPWP', 16)->nullable();
            $table->char('KODE_MINGGU_HARI', 6)->nullable();
            $table->string('PHOTO_PROFILE', 200)->nullable();
            $table->string('PHOTO_PROFILE2', 200)->nullable();
            $table->string('PHOTO_OWNER1', 200)->nullable();
            $table->string('PHOTO_OWNER2', 200)->nullable();
            $table->string('PHOTO_SIGN', 200)->nullable();
            $table->string('PHOTO_OTHER', 200)->nullable();
            $table->tinyInteger('STATUS')->nullable();
            $table->string('INFO', 250)->nullable();
            $table->char('ID_SALESMAN2', 8)->nullable();
            $table->string('NAMA_PEMILIK', 125)->nullable();
            $table->string('NAMA_KTP', 125)->nullable();
            $table->string('ALAMAT_KTP', 125)->nullable();
            $table->string('NAMA_NPWP', 125)->nullable();
            $table->string('ALAMAT_NPWP', 125)->nullable();
            $table->char('ID_PASAR_DC', 8)->nullable();

            // Karena di SQL tidak ada PRIMARY KEY otomatis → kita set manual
            $table->primary('ID_CUST');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer');
    }
};
