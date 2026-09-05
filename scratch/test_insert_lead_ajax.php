<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;

$user = User::first();
Auth::login($user);

$controller = app(\App\Http\Controllers\LeadsController::class);

$postData = [
    'user_name' => 'Test User WA',
    'email' => 'testwa' . time() . '@gmail.com',
    'countrycode' => '91',
    'mobile' => '9876543210',
    'lead_source' => 5,
    'semester' => 'I Semester',
    'project_title' => ['Test Assignment WA'],
    'pages' => [1000],
    'amount' => [50],
    'delivery_date' => [date('Y-m-d', strtotime('+3 days'))],
    'delivery_time' => ['18:00'],
    'service_type' => ['Writing'],
    'paper' => ['Essay'],
    'message' => ['Test message'],
    'i_status' => ['Waiting'],
    'module_code' => ['CS101'],
];

$request = Request::create(route('leads'), 'POST', $postData);
$request->headers->set('X-Requested-With', 'XMLHttpRequest');
$request->headers->set('Accept', 'application/json');

try {
    $response = $controller->insert_leads($request);
    echo "STATUS: " . $response->getStatusCode() . "\n";
    echo "RESPONSE: " . $response->getContent() . "\n";
} catch (\Throwable $e) {
    echo "EXCEPTION: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
