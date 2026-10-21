<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('email_campaign_recipients')) {
            Schema::create('email_campaign_recipients', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('campaign_id')->index();
                $table->foreign('campaign_id')->references('id')->on('email_campaigns')->cascadeOnDelete();
                $table->string('recipient_email', 191)->index();
                $table->string('recipient_name', 191)->nullable();
                $table->unsignedBigInteger('user_id')->nullable()->index();
                $table->unsignedBigInteger('lead_id')->nullable()->index();
                $table->string('status', 30)->default('pending')->index(); // pending, sent, failed, bounced
                $table->string('tracking_token', 64)->nullable()->unique();
                $table->dateTime('sent_at')->nullable();
                $table->dateTime('opened_at')->nullable();
                $table->dateTime('clicked_at')->nullable();
                $table->text('error_message')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('email_campaign_recipients');
    }
};
