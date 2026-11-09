<?php

declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Front controller IndoFX.
// Semua permintaan masuk melalui berkas ini.

define('BASE_PATH', dirname(__DIR__));

require BASE_PATH . '/vendor/autoload.php';

// Memuat helper aplikasi
if (file_exists(BASE_PATH . '/app/Helpers/format.php')) {
    require_once BASE_PATH . '/app/Helpers/format.php';
}
if (file_exists(BASE_PATH . '/app/Helpers/markdown.php')) {
    require_once BASE_PATH . '/app/Helpers/markdown.php';
}

// Inisialisasi phpdotenv untuk membaca file .env
if (class_exists('Dotenv\\Dotenv') && file_exists(BASE_PATH . '/.env')) {
    $dotenv = \Dotenv\Dotenv::createImmutable(BASE_PATH);
    $dotenv->safeLoad();
}

use IndoFX\Core\Router;

$config = file_exists(BASE_PATH . '/app/Config/app.php')
    ? require BASE_PATH . '/app/Config/app.php'
    : [];

if ($config['debug'] ?? true) {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
}

// Manajemen Sesi Aman
$sessionName = $config['session']['name'] ?? 'INDOFX_SESS';

if (class_exists('IndoFX\\Core\\Session')) {
    $sessionClass = 'IndoFX\\Core\\Session';
    $sessionClass::start($sessionName, (int)($config['session']['lifetime'] ?? 7200));
} elseif (session_status() === PHP_SESSION_NONE) {
    session_name($sessionName);
    session_start();
}

// Jika sesi kosong tapi browser memiliki cookie remember_token
if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_token'])) {
    $userModel = new \IndoFX\Models\User();
    $user = $userModel->findByRememberToken($_COOKIE['remember_token']);
    
    if ($user) {
        // Regenerasi ID Sesi untuk mencegah pembajakan (Session Fixation)
        session_regenerate_id(true);

        // Otomatis buatkan sesi baru berdasarkan data di Supabase
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role']    = $user['role'];
        $_SESSION['name']    = $user['name'];
        $_SESSION['bio']     = $user['bio'] ?? 'Hanya game developer pemula :v';
    } else {
        // Jika token palsu/kadaluarsa, hapus cookie-nya
        setcookie('remember_token', '', time() - 3600, '/');
    }
}

// Menjalankan router utama
$router = new Router();
require BASE_PATH . '/routes/web.php';

$uri = $_SERVER['REQUEST_URI'] ?? '/';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

$router->dispatch($uri, $method);