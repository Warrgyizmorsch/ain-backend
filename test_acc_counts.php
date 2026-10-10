<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$msgs = App\Models\EmailMessage::take(10)->get(['id', 'email_configuration_id', 'subject', 'from_email', 'to_email']);
foreach ($msgs as $m) {
    echo "ID: {$m->id}, Acc: {$m->email_configuration_id}, Subj: {$m->subject}, From: {$m->from_email}, To: {$m->to_email}" . PHP_EOL;
}
