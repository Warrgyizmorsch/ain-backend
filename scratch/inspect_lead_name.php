<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$lead = DB::table('leads')->where('mobile', 'like', '%7481280711%')->orWhere('id', 36638)->first();
$user = DB::table('users')->where('id', 14424)->first();
$order = DB::table('orders')->where('uid', 14424)->first();

echo "LEAD:\n" . print_r($lead, true) . "\n";
echo "USER:\n" . print_r($user, true) . "\n";
echo "ORDER:\n" . print_r($order, true) . "\n";
