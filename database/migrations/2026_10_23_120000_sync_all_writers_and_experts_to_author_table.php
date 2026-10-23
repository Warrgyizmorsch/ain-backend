<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Services\AuthorService;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('author', function (Blueprint $table) {
            if (!Schema::hasColumn('author', 'author_type')) {
                $table->string('author_type', 50)->default('author')->after('description');
            }
            if (!Schema::hasColumn('author', 'expert_id')) {
                $table->integer('expert_id')->nullable()->after('author_type');
            }
            if (!Schema::hasColumn('author', 'user_id')) {
                $table->integer('user_id')->nullable()->after('expert_id');
            }
        });

        // Run full sync to populate all experts and writers into author table
        AuthorService::syncAll();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('author', function (Blueprint $table) {
            if (Schema::hasColumn('author', 'user_id')) {
                $table->dropColumn('user_id');
            }
            if (Schema::hasColumn('author', 'expert_id')) {
                $table->dropColumn('expert_id');
            }
            if (Schema::hasColumn('author', 'author_type')) {
                $table->dropColumn('author_type');
            }
        });

        AuthorService::clearCache();
    }
};
