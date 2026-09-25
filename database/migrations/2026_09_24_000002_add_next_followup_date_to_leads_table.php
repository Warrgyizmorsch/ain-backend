<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('leads') && !Schema::hasColumn('leads', 'next_followup_date')) {
            Schema::table('leads', function (Blueprint $table) {
                $table->date('next_followup_date')->nullable()->index()->after('lead_status');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('leads') && Schema::hasColumn('leads', 'next_followup_date')) {
            Schema::table('leads', function (Blueprint $table) {
                $table->dropColumn('next_followup_date');
            });
        }
    }
};
