<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('followupcomment', 'status')) {
            Schema::table('followupcomment', function (Blueprint $table) {
                $table->string('status', 191)->nullable()->after('comment');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('followupcomment', 'status')) {
            Schema::table('followupcomment', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
};
