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
        Schema::create('specialist_manager', function (Blueprint $table) {
            $table->char('ID_SPC_MANAGER', 8)->primary();
            $table->integer('ID_USER')->nullable();
            $table->string('NAMA', 100);
            $table->char('ID_HEAD_SPCM', 8)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('specialist_manager');
    }
};