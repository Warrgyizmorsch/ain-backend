<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add parent_id column to menu table
        if (!Schema::hasColumn('menu', 'parent_id')) {
            Schema::table('menu', function (Blueprint $table) {
                $table->integer('parent_id')->nullable()->after('id');
            });
        }

        // 2. Set parent_id = 43 ("Other") for the existing child menus
        $childMenuIds = [2, 3, 12, 14, 15, 16, 21, 23, 25, 27, 28, 30, 32, 33, 34, 35, 36, 37, 38, 39, 73];
        
        DB::table('menu')
            ->whereIn('id', $childMenuIds)
            ->update(['parent_id' => 44]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Clear parent_id values
        $childMenuIds = [2, 3, 12, 14, 15, 16, 21, 23, 25, 27, 28, 30, 32, 33, 34, 35, 36, 37, 38, 39, 73];
        DB::table('menu')
            ->whereIn('id', $childMenuIds)
            ->update(['parent_id' => null]);

        // 2. Drop parent_id column
        if (Schema::hasColumn('menu', 'parent_id')) {
            Schema::table('menu', function (Blueprint $table) {
                $table->dropColumn('parent_id');
            });
        }
    }
};
