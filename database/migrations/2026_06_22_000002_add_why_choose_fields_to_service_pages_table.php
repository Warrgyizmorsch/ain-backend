<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('service_pages', function (Blueprint $table) {
            $table->string('why_heading')->nullable()->after('section_three_content');
            $table->text('why_subheading')->nullable()->after('why_heading');
            $table->json('why_items')->nullable()->after('why_subheading');
        });

        DB::table('service_pages')->whereNull('why_heading')->update([
            'why_heading' => 'Why Choose Engineering Assignment Help from Us?',
            'why_subheading' => 'Our experienced subject specialists provide accurate, personalised and confidential academic support designed around your university requirements.',
            'why_items' => json_encode([
                ['icon' => 'fas fa-clipboard-check', 'heading' => 'Quality Assistance', 'content' => 'Experienced engineering professionals use proven academic methods and current tools to deliver high-quality assignment support.'],
                ['icon' => 'fas fa-edit', 'heading' => 'Customized Solutions', 'content' => 'Every solution is personalised around your instructions, academic level, marking criteria and deadline.'],
                ['icon' => 'fas fa-check-square', 'heading' => 'Accuracy and Precision', 'content' => 'Our experts carefully check calculations, technical explanations and references to provide precise work.'],
                ['icon' => 'fas fa-link', 'heading' => '100% Confidentiality', 'content' => 'Your identity, files and order information remain private and securely protected at every stage.'],
            ]),
        ]);
    }

    public function down(): void
    {
        Schema::table('service_pages', function (Blueprint $table) {
            $table->dropColumn(['why_heading', 'why_subheading', 'why_items']);
        });
    }
};
