<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('whatsapp_messages')) {
            // Shorten phone column to 50 chars so composite index does not exceed MySQL key length limit
            DB::statement("ALTER TABLE `whatsapp_messages` MODIFY `phone` VARCHAR(50) NOT NULL");

            $existingIndexes = collect(DB::select("SHOW INDEX FROM `whatsapp_messages`"))
                ->pluck('Key_name')
                ->unique()
                ->toArray();

            if (!in_array('whatsapp_messages_phone_id_idx', $existingIndexes, true)) {
                DB::statement("ALTER TABLE `whatsapp_messages` ADD INDEX `whatsapp_messages_phone_id_idx` (`phone`, `id`)");
            }
            if (!in_array('whatsapp_messages_phone_dir_status_idx', $existingIndexes, true)) {
                DB::statement("ALTER TABLE `whatsapp_messages` ADD INDEX `whatsapp_messages_phone_dir_status_idx` (`phone`, `direction`, `status`)");
            }
            if (!in_array('whatsapp_messages_created_at_idx', $existingIndexes, true)) {
                DB::statement("ALTER TABLE `whatsapp_messages` ADD INDEX `whatsapp_messages_created_at_idx` (`created_at`)");
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('whatsapp_messages')) {
            $existingIndexes = collect(DB::select("SHOW INDEX FROM `whatsapp_messages`"))
                ->pluck('Key_name')
                ->unique()
                ->toArray();

            if (in_array('whatsapp_messages_phone_id_idx', $existingIndexes, true)) {
                DB::statement("ALTER TABLE `whatsapp_messages` DROP INDEX `whatsapp_messages_phone_id_idx`");
            }
            if (in_array('whatsapp_messages_phone_dir_status_idx', $existingIndexes, true)) {
                DB::statement("ALTER TABLE `whatsapp_messages` DROP INDEX `whatsapp_messages_phone_dir_status_idx`");
            }
            if (in_array('whatsapp_messages_created_at_idx', $existingIndexes, true)) {
                DB::statement("ALTER TABLE `whatsapp_messages` DROP INDEX `whatsapp_messages_created_at_idx`");
            }
        }
    }
};
