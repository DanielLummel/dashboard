<?php
$token = $_GET['token'] ?? '';
$tokenFile = dirname(__DIR__) . '/.deploy_token';
$storedToken = file_exists($tokenFile) ? trim(file_get_contents($tokenFile)) : '';
if (empty($token) || empty($storedToken) || !hash_equals($storedToken, $token)) {
    http_response_code(403);
    die('Forbidden');
}

$webRoot  = __DIR__;                    // html/
$appRoot  = dirname($webRoot) . '/app'; // app/
$zipFile  = dirname($webRoot) . '/deploy.zip';

if (!file_exists($zipFile)) {
    die('deploy.zip nicht gefunden');
}

$zip = new ZipArchive();
if ($zip->open($zipFile) !== true) {
    die('ZIP konnte nicht geöffnet werden');
}

$zip->extractTo($appRoot);
$zip->close();
unlink($zipFile);

// public/ in Webroot verschieben
$publicSrc = $appRoot . '/public';
if (is_dir($publicSrc)) {
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($publicSrc, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );
    foreach ($files as $file) {
        $dest = $webRoot . '/' . $files->getSubPathname();
        if ($file->isDir()) {
            @mkdir($dest, 0755, true);
        } else {
            copy($file->getRealPath(), $dest);
        }
    }
}

echo "Deploy erfolgreich!";
