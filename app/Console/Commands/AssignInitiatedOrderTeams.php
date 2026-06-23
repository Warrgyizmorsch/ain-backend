<?php

namespace App\Console\Commands;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Throwable;

class AssignInitiatedOrderTeams extends Command
{
    protected $signature = 'orders:assign-initiated-teams
        {--dry-run : Show matching orders without assigning teams}
        {--all-statuses : Assign teams to every order without a team, not only initiated orders}
        {--from= : Only include orders with order_date on or after this date, for example 2024-01-01}
        {--to= : Only include orders with order_date on or before this date, for example 2026-06-23}';

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

        if (!$this->applyOrderDateFilters($query)) {
            return self::FAILURE;
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

    protected function applyOrderDateFilters($query): bool
    {
        $from = $this->normalizeDateOption('from');
        $to = $this->normalizeDateOption('to');

        if ($from === false || $to === false) {
            return false;
        }

        if ($from) {
            $query->whereDate('order_date', '>=', $from);
        }

        if ($to) {
            $query->whereDate('order_date', '<=', $to);
        }

        return true;
    }

    protected function normalizeDateOption(string $option): string|false|null
    {
        $value = $this->option($option);

        if (!$value) {
            return null;
        }

        try {
            return Carbon::parse($value)->format('Y-m-d');
        } catch (Throwable $exception) {
            $this->error("Invalid --{$option} date. Use YYYY-MM-DD format.");
            return false;
        }
    }
}
