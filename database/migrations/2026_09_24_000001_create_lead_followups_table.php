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
        if (!Schema::hasTable('lead_followups')) {
            Schema::create('lead_followups', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('lead_id')->index();
                $table->string('lead_type', 50)->default('primeassiment')->index();
                $table->unsignedBigInteger('user_id')->nullable()->index();
                $table->text('message');
                $table->date('followup_date')->index();
                $table->string('status', 20)->default('pending')->index(); // 'pending', 'done'
                $table->unsignedBigInteger('done_by')->nullable();
                $table->timestamp('done_at')->nullable();
                $table->timestamps();
            });
        }

        if (Schema::hasTable('primeassiment') && !Schema::hasColumn('primeassiment', 'next_followup_date')) {
            Schema::table('primeassiment', function (Blueprint $table) {
                $table->date('next_followup_date')->nullable()->index()->after('source_url');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_followups');

        if (Schema::hasTable('primeassiment') && Schema::hasColumn('primeassiment', 'next_followup_date')) {
            Schema::table('primeassiment', function (Blueprint $table) {
                $table->dropColumn('next_followup_date');
            });
        }
    }
};
