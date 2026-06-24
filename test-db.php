<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

header('Content-Type: application/json');
echo json_encode([
    'menu_sources' => \App\Models\menu::where('routes', 'like', '%source%')->orWhere('menu_name', 'like', '%source%')->get()->toArray(),
    'submenu_sources' => \App\Models\Submenus::where('routes', 'like', '%source%')->orWhere('sub_menu_name', 'like', '%source%')->get()->toArray()
], JSON_PRETTY_PRINT);
