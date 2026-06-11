<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$sources = \App\Models\Source::all();
foreach ($sources as $source) {
    echo "ID: " . $source->id . " | Name: " . $source->source_name . " | Icon DB Value: " . $source->source_icon . "<br>";
    if ($source->source_icon) {
        $realPath = base_path($source->source_icon);
        echo " - Real Path: " . $realPath . " (Exists: " . (file_exists($realPath) ? 'Yes' : 'No') . ")<br>";
        $publicPath = public_path($source->source_icon);
        echo " - Public Path: " . $publicPath . " (Exists: " . (file_exists($publicPath) ? 'Yes' : 'No') . ")<br>";
    }
}
