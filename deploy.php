<?php
$token = $_GET['token'] ?? '';
$tokenFile = dirname(__DIR__) . '/.deploy_token';
$storedToken = file_exists($tokenFile) ? trim(file_get_contents($tokenFile)) : '';
if (empty($token) || empty($storedToken) || !hash_equals($storedToken, $token)) {
    http_response_code(403);
    die('Forbidden');
}

$webRoot = __DIR__;                    // html/
$appRoot = dirname($webRoot) . '/app'; // app/
$zipFile = dirname($webRoot) . '/deploy.zip';

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

// Schreibrechte für storage/ und bootstrap/cache/
$writableDirs = [
    $appRoot . '/storage',
    $appRoot . '/storage/app',
    $appRoot . '/storage/app/public',
    $appRoot . '/storage/framework',
    $appRoot . '/storage/framework/cache',
    $appRoot . '/storage/framework/sessions',
    $appRoot . '/storage/framework/views',
    $appRoot . '/storage/logs',
    $appRoot . '/bootstrap/cache',
];
foreach ($writableDirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0775, true);
    }
    chmod($dir, 0775);
}

// Migrations ausführen
$php = PHP_BINARY;
$artisan = $appRoot . '/artisan';
$output = shell_exec("cd $appRoot && $php $artisan migrate --force 2>&1");
echo "Migrations: " . htmlspecialchars($output) . "\n";

// Caches leeren
shell_exec("cd $appRoot && $php $artisan config:cache 2>&1");
shell_exec("cd $appRoot && $php $artisan route:cache 2>&1");
shell_exec("cd $appRoot && $php $artisan view:cache 2>&1");

echo "Deploy erfolgreich!";
