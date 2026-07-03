<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Delete the default service page and subject
        if (Schema::hasTable('service_pages')) {
            DB::table('service_pages')->where('slug', 'engineering-assignment-writing-help')->delete();
        }
        if (Schema::hasTable('subjects')) {
            DB::table('subjects')->where('slug', 'engineering')->delete();
        }
    }

    public function down(): void
    {
        // Nothing to rollback
    }
};
