<?php

/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @author   Taylor Otwell <taylor@otwell.com>
 */

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? ''
);

// If the requested URI is a direct file in the project or in public, serve it.
if ($uri !== '/') {
    $publicFile = __DIR__ . '/public' . $uri;
    $rootFile   = __DIR__ . $uri;

    $targetFile = null;
    if (file_exists($rootFile) && !is_dir($rootFile)) {
        $targetFile = $rootFile;
    } elseif (file_exists($publicFile) && !is_dir($publicFile)) {
        $targetFile = $publicFile;
    }

    if ($targetFile !== null) {
        $ext = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $mimes = [
            'css'   => 'text/css; charset=utf-8',
            'js'    => 'application/javascript; charset=utf-8',
            'json'  => 'application/json; charset=utf-8',
            'png'   => 'image/png',
            'jpg'   => 'image/jpeg',
            'jpeg'  => 'image/jpeg',
            'gif'   => 'image/gif',
            'webp'  => 'image/webp',
            'svg'   => 'image/svg+xml',
            'ico'   => 'image/x-icon',
            'woff'  => 'font/woff',
            'woff2' => 'font/woff2',
            'ttf'   => 'font/ttf',
            'eot'   => 'application/vnd.ms-fontobject',
            'pdf'   => 'application/pdf',
            'csv'   => 'text/csv',
            'map'   => 'application/json',
        ];

        $mime = $mimes[$ext] ?? (function_exists('mime_content_type') ? mime_content_type($targetFile) : 'application/octet-stream');

        header('Content-Type: ' . $mime);
        header('Content-Length: ' . filesize($targetFile));
        header('Cache-Control: public, max-age=86400');
        readfile($targetFile);
        exit;
    }
}

if (file_exists(__DIR__ . '/public/index.php')) {
    require_once __DIR__ . '/public/index.php';
} else {
    require_once __DIR__ . '/index.php';
}