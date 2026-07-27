<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedInteger('writer_id')->nullable()->after('lead_id')->index();
        });

        Schema::table('leads', function (Blueprint $table) {
            $table->unsignedInteger('writer_id')->nullable()->after('order_id')->index();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['writer_id']);
            $table->dropColumn('writer_id');
        });

        Schema::table('leads', function (Blueprint $table) {
            $table->dropIndex(['writer_id']);
            $table->dropColumn('writer_id');
        });
    }
};