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
        if (!Schema::hasTable('peer_contact_logs')) {
            Schema::create('peer_contact_logs', function (Blueprint $table) {
                $table->id();
                $table->string('peer_identifier', 100)->index();
                $table->unsignedBigInteger('peer_user_id')->nullable()->index();
                $table->string('peer_phone', 50)->nullable()->index();
                $table->unsignedBigInteger('order_id')->nullable()->index();
                $table->unsignedBigInteger('contacted_by')->nullable()->index();
                $table->timestamp('contacted_at')->nullable()->index();
                $table->boolean('is_contacted')->default(true)->index();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peer_contact_logs');
    }
};
