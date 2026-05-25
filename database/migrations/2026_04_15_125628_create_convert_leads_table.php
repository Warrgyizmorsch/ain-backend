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
        Schema::create('convert_leads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lead_id'); // Main table ki lead ID
            $table->string('action_type'); // Isme 'order' ya 'cancel' save hoga
            $table->text('message')->nullable(); // Cancellation reason ke liye
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('convert_leads');
    }
};
