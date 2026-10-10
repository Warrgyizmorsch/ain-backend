<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'first_failed_at')) {
            Schema::table('users', function (Blueprint $table) {
                $table->timestamp('first_failed_at')->nullable()->after('refer_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'first_failed_at')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('first_failed_at');
            });
        }
    }
};
