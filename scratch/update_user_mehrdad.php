<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

DB::table('users')->where('id', 14424)->update(['name' => 'Mehrdad']);
DB::table('leads')->where('id', 36638)->update(['user_name' => 'Mehrdad']);

$user = DB::table('users')->where('id', 14424)->first();
$lead = DB::table('leads')->where('id', 36638)->first();

echo "UPDATED USER: " . json_encode($user) . "\n";
echo "UPDATED LEAD: " . json_encode($lead) . "\n";
