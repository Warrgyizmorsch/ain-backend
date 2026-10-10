<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('next2call_call_logs')) {
            Schema::create('next2call_call_logs', function (Blueprint $table) {
                $table->id();
                $table->string('next2call_id', 100)->nullable()->unique();
                $table->string('uniqueid', 100)->nullable()->index();
                $table->string('did', 100)->nullable();
                $table->string('direction', 50)->default('outbound'); // outbound | inbound
                $table->string('status', 50)->default('NOANSWER');   // ANSWER | CANCEL | NOANSWER | CONGESTION
                $table->string('call_from', 50)->nullable();
                $table->string('call_to', 50)->nullable();
                $table->string('customer_name', 191)->nullable();
                $table->integer('duration')->default(0);             // seconds
                $table->string('duration_formatted', 50)->nullable();
                $table->string('hangup', 50)->nullable();            // AGENT | CLIENT
                $table->string('campaign_id', 100)->nullable();
                $table->text('record_url')->nullable();
                $table->text('local_record_path')->nullable();
                $table->unsignedBigInteger('agent_user_id')->nullable()->index();
                $table->string('agent_id', 100)->nullable();         // e.g. 30102
                $table->timestamp('started_at')->nullable();
                $table->timestamp('ended_at')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('next2call_call_logs');
    }
};
