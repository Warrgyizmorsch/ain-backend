<?php
$js = file_get_contents('https://cdn.jsdelivr.net/npm/luckysheet@latest/dist/luckysheet.umd.js');
echo substr($js, 1899500, 1000);
