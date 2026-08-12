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

        $order = Order::where('id', $orderId)->orWhere('order_id', $orderId)->first();
        if (!$order) {
            return;
        }

        $totalPaidAmount = static::where(function ($q) use ($order) {
            $q->where('order_id', (string) $order->id)
              ->orWhere('order_id', (string) $order->order_id);
        })
        ->where(function ($q) {
            $q->where('is_revoked', 0)->orWhereNull('is_revoked');
        })
        ->sum('paid_amount');

        $order->received_amount = $totalPaidAmount;
        $order->save();
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id')
            ->orWhere('order_id', $this->order_id);
    }
}
