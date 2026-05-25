<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('career_applications', function (Blueprint $table) {
        $table->id();

        // Candidate info
        $table->string('name');
        $table->string('email')->nullable();
        $table->string('phone', 20)->nullable();
        $table->string('current_location')->nullable();
        $table->decimal('experience_years', 4, 1)->nullable(); // e.g. 2.5

        // Application info
        $table->enum('role_mode', ['job', 'internship'])->default('job');
        $table->string('role_title')->nullable(); // e.g. Frontend Developer, Internship - SEO
        $table->string('source')->nullable(); // e.g. react-careers-page
        $table->text('message')->nullable();
        $table->string('resume_url')->nullable();
        $table->string('portfolio_url')->nullable();

        // CRM tracking
        $table->string('status')->default('new'); // new, in_review, shortlisted, rejected, hired
        $table->unsignedBigInteger('assigned_to_user_id')->nullable();
        $table->text('internal_notes')->nullable();

        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('career_applications');
    }
};
