<?php

declare(strict_types=1);

/**
 * Front controller IndoFX.
 * Semua permintaan masuk melalui berkas ini.
 */

define('BASE_PATH', dirname(__DIR__));

require BASE_PATH . '/vendor/autoload.php';

// Inisialisasi phpdotenv untuk membaca file .env
$dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
$dotenv->safeLoad();

use IndoFX\Core\Router;

$config = require BASE_PATH . '/app/Config/app.php';

if ($config['debug']) {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
}

// Sesi dipakai AuthController untuk menyimpan status login
session_name($config['session']['name']);
session_start();

$router = new Router();
require BASE_PATH . '/routes/web.php';

$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

$router->dispatch($uri, $method);
