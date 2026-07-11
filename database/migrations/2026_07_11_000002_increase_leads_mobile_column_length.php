<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('leads')) {
            Schema::table('leads', function (Blueprint $table) {
                $table->string('mobile', 20)->nullable()->change();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('leads')) {
            Schema::table('leads', function (Blueprint $table) {
                $table->string('mobile', 11)->nullable()->change();
            });
        }
    }
};
