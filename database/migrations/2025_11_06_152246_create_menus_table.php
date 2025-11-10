<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk membuat tabel menus.
     */
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('main_menu', 100)->nullable(); // menu utama
            $table->string('sub_menu', 100)->nullable();  // sub menu (opsional)
            $table->string('icon', 255)->nullable();      // ikon menu (opsional)
            $table->string('route', 255)->nullable();     // route Laravel (opsional)
            $table->integer('main_order')->default(0);    // urutan antar main menu
            $table->integer('order')->default(0);         // urutan submenu di dalam main menu
            $table->tinyInteger('can_crud')->default(0);  // izin CRUD
            $table->tinyInteger('can_print')->default(0); // izin cetak
            $table->timestamps();

            // 🔒 Unik untuk kombinasi main_menu + order (tiap main_menu tidak boleh punya order sama)
            $table->unique(['main_menu', 'order'], 'main_menu_order_unique');
        });
    }

    /**
     * Hapus tabel menus jika rollback.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};