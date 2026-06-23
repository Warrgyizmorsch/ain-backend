<?php

namespace App\Console\Commands;

class AssignMissingOrderTeams extends AssignInitiatedOrderTeams
{
    protected $signature = 'orders:assign-missing-teams
        {--dry-run : Show matching orders without assigning teams}
        {--from= : Only include orders with order_date on or after this date, for example 2024-01-01}
        {--to= : Only include orders with order_date on or before this date, for example 2026-06-23}';

    protected $description = 'Assign Alpha/Giga teams to every order that does not have a team assigned.';

    protected function shouldAssignAllStatuses(): bool
    {
        return true;
    }
}
