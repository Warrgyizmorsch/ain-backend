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
        if (!Schema::hasTable('email_configurations')) {
            Schema::create('email_configurations', function (Blueprint $table) {
                $table->id();
                $table->string('name'); // e.g. "Support Email", "Order Notification", "Sales"
                $table->string('email_address')->nullable();
                $table->string('from_name')->nullable();
                $table->string('driver')->default('smtp'); // smtp, sendgrid, mailgun, ses, resend
                $table->string('host')->nullable();
                $table->integer('port')->default(587);
                $table->string('encryption')->default('tls'); // tls, ssl, none
                $table->string('username')->nullable();
                $table->text('password')->nullable();
                
                // Incoming Mail settings (IMAP / POP3)
                $table->string('incoming_protocol')->default('imap');
                $table->string('incoming_host')->nullable();
                $table->integer('incoming_port')->default(993);
                $table->string('incoming_encryption')->default('ssl');
                $table->string('incoming_username')->nullable();
                $table->text('incoming_password')->nullable();

                // Additional metadata & settings
                $table->json('settings')->nullable();
                $table->boolean('is_default')->default(false);
                $table->boolean('is_active')->default(true);
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_configurations');
    }
};
