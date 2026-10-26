<?php

declare(strict_types=1);

use IndoFX\Controllers\AuthController;
use IndoFX\Core\Router;

/** @var Router $router */

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

$router->get('/asset/detail', function () {
    require BASE_PATH . '/resources/views/asset/detail.php';
});

// 1. Dashboard Utama (Dashboard Penjual)
$router->get('/dashboard', function () {
    require __DIR__ . '/../resources/views/dashboard/main.php';
});

// 2. Halaman Semua Aset Saya (yang dibuat sebelumnya)
$router->get('/dashboard/assets', function () {
    require __DIR__ . '/../resources/views/dashboard/dashboard.php';
});

$router->get('/dashboard/transactions', function () {
    require __DIR__ . '/../resources/views/dashboard/transactions.php';
});

$router->get('/dashboard/add-asset', function () {
    require __DIR__ . '/../resources/views/dashboard/add_asset.php';
});

$router->get('/catalog/explore', function () {
    require BASE_PATH . '/resources/views/catalog/explore.php';
});
