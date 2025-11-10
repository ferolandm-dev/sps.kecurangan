<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_access', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('main_menu', 100);
            $table->string('sub_menu', 100)->nullable();
            $table->tinyInteger('can_access')->default(0);
            $table->tinyInteger('can_create')->default(0);
            $table->tinyInteger('can_edit')->default(0);
            $table->tinyInteger('can_delete')->default(0);
            $table->tinyInteger('can_print')->default(0); 
            $table->timestamps();

            // Relasi ke tabel users
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_access');
    }
};
