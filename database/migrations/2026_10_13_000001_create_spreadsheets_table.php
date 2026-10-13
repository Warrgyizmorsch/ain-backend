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
        if (!Schema::hasTable('spreadsheets')) {
            Schema::create('spreadsheets', function (Blueprint $table) {
                $table->id();
                $table->string('uuid', 36)->unique();
                $table->string('title', 255)->default('Untitled Spreadsheet');
                $table->longText('sheet_data')->nullable();
                $table->unsignedBigInteger('created_by')->nullable()->index();
                $table->unsignedBigInteger('updated_by')->nullable()->index();
                $table->boolean('is_favorite')->default(false)->index();
                $table->boolean('is_archived')->default(false)->index();
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spreadsheets');
    }
};
