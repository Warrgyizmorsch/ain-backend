<?php
$js = file_get_contents('https://cdn.jsdelivr.net/npm/luckysheet@latest/dist/luckysheet.umd.js');
$pos = strpos($js, 'luckysheet-wa-editor" style="position:absolute;');
if ($pos !== false) {
    echo substr($js, max(0, $pos - 100), 500);
} else {
    echo "Not found";
}
