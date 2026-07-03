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
        if (Schema::hasTable('service_pages') && !Schema::hasColumn('service_pages', 'long_content')) {
            Schema::table('service_pages', function (Blueprint $table) {
                $table->longText('long_content')->nullable()->after('cta_button_url');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_pages', function (Blueprint $table) {
            $table->dropColumn('long_content');
        });
    }
};
