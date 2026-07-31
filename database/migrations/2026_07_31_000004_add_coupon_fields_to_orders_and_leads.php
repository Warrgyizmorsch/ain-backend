<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['orders', 'leads'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                if (!Schema::hasColumn($tableName, 'coupon_code')) {
                    $table->string('coupon_code', 50)->nullable()->index();
                }
                if (!Schema::hasColumn($tableName, 'coupon_discount_type')) {
                    $table->string('coupon_discount_type', 20)->nullable();
                }
                if (!Schema::hasColumn($tableName, 'coupon_discount_value')) {
                    $table->decimal('coupon_discount_value', 10, 2)->nullable();
                }
                if (!Schema::hasColumn($tableName, 'coupon_discount_amount')) {
                    $table->decimal('coupon_discount_amount', 10, 2)->default(0);
                }
                if (!Schema::hasColumn($tableName, 'coupon_original_amount')) {
                    $table->decimal('coupon_original_amount', 10, 2)->nullable();
                }
            });
        }
    }

    public function down(): void
    {
        foreach (['orders', 'leads'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                $columns = array_filter([
                    Schema::hasColumn($tableName, 'coupon_code') ? 'coupon_code' : null,
                    Schema::hasColumn($tableName, 'coupon_discount_type') ? 'coupon_discount_type' : null,
                    Schema::hasColumn($tableName, 'coupon_discount_value') ? 'coupon_discount_value' : null,
                    Schema::hasColumn($tableName, 'coupon_discount_amount') ? 'coupon_discount_amount' : null,
                    Schema::hasColumn($tableName, 'coupon_original_amount') ? 'coupon_original_amount' : null,
                ]);
                if ($columns) {
                    $table->dropColumn($columns);
                }
            });
        }
    }
};
