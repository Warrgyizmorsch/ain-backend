<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Order;

class SyncPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payments:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync received_amount for all orders from payment_details and update lead conversion statuses.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Starting Payment & Order Received Amount Sync...');

        // 1. Recalculate received_amount for all orders based on non-revoked payment_details
        $affectedOrders = DB::update("
            UPDATE orders o
            LEFT JOIN (
                SELECT order_id, SUM(paid_amount) as total_paid
                FROM payment_details
                WHERE is_revoked = 0 OR is_revoked IS NULL
                GROUP BY order_id
            ) p ON p.order_id = o.id
            SET o.received_amount = COALESCE(p.total_paid, 0)
        ");

        $totalOrders = Order::count();
        $this->info("✅ Recalculated received_amount for all {$totalOrders} orders ({$affectedOrders} orders updated).");

        // 2. Sync lead conversion status: mark is_converted = 1 for leads that have confirmed orders/payments
        if (DB::getSchemaBuilder()->hasTable('leads')) {
            $affectedLeads = DB::update("
                UPDATE leads l
                INNER JOIN orders o ON (o.lead_id = l.id OR (o.uid = l.emp_id AND o.uid > 0))
                INNER JOIN payment_details p ON p.order_id = o.id
                SET l.is_converted = 1
                WHERE (l.is_converted = 0 OR l.is_converted IS NULL)
                  AND (p.is_revoked = 0 OR p.is_revoked IS NULL)
            ");
            $this->info("✅ Synced lead conversion status ({$affectedLeads} leads marked as converted).");
        }

        $this->newLine();
        $this->info('🎉 Payment Sync completed successfully!');

        return Command::SUCCESS;
    }
}
