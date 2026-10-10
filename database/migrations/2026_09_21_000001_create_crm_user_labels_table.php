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
        if (!Schema::hasTable('crm_user_labels')) {
            Schema::create('crm_user_labels', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable()->index();
                $table->string('phone', 50)->nullable()->index();
                $table->string('email', 191)->nullable()->index();
                $table->unsignedBigInteger('label_id')->index();
                $table->unsignedBigInteger('assigned_by')->nullable();
                $table->timestamps();

                $table->unique(['user_id', 'label_id'], 'unique_crm_user_label');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crm_user_labels');
    }
};
