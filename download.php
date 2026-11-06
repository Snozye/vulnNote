<?php
require_once __DIR__ . '/config.php';

// VULN #6: Path Traversal — no canonicalization of file path
// Payload: download.php?file=../../etc/passwd
//          download.php?file=../config.php
$file = $_GET['file'] ?? '';
if (!$file) {
    die('No file specified.');
}

$path = UPLOAD_DIR . $file;

if (!file_exists($path)) {
    http_response_code(404);
    die('File not found.');
}

$mime = mime_content_type($path) ?: 'application/octet-stream';
header('Content-Type: ' . $mime);
header('Content-Disposition: attachment; filename="' . basename($file) . '"');
header('Content-Length: ' . filesize($path));
readfile($path);
exit;
