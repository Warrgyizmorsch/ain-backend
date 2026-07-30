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

        $chunkSize = (int) $this->option('chunk');
        $totalOrders = Order::count();
        $updatedCount = 0;

        $bar = $this->output->createProgressBar($totalOrders);
        $bar->start();

        Order::chunk($chunkSize, function ($orders) use (&$updatedCount, $bar) {
            foreach ($orders as $order) {
                $totalPaidAmount = Payment::where('order_id', $order->id)
                    ->where('is_revoked', 0)
                    ->where('account_status', 1)
                    ->sum('paid_amount');

                if ((float) $order->received_amount !== (float) $totalPaidAmount) {
                    $order->received_amount = $totalPaidAmount;
                    $order->save();
                    $updatedCount++;
                }

                $bar->advance();
            }
        });

        $bar->finish();
        $this->newLine();
        $this->info("Completed! Processed {$totalOrders} orders. Updated received_amount for {$updatedCount} orders.");

        return Command::SUCCESS;
    }
}
