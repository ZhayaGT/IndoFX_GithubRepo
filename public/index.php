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

// Ingat Saya: pulihkan sesi dari cookie bila sesi PHP kosong.
if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_token'])) {
    $user = (new \IndoFX\Models\User())->findByRememberToken($_COOKIE['remember_token']);

    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['public_id'] = $user['public_id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['bio'] = $user['bio'] ?? '';

        if ($user['role'] === 'seller') {
            $profile = (new \IndoFX\Models\SellerProfile())->find((int) $user['id']);
            $_SESSION['store_name'] = $profile['store_name'] ?? $user['name'];
        }
    } else {
        setcookie('remember_token', '', time() - 3600, '/');
    }
}
$router = new Router();
require BASE_PATH . '/routes/web.php';

$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
