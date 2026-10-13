<?php
foreach (['luckysheet.css', 'plugins/plugins.css', 'plugins/css/pluginsCss.css'] as $file) {
    $url = "https://cdn.jsdelivr.net/npm/luckysheet@latest/dist/" . $file;
    $css = file_get_contents($url);
    echo "=== FILE: $file ===\n";
    preg_match_all('/([.#][a-zA-Z0-9_-]+)[^{]*\{([^}]+)\}/', $css, $matches, PREG_SET_ORDER);
    foreach ($matches as $m) {
        if (strpos($m[0], 'wa-editor') !== false || strpos($m[0], 'wa-calculate') !== false) {
            echo $m[0] . "\n";
        }
    }
}
