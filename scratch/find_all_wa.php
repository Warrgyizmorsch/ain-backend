<?php
$js = file_get_contents('https://cdn.jsdelivr.net/npm/luckysheet@latest/dist/luckysheet.umd.js');
// Find where luckysheet creates the toolbar and container
$pos = strpos($js, 'luckysheet-wa-editor');
while ($pos !== false) {
    echo "POS: $pos\n";
    echo substr($js, max(0, $pos - 80), 200) . "\n---\n";
    $pos = strpos($js, 'luckysheet-wa-editor', $pos + 1);
}
