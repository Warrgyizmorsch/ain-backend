<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('whatsapp_messages')) {
            DB::statement('ALTER TABLE whatsapp_messages MODIFY wa_message_id VARCHAR(250) NULL DEFAULT NULL');
            DB::statement('ALTER TABLE whatsapp_messages MODIFY name VARCHAR(255) NULL DEFAULT NULL');
        }
    }

    public function down(): void
    {
        // No down needed
    }
};
