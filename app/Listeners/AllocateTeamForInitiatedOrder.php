<?php

namespace App\Listeners;

use App\Events\OrderStatusChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AllocateTeamForInitiatedOrder implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(OrderStatusChanged $event)
    {
        // mk 7 10 26 - Prevent auto team allocation on order status change
        // $event->order->assignTeamForInitiatedStatus();
    }
}
