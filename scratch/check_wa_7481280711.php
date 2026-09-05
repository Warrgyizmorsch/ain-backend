<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$msgs = DB::table('whatsapp_messages')->where('phone', 'like', '%7481280711%')->get();
echo "WHATSAPP MESSAGES:\n" . print_r($msgs, true) . "\n";

$contacts = DB::table('whatsapp_contacts')->where('phone', 'like', '%7481280711%')->get();
echo "WHATSAPP CONTACTS:\n" . print_r($contacts, true) . "\n";
