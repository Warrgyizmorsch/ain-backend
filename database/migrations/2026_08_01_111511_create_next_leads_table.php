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
        Schema::create('next_leads', function (Blueprint $table) {
            $table->id();
            $table->string('user_name');
            $table->string('countrycode', 20)->default('+44');
            $table->string('mobile', 50);
            $table->string('email')->nullable();
            $table->unsignedBigInteger('emp_id')->nullable();
            $table->string('target_month', 20);
            $table->text('message')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->tinyInteger('is_converted')->default(0);
            $table->unsignedBigInteger('converted_lead_id')->nullable();
            $table->timestamp('converted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('next_leads');
    }
};
