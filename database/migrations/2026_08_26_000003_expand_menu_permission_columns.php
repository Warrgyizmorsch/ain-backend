<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE permission MODIFY menu_id LONGTEXT NULL');
        DB::statement('ALTER TABLE permission MODIFY submenu_id LONGTEXT NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE permission MODIFY menu_id VARCHAR(255) NULL');
        DB::statement('ALTER TABLE permission MODIFY submenu_id VARCHAR(255) NULL');
    }
};
