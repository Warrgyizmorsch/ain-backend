<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('next2call_sessions')) {
            Schema::create('next2call_sessions', function (Blueprint $table) {
                $table->id();
                $table->string('user_id', 100)->index(); // SIP user ID (e.g. 10101)
                $table->text('token');
                $table->text('webphone_url')->nullable();
                $table->text('click_to_call_url')->nullable();
                $table->timestamp('generated_at')->nullable();
                $table->timestamp('expires_at')->nullable();
                $table->integer('agent_status')->default(1);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('next2call_sessions');
    }
};
