<?php
$css = file_get_contents('https://cdn.jsdelivr.net/npm/luckysheet@latest/dist/css/luckysheet.css');
preg_match_all('/\.luckysheet-wa-calculate[^{]*\{([^}]+)\}/', $css, $m);
print_r($m[0]);
