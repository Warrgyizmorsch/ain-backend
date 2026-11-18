<?php

use App\Models\Order;
use App\Models\User;
use App\Services\ReferralCodeService;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Standardize all existing staff prefixes (2 chars) and generated client referral codes
     * to: [User Name Prefix (3)][Marketing Executive Prefix (2)][Discount (10%)].
     */
    public function up(): void
    {
        if (! Schema::hasTable('users') || ! Schema::hasColumn('users', 'referral_code')) {
            return;
        }

        $service = app(ReferralCodeService::class);
        $hasExecCol = Schema::hasColumn('users', 'referral_executive_id');
        $hasPrefixCol = Schema::hasColumn('users', 'referral_prefix');
        $hasDiscountCol = Schema::hasColumn('users', 'referral_discount_percent');
        $hasGenAtCol = Schema::hasColumn('users', 'referral_code_generated_at');

        DB::transaction(function () use ($service, $hasExecCol, $hasPrefixCol, $hasDiscountCol, $hasGenAtCol) {
            // 1. Convert any existing non-2-char staff executive prefixes to unique 2-char prefixes
            if ($hasPrefixCol) {
                $staffList = User::where('flag', 0)
                    ->whereIn('role_id', ReferralCodeService::STAFF_EXECUTIVE_ROLE_IDS)
                    ->whereNotNull('referral_prefix')
                    ->where('referral_prefix', '!=', '')
                    ->orderBy('id')
                    ->get();

                foreach ($staffList as $staff) {
                    $clean = strtoupper((string) preg_replace('/[^A-Za-z0-9]/', '', trim((string) $staff->referral_prefix)));
                    if (! preg_match('/^[A-Z0-9]{2}$/', $clean)) {
                        $service->ensureExecutivePrefix($staff);
                    }
                }
            }

            // 2. Reformat all existing non-empty client referral codes to [User3][Exec2][Discount]
            $codedClients = User::whereNotNull('referral_code')
                ->whereRaw("TRIM(referral_code) != ''")
                ->orderBy('id')
                ->get();

            foreach ($codedClients as $client) {
                $exec = null;
                if ($hasExecCol && ! empty($client->referral_executive_id)) {
                    $exec = User::where('id', $client->referral_executive_id)
                        ->whereIn('role_id', ReferralCodeService::STAFF_EXECUTIVE_ROLE_IDS)
                        ->first();
                }

                if (! $exec && Schema::hasTable('orders')) {
                    $latestGenuineOrder = Order::genuinePlaced('orders')
                        ->where('orders.uid', $client->id)
                        ->orderByDesc('orders.id')
                        ->first();

                    if ($latestGenuineOrder) {
                        $exec = $service->resolveOrderExecutive($latestGenuineOrder);
                    }
                }

                $execPrefix = null;
                if ($exec) {
                    $execPrefix = $service->ensureExecutivePrefix($exec);
                    if ($hasExecCol && empty($client->referral_executive_id)) {
                        $client->referral_executive_id = $exec->id;
                    }
                } elseif ($hasPrefixCol && ! empty($client->referral_prefix)) {
                    $cleanExistingPrefix = strtoupper((string) preg_replace('/[^A-Za-z0-9]/', '', trim((string) $client->referral_prefix)));
                    if (strlen($cleanExistingPrefix) >= ReferralCodeService::EXECUTIVE_PREFIX_LENGTH) {
                        $execPrefix = substr($cleanExistingPrefix, 0, ReferralCodeService::EXECUTIVE_PREFIX_LENGTH);
                    }
                }

                $execPrefix = $execPrefix ?: ReferralCodeService::FALLBACK_PREFIX;

                $discountPercent = ($hasDiscountCol && $client->referral_discount_percent !== null && $client->referral_discount_percent !== '')
                    ? (float) $client->referral_discount_percent
                    : ReferralCodeService::DEFAULT_DISCOUNT_PERCENT;
                $discountToken = ReferralCodeService::formatDiscountToken($discountPercent);

                $currentCode = ReferralCodeService::normalizeReferralCode($client->referral_code);
                $validClientPrefixes = $service->deriveClientNamePrefixCandidates((string) $client->name, $client->email);

                $alreadyCompliant = false;
                if (preg_match('/^([A-Z0-9]{3})' . preg_quote($execPrefix . $discountToken, '/') . '$/', $currentCode, $matches)) {
                    if (in_array($matches[1], $validClientPrefixes, true)) {
                        $alreadyCompliant = true;
                    }
                }

                if (! $alreadyCompliant) {
                    $client->referral_code = $service->generateUniqueClientCode(
                        $execPrefix,
                        (string) $client->name,
                        $discountPercent,
                        $client->email,
                        (int) $client->id
                    );
                }

                if ($hasPrefixCol) {
                    $client->referral_prefix = $execPrefix;
                }
                if ($hasDiscountCol && ($client->referral_discount_percent === null || $client->referral_discount_percent === '')) {
                    $client->referral_discount_percent = ReferralCodeService::DEFAULT_DISCOUNT_PERCENT;
                }
                if ($hasGenAtCol && empty($client->referral_code_generated_at)) {
                    $client->referral_code_generated_at = now();
                }

                $client->saveQuietly();
            }
        });
    }

    public function down(): void
    {
        // Data reformatting cannot be losslessly reversed.
    }
};

