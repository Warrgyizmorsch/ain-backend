<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$targetPath = __DIR__ . '/assets/media/sources';

echo "Target path: $targetPath<br>";

if (file_exists($targetPath)) {
    if (is_file($targetPath)) {
        echo "Detected that 'sources' is a file. Attempting to delete...<br>";
        if (unlink($targetPath)) {
            echo "Successfully deleted the file 'sources'.<br>";
        } else {
            echo "Failed to delete the file 'sources'.<br>";
        }
    } else {
        echo "'sources' is already a directory.<br>";
    }
} else {
    echo "'sources' does not exist.<br>";
}

// Now attempt to create the directory
if (!file_exists($targetPath)) {
    echo "Attempting to create directory...<br>";
    if (mkdir($targetPath, 0777, true)) {
        echo "Directory created successfully!<br>";
    } else {
        echo "Failed to create directory.<br>";
    }
}

// Copy the uploaded file from public directory
$publicDir = __DIR__ . '/public/assets/media/sources';
if (is_dir($publicDir)) {
    $files = scandir($publicDir);
    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..') {
            $src = $publicDir . '/' . $file;
            $dest = $targetPath . '/' . $file;
            echo "Copying $file to $dest... ";
            if (copy($src, $dest)) {
                echo "Success!<br>";
            } else {
                echo "Failed!<br>";
            }
        }
    }
}
