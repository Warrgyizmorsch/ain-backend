<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('whatsapp_chat_labels', function (Blueprint $table) {
            if (!Schema::hasColumn('whatsapp_chat_labels', 'is_client_email')) {
                $table->boolean('is_client_email')->default(true)->after('is_whatsapp');
            }
            if (!Schema::hasColumn('whatsapp_chat_labels', 'is_writer_email')) {
                $table->boolean('is_writer_email')->default(true)->after('is_client_email');
            }
        });

        // Initialize based on existing is_email
        if (Schema::hasColumn('whatsapp_chat_labels', 'is_email')) {
            DB::table('whatsapp_chat_labels')
                ->where('is_email', 0)
                ->update([
                    'is_client_email' => 0,
                    'is_writer_email' => 0,
                ]);

            DB::table('whatsapp_chat_labels')
                ->where('is_email', 1)
                ->update([
                    'is_client_email' => 1,
                    'is_writer_email' => 1,
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('whatsapp_chat_labels', function (Blueprint $table) {
            $columns = ['is_client_email', 'is_writer_email'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('whatsapp_chat_labels', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
