<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('users') && !Schema::hasColumn('users', 'sip_password')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('sip_password', 255)->nullable()->after('sip');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'sip_password')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('sip_password');
            });
        }
    }
};
