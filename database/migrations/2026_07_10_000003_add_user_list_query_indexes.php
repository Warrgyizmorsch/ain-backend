<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->index('flag', 'idx_users_flag');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->index(['uid', 'order_date'], 'idx_orders_uid_order_date');
        });

        Schema::table('leads', function (Blueprint $table) {
            $table->index('emp_id', 'idx_leads_emp_id');
        });

        Schema::table('followupcomment', function (Blueprint $table) {
            $table->index(['uid', 'created_at'], 'idx_followup_uid_created_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('idx_users_flag');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('idx_orders_uid_order_date');
        });

        Schema::table('leads', function (Blueprint $table) {
            $table->dropIndex('idx_leads_emp_id');
        });

        Schema::table('followupcomment', function (Blueprint $table) {
            $table->dropIndex('idx_followup_uid_created_at');
        });
    }
};
