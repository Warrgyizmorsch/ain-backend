<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('twilio_call_logs', function (Blueprint $table) {
            $table->id();
            $table->string('call_sid')->nullable()->index();          // Twilio Call SID
            $table->string('direction')->default('outbound');         // outbound | inbound
            $table->string('status')->default('initiated');           // initiated | ringing | in-progress | completed | missed | no-answer | failed | cancelled
            $table->string('from_number')->nullable();                // raw from number
            $table->string('to_number')->nullable();                  // raw to number
            $table->string('customer_name')->nullable();              // display name
            $table->integer('duration')->default(0);                  // seconds
            $table->unsignedBigInteger('agent_user_id')->nullable();
            $table->foreign('agent_user_id')->references('id')->on('users')->nullOnDelete();
            $table->string('agent_identity')->nullable();             // agent_{userId}
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('twilio_call_logs');
    }
};
