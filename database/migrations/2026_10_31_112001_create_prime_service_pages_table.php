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
        if (!Schema::hasTable('prime_service_pages')) {
            Schema::create('prime_service_pages', function (Blueprint $table) {
                $table->id();
                $table->foreignId('prime_subject_id')->nullable()->constrained('prime_subjects')->nullOnDelete();
                $table->string('slug', 191)->unique();
                $table->string('meta_title', 255);
                $table->text('meta_description');
                $table->string('hero_heading', 255);
                $table->string('hero_highlight', 255)->nullable();
                $table->text('hero_content');
                $table->string('section_two_heading', 255)->nullable();
                $table->text('section_two_content')->nullable();
                $table->string('section_three_heading', 255)->nullable();
                $table->text('section_three_content')->nullable();
                $table->string('why_heading', 255)->nullable();
                $table->text('why_subheading')->nullable();
                $table->json('why_items')->nullable();
                $table->text('cta_content')->nullable();
                $table->string('cta_button_label', 100)->nullable();
                $table->string('cta_button_url', 500)->nullable();
                $table->longText('long_content')->nullable();
                $table->json('expert_ids')->nullable();
                $table->json('review_ids')->nullable();
                $table->json('faqs')->nullable();
                $table->boolean('is_published')->default(false)->index();
                $table->timestamps();

                $table->index(['prime_subject_id', 'is_published']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prime_service_pages');
    }
};

