<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('prime_writers')) {
            Schema::create('prime_writers', function (Blueprint $table) {
                $table->id();
                $table->string('name', 255);
                $table->string('email', 191)->unique();
                $table->boolean('is_active')->default(true)->index();
                $table->timestamps();
            });
        }

        if (Schema::hasTable('prime_service_pages') && !Schema::hasColumn('prime_service_pages', 'prime_writer_ids')) {
            Schema::table('prime_service_pages', function (Blueprint $table) {
                $table->json('prime_writer_ids')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('prime_service_pages') && Schema::hasColumn('prime_service_pages', 'prime_writer_ids')) {
            Schema::table('prime_service_pages', function (Blueprint $table) {
                $table->dropColumn('prime_writer_ids');
            });
        }

        Schema::dropIfExists('prime_writers');
    }
};
