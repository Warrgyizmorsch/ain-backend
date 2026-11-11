<?php

namespace App\Services;

use App\Models\Leads;
use App\Models\Order;
use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ReferralCodeService
{
    public const VIEW_ROLE_IDS = [1, 4, 9];
    public const EDIT_ROLE_IDS = [1, 9];
    public const STAFF_EXECUTIVE_ROLE_IDS = [1, 4, 9];

    public const CODE_SUFFIX_ALPHABET = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
    public const PREFIX_LENGTH = 3;
    public const SUFFIX_LENGTH = 3;
    public const DEFAULT_DISCOUNT_PERCENT = 10.00;
    public const DEFAULT_REFERRER_BONUS_PERCENT = 10.00;
    public const FALLBACK_PREFIX = 'AIN';

    /** @var array<int, bool> */
    private static array $processingOrderIds = [];

    public static function canView(?User $user): bool
    {
        return $user !== null && in_array((int) $user->role_id, self::VIEW_ROLE_IDS, true);
    }

    public static function canEdit(?User $user): bool
    {
        return $user !== null && in_array((int) $user->role_id, self::EDIT_ROLE_IDS, true);
    }

    public static function normalizePrefix(?string $prefix): string
    {
        $clean = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', Str::ascii((string) $prefix)));

        return substr($clean, 0, self::PREFIX_LENGTH);
    }

    public static function normalizeReferralCode(?string $code): string
    {
        return strtoupper(preg_replace('/[^A-Za-z0-9]/', '', trim((string) $code)));
    }

    /**
     * Build ordered list of human-readable 3-character prefix candidates from an executive name.
     *
     * @return array<int, string>
     */
    public function derivePrefixCandidates(string $name): array
    {
        $asciiUpper = strtoupper(Str::ascii(trim($name)));
        $wordString = trim((string) preg_replace('/[^A-Z0-9\s]+/', ' ', $asciiUpper));
        $words = array_values(array_filter(preg_split('/\s+/', $wordString) ?: []));
        $flatLetters = preg_replace('/[^A-Z]/', '', $wordString);
        $flatAlphaNum = preg_replace('/[^A-Z0-9]/', '', $wordString);

        $candidates = [];

        if (! empty($words)) {
            $w0 = $words[0];
            $w1 = $words[1] ?? '';
            $w2 = $words[2] ?? '';

            // 1. First 3 chars of first word (e.g. MAX -> MAX, Piyush -> PIY, Nikhil -> NIK)
            if (strlen($w0) >= 3) {
                $candidates[] = substr($w0, 0, 3);
            }

            // 2. Combinations using second / third word initials
            if ($w1 !== '') {
                if (strlen($w0) >= 2) {
                    $candidates[] = substr($w0, 0, 2) . $w1[0];
                }
                if (strlen($w1) >= 2) {
                    $candidates[] = $w0[0] . substr($w1, 0, 2);
                }
                if ($w2 !== '') {
                    $candidates[] = $w0[0] . $w1[0] . $w2[0];
                }
                $candidates[] = $w0[0] . $w1[0] . ($w1[1] ?? ($w0[1] ?? 'X'));
            }
        }

        // 3. Sliding 3-char chunks & combinations across cleaned string
        if (strlen($flatLetters) >= 3) {
            $candidates[] = substr($flatLetters, 0, 3);
            $len = strlen($flatLetters);
            for ($i = 2; $i < $len; $i++) {
                $candidates[] = $flatLetters[0] . $flatLetters[1] . $flatLetters[$i];
            }
            for ($i = 1; $i < $len - 1; $i++) {
                $candidates[] = $flatLetters[0] . $flatLetters[$i] . $flatLetters[$i + 1];
            }
        }

        if (strlen($flatAlphaNum) >= 3) {
            $candidates[] = substr($flatAlphaNum, 0, 3);
        }

        // 4. Deterministic 2-char stem + unambiguous digit/letter fallbacks
        $stem2 = substr(str_pad($flatLetters !== '' ? $flatLetters : ($flatAlphaNum !== '' ? $flatAlphaNum : 'EX'), 2, 'X'), 0, 2);
        foreach (['2', '3', '4', '5', '6', '7', '8', '9', 'K', 'M', 'P', 'R', 'S', 'T', 'V', 'W', 'X', 'Z'] as $ch) {
            $candidates[] = $stem2 . $ch;
        }

        $unique = [];
        foreach ($candidates as $cand) {
            $cand = strtoupper((string) $cand);
            if (strlen($cand) === self::PREFIX_LENGTH && preg_match('/^[A-Z0-9]{3}$/', $cand)) {
                $unique[$cand] = $cand;
            }
        }

        return array_values($unique);
    }

    /**
     * Ensure an executive (Admin / Sub-Admin / Marketing) has a persistent unique 3-char prefix.
     */
    public function ensureExecutivePrefix(User $executive): string
    {
        if (Schema::hasColumn('users', 'referral_prefix')) {
            $existing = self::normalizePrefix($executive->referral_prefix);
            if (strlen($existing) === self::PREFIX_LENGTH) {
                if ($executive->referral_prefix !== $existing) {
                    $executive->referral_prefix = $existing;
                    $executive->saveQuietly();
                }

                return $existing;
            }
        }

        $takenPrefixes = Schema::hasColumn('users', 'referral_prefix')
            ? DB::table('users')
                ->where('id', '!=', $executive->id)
                ->where('flag', 0)
                ->whereIn('role_id', self::STAFF_EXECUTIVE_ROLE_IDS)
                ->whereNotNull('referral_prefix')
                ->where('referral_prefix', '!=', '')
                ->pluck('referral_prefix')
                ->map(fn ($p) => strtoupper(trim((string) $p)))
                ->flip()
                ->all()
            : [];

        $chosen = null;
        foreach ($this->derivePrefixCandidates((string) $executive->name) as $candidate) {
            if (! isset($takenPrefixes[$candidate])) {
                $chosen = $candidate;
                break;
            }
        }

        if ($chosen === null) {
            $alphabet = self::CODE_SUFFIX_ALPHABET;
            $len = strlen($alphabet);
            for ($attempt = 0; $attempt < 200; $attempt++) {
                $cand = $alphabet[random_int(0, $len - 1)]
                    . $alphabet[random_int(0, $len - 1)]
                    . $alphabet[random_int(0, $len - 1)];
                if (! isset($takenPrefixes[$cand])) {
                    $chosen = $cand;
                    break;
                }
            }
        }

        $chosen = $chosen ?: self::FALLBACK_PREFIX;

        if (Schema::hasColumn('users', 'referral_prefix')) {
            $executive->referral_prefix = $chosen;
            $executive->saveQuietly();
        }

        return $chosen;
    }

    /**
     * Generate a globally unique 6-character referral code: [3-char prefix][3-char unambiguous suffix].
     */
    public function generateUniqueClientCode(string $prefix): string
    {
        $cleanPrefix = self::normalizePrefix($prefix);
        if (strlen($cleanPrefix) < self::PREFIX_LENGTH) {
            $cleanPrefix = str_pad($cleanPrefix !== '' ? $cleanPrefix : self::FALLBACK_PREFIX, self::PREFIX_LENGTH, 'X');
        }

        $alphabet = self::CODE_SUFFIX_ALPHABET;
        $maxIndex = strlen($alphabet) - 1;

        for ($attempt = 0; $attempt < 300; $attempt++) {
            $suffix = '';
            for ($i = 0; $i < self::SUFFIX_LENGTH; $i++) {
                $suffix .= $alphabet[random_int(0, $maxIndex)];
            }
            $code = $cleanPrefix . $suffix;

            if (! $this->codeExistsAnywhere($code)) {
                return $code;
            }
        }

        // Deterministic collision escape should random 3-char space ever fill for one prefix
        for ($i = 0; $i < 100; $i++) {
            $code = $cleanPrefix . substr(strtoupper(Str::random(4)), 0, 3);
            if (! $this->codeExistsAnywhere($code)) {
                return $code;
            }
        }

        return $cleanPrefix . strtoupper(Str::random(4));
    }

    public function codeExistsAnywhere(string $code, ?int $ignoreUserId = null): bool
    {
        $upper = self::normalizeReferralCode($code);
        if ($upper === '') {
            return false;
        }

        $userQuery = DB::table('users')->whereRaw('UPPER(referral_code) = ?', [$upper]);
        if ($ignoreUserId !== null) {
            $userQuery->where('id', '!=', $ignoreUserId);
        }
        if ($userQuery->exists()) {
            return true;
        }

        if (Schema::hasTable('coupons')) {
            return DB::table('coupons')->whereRaw('UPPER(coupon_code) = ?', [$upper])->exists();
        }

        return false;
    }

    /**
     * Strictly verify whether an Order is a genuine Converted / Confirmed placed order.
     */
    public function isOrderEligibleForReferralCode(Order $order, ?Leads $lead = null): bool
    {
        if (! Schema::hasColumn('users', 'referral_code_generated_at')) {
            return false;
        }

        $orderCode = trim((string) ($order->order_id ?? ''));
        if ($orderCode === '') {
            return false;
        }

        $uid = (int) ($order->uid ?? 0);
        if ($uid <= 0) {
            return false;
        }

        $status = strtolower(trim((string) ($order->projectstatus ?? '')));
        if (in_array($status, ['cancelled', 'trash'], true)) {
            return false;
        }

        $hasOrderDate = ! empty($order->order_date);
        $hasConfirmedStatus = ! in_array($status, ['', 'pending'], true);
        if (! $hasOrderDate && ! $hasConfirmedStatus) {
            return false;
        }

        $customer = User::select('id', 'role_id', 'flag')->find($uid);
        if (! $customer || (int) $customer->role_id !== 2 || (int) $customer->flag !== 0) {
            return false;
        }

        if ($lead !== null && (int) ($lead->is_converted ?? 0) === 0) {
            return false;
        }

        // Check linked lead(s): if an unconverted active lead still exists for this order, do not generate yet.
        $hasUnconvertedLead = Leads::query()
            ->where('is_converted', 0)
            ->where(function ($q) use ($order, $orderCode) {
                if (! empty($order->lead_id)) {
                    $q->where('id', $order->lead_id);
                }
                if ($orderCode !== '') {
                    $sub = function ($sq) use ($orderCode) {
                        $sq->where('order_id', $orderCode)
                            ->where(function ($dup) {
                                $dup->where('duplicate_lead', 0)->orWhereNull('duplicate_lead');
                            });
                    };
                    if (! empty($order->lead_id)) {
                        $q->orWhere($sub);
                    } else {
                        $sub($q);
                    }
                }
            })
            ->exists();

        if ($hasUnconvertedLead) {
            return false;
        }

        return true;
    }

    /**
     * Resolve the responsible marketing executive / admin for executive prefix attribution.
     */
    public function resolveOrderExecutive(Order $order, ?Leads $lead = null, ?User $actingUser = null): ?User
    {
        $resolvedLead = $lead;
        if (! $resolvedLead) {
            if (! empty($order->lead_id)) {
                $resolvedLead = Leads::select('id', 'order_id', 'created_by')->find($order->lead_id);
            } elseif (! empty($order->order_id)) {
                $resolvedLead = Leads::select('id', 'order_id', 'created_by')->where('order_id', $order->order_id)->first();
            }
        }

        $orderCreatedBy = ! empty($order->created_by) ? (int) $order->created_by : null;
        $leadCreatedBy = $resolvedLead && ! empty($resolvedLead->created_by) ? (int) $resolvedLead->created_by : null;

        // 1. l_converted_by exact name match among active staff
        $convertedByName = trim((string) ($order->l_converted_by ?? ''));
        if ($convertedByName !== '' && strcasecmp($convertedByName, 'system') !== 0) {
            $matches = User::where('flag', 0)
                ->whereIn('role_id', self::STAFF_EXECUTIVE_ROLE_IDS)
                ->whereRaw('LOWER(TRIM(name)) = ?', [mb_strtolower($convertedByName)])
                ->orderByRaw('CASE WHEN referral_prefix IS NOT NULL AND referral_prefix != "" THEN 0 ELSE 1 END, id DESC')
                ->get();

            if ($matches->isEmpty()) {
                $matches = User::where('flag', 0)
                    ->where('role_id', '!=', 2)
                    ->whereRaw('LOWER(TRIM(name)) = ?', [mb_strtolower($convertedByName)])
                    ->orderByRaw('CASE WHEN referral_prefix IS NOT NULL AND referral_prefix != "" THEN 0 ELSE 1 END, id DESC')
                    ->get();
            }

            if ($matches->count() === 1) {
                return $matches->first();
            }

            if ($matches->count() > 1) {
                if ($actingUser && $matches->contains('id', $actingUser->id)) {
                    return $matches->firstWhere('id', $actingUser->id);
                }
                if ($orderCreatedBy && $matches->contains('id', $orderCreatedBy)) {
                    return $matches->firstWhere('id', $orderCreatedBy);
                }
                if ($leadCreatedBy && $matches->contains('id', $leadCreatedBy)) {
                    return $matches->firstWhere('id', $leadCreatedBy);
                }

                return $matches->first();
            }
        }

        // 2. Fallback to order->created_by (staff only)
        if ($orderCreatedBy) {
            $creator = User::where('id', $orderCreatedBy)
                ->where('flag', 0)
                ->whereIn('role_id', self::STAFF_EXECUTIVE_ROLE_IDS)
                ->first()
                ?? User::where('id', $orderCreatedBy)->where('flag', 0)->where('role_id', '!=', 2)->first();
            if ($creator) {
                return $creator;
            }
        }

        // 3. Fallback to lead->created_by (staff only)
        if ($leadCreatedBy) {
            $leadCreator = User::where('id', $leadCreatedBy)
                ->where('flag', 0)
                ->whereIn('role_id', self::STAFF_EXECUTIVE_ROLE_IDS)
                ->first()
                ?? User::where('id', $leadCreatedBy)->where('flag', 0)->where('role_id', '!=', 2)->first();
            if ($leadCreator) {
                return $leadCreator;
            }
        }

        // 4. Fallback to authenticated acting staff user
        if ($actingUser && (int) $actingUser->flag === 0 && in_array((int) $actingUser->role_id, self::STAFF_EXECUTIVE_ROLE_IDS, true)) {
            return $actingUser;
        }
        if ($actingUser && (int) $actingUser->flag === 0 && (int) $actingUser->role_id !== 2) {
            return $actingUser;
        }

        return null;
    }

    /**
     * Handle code generation + idempotent referrer reward when an order is converted / confirmed.
     */
    public function handleEligibleOrder(Order $order, ?Leads $lead = null, ?User $actingUser = null): ?User
    {
        $orderKey = (int) ($order->id ?? 0);
        if ($orderKey > 0 && isset(self::$processingOrderIds[$orderKey])) {
            return null;
        }

        if ($orderKey > 0) {
            self::$processingOrderIds[$orderKey] = true;
        }

        try {
            if (! $this->isOrderEligibleForReferralCode($order, $lead)) {
                return null;
            }

            $customer = $this->ensureClientReferralCodeForOrder($order, $lead, $actingUser);

            if ($customer) {
                $this->awardReferrerBonusIfEligible($order, $customer);
            }

            return $customer;
        } catch (\Throwable $e) {
            Log::warning('ReferralCodeService::handleEligibleOrder failed: ' . $e->getMessage(), [
                'order_id' => $order->id ?? null,
                'order_code' => $order->order_id ?? null,
            ]);

            return null;
        } finally {
            if ($orderKey > 0) {
                unset(self::$processingOrderIds[$orderKey]);
            }
        }
    }

    /**
     * Idempotently assign a 6-char referral code & default 10% discount once per client on first eligible order.
     */
    public function ensureClientReferralCodeForOrder(Order $order, ?Leads $lead = null, ?User $actingUser = null): ?User
    {
        return DB::transaction(function () use ($order, $lead, $actingUser) {
            $lockedCustomer = User::where('id', $order->uid)->lockForUpdate()->first();
            if (! $lockedCustomer || (int) $lockedCustomer->role_id !== 2 || (int) $lockedCustomer->flag !== 0) {
                return null;
            }

            if (! empty(trim((string) $lockedCustomer->referral_code))) {
                return $lockedCustomer;
            }

            $executive = $this->resolveOrderExecutive($order, $lead, $actingUser);
            $prefix = $executive ? $this->ensureExecutivePrefix($executive) : self::FALLBACK_PREFIX;
            $code = $this->generateUniqueClientCode($prefix);

            $lockedCustomer->referral_code = $code;
            if ($lockedCustomer->referral_discount_percent === null || $lockedCustomer->referral_discount_percent === '') {
                $lockedCustomer->referral_discount_percent = self::DEFAULT_DISCOUNT_PERCENT;
            }
            if (Schema::hasColumn('users', 'referral_executive_id')) {
                $lockedCustomer->referral_executive_id = $executive?->id;
            }
            if (Schema::hasColumn('users', 'referral_prefix')) {
                $lockedCustomer->referral_prefix = $prefix;
            }
            if (Schema::hasColumn('users', 'referral_code_generated_at')) {
                $lockedCustomer->referral_code_generated_at = now();
            }

            $lockedCustomer->saveQuietly();

            return $lockedCustomer;
        });
    }

    /**
     * Credit the referring client's wallet (10% of referred client's first eligible genuine order), strictly once.
     */
    public function awardReferrerBonusIfEligible(Order $order, ?User $customer = null): bool
    {
        if (! Schema::hasColumn('orders', 'referral_bonus_awarded_at') || ! Schema::hasColumn('orders', 'referred_by_user_id')) {
            return false;
        }

        return DB::transaction(function () use ($order, $customer) {
            $lockedOrder = Order::where('id', $order->id)->lockForUpdate()->first();
            if (! $lockedOrder || ! empty($lockedOrder->referral_bonus_awarded_at)) {
                return false;
            }

            $resolvedCustomer = $customer ?: User::find($lockedOrder->uid);
            if (! $resolvedCustomer || (int) $resolvedCustomer->role_id !== 2) {
                return false;
            }

            $referrerId = (int) ($lockedOrder->referred_by_user_id ?: ($resolvedCustomer->refer_id ?? 0));
            if ($referrerId <= 0 || $referrerId === (int) $resolvedCustomer->id) {
                return false;
            }

            // Guarantee a referred customer can only trigger one referrer reward (on their first genuine order).
            $alreadyAwardedBefore = Order::where('uid', $resolvedCustomer->id)
                ->whereNotNull('referral_bonus_awarded_at')
                ->exists();
            if ($alreadyAwardedBefore) {
                return false;
            }

            $hadEarlierGenuineOrder = Order::genuinePlaced('orders')
                ->where('orders.uid', $resolvedCustomer->id)
                ->where('orders.id', '<', $lockedOrder->id)
                ->exists();
            if ($hadEarlierGenuineOrder) {
                return false;
            }

            $referrer = User::where('id', $referrerId)->where('flag', 0)->lockForUpdate()->first();
            if (! $referrer) {
                return false;
            }

            $orderAmount = is_numeric($lockedOrder->amount) ? (float) $lockedOrder->amount : 0.0;
            if ($orderAmount <= 0) {
                return false;
            }

            $bonusAmount = round($orderAmount * (self::DEFAULT_REFERRER_BONUS_PERCENT / 100), 2);
            if ($bonusAmount <= 0) {
                return false;
            }

            $balanceBefore = (float) ($referrer->Wallet ?? 0.00);
            $balanceAfter = round($balanceBefore + $bonusAmount, 2);

            $referrer->Wallet = $balanceAfter;
            $referrer->total_referral_earnings = round((float) ($referrer->total_referral_earnings ?? 0.00) + $bonusAmount, 2);
            $referrer->saveQuietly();

            WalletTransaction::create([
                'user_id' => $referrer->id,
                'amount' => $bonusAmount,
                'type' => 'credit',
                'description' => '10% Referral bonus from ' . $resolvedCustomer->name . ' (Order #' . $lockedOrder->order_id . ')',
                'balance_after' => $balanceAfter,
            ]);

            if (empty($lockedOrder->referred_by_user_id)) {
                $lockedOrder->referred_by_user_id = $referrer->id;
            }
            $lockedOrder->referral_bonus_awarded_at = now();
            $lockedOrder->saveQuietly();

            if (empty($resolvedCustomer->refer_id)) {
                $resolvedCustomer->refer_id = $referrer->id;
                $resolvedCustomer->saveQuietly();
            }

            return true;
        });
    }

    public function findReferrerByCode(?string $code): ?User
    {
        $normalized = self::normalizeReferralCode($code);
        if ($normalized === '') {
            return null;
        }

        return User::where('flag', 0)
            ->whereRaw('UPPER(referral_code) = ?', [$normalized])
            ->first();
    }

    public function hasCustomerUsedReferralDiscount(?int $customerId): bool
    {
        if (! $customerId || $customerId <= 0) {
            return false;
        }

        if (Schema::hasColumn('orders', 'referred_by_user_id')) {
            $usedBefore = Order::where('uid', $customerId)
                ->whereNotNull('referred_by_user_id')
                ->whereRaw("LOWER(TRIM(COALESCE(projectstatus, ''))) NOT IN ('cancelled', 'trash')")
                ->where(function ($q) {
                    $q->where('coupon_discount_amount', '>', 0)
                        ->orWhereNotNull('referral_bonus_awarded_at');
                })
                ->exists();

            if ($usedBefore) {
                return true;
            }
        }

        return Order::genuinePlaced('orders')
            ->where('orders.uid', $customerId)
            ->exists();
    }

    /**
     * Validate a client's referral code when applied by a friend on an order/checkout.
     *
     * @return array{valid: bool, message: string, discount: float, discount_percent: float, referrer: ?User}
     */
    public function validateReferralUseForCustomer(User $referrer, ?int $customerId, float $orderAmount = 0.0): array
    {
        if ((int) $referrer->flag !== 0 || empty(trim((string) $referrer->referral_code))) {
            return [
                'valid' => false,
                'message' => 'This referral code is inactive.',
                'discount' => 0.0,
                'discount_percent' => 0.0,
                'referrer' => null,
            ];
        }

        if ($customerId && (int) $customerId === (int) $referrer->id) {
            return [
                'valid' => false,
                'message' => 'You cannot use your own referral code.',
                'discount' => 0.0,
                'discount_percent' => 0.0,
                'referrer' => $referrer,
            ];
        }

        if ($customerId && $this->hasCustomerUsedReferralDiscount($customerId)) {
            return [
                'valid' => false,
                'message' => 'Referral code discount is valid only on your first order.',
                'discount' => 0.0,
                'discount_percent' => 0.0,
                'referrer' => $referrer,
            ];
        }

        $pct = $referrer->referral_discount_percent !== null
            ? max(0.0, min(100.0, (float) $referrer->referral_discount_percent))
            : self::DEFAULT_DISCOUNT_PERCENT;

        $discount = $orderAmount > 0 ? round(($orderAmount * $pct) / 100, 2) : 0.0;

        return [
            'valid' => true,
            'message' => 'Referral code applied successfully!',
            'discount' => $discount,
            'discount_percent' => $pct,
            'referrer' => $referrer,
        ];
    }
}

