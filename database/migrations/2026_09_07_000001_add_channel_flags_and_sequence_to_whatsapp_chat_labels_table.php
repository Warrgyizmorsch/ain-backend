<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('whatsapp_chat_labels', function (Blueprint $table) {
            if (!Schema::hasColumn('whatsapp_chat_labels', 'is_whatsapp')) {
                $table->boolean('is_whatsapp')->default(true)->after('color');
            }
            if (!Schema::hasColumn('whatsapp_chat_labels', 'is_email')) {
                $table->boolean('is_email')->default(true)->after('is_whatsapp');
            }
            if (!Schema::hasColumn('whatsapp_chat_labels', 'is_crm')) {
                $table->boolean('is_crm')->default(true)->after('is_email');
            }
            if (!Schema::hasColumn('whatsapp_chat_labels', 'sequence')) {
                $table->integer('sequence')->default(0)->after('is_crm');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('whatsapp_chat_labels', function (Blueprint $table) {
            $columns = ['is_whatsapp', 'is_email', 'is_crm', 'sequence'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('whatsapp_chat_labels', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
