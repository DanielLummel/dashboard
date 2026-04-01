<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Pfad zum App-Verzeichnis (außerhalb des Webroots auf Shared Hosting)
// Auf dem Server liegt die App unter ~/app/, der Webroot unter ~/html/
$appPath = is_dir(dirname(__DIR__).'/app')
    ? dirname(__DIR__).'/app'
    : dirname(__DIR__);

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = $appPath.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require $appPath.'/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
(require_once $appPath.'/bootstrap/app.php')
    ->handleRequest(Request::capture());
