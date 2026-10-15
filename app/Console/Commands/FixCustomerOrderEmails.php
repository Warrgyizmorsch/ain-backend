<?php

namespace App\Console\Commands;

use App\Models\Leads;
use App\Models\User;
use Illuminate\Console\Command;

class FixCustomerOrderEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:fix-customer-emails {--dry-run : Only preview affected records without modifying the database}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix customer accounts and leads incorrectly assigned company emails (e.g. order@assignnmentinneed.com), resetting them to user{mobile}@gmail.com.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = (bool) $this->option('dry-run');

        if ($dryRun) {
            $this->warn('DRY-RUN MODE ENABLED: No database records will be modified.');
        } else {
            $this->info('Starting database cleanup for customer order emails...');
        }

        // 1. Fetch potential affected customers (role_id = 2)
        $potentialUsers = User::where('role_id', 2)
            ->where(function ($q) {
                $q->where('email', 'like', '%order@assign%')
                    ->orWhere('email', 'like', '%assignmentinneed%')
                    ->orWhere('email', 'like', '%assignnmentinneed%')
                    ->orWhere('email', 'like', '%assignmentinneedhelp@gmail.com%');
            })
            ->get();

        $affectedUsersCount = 0;
        $affectedLeadsCount = 0;
        $processedLeadIds = [];
        $rows = [];

        foreach ($potentialUsers as $user) {
            if (! is_company_email($user->email)) {
                continue;
            }

            $oldEmail = $user->email;
            $newEmail = generate_customer_dummy_email($user->mobile_no, $user->id);

            // Check if this new email is already taken by another user
            $conflict = User::where('email', $newEmail)->where('id', '!=', $user->id)->exists();
            if ($conflict) {
                $cleanMobile = preg_replace('/\D+/', '', (string) $user->mobile_no);
                $newEmail = 'user' . ($cleanMobile ?: 'id') . '_' . $user->id . '@gmail.com';
            }

            $affectedUsersCount++;
            $leadsUpdatedForThisUser = 0;

            $userLeads = Leads::where('emp_id', $user->id)->get();
            foreach ($userLeads as $lead) {
                if (is_company_email($lead->email) || empty($lead->email)) {
                    $processedLeadIds[] = $lead->id;
                    $leadsUpdatedForThisUser++;
                    $affectedLeadsCount++;
                    if (! $dryRun) {
                        $lead->email = $newEmail;
                        $lead->save();
                    }
                }
            }

            if (! $dryRun) {
                $user->email = $newEmail;
                $user->save();
            }

            $rows[] = [
                'User ID' => $user->id,
                'Name' => $user->name,
                'Mobile' => $user->mobile_no,
                'Old Email' => $oldEmail,
                'New Email' => $newEmail,
                'Leads Updated' => $leadsUpdatedForThisUser,
            ];
        }

        // 2. Check for any orphaned leads with company email (whose emp_id was 0 or not linked to user)
        $potentialLeads = Leads::where(function ($q) {
            $q->where('email', 'like', '%order@assign%')
                ->orWhere('email', 'like', '%assignmentinneed%')
                ->orWhere('email', 'like', '%assignnmentinneed%')
                ->orWhere('email', 'like', '%assignmentinneedhelp@gmail.com%');
        })->get();

        $orphanedRows = [];
        foreach ($potentialLeads as $lead) {
            if (in_array($lead->id, $processedLeadIds, true) || ! is_company_email($lead->email)) {
                continue;
            }

            $processedLeadIds[] = $lead->id;
            $oldLeadEmail = $lead->email;
            $newLeadEmail = generate_customer_dummy_email($lead->mobile, $lead->emp_id ?: $lead->id);

            if (! $dryRun) {
                $lead->email = $newLeadEmail;
                $lead->save();
            }

            $affectedLeadsCount++;
            $orphanedRows[] = [
                'Lead ID' => $lead->id,
                'Order ID' => $lead->order_id,
                'Mobile' => $lead->mobile,
                'Old Email' => $oldLeadEmail,
                'New Email' => $newLeadEmail,
            ];
        }

        if (! empty($rows)) {
            $this->info('Affected Customers Fixed:');
            $this->table(['User ID', 'Name', 'Mobile', 'Old Email', 'New Email', 'Leads Updated'], $rows);
        }

        if (! empty($orphanedRows)) {
            $this->info('Orphaned Leads Fixed:');
            $this->table(['Lead ID', 'Order ID', 'Mobile', 'Old Email', 'New Email'], $orphanedRows);
        }

        if (empty($rows) && empty($orphanedRows)) {
            $this->info('No customer accounts or leads found with company email. Everything is clean!');
        } else {
            $action = $dryRun ? 'Identified (Dry-run)' : 'Successfully updated';
            $this->info("{$action}: {$affectedUsersCount} customer(s) and {$affectedLeadsCount} lead(s).");
        }

        return 0;
    }
}
