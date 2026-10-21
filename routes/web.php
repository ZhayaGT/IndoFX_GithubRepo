<?php

use IndoFX\Core\Router;

/** @var Router $router */

$router->get('/', function () {
    require BASE_PATH . '/resources/views/catalog/index.php';
});

$router->get('/auth/login', function () {
    require BASE_PATH . '/resources/views/auth/login.php';
});

$router->get('/auth/register', function () {
    require BASE_PATH . '/resources/views/auth/register.php';
});

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
    require __DIR__ . '/../resources/views/catalog/explore.php';
});
