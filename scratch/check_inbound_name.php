<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$inbound = DB::table('whatsapp_messages')
    ->where('phone', '447481280711')
    ->where('direction', 'inbound')
    ->whereNotNull('name')
    ->where('name', '!=', '')
    ->where('name', '!=', 'System')
    ->orderByDesc('id')
    ->first();

echo "Latest Inbound Name: " . ($inbound ? $inbound->name : 'None') . "\n";
