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
        if (!Schema::hasTable('email_thread_labels')) {
            Schema::create('email_thread_labels', function (Blueprint $table) {
                $table->id();
                $table->string('thread_id', 100)->index()->nullable();
                $table->string('email', 191)->index()->nullable();
                $table->unsignedBigInteger('label_id')->index();
                $table->unsignedBigInteger('assigned_by')->nullable();
                $table->timestamps();

                $table->unique(['thread_id', 'label_id'], 'unique_thread_label');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_thread_labels');
    }
};
