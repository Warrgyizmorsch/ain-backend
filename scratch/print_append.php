<?php
$js = file_get_contents('https://cdn.jsdelivr.net/npm/luckysheet@latest/dist/luckysheet.umd.js');
$pos = strpos($js, 'id="luckysheet-wa-editor"');
if ($pos !== false) {
    // Find the variable or where it is appended
    echo substr($js, max(0, $pos - 1500), 1400);
}
