<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('whatsapp_templates')) {
            Schema::create('whatsapp_templates', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique()->index();
                $table->string('title')->nullable();
                $table->string('category')->default('MARKETING');
                $table->string('language')->default('en_US');
                $table->string('header_type')->nullable();
                $table->text('header_text')->nullable();
                $table->text('body');
                $table->text('footer_text')->nullable();
                $table->json('buttons')->nullable();
                $table->json('variables')->nullable();
                $table->string('status')->default('APPROVED');
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('whatsapp_templates');
    }
};
