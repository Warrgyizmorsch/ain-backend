<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('prime_sample_types')) {
            Schema::create('prime_sample_types', function (Blueprint $table) {
                $table->id();
                $table->string('name', 120)->unique();
                $table->string('slug', 191)->index();
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        if (Schema::hasTable('prime_samples') && !Schema::hasColumn('prime_samples', 'type_id')) {
            Schema::table('prime_samples', function (Blueprint $table) {
                $table->foreignId('type_id')
                    ->nullable()
                    ->after('category_id')
                    ->constrained('prime_sample_types')
                    ->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('prime_samples') && Schema::hasColumn('prime_samples', 'type_id')) {
            Schema::table('prime_samples', function (Blueprint $table) {
                $table->dropForeign(['type_id']);
                $table->dropColumn('type_id');
            });
        }

        Schema::dropIfExists('prime_sample_types');
    }
};
