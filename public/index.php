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
$dotenv->load();

use IndoFX\Core\Database;
use IndoFX\Core\Router;
// use IndoFX\Core\Session;

$config = require BASE_PATH . '/app/Config/app.php';

if ($config['debug']) {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
}

// Session::start($config['session']['name'], $config['session']['lifetime']);

$router = new Router();
require BASE_PATH . '/routes/web.php';

$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

$router->dispatch($uri, $method);