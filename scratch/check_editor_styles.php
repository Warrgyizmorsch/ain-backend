<?php
$js = file_get_contents('https://cdn.jsdelivr.net/npm/luckysheet@latest/dist/luckysheet.umd.js');
preg_match_all('/luckysheet-wa-editor[^;]+;/', $js, $m);
foreach ($m[0] as $match) {
    if (strpos($match, 'css(') !== false || strpos($match, 'style') !== false || strpos($match, 'top') !== false || strpos($match, 'append') !== false) {
        echo $match . "\n---\n";
    }
}
