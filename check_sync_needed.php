<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
use Illuminate\Support\Facades\DB;

echo "=== CHECKING OUT OF SYNC ORDERS ===\n";

// Raw SQL query to compare orders.received_amount DB column with SUM(payment_details.paid_amount)
$mismatchedOrders = DB::select("
    SELECT o.id, o.order_id, o.received_amount as db_received,
           COALESCE(SUM(CASE WHEN p.is_revoked = 0 OR p.is_revoked IS NULL THEN p.paid_amount ELSE 0 END), 0) as payment_sum
    FROM orders o
    LEFT JOIN payment_details p ON p.order_id = o.id
    GROUP BY o.id, o.order_id, o.received_amount
    HAVING ABS(o.received_amount - payment_sum) > 0.01
");

echo "Total orders with mismatched received_amount in DB: " . count($mismatchedOrders) . "\n\n";

echo "--- SAMPLE 20 MISMATCHED ORDERS ---\n";
foreach (array_slice($mismatchedOrders, 0, 20) as $o) {
    echo "Order PK ID: {$o->id} | Code: {$o->order_id} | DB received_amount: {$o->db_received} | Real Payments Sum: {$o->payment_sum} | Diff: " . ($o->payment_sum - $o->db_received) . "\n";
}
