<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MergeDuplicateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:merge {duplicate_id : The ID of the duplicate user to be removed} {target_id : The ID of the main user to keep} {--force : Skip confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Safely merge a duplicate user into a primary user, moving all orders, leads, and activity records.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dupId = (int) $this->argument('duplicate_id');
        $targetId = (int) $this->argument('target_id');
        $force = (bool) $this->option('force');

        if ($dupId <= 0 || $targetId <= 0 || $dupId === $targetId) {
            $this->error("Invalid arguments: duplicate_id ($dupId) and target_id ($targetId) must be distinct positive integers.");
            return 1;
        }

        $dupUser = User::find($dupId);
        $targetUser = User::find($targetId);

        if (!$targetUser) {
            $this->error("Target user ID $targetId does not exist in users table.");
            return 1;
        }

        if (!$dupUser) {
            $this->warn("Duplicate user ID $dupId does not exist in users table (it may already have been merged or deleted).");
            return 0;
        }

        $this->info("==================================================");
        $this->info("             MERGE USER ACCOUNTS                   ");
        $this->info("==================================================");
        $this->line("• Duplicate to Remove: [ID: {$dupUser->id}] {$dupUser->name} ({$dupUser->email}) | Phone: {$dupUser->mobile_no}");
        $this->line("• Primary to Keep:    [ID: {$targetUser->id}] {$targetUser->name} ({$targetUser->email}) | Phone: {$targetUser->mobile_no}");

        if (!$force && !$this->confirm("Are you sure you want to merge User #{$dupId} into User #{$targetId} and remove User #{$dupId}?")) {
            $this->info("Merge cancelled.");
            return 0;
        }

        DB::beginTransaction();
        try {
            // Mapping of table => column
            $tablesToUpdate = [
                'orders'                    => 'uid',
                'leads'                     => 'emp_id',
                'next_leads'                => 'emp_id',
                'followupcomment'           => 'uid',
                'lead_followups'            => 'user_id',
                'client_behaviours'         => 'uid',
                'comment'                   => 'user_id',
                'coupon_usages'             => 'user_id',
                'crm_user_labels'           => 'user_id',
                'email_campaign_recipients' => 'user_id',
                'wallet_transactions'       => 'user_id',
                'writer_feedbacks'          => 'uid',
                'users'                     => 'refer_id',
            ];

            foreach ($tablesToUpdate as $table => $col) {
                if (Schema::hasTable($table) && Schema::hasColumn($table, $col)) {
                    $count = DB::table($table)->where($col, $dupId)->count();
                    if ($count > 0) {
                        DB::table($table)->where($col, $dupId)->update([$col => $targetId]);
                        $this->line("• Updated $count record(s) in `$table` ($col: $dupId -> $targetId).");
                    }
                }
            }

            // Pivot table handling: group_master_user
            if (Schema::hasTable('group_master_user')) {
                $dupGroups = DB::table('group_master_user')->where('user_id', $dupId)->pluck('group_master_id');
                $targetGroups = DB::table('group_master_user')->where('user_id', $targetId)->pluck('group_master_id')->all();

                foreach ($dupGroups as $gId) {
                    if (!in_array($gId, $targetGroups)) {
                        DB::table('group_master_user')->insert([
                            'user_id' => $targetId,
                            'group_master_id' => $gId,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
                DB::table('group_master_user')->where('user_id', $dupId)->delete();
            }

            // Delete the duplicate user
            DB::table('users')->where('id', $dupId)->delete();

            DB::commit();

            // Clear any user caches
            try {
                \Illuminate\Support\Facades\Cache::forget('orders_index_dropdown_data');
                \Illuminate\Support\Facades\Cache::forget('leads_active_group_masters');
            } catch (\Throwable $e) {
                // Ignore cache clear error
            }

            $this->info("SUCCESS: User #{$dupId} merged into User #{$targetId} successfully.");
            return 0;

        } catch (\Throwable $e) {
            DB::rollBack();
            $this->error("FAILED to merge users: " . $e->getMessage());
            return 1;
        }
    }
}
