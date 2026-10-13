<?php
$css = file_get_contents('https://cdn.jsdelivr.net/npm/luckysheet@latest/dist/css/luckysheet.css');
preg_match_all('/#luckysheet-wa-editor[^{]*\{([^}]+)\}/', $css, $m);
print_r($m[0]);

$plugins = file_get_contents('https://cdn.jsdelivr.net/npm/luckysheet@latest/dist/plugins/plugins.css');
preg_match_all('/#luckysheet-wa-editor[^{]*\{([^}]+)\}/', $plugins, $m2);
print_r($m2[0]);
