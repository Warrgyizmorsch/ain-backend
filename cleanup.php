<?php
$files = ['test-upload.php', 'fix-sources.php', 'test-db.php', 'cleanup.php'];
foreach ($files as $file) {
    $path = __DIR__ . '/' . $file;
    if (file_exists($path)) {
        unlink($path);
        echo "Deleted $file<br>";
    }
}
