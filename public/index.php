<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

ini_set('upload_max_filesize', '100M');
ini_set('post_max_size', '120M');
ini_set('max_file_uploads', '20');
ini_set('max_execution_time', '120');

if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
