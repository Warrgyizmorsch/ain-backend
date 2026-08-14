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
        $existingOrderIndexes = collect(DB::select("SHOW INDEX FROM orders"))->pluck('Key_name')->unique()->toArray();
        $existingLeadIndexes  = collect(DB::select("SHOW INDEX FROM leads"))->pluck('Key_name')->unique()->toArray();

        Schema::table('orders', function (Blueprint $table) use ($existingOrderIndexes) {
            if (!in_array('idx_orders_date_id', $existingOrderIndexes)) {
                $table->index(['order_date', 'id'], 'idx_orders_date_id');
            }
            if (!in_array('idx_orders_uid', $existingOrderIndexes)) {
                $table->index('uid', 'idx_orders_uid');
            }
            if (!in_array('idx_orders_team_id', $existingOrderIndexes)) {
                $table->index('team_id', 'idx_orders_team_id');
            }
            if (!in_array('idx_orders_is_fail', $existingOrderIndexes)) {
                $table->index('is_fail', 'idx_orders_is_fail');
            }
            if (!in_array('idx_orders_refund', $existingOrderIndexes)) {
                $table->index('looking_for_refund', 'idx_orders_refund');
            }
            if (!in_array('idx_orders_deliv', $existingOrderIndexes)) {
                $table->index('delivery_date', 'idx_orders_deliv');
            }
            if (!in_array('idx_orders_order_id', $existingOrderIndexes)) {
                $table->index('order_id', 'idx_orders_order_id');
            }
        });

        Schema::table('leads', function (Blueprint $table) use ($existingLeadIndexes) {
            if (!in_array('idx_leads_order_id', $existingLeadIndexes)) {
                $table->index('order_id', 'idx_leads_order_id');
            }
            if (!in_array('idx_leads_is_converted', $existingLeadIndexes)) {
                $table->index('is_converted', 'idx_leads_is_converted');
            }
            if (!in_array('idx_leads_emp_id', $existingLeadIndexes)) {
                $table->index('emp_id', 'idx_leads_emp_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('idx_orders_date_id');
            $table->dropIndex('idx_orders_uid');
            $table->dropIndex('idx_orders_team_id');
            $table->dropIndex('idx_orders_is_fail');
            $table->dropIndex('idx_orders_refund');
            $table->dropIndex('idx_orders_deliv');
        });

        Schema::table('leads', function (Blueprint $table) {
            $table->dropIndex('idx_leads_order_id');
            $table->dropIndex('idx_leads_is_converted');
            $table->dropIndex('idx_leads_emp_id');
        });
    }
};
