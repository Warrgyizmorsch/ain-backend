<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('twilio_call_logs')) {
            Schema::create('twilio_call_logs', function (Blueprint $table) {
                $table->id();
                $table->string('call_sid', 100)->nullable()->index();          // Twilio Call SID (max ~34 chars)
                $table->string('direction', 50)->default('outbound');         // outbound | inbound
                $table->string('status', 50)->default('initiated');           // initiated | ringing | in-progress | completed | missed | no-answer | failed | cancelled
                $table->string('from_number', 50)->nullable();                // raw from number
                $table->string('to_number', 50)->nullable();                  // raw to number
                $table->string('customer_name', 191)->nullable();              // display name
                $table->integer('duration')->default(0);                      // seconds
                $table->unsignedBigInteger('agent_user_id')->nullable();
                $table->foreign('agent_user_id')->references('id')->on('users')->nullOnDelete();
                $table->string('agent_identity', 100)->nullable();             // agent_{userId}
                $table->timestamp('started_at')->nullable();
                $table->timestamp('ended_at')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        } else {
            // Handle case where table was created before the index failed on older MySQL/MariaDB
            try {
                DB::statement("ALTER TABLE `twilio_call_logs` MODIFY `call_sid` VARCHAR(100) NULL");
                $indexes = collect(DB::select("SHOW INDEXES FROM `twilio_call_logs`"))
                    ->pluck('Key_name')
                    ->all();
                if (!in_array('twilio_call_logs_call_sid_index', $indexes)) {
                    DB::statement("ALTER TABLE `twilio_call_logs` ADD INDEX `twilio_call_logs_call_sid_index` (`call_sid`)");
                }
            } catch (\Throwable $e) {
                // Keep resilient
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('twilio_call_logs');
    }
};
