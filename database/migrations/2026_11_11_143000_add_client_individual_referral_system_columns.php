<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (! Schema::hasColumn('users', 'referral_discount_percent')) {
                    $col = $table->decimal('referral_discount_percent', 5, 2)->default(10.00);
                    if (Schema::hasColumn('users', 'referral_code')) {
                        $col->after('referral_code');
                    }
                }

                if (! Schema::hasColumn('users', 'referral_executive_id')) {
                    $col = $table->integer('referral_executive_id')->nullable();
                    if (Schema::hasColumn('users', 'referral_discount_percent')) {
                        $col->after('referral_discount_percent');
                    }
                    $table->index('referral_executive_id', 'users_referral_executive_id_idx');
                }

                if (! Schema::hasColumn('users', 'referral_prefix')) {
                    $col = $table->string('referral_prefix', 6)->nullable();
                    if (Schema::hasColumn('users', 'referral_executive_id')) {
                        $col->after('referral_executive_id');
                    }
                    $table->index('referral_prefix', 'users_referral_prefix_idx');
                }

                if (! Schema::hasColumn('users', 'referral_code_generated_at')) {
                    $col = $table->timestamp('referral_code_generated_at')->nullable();
                    if (Schema::hasColumn('users', 'referral_prefix')) {
                        $col->after('referral_prefix');
                    }
                }

                if (! Schema::hasColumn('users', 'referral_updated_by')) {
                    $col = $table->integer('referral_updated_by')->nullable();
                    if (Schema::hasColumn('users', 'referral_code_generated_at')) {
                        $col->after('referral_code_generated_at');
                    }
                }
            });
        }

        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                if (! Schema::hasColumn('orders', 'referred_by_user_id')) {
                    $col = $table->integer('referred_by_user_id')->nullable();
                    if (Schema::hasColumn('orders', 'coupon_original_amount')) {
                        $col->after('coupon_original_amount');
                    }
                    $table->index('referred_by_user_id', 'orders_referred_by_user_id_idx');
                }

                if (! Schema::hasColumn('orders', 'referral_bonus_awarded_at')) {
                    $col = $table->timestamp('referral_bonus_awarded_at')->nullable();
                    if (Schema::hasColumn('orders', 'referred_by_user_id')) {
                        $col->after('referred_by_user_id');
                    }
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                if (Schema::hasColumn('orders', 'referral_bonus_awarded_at')) {
                    $table->dropColumn('referral_bonus_awarded_at');
                }
                if (Schema::hasColumn('orders', 'referred_by_user_id')) {
                    $table->dropIndex('orders_referred_by_user_id_idx');
                    $table->dropColumn('referred_by_user_id');
                }
            });
        }

        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (Schema::hasColumn('users', 'referral_updated_by')) {
                    $table->dropColumn('referral_updated_by');
                }
                if (Schema::hasColumn('users', 'referral_code_generated_at')) {
                    $table->dropColumn('referral_code_generated_at');
                }
                if (Schema::hasColumn('users', 'referral_prefix')) {
                    $table->dropIndex('users_referral_prefix_idx');
                    $table->dropColumn('referral_prefix');
                }
                if (Schema::hasColumn('users', 'referral_executive_id')) {
                    $table->dropIndex('users_referral_executive_id_idx');
                    $table->dropColumn('referral_executive_id');
                }
                if (Schema::hasColumn('users', 'referral_discount_percent')) {
                    $table->dropColumn('referral_discount_percent');
                }
            });
        }
    }
};
