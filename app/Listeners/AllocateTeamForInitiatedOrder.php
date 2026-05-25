<?php

namespace App\Listeners;

use App\Events\OrderStatusChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Order;
use App\Models\Team;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AllocateTeamForInitiatedOrder implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(OrderStatusChanged $event)
    {
        $order = $event->order;

        if (strtolower($order->projectstatus) !== 'initiated' || !is_null($order->team_id)) {
            return;
        }

        DB::transaction(function () use ($order) {
            // Same UID check
            $existingTeamOrder = Order::where('uid', $order->uid)
                ->whereDate('updated_at', Carbon::today())
                ->whereNotNull('team_id')
                ->first();

            if ($existingTeamOrder) {
                $order->team_id = $existingTeamOrder->team_id;
                $order->team_assigned_at = now();
                $order->save();
                return;
            }

            // Active teams
            $teams = Team::where('is_delete', 0)
                ->orderBy('priority', 'asc')
                ->get();

            if ($teams->isEmpty()) return;

            // Total assigned initiated orders today (excluding current order)
            $totalAssignedToday = Order::whereDate('updated_at', Carbon::today())
                ->whereRaw('LOWER(projectstatus) = ?', ['initiated'])
                ->whereNotNull('team_id')
                ->count();

            // Allocation logic
            $allocations = [];
            $assignedCount = [];

            foreach ($teams as $team) {
                $allocations[$team->id] = floor(($team->percentage / 100) * ($totalAssignedToday + 1)); // +1 includes current
                $assignedCount[$team->id] = Order::whereDate('updated_at', Carbon::today())
                    ->whereRaw('LOWER(projectstatus) = ?', ['initiated'])
                    ->where('team_id', $team->id)
                    ->count();
            }

            // Find team with available slot
            foreach ($teams as $team) {
                if ($assignedCount[$team->id] < $allocations[$team->id]) {
                    $order->team_id = $team->id;
                    $order->team_assigned_at = now();
                    $order->save();
                    return;
                }
            }

            // Fallback to least assigned team
            $fallbackTeamId = collect($assignedCount)->sort()->keys()->first();

            $order->team_id = $fallbackTeamId;
            $order->team_assigned_at = now();
            $order->save();
        });
    }
}
