<?php

namespace App\Console\Commands;

class AssignMissingOrderTeams extends AssignInitiatedOrderTeams
{
    protected $signature = 'orders:assign-missing-teams
        {--dry-run : Show matching orders without assigning teams}';

    protected $description = 'Assign Alpha/Giga teams to every order that does not have a team assigned.';

    protected function shouldAssignAllStatuses(): bool
    {
        return true;
    }
}
