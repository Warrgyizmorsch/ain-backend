<?php
$files = [
    'https://cdn.jsdelivr.net/npm/luckysheet@latest/dist/plugins/css/pluginsCss.css',
    'https://cdn.jsdelivr.net/npm/luckysheet@latest/dist/plugins/plugins.css',
    'https://cdn.jsdelivr.net/npm/luckysheet@latest/dist/css/luckysheet.css'
];

foreach ($files as $url) {
    echo "=== URL: $url ===\n";
    $css = file_get_contents($url);
    if ($css === false) {
        echo "Failed to load\n";
        continue;
    }
    preg_match_all('/[^{}]*wa-editor[^{]*\{[^}]*\}/i', $css, $m);
    foreach ($m[0] as $rule) {
        echo $rule . "\n";
    }
}
