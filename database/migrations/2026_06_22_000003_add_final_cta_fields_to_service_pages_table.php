<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('service_pages') && !Schema::hasColumn('service_pages', 'cta_content')) {
            Schema::table('service_pages', function (Blueprint $table) {
                $table->text('cta_content')->nullable()->after('why_items');
                $table->string('cta_button_label')->nullable()->after('cta_content');
                $table->string('cta_button_url')->nullable()->after('cta_button_label');
            });

            DB::table('service_pages')->whereNull('cta_content')->update([
                'cta_content' => 'Engineering is a crucial subject that requires precision and analytical thinking. Our experts are here to help you excel in your assignments!',
                'cta_button_label' => 'Get Free Quote Now',
                'cta_button_url' => '/order-now',
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('service_pages', function (Blueprint $table) {
            $table->dropColumn(['cta_content', 'cta_button_label', 'cta_button_url']);
        });
    }
};
