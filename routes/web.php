<?php

declare(strict_types=1);

use IndoFX\Controllers\AuthController;
use IndoFX\Core\Router;

/** @var Router $router */

/**
 * Merender view; bila berkasnya belum ada tampilkan halaman 404
 * (bukan fatal error), supaya tautan navbar tetap aman diklik.
 */
$renderView = static function (string $view): void {
    $path = BASE_PATH . '/resources/views/' . $view . '.php';

    if (file_exists($path)) {
        require $path;
        return;
    }

    require BASE_PATH . '/resources/views/errors/404.php';
};

$router->get('/', function () {
    require BASE_PATH . '/resources/views/catalog/index.php';
});

// Autentikasi
$router->get('/auth/login', [AuthController::class, 'showLogin']);
$router->post('/auth/login', [AuthController::class, 'login']);

$router->get('/auth/register', [AuthController::class, 'showRegister']);
$router->post('/auth/register', [AuthController::class, 'register']);

$router->get('/auth/logout', [AuthController::class, 'logout']);
$router->post('/auth/logout', [AuthController::class, 'logout']);

$router->get('/asset/detail', function () use ($renderView) {
    $renderView('asset/detail');
});

// 1. Dashboard Utama (Dashboard Penjual)
$router->get('/dashboard', function () use ($renderView) {
    $renderView('dashboard/main');
});

// 2. Halaman Semua Aset Saya
$router->get('/dashboard/assets', function () use ($renderView) {
    $renderView('dashboard/dashboard');
});

$router->get('/dashboard/transactions', function () use ($renderView) {
    $renderView('dashboard/transactions');
});

$router->get('/dashboard/add-asset', function () use ($renderView) {
    $renderView('dashboard/add_asset');
});

$router->get('/catalog/explore', function () {
    require BASE_PATH . '/resources/views/catalog/explore.php';
});
