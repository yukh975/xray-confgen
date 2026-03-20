<?php
declare(strict_types=1);
header('Content-Type: application/json');

const GEO_DIR = '/home/web/ip/www/vless-parser/db';

$files = [];

if (is_dir(GEO_DIR)) {
    foreach (scandir(GEO_DIR) as $file) {
        if (preg_match('/^[a-zA-Z0-9_\-]+\.dat$/', $file) && is_file(GEO_DIR . '/' . $file)) {
            $files[] = $file;
        }
    }
    sort($files);
}

echo json_encode(['databases' => $files], JSON_UNESCAPED_UNICODE);
