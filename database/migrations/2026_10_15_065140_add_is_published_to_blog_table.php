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
        Schema::table('blog', function (Blueprint $table) {
            if (!Schema::hasColumn('blog', 'is_published')) {
                $table->boolean('is_published')->default(true)->after('type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blog', function (Blueprint $table) {
            if (Schema::hasColumn('blog', 'is_published')) {
                $table->dropColumn('is_published');
            }
        });
    }
};
