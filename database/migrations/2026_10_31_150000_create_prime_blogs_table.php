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
        if (!Schema::hasTable('prime_blogs')) {
            Schema::create('prime_blogs', function (Blueprint $table) {
                $table->id();
                $table->string('title', 255);
                $table->string('slug', 191)->unique();
                $table->longText('content');
                $table->string('image', 255)->nullable();
                $table->unsignedBigInteger('author_id')->nullable();
                $table->string('author_name', 150)->nullable();
                $table->string('category', 100)->nullable();
                $table->string('meta_title', 255)->nullable();
                $table->text('meta_description')->nullable();
                $table->text('meta_keywords')->nullable();
                $table->json('faqs')->nullable();
                $table->boolean('is_published')->default(true)->index();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prime_blogs');
    }
};

