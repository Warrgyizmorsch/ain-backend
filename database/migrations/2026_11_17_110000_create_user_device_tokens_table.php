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
        if (!Schema::hasTable('user_device_tokens')) {
            Schema::create('user_device_tokens', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id')->index();
                $table->string('fcm_token', 255)->unique();
                $table->string('device_type', 30)->default('android');
                $table->string('device_name', 100)->nullable();
                $table->boolean('is_active')->default(true)->index();
                $table->timestamp('last_used_at')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_device_tokens');
    }
};
