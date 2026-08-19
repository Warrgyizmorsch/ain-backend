<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add index to users.mobile_no if not present
        $userIndexes = collect(DB::select("SHOW INDEX FROM `users`"))->pluck('Key_name')->unique()->toArray();
        if (!in_array('users_mobile_no_index', $userIndexes, true)) {
            DB::statement("ALTER TABLE `users` ADD INDEX `users_mobile_no_index` (`mobile_no`)");
        }

        // Add index to leads.mobile if not present
        $leadIndexes = collect(DB::select("SHOW INDEX FROM `leads`"))->pluck('Key_name')->unique()->toArray();
        if (!in_array('leads_mobile_index', $leadIndexes, true)) {
            DB::statement("ALTER TABLE `leads` ADD INDEX `leads_mobile_index` (`mobile`)");
        }

        // Add index to orders.uid if not present
        $orderIndexes = collect(DB::select("SHOW INDEX FROM `orders`"))->pluck('Key_name')->unique()->toArray();
        if (!in_array('orders_uid_index', $orderIndexes, true)) {
            DB::statement("ALTER TABLE `orders` ADD INDEX `orders_uid_index` (`uid`)");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            DB::statement("ALTER TABLE `users` DROP INDEX `users_mobile_no_index`");
        } catch (\Throwable $e) {}

        try {
            DB::statement("ALTER TABLE `leads` DROP INDEX `leads_mobile_index`");
        } catch (\Throwable $e) {}

        try {
            DB::statement("ALTER TABLE `orders` DROP INDEX `orders_uid_index`");
        } catch (\Throwable $e) {}
    }
};
