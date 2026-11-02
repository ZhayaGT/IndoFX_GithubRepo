<?php

/** @var \IndoFX\Core\Router $router */

use IndoFX\Core\Database;

$router->add('GET', '/', 'HomeController', 'index');

$router->add('GET', '/catalog/explore', 'CatalogController', 'explore');

$router->add('GET', '/asset/detail', 'AssetController', 'detail');

$router->add('GET', '/login', 'AuthController', 'login');
$router->add('POST', '/login', 'AuthController', 'processLogin');

$router->add('GET', '/register', 'AuthController', 'register');
$router->add('POST', '/register', 'AuthController', 'processRegister');

$router->add('GET', '/logout', 'AuthController', 'logout');

// Pendaftaran penjual (tampilan statis, belum terhubung ke backend).
$router->get('/seller/register', function () {
    require BASE_PATH . '/resources/views/auth/seller_register.php';
});

// Profil publik kreator (tampilan statis).
$router->get('/creator/profile', function () {
    require BASE_PATH . '/resources/views/creator/profile.php';
});

// Dashboard penjual (tampilan statis).
$router->get('/dashboard', function () {
    require BASE_PATH . '/resources/views/dashboard/main.php';
});

$router->get('/dashboard/assets', function () {
    require BASE_PATH . '/resources/views/dashboard/dashboard.php';
});

$router->get('/dashboard/transactions', function () {
    require BASE_PATH . '/resources/views/dashboard/transactions.php';
});

$router->get('/dashboard/add-asset', function () {
    require BASE_PATH . '/resources/views/dashboard/add_asset.php';
});

$router->get('/dashboard/profile', function () {
    require BASE_PATH . '/resources/views/dashboard/profile.php';
});

// Kelola profil pembeli (tampilan statis; hanya nama yang disimpan ke sesi).
$router->get('/dashboard/ubah-profil', function () {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /login');
        exit;
    }

    require BASE_PATH . '/resources/views/dashboard/ubahprofile.php';
});

$router->post('/dashboard/ubah-profil', function () {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /login');
        exit;
    }

    $newName = trim($_POST['name'] ?? '');

    if ($newName !== '') {
        Database::getInstance()->query(
            'UPDATE users SET name = :name, updated_at = NOW() WHERE id = :id',
            [
                'name' => $newName,
                'id'   => $_SESSION['user_id'],
            ]
        );

        $_SESSION['name'] = $newName;
    }

    $_SESSION['bio'] = trim($_POST['bio'] ?? '');

    header('Location: /dashboard/ubah-profil?success=1');
    exit;
});

// Library pembeli (tampilan statis).
$router->get('/library', function () {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /login');
        exit;
    }

    require BASE_PATH . '/resources/views/dashboard/library.php';
});

// Keranjang belanja (tampilan statis).
$router->get('/cart', function () {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /login');
        exit;
    }

    require BASE_PATH . '/resources/views/cart/keranjang.php';
});

// Halaman pembayaran (tampilan statis).
$router->get('/pembayaran', function () {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /login');
        exit;
    }

    require BASE_PATH . '/resources/views/checkout/pembayaran.php';
});

// Riwayat transaksi dan invoice (tampilan statis).
$router->get('/invoice', function () {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /login');
        exit;
    }

    require BASE_PATH . '/resources/views/dashboard/invoice.php';
});
