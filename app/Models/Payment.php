<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payment_details';

    protected static function booted()
    {
        static::saved(function ($payment) {
            static::updateOrderReceivedAmount($payment->order_id);
        });

        static::deleted(function ($payment) {
            static::updateOrderReceivedAmount($payment->order_id);
        });
    }

    public static function updateOrderReceivedAmount($orderId)
    {
        if (!$orderId) {
            return;
        }

        $totalPaidAmount = static::where('order_id', $orderId)
            ->where(function ($q) {
                $q->where('is_revoked', 0)->orWhereNull('is_revoked');
            })
            ->sum('paid_amount');

        Order::where('id', $orderId)->update([
            'received_amount' => $totalPaidAmount,
        ]);
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id')->with('user');
    }
}
