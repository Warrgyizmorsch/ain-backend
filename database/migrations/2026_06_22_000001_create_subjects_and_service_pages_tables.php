<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('subjects')) {
            Schema::create('subjects', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->string('slug')->unique();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('service_pages')) {
            Schema::create('service_pages', function (Blueprint $table) {
                $table->id();
                $table->foreignId('subject_id')->constrained('subjects')->cascadeOnDelete();
                $table->string('slug')->unique();
                $table->string('meta_title');
                $table->text('meta_description');
                $table->string('hero_heading');
                $table->string('hero_highlight')->nullable();
                $table->text('hero_content');
                $table->string('section_two_heading')->nullable();
                $table->longText('section_two_content')->nullable();
                $table->string('section_three_heading')->nullable();
                $table->longText('section_three_content')->nullable();
                $table->json('expert_ids')->nullable();
                $table->json('review_ids')->nullable();
                $table->json('faqs')->nullable();
                $table->boolean('is_published')->default(false);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('service_pages');
        Schema::dropIfExists('subjects');
    }
};
