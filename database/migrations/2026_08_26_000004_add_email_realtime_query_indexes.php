<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('email_messages', function (Blueprint $table) {
            $table->index(['email_configuration_id', 'folder', 'id'], 'email_account_folder_id_idx');
            $table->index(['email_configuration_id', 'direction', 'id'], 'email_account_direction_id_idx');
            $table->index(['email_configuration_id', 'updated_at'], 'email_account_updated_idx');
        });
    }

    public function down(): void
    {
        Schema::table('email_messages', function (Blueprint $table) {
            $table->dropIndex('email_account_folder_id_idx');
            $table->dropIndex('email_account_direction_id_idx');
            $table->dropIndex('email_account_updated_idx');
        });
    }
};
