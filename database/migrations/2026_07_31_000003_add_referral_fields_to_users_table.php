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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'refer_id')) {
                $table->unsignedBigInteger('refer_id')->nullable()->after('team_id');
                $table->index('refer_id');
            }
            if (!Schema::hasColumn('users', 'referral_code')) {
                $table->string('referral_code', 30)->unique()->nullable()->after('refer_id');
            }
            if (!Schema::hasColumn('users', 'total_referral_earnings')) {
                $table->decimal('total_referral_earnings', 10, 2)->default(0.00)->after('referral_code');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = [];
            if (Schema::hasColumn('users', 'refer_id')) $columns[] = 'refer_id';
            if (Schema::hasColumn('users', 'referral_code')) $columns[] = 'referral_code';
            if (Schema::hasColumn('users', 'total_referral_earnings')) $columns[] = 'total_referral_earnings';

            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
