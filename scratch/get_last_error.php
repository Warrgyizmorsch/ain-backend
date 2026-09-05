<?php
$logFile = __DIR__ . '/../storage/logs/laravel.log';
if (file_exists($logFile)) {
    $content = file_get_contents($logFile);
    $pos = strrpos($content, 'local.ERROR');
    if ($pos !== false) {
        echo substr($content, $pos, 2500);
    } else {
        echo "No local.ERROR found.";
    }
}
