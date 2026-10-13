<?php
$js = file_get_contents('https://cdn.jsdelivr.net/npm/luckysheet@latest/dist/luckysheet.umd.js');
$pos = strpos($js, 'luckysheet-wa-editor');
while ($pos !== false) {
    echo substr($js, max(0, $pos - 150), 300) . "\n===\n";
    $pos = strpos($js, 'luckysheet-wa-editor', $pos + 1);
}
