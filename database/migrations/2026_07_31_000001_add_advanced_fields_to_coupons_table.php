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
        Schema::table('coupons', function (Blueprint $table) {
            if (!Schema::hasColumn('coupons', 'expires_at')) {
                $table->dateTime('expires_at')->nullable()->after('discount_value');
            }
            if (!Schema::hasColumn('coupons', 'min_order_amount')) {
                $table->decimal('min_order_amount', 10, 2)->default(0.00)->after('expires_at');
            }
            if (!Schema::hasColumn('coupons', 'max_discount_amount')) {
                $table->decimal('max_discount_amount', 10, 2)->nullable()->after('min_order_amount');
            }
            if (!Schema::hasColumn('coupons', 'usage_limit_per_user')) {
                $table->unsignedInteger('usage_limit_per_user')->default(1)->after('max_discount_amount');
            }
            if (!Schema::hasColumn('coupons', 'total_usage_limit')) {
                $table->unsignedInteger('total_usage_limit')->nullable()->after('usage_limit_per_user');
            }
            if (!Schema::hasColumn('coupons', 'total_used_count')) {
                $table->unsignedInteger('total_used_count')->default(0)->after('total_usage_limit');
            }
            if (!Schema::hasColumn('coupons', 'description')) {
                $table->text('description')->nullable()->after('total_used_count');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            $columnsToDrop = [];
            foreach (['expires_at', 'min_order_amount', 'max_discount_amount', 'usage_limit_per_user', 'total_usage_limit', 'total_used_count', 'description'] as $col) {
                if (Schema::hasColumn('coupons', $col)) {
                    $columnsToDrop[] = $col;
                }
            }
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
