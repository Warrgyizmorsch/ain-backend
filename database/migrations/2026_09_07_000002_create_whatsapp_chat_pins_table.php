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
        if (!Schema::hasTable('whatsapp_chat_pins')) {
            Schema::create('whatsapp_chat_pins', function (Blueprint $table) {
                $table->id();
                $table->string('phone', 50);
                $table->unsignedBigInteger('user_id')->nullable()->index();
                $table->timestamps();

                $table->index(['phone', 'user_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_chat_pins');
    }
};
