<?php

declare(strict_types=1);

/**
 * Front controller IndoFX.
 * Semua permintaan masuk melalui berkas ini.
 */

define('BASE_PATH', dirname(__DIR__));

require BASE_PATH . '/vendor/autoload.php';

use IndoFX\Core\Router;
use IndoFX\Core\Session;

// Variabel lingkungan dari .env (diabaikan bila berkas tidak ada).
if (file_exists(BASE_PATH . '/.env')) {
    Dotenv\Dotenv::createImmutable(BASE_PATH)->load();
}

$config = require BASE_PATH . '/app/Config/app.php';

if ($config['debug']) {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
}

date_default_timezone_set($config['timezone']);

Session::start($config['session']['name'], $config['session']['lifetime']);

$router = new Router();
require BASE_PATH . '/routes/web.php';

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
