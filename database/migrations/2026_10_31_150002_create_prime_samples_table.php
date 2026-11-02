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
        if (!Schema::hasTable('prime_samples')) {
            Schema::create('prime_samples', function (Blueprint $table) {
                $table->id();
                $table->foreignId('category_id')->nullable()->constrained('prime_sample_categories')->nullOnDelete();
                $table->string('title', 255);
                $table->string('slug', 191)->unique();
                $table->longText('content');
                $table->string('meta_title', 255)->nullable();
                $table->text('meta_description')->nullable();
                $table->integer('pages_count')->default(1);
                $table->integer('words_count')->default(250);
                $table->boolean('is_published')->default(true)->index();
                $table->timestamps();

                $table->index(['category_id', 'is_published']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prime_samples');
    }
};

