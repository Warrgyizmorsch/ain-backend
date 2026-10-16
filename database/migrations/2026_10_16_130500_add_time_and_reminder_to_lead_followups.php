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
        if (Schema::hasTable('lead_followups')) {
            Schema::table('lead_followups', function (Blueprint $table) {
                if (!Schema::hasColumn('lead_followups', 'followup_time')) {
                    $table->time('followup_time')->nullable()->after('followup_date');
                }
                if (!Schema::hasColumn('lead_followups', 'reminded_at')) {
                    $table->timestamp('reminded_at')->nullable()->after('status');
                }
                if (!Schema::hasColumn('lead_followups', 'snoozed_until')) {
                    $table->timestamp('snoozed_until')->nullable()->after('reminded_at');
                }
            });
        }

        if (Schema::hasTable('leads')) {
            Schema::table('leads', function (Blueprint $table) {
                if (!Schema::hasColumn('leads', 'next_followup_time')) {
                    $table->time('next_followup_time')->nullable()->after('next_followup_date');
                }
            });
        }

        if (Schema::hasTable('primeassiment')) {
            Schema::table('primeassiment', function (Blueprint $table) {
                if (!Schema::hasColumn('primeassiment', 'next_followup_time')) {
                    $table->time('next_followup_time')->nullable()->after('next_followup_date');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('lead_followups')) {
            Schema::table('lead_followups', function (Blueprint $table) {
                $columns = [];
                if (Schema::hasColumn('lead_followups', 'followup_time')) $columns[] = 'followup_time';
                if (Schema::hasColumn('lead_followups', 'reminded_at')) $columns[] = 'reminded_at';
                if (Schema::hasColumn('lead_followups', 'snoozed_until')) $columns[] = 'snoozed_until';
                if (!empty($columns)) {
                    $table->dropColumn($columns);
                }
            });
        }

        if (Schema::hasTable('leads')) {
            Schema::table('leads', function (Blueprint $table) {
                if (Schema::hasColumn('leads', 'next_followup_time')) {
                    $table->dropColumn('next_followup_time');
                }
            });
        }

        if (Schema::hasTable('primeassiment')) {
            Schema::table('primeassiment', function (Blueprint $table) {
                if (Schema::hasColumn('primeassiment', 'next_followup_time')) {
                    $table->dropColumn('next_followup_time');
                }
            });
        }
    }
};
