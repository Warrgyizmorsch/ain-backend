<?php
$css = file_get_contents('https://cdn.jsdelivr.net/npm/luckysheet@latest/dist/css/luckysheet.css');
preg_match_all('/#luckysheet[^{]*\{[^}]*\}/', $css, $m);
print_r($m[0]);
preg_match_all('/\.luckysheet[^{]*\{[^}]*\}/', $css, $m2);
print_r(array_slice($m2[0], 0, 5));
