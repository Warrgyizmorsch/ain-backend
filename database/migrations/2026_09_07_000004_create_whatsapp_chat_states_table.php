<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('whatsapp_chat_states', function (Blueprint $table) {
            $table->id();
            $table->string('phone', 30)->unique();
            $table->string('provider', 30)->default('ai-sense');
            $table->string('conversation_status', 30)->index();
            $table->timestamp('provider_updated_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('whatsapp_chat_states');
    }
};
