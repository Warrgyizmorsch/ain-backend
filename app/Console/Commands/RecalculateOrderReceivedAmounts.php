<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Console\Command;

class RecalculateOrderReceivedAmounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:recalculate-received {--chunk=500 : The number of orders to process per chunk}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recalculate and update the received_amount for all existing orders in the database based on approved and non-revoked payments.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting recalculation of received_amount for all orders...');

        $affected = \Illuminate\Support\Facades\DB::update("
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
        $this->info("Completed! Recalculated received_amount for {$totalOrders} orders ({$affected} rows updated).");

        return Command::SUCCESS;
    }
}
