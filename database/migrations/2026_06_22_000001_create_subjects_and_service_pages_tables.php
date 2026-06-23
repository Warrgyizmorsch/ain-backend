<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

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

        $subjectId = DB::table('subjects')->insertGetId([
            'name' => 'Engineering', 'slug' => 'engineering', 'is_active' => true,
            'created_at' => now(), 'updated_at' => now(),
        ]);
        $expertIds = Schema::hasTable('expert') ? DB::table('expert')->where('subject', 'Engineering')->limit(5)->pluck('id')->all() : [];
        $reviewIds = Schema::hasTable('review') ? DB::table('review')->latest('id')->limit(3)->pluck('id')->all() : [];
        DB::table('service_pages')->insert([
            'subject_id' => $subjectId,
            'slug' => 'engineering-assignment-writing-help',
            'meta_title' => 'Engineering Assignment Help UK | Expert Academic Support',
            'meta_description' => 'Get accurate, plagiarism-free engineering assignment help from qualified UK experts with timely delivery and student-friendly pricing.',
            'hero_heading' => 'Expert Engineering Assignment Help',
            'hero_highlight' => 'You Can Rely On',
            'hero_content' => 'Get accurate, well-researched and plagiarism-free engineering assignments prepared by qualified experts to help you achieve top grades.',
            'section_two_heading' => 'Why Choose Engineering Assignment Help from Us?',
            'section_two_content' => '<p>Our engineering specialists combine academic knowledge with practical experience to provide accurate and well-structured solutions.</p><ul><li>Original, carefully researched work</li><li>Accurate calculations and technical explanations</li><li>Solutions tailored to UK university standards</li></ul>',
            'section_three_heading' => 'Support Across Every Engineering Subject',
            'section_three_content' => '<p>We support civil, mechanical, electrical, electronics, software and other engineering disciplines. Every assignment is matched with a suitable subject expert.</p><ul><li>Personalised academic support</li><li>Complete confidentiality</li><li>On-time delivery and free revisions</li></ul>',
            'expert_ids' => json_encode($expertIds),
            'review_ids' => json_encode($reviewIds),
            'faqs' => json_encode([
                ['question' => 'Is the engineering assignment work original?', 'answer' => 'Yes. Every solution is prepared from scratch and checked for originality before delivery.'],
                ['question' => 'Can I select an engineering specialist?', 'answer' => 'Yes. The page editor lets administrators select the most suitable experts for this subject.'],
                ['question' => 'Do you support urgent engineering assignments?', 'answer' => 'Yes. Urgent delivery is available depending on the complexity and required word count.'],
            ]),
            'is_published' => true,
            'created_at' => now(), 'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('service_pages');
        Schema::dropIfExists('subjects');
    }
};
