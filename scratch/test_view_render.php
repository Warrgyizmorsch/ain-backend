<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Auth;
use App\Models\User;

$user = User::first();
Auth::login($user);

$controller = app(\App\Http\Controllers\WhatsappController::class);
$request = new \Illuminate\Http\Request();
$view = $controller->chat($request);
echo "VIEW COMPILED OK! View Name: " . $view->getName() . "\n";
