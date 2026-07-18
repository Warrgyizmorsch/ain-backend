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
        if (Schema::hasTable('payment_details')) {
            Schema::table('payment_details', function (Blueprint $table) {
                if (!Schema::hasColumn('payment_details', 'screenshot')) {
                    $table->string('screenshot')->nullable()->after('company_accounts');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('payment_details')) {
            Schema::table('payment_details', function (Blueprint $table) {
                if (Schema::hasColumn('payment_details', 'screenshot')) {
                    $table->dropColumn('screenshot');
                }
            });
        }
    }
};
