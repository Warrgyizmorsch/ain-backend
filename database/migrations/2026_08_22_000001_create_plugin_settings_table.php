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
        if (Schema::hasTable('plugin_settings')) {
            Schema::dropIfExists('plugin_settings');
        }

        Schema::create('plugin_settings', function (Blueprint $table) {
            $table->id();
            $table->string('plugin_key', 100)->unique();
            $table->string('name', 150);
            $table->string('category', 50)->default('communication');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(false);
            $table->json('settings')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plugin_settings');
    }
};
