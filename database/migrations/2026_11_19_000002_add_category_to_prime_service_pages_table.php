<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('prime_service_pages') && !Schema::hasColumn('prime_service_pages', 'category')) {
            Schema::table('prime_service_pages', function (Blueprint $table) {
                $table->string('category', 120)->nullable()->after('type')->index();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('prime_service_pages') && Schema::hasColumn('prime_service_pages', 'category')) {
            Schema::table('prime_service_pages', function (Blueprint $table) {
                $table->dropIndex(['category']);
                $table->dropColumn('category');
            });
        }
    }
};
