<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('subject_pages')) {
            Schema::create('subject_pages', function (Blueprint $table) {
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
                $table->string('why_heading')->nullable();
                $table->text('why_subheading')->nullable();
                $table->json('why_items')->nullable();
                $table->text('cta_content')->nullable();
                $table->string('cta_button_label')->nullable();
                $table->string('cta_button_url', 500)->nullable();
                $table->longText('long_content')->nullable();
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
        Schema::dropIfExists('subject_pages');
    }
};
