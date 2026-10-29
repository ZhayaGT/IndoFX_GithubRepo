<?php

/** @var \IndoFX\Core\Router $router */

// Mendaftarkan URL Landing Page
$router->add('GET', '/', 'HomeController', 'index');

// Rute Autentikasi
$router->add('GET', '/login', 'AuthController', 'login');
$router->add('POST', '/login', 'AuthController', 'processLogin');

$router->add('GET', '/register', 'AuthController', 'register');
$router->add('POST', '/register', 'AuthController', 'processRegister');

$router->add('GET', '/logout', 'AuthController', 'logout');

// Halaman katalog lanjutan (front end)
$router->add('GET', '/catalog/explore', 'CatalogController', 'explore');

<<<<<<< Updated upstream
// Halaman detail aset (data masih dummy, dipilih lewat ?slug=)
$router->add('GET', '/asset/detail', 'AssetController', 'detail');
=======
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


$router->get('/dashboard/profile', function () {
    require __DIR__ . '/../resources/views/dashboard/profile.php';
});

$router->get('/creator/profile', function () {
    require __DIR__ . '/../resources/views/creator/profile.php';
});
>>>>>>> Stashed changes
