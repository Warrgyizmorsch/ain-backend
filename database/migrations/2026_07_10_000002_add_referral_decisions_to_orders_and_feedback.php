<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('client_will_refer', 3)->nullable()->after('referal');
        });

        Schema::table('feddbacksheet', function (Blueprint $table) {
            $table->string('client_will_refer', 3)->nullable()->after('order_status');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('client_will_refer');
        });

        Schema::table('feddbacksheet', function (Blueprint $table) {
            $table->dropColumn('client_will_refer');
        });
    }
};
