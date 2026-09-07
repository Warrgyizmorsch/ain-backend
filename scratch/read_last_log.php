<?php
$logFile = __DIR__ . '/../storage/logs/laravel.log';
if (!file_exists($logFile)) {
    echo "No log file found.\n";
    exit;
}
$lines = file($logFile);
$last100 = array_slice($lines, -100);
echo implode('', $last100);
