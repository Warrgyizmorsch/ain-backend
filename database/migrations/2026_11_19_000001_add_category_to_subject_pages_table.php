<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('subject_pages') && !Schema::hasColumn('subject_pages', 'category')) {
            Schema::table('subject_pages', function (Blueprint $table) {
                $table->string('category', 120)->nullable()->after('subject_id')->index();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('subject_pages') && Schema::hasColumn('subject_pages', 'category')) {
            Schema::table('subject_pages', function (Blueprint $table) {
                $table->dropIndex(['category']);
                $table->dropColumn('category');
            });
        }
    }
};
