<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Clear unused legacy 9-character "REF******" signup codes so clients start clean
     * and receive new 6-character [PREFIX][3-CHAR] codes on their next converted order.
     */
    public function up(): void
    {
        if (! Schema::hasTable('users') || ! Schema::hasColumn('users', 'referral_code')) {
            return;
        }

        $query = DB::table('users as u')
            ->whereNotNull('u.referral_code')
            ->whereRaw("UPPER(TRIM(u.referral_code)) REGEXP '^REF[A-Z0-9]{6}$'");

        if (Schema::hasColumn('users', 'referral_code_generated_at')) {
            $query->whereNull('u.referral_code_generated_at');
        }
        if (Schema::hasColumn('users', 'referral_executive_id')) {
            $query->whereNull('u.referral_executive_id');
        }
        if (Schema::hasColumn('users', 'referral_prefix')) {
            $query->whereNull('u.referral_prefix');
        }
        if (Schema::hasColumn('users', 'referral_updated_by')) {
            $query->whereNull('u.referral_updated_by');
        }
        if (Schema::hasColumn('users', 'total_referral_earnings')) {
            $query->where(function ($q) {
                $q->whereNull('u.total_referral_earnings')
                    ->orWhere('u.total_referral_earnings', '<=', 0);
            });
        }
        if (Schema::hasColumn('users', 'refer_id')) {
            $query->whereNotExists(function ($sub) {
                $sub->select(DB::raw(1))
                    ->from('users as child')
                    ->whereColumn('child.refer_id', 'u.id');
            });
        }

        if (Schema::hasTable('orders')) {
            if (Schema::hasColumn('orders', 'referred_by_user_id')) {
                $query->whereNotExists(function ($sub) {
                    $sub->select(DB::raw(1))
                        ->from('orders as o')
                        ->whereColumn('o.referred_by_user_id', 'u.id');
                });
            }
            if (Schema::hasColumn('orders', 'coupon_code')) {
                $query->whereNotExists(function ($sub) {
                    $sub->select(DB::raw(1))
                        ->from('orders as o2')
                        ->whereNotNull('o2.coupon_code')
                        ->whereRaw('UPPER(TRIM(o2.coupon_code)) = UPPER(TRIM(u.referral_code))');
                });
            }
        }

        $candidateIds = $query->pluck('u.id')->all();

        foreach (array_chunk($candidateIds, 500) as $chunk) {
            DB::table('users')
                ->whereIn('id', $chunk)
                ->update([
                    'referral_code' => null,
                    'updated_at' => now(),
                ]);
        }
    }

    public function down(): void
    {
        // Deliberately non-destructive rollback: removed legacy random codes should not be restored.
    }
};

