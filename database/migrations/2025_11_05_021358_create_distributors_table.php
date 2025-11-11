<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('distributors', function (Blueprint $table) {
            $table->string('id', 12)->primary();
            $table->string('distributor');
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamp('nonaktif_at')->nullable()->after('status');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('distributors');
    }
};
