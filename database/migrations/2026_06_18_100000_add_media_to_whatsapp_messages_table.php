<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('whatsapp_messages', function (Blueprint $table) {
            if (! Schema::hasColumn('whatsapp_messages', 'media_url')) {
                $table->string('media_url')->nullable()->after('message');
            }
            if (! Schema::hasColumn('whatsapp_messages', 'media_type')) {
                // image | video | document | audio
                $table->string('media_type', 20)->nullable()->after('media_url');
            }
            if (! Schema::hasColumn('whatsapp_messages', 'media_name')) {
                $table->string('media_name')->nullable()->after('media_type');
            }
            if (! Schema::hasColumn('whatsapp_messages', 'media_size')) {
                $table->unsignedBigInteger('media_size')->nullable()->after('media_name');
            }
        });
    }

    public function down(): void
    {
        Schema::table('whatsapp_messages', function (Blueprint $table) {
            $table->dropColumn(['media_url', 'media_type', 'media_name', 'media_size']);
        });
    }
};
