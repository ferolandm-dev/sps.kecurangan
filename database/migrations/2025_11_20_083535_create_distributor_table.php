<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('distributor', function (Blueprint $table) {

            // Primary Key
            $table->char('ID_DISTRIBUTOR', 5)->primary();

            // Columns
            $table->integer('ID_KOTA')->nullable();
            $table->char('ID_REGION', 2)->nullable();
            $table->char('ID_SPV', 8)->nullable();
            $table->char('ID_LOGISTIC', 8)->nullable();
            $table->integer('ID_PROV')->nullable();
            $table->string('NAMA_DISTRIBUTOR', 50);

            // Coordinates
            $table->double('LATITUDE_DIST')->nullable();
            $table->double('LONGITUDE_DIST')->nullable();
            $table->double('ACCURACY_DIST')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('distributor');
    }
};
