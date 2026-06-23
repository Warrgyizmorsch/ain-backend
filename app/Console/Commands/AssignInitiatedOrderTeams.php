<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;

class AssignInitiatedOrderTeams extends Command
{
    protected $signature = 'orders:assign-initiated-teams
        {--dry-run : Show matching orders without assigning teams}
        {--all-statuses : Assign teams to every order without a team, not only initiated orders}';

    protected $description = 'Assign Alpha/Giga teams to initiated orders that do not have a team assigned.';

    public function handle(): int
    {
        $allStatuses = $this->shouldAssignAllStatuses();

        $query = Order::query()
            ->where(function ($q) {
                $q->whereNull('team_id')
                    ->orWhere('team_id', 0)
                    ->orWhere('team_id', '');
            });

        if (!$allStatuses) {
            $query->whereRaw('LOWER(TRIM(projectstatus)) = ?', ['initiated']);
        }

        $total = (clone $query)->count();

        if ($total === 0) {
            $scope = $allStatuses ? 'orders' : 'initiated orders';
            $this->info("No {$scope} found without team assignment.");
            return self::SUCCESS;
        }

        $scope = $allStatuses ? 'orders' : 'initiated orders';
        $this->info("Found {$total} {$scope} without team assignment.");

        if ($this->option('dry-run')) {
            $query->orderBy('id')->chunkById(100, function ($orders) {
                foreach ($orders as $order) {
                    $this->line("DRY RUN: {$order->order_id} (id: {$order->id})");
                }
            });

            return self::SUCCESS;
        }

        $assigned = 0;
        $notAssigned = 0;

        $query->orderBy('id')->chunkById(100, function ($orders) use (&$assigned, &$notAssigned, $allStatuses) {
            foreach ($orders as $order) {
                if (!is_null($order->team_id)) {
                    $order->team_id = null;
                    $order->save();
                }

                $order->assignTeamForInitiatedStatus($allStatuses);
                $order->refresh();

                if ($order->team_id) {
                    $assigned++;
                    $this->line("Assigned: {$order->order_id} => team_id {$order->team_id}");
                } else {
                    $notAssigned++;
                    $this->warn("Not assigned: {$order->order_id}");
                }
            }
        });

        $this->info("Done. Assigned: {$assigned}. Not assigned: {$notAssigned}.");

        return self::SUCCESS;
    }

    protected function shouldAssignAllStatuses(): bool
    {
        return (bool) $this->option('all-statuses');
    }
}
