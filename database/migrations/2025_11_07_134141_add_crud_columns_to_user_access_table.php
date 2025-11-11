<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_access', function (Blueprint $table) {
            $table->boolean('can_create')->default(false)->after('can_access');
            $table->boolean('can_edit')->default(false)->after('can_create');
            $table->boolean('can_delete')->default(false)->after('can_edit');
        });
    }

    public function down(): void
    {
        Schema::table('user_access', function (Blueprint $table) {
            $table->dropColumn(['can_create', 'can_edit', 'can_delete']);
        });
    }
};
