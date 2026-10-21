<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('email_campaigns')) {
            Schema::create('email_campaigns', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->unsignedBigInteger('email_configuration_id')->nullable()->index();
                $table->string('subject');
                $table->longText('body_html');
                $table->string('status', 30)->default('draft')->index(); // draft, scheduled, processing, completed, paused, cancelled
                $table->string('audience_type', 50)->default('leads'); // leads, orders, users, csv
                $table->json('audience_filters')->nullable();
                $table->dateTime('scheduled_at')->nullable();
                $table->dateTime('started_at')->nullable();
                $table->dateTime('completed_at')->nullable();
                $table->integer('total_recipients')->default(0);
                $table->integer('sent_count')->default(0);
                $table->integer('delivered_count')->default(0);
                $table->integer('opened_count')->default(0);
                $table->integer('clicked_count')->default(0);
                $table->integer('failed_count')->default(0);
                $table->integer('throttle_rate')->default(30); // emails per minute/chunk
                $table->unsignedBigInteger('created_by')->nullable()->index();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('email_campaigns');
    }
};
