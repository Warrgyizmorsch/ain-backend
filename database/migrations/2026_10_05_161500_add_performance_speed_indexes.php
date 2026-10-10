<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. WhatsApp Messages indexes
        if (Schema::hasTable('whatsapp_messages')) {
            try {
                DB::statement("ALTER TABLE `whatsapp_messages` MODIFY `phone` VARCHAR(50) NOT NULL");
            } catch (\Throwable $e) {}

            $this->addIndexIfNotExists('whatsapp_messages', 'idx_wm_phone_id', '`phone`, `id`');
            $this->addIndexIfNotExists('whatsapp_messages', 'idx_wm_phone_dir_status', '`phone`, `direction`, `status`');
            $this->addIndexIfNotExists('whatsapp_messages', 'idx_wm_created_at', '`created_at`');
        }

        // 2. Leads indexes
        if (Schema::hasTable('leads')) {
            $this->addIndexIfNotExists('leads', 'idx_leads_created_at', '`created_at`');
            $this->addIndexIfNotExists('leads', 'idx_leads_uid', '`u_id`');
            $this->addIndexIfNotExists('leads', 'idx_leads_lead_status', '`lead_status`');
            $this->addIndexIfNotExists('leads', 'idx_leads_lead_source', '`lead_source`');
            $this->addIndexIfNotExists('leads', 'idx_leads_email', '`email`');
        }

        // 3. Users indexes
        if (Schema::hasTable('users')) {
            $this->addIndexIfNotExists('users', 'idx_users_email', '`email`');
            $this->addIndexIfNotExists('users', 'idx_users_role_id', '`role_id`');
            $this->addIndexIfNotExists('users', 'idx_users_created_at', '`created_at`');
            $this->addIndexIfNotExists('users', 'idx_users_countrycode', '`countrycode`');
        }

        // 4. Orders indexes
        if (Schema::hasTable('orders')) {
            $this->addIndexIfNotExists('orders', 'idx_orders_created_at', '`created_at`');
            $this->addIndexIfNotExists('orders', 'idx_orders_college_name', '`college_name`');
            $this->addIndexIfNotExists('orders', 'idx_orders_uid_order_date', '`uid`, `order_date`');
        }
    }

    private function addIndexIfNotExists(string $table, string $indexName, string $columns): void
    {
        try {
            $existing = collect(DB::select("SHOW INDEX FROM `{$table}`"))
                ->pluck('Key_name')
                ->unique()
                ->toArray();

            if (!in_array($indexName, $existing, true)) {
                DB::statement("ALTER TABLE `{$table}` ADD INDEX `{$indexName}` ({$columns})");
            }
        } catch (\Throwable $e) {
            \Log::warning("Could not add index {$indexName} on {$table}: " . $e->getMessage());
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $this->dropIndexIfExists('whatsapp_messages', 'idx_wm_phone_id');
        $this->dropIndexIfExists('whatsapp_messages', 'idx_wm_phone_dir_status');
        $this->dropIndexIfExists('whatsapp_messages', 'idx_wm_created_at');

        $this->dropIndexIfExists('leads', 'idx_leads_created_at');
        $this->dropIndexIfExists('leads', 'idx_leads_uid');
        $this->dropIndexIfExists('leads', 'idx_leads_lead_status');
        $this->dropIndexIfExists('leads', 'idx_leads_lead_source');
        $this->dropIndexIfExists('leads', 'idx_leads_email');

        $this->dropIndexIfExists('users', 'idx_users_email');
        $this->dropIndexIfExists('users', 'idx_users_role_id');
        $this->dropIndexIfExists('users', 'idx_users_created_at');
        $this->dropIndexIfExists('users', 'idx_users_countrycode');

        $this->dropIndexIfExists('orders', 'idx_orders_created_at');
        $this->dropIndexIfExists('orders', 'idx_orders_college_name');
        $this->dropIndexIfExists('orders', 'idx_orders_uid_order_date');
    }

    private function dropIndexIfExists(string $table, string $indexName): void
    {
        try {
            DB::statement("ALTER TABLE `{$table}` DROP INDEX `{$indexName}`");
        } catch (\Throwable $e) {}
    }
};
