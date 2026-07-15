<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // A failed unique-index creation on older MySQL can leave this new table
        // behind even though the migration was not recorded as completed.
        Schema::dropIfExists('password_reset_otps');

        Schema::create('password_reset_otps', function (Blueprint $table) {
            $table->id();
            // 191 chars keeps the utf8mb4 unique index below legacy 1000-byte limits.
            $table->string('email', 191)->unique();
            $table->string('otp_hash');
            $table->unsignedTinyInteger('attempts')->default(0);
            $table->timestamp('expires_at');
            $table->timestamp('verified_at')->nullable();
            $table->string('reset_token_hash')->nullable();
            $table->timestamp('reset_token_expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('password_reset_otps');
    }
};
