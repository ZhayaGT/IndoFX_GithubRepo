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

if ($config['debug'] ?? true) {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
}

// Inisialisasi Session jika class tersedia
if (class_exists(Session::class)) {
    Session::start($config['session']['name'] ?? 'INDOFX_SESS', (int)($config['session']['lifetime'] ?? 7200));
}

// Coba koneksi ke database (abaikan jika DB belum menyala di lokal)
try {
    $dbConfig = BASE_PATH . '/app/Config/database.php';
    if (file_exists($dbConfig) && class_exists(Database::class)) {
        Database::connect(require $dbConfig);
    }
} catch (\Throwable $e) {
    // Database belum diatur/aktif, lewati agar tampilan frontend tetap bisa diakses
}

// Jalankan sesi bawaan PHP secara langsung agar aman
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$router = new Router();
require BASE_PATH . '/routes/web.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

$router->dispatch($uri, $method);
