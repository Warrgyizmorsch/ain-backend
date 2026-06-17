<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('whatsapp_messages')) {
            Schema::create('whatsapp_messages', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->string('phone')->index();
                $table->text('message');
                $table->enum('direction', ['inbound', 'outbound']);
                $table->string('wa_message_id')->nullable()->index();
                $table->string('status')->nullable()->index();
                $table->timestamps();
            });

            return;
        }

        Schema::table('whatsapp_messages', function (Blueprint $table) {
            if (! Schema::hasColumn('whatsapp_messages', 'wa_message_id')) {
                $table->string('wa_message_id')->nullable()->after('direction')->index();
            }

            if (! Schema::hasColumn('whatsapp_messages', 'status')) {
                $table->string('status')->nullable()->after('wa_message_id')->index();
            }
        });
    }

    public function down(): void
    {
        Schema::table('whatsapp_messages', function (Blueprint $table) {
            if (Schema::hasColumn('whatsapp_messages', 'status')) {
                $table->dropColumn('status');
            }

            if (Schema::hasColumn('whatsapp_messages', 'wa_message_id')) {
                $table->dropColumn('wa_message_id');
            }
        });
    }
};
