<?php
$css = file_get_contents('https://cdn.jsdelivr.net/npm/luckysheet@latest/dist/css/luckysheet.css');
preg_match_all('/\.luckysheet-wa-editor[^{]*\{([^}]+)\}/', $css, $m);
foreach ($m[0] as $rule) {
    echo $rule . "\n";
}
