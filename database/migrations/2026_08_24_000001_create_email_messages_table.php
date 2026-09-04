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
        Schema::create('email_messages', function (Blueprint $table) {
            $table->id();
            $table->string('message_id', 255)->nullable()->index();
            $table->string('in_reply_to', 255)->nullable()->index();
            $table->text('references')->nullable();
            $table->string('thread_id', 128)->nullable()->index();
            $table->string('from_email', 191)->index();
            $table->string('from_name', 191)->nullable();
            $table->text('to_email');
            $table->string('to_name', 191)->nullable();
            $table->text('cc')->nullable();
            $table->text('bcc')->nullable();
            $table->string('reply_to', 191)->nullable();
            $table->text('subject')->nullable();
            $table->longText('body_html')->nullable();
            $table->longText('body_plain')->nullable();
            $table->string('folder', 50)->default('inbox')->index(); // inbox, sent, drafts, trash, spam
            $table->string('direction', 20)->default('inbound')->index(); // inbound, outbound
            $table->string('status', 30)->default('received')->index(); // received, draft, sent, failed, read
            $table->boolean('is_read')->default(false)->index();
            $table->boolean('is_starred')->default(false)->index();
            $table->boolean('is_draft')->default(false)->index();
            $table->boolean('has_attachments')->default(false)->index();
            $table->timestamp('received_at')->nullable()->index();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            // Composite indexes for fast inbox & thread querying
            $table->index(['folder', 'is_read', 'created_at']);
            $table->index(['from_email', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_messages');
    }
};
