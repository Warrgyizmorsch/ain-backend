<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('twilio_call_logs', function (Blueprint $table) {
            if (!Schema::hasColumn('twilio_call_logs', 'recording_url')) {
                $table->text('recording_url')->nullable()->after('notes');
            }
            if (!Schema::hasColumn('twilio_call_logs', 'recording_sid')) {
                $table->string('recording_sid', 100)->nullable()->after('recording_url');
            }
        });
    }

    public function down(): void
    {
        Schema::table('twilio_call_logs', function (Blueprint $table) {
            $columns = [];
            if (Schema::hasColumn('twilio_call_logs', 'recording_url')) {
                $columns[] = 'recording_url';
            }
            if (Schema::hasColumn('twilio_call_logs', 'recording_sid')) {
                $columns[] = 'recording_sid';
            }
            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
