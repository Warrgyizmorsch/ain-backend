<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('whatsapp_chat_panel_settings')) {
            Schema::create('whatsapp_chat_panel_settings', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->index();
                $table->string('panel_key', 50);
                $table->boolean('is_enabled')->default(true);
                $table->timestamps();

                $table->unique(['user_id', 'panel_key']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('whatsapp_chat_panel_settings');
    }
};
