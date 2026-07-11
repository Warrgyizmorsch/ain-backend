<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('enquiries')) {
            Schema::create('enquiries', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email');
                $table->string('country_code')->nullable();
                $table->string('mobile')->nullable();
                $table->string('subject');
                $table->string('inquiry_type');
                $table->text('message');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('enquiries');
    }
};
