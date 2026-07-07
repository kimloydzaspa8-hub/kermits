<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

if (isset($_SERVER['REQUEST_URI']) && str_starts_with($_SERVER['REQUEST_URI'], '/index.php')) {
    $target = preg_replace('#^/index\.php#', '', $_SERVER['REQUEST_URI']);
    $target = $target === '' || str_starts_with($target, '?') ? '/'.$target : $target;
    header('Location: '.$target, true, 302);
    exit;
}

if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

require __DIR__.'/../vendor/autoload.php';

/** @var \Illuminate\Foundation\Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());