<?php

declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__));

require BASE_PATH . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
$dotenv->safeLoad();

use IndoFX\Core\Router;

$config = require BASE_PATH . '/app/Config/app.php';

ini_set('display_errors', $config['debug'] ? '1' : '0');
error_reporting($config['debug'] ? E_ALL : E_ALL & ~E_DEPRECATED & ~E_STRICT);

session_name($config['session']['name']);
session_start();

$router = new Router();
require BASE_PATH . '/routes/web.php';

$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
