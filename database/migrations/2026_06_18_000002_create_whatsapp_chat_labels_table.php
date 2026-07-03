<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('whatsapp_chat_labels')) {
            Schema::create('whatsapp_chat_labels', function (Blueprint $table) {
                $table->id();
                $table->string('name', 100);
                $table->string('color', 20)->default('#00a884');
                $table->unsignedBigInteger('created_by')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('whatsapp_chat_contact_labels')) {
            Schema::create('whatsapp_chat_contact_labels', function (Blueprint $table) {
                $table->id();
                $table->string('phone', 30)->index();
                $table->unsignedBigInteger('label_id')->index();
                $table->unsignedBigInteger('assigned_by')->nullable();
                $table->timestamps();

                $table->unique(['phone', 'label_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('whatsapp_chat_contact_labels');
        Schema::dropIfExists('whatsapp_chat_labels');
    }
};
