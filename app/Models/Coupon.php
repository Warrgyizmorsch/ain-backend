<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'coupon_code',
        'discount_type',
        'discount_value',
        'expires_at',
        'min_order_amount',
        'max_discount_amount',
        'usage_limit_per_user',
        'total_usage_limit',
        'total_used_count',
        'is_active',
        'description',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'discount_value' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'max_discount_amount' => 'decimal:2',
        'expires_at' => 'datetime',
        'usage_limit_per_user' => 'integer',
        'total_usage_limit' => 'integer',
        'total_used_count' => 'integer',
    ];

    public function usages()
    {
        return $this->hasMany(CouponUsage::class, 'coupon_id');
    }

    /**
     * Validate if coupon is applicable for user and order amount.
     */
    public function isValidForUser($userId, $orderAmount = 0): array
    {
        if (!$this->is_active) {
            return ['valid' => false, 'message' => 'This coupon is inactive.', 'discount' => 0];
        }

        if ($this->expires_at && now()->greaterThan($this->expires_at)) {
            return ['valid' => false, 'message' => 'This coupon has expired.', 'discount' => 0];
        }

        if (!is_null($this->total_usage_limit) && $this->total_used_count >= $this->total_usage_limit) {
            return ['valid' => false, 'message' => 'This coupon usage limit has been reached.', 'discount' => 0];
        }

        if ($orderAmount > 0 && (float) $orderAmount < (float) $this->min_order_amount) {
            return [
                'valid' => false,
                'message' => 'Minimum order amount to apply this coupon is ' . number_format($this->min_order_amount, 2) . '.',
                'discount' => 0
            ];
        }

        if ($userId) {
            $userUsageCount = $this->usages()->where('user_id', $userId)->count();
            if ($userUsageCount >= $this->usage_limit_per_user) {
                return [
                    'valid' => false,
                    'message' => 'You have already used this coupon maximum allowed times (' . $this->usage_limit_per_user . ').',
                    'discount' => 0
                ];
            }
        }

        // Calculate discount
        $discount = 0;
        $orderAmt = (float) $orderAmount;

        if ($this->discount_type === 'percentage') {
            $calculated = ($orderAmt * (float) $this->discount_value) / 100;
            if (!is_null($this->max_discount_amount) && (float) $this->max_discount_amount > 0) {
                $discount = min($calculated, (float) $this->max_discount_amount);
            } else {
                $discount = $calculated;
            }
        } else {
            // Fixed discount
            $discount = (float) $this->discount_value;
            if ($orderAmt > 0 && $discount > $orderAmt) {
                $discount = $orderAmt;
            }
        }

        return [
            'valid' => true,
            'message' => 'Coupon applied successfully!',
            'discount' => round($discount, 2),
        ];
    }
}
