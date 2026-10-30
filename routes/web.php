<?php

/** @var \IndoFX\Core\Router $router */

use IndoFX\Core\Database;

// Beranda & Katalog
$router->add('GET', '/', 'HomeController', 'index');

$router->add('GET', '/catalog/explore', function () {
    require __DIR__ . '/../resources/views/catalog/explore.php';
});

// Halaman Detail Aset (Membaca file detail.php)
$router->add('GET', '/asset/detail', function () {
    $detailPath = __DIR__ . '/../resources/views/catalog/detail.php';
    if (!file_exists($detailPath)) {
        $detailPath = __DIR__ . '/../resources/views/asset/detail.php';
    }
    require $detailPath;
});

$router->add('GET', '/asset', function () {
    $detailPath = __DIR__ . '/../resources/views/catalog/detail.php';
    if (!file_exists($detailPath)) {
        $detailPath = __DIR__ . '/../resources/views/asset/detail.php';
    }
    require $detailPath;
});

// Autentikasi Pengguna
$router->add('GET', '/login', 'AuthController', 'login');
$router->add('POST', '/login', 'AuthController', 'processLogin');
$router->add('GET', '/register', 'AuthController', 'register');
$router->add('POST', '/register', 'AuthController', 'processRegister');
$router->add('GET', '/logout', 'AuthController', 'logout');

// Seller
$router->get('/seller/register', function () {
    require BASE_PATH . '/resources/views/auth/seller_register.php';
});
$router->add('POST', '/seller/register', 'AuthController', 'processSellerRegister');

// Profil Publik Kreator
$router->add('GET', '/creator/profile', function () {
    require __DIR__ . '/../resources/views/creator/profile.php';
});

// Dashboard Penjual Bawaan
$router->add('GET', '/dashboard', function () {
    require __DIR__ . '/../resources/views/dashboard/main.php';
});
$router->add('GET', '/dashboard/assets', function () {
    require __DIR__ . '/../resources/views/dashboard/dashboard.php';
});
$router->add('GET', '/dashboard/transactions', function () {
    require __DIR__ . '/../resources/views/dashboard/transactions.php';
});
$router->add('GET', '/dashboard/add-asset', function () {
    require __DIR__ . '/../resources/views/dashboard/add_asset.php';
});
$router->add('GET', '/dashboard/profile', function () {
    require __DIR__ . '/../resources/views/dashboard/profile.php';
});

// Kelola Profil Pembeli (ubahprofile.php)
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

    $userId  = $_SESSION['user_id'];
    $newName = trim($_POST['name'] ?? '');
    $newBio  = trim($_POST['bio'] ?? '');

    if ($newName !== '') {
        $db = Database::getInstance();
        $db->query(
            "UPDATE users SET name = :name, bio = :bio, updated_at = NOW() WHERE id = :id",
            [
                'name' => $newName,
                'bio'  => $newBio,
                'id'   => $userId
            ]
        );

        $_SESSION['name'] = $newName;
        $_SESSION['bio']  = $newBio;
    }

    header('Location: /dashboard/ubah-profil?success=1');
    exit;
});

// Library Saya (Milik Pembeli)
$router->get('/library', function () {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /login');
        exit;
    }
    require BASE_PATH . '/resources/views/dashboard/library.php';
});

// Keranjang Belanja
$router->get('/cart', function () {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /login');
        exit;
    }
    require BASE_PATH . '/resources/views/cart/keranjang.php';
});

// Selesaikan Pembayaran (pembayaran.php)
$router->get('/pembayaran', function () {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /login');
        exit;
    }
    require BASE_PATH . '/resources/views/checkout/pembayaran.php';
});

// Riwayat Transaksi & Invoice (invoice.php)
$router->get('/invoice', function () {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /login');
        exit;
    }
    require BASE_PATH . '/resources/views/dashboard/invoice.php';
});