<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Delete the default service page and subject
        DB::table('service_pages')->where('slug', 'engineering-assignment-writing-help')->delete();
        DB::table('subjects')->where('slug', 'engineering')->delete();
    }

    public function down(): void
    {
        // Nothing to rollback
    }
};
