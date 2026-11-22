<?php

/** @var \IndoFX\Core\Router $router */

use IndoFX\Core\Database;

// ==========================================
// 1. BERANDA & KATALOG
// ==========================================
$router->add('GET', '/', 'HomeController', 'index');
$router->add('GET', '/catalog/explore', 'CatalogController', 'explore');

// Halaman Detail Aset
$router->add('GET', '/asset/detail', 'AssetController', 'detail');
$router->add('GET', '/asset', 'AssetController', 'detail');

// ==========================================
// 2. AUTENTIKASI PENGGUNA
// ==========================================
$router->add('GET', '/login', 'AuthController', 'login');
$router->add('POST', '/login', 'AuthController', 'processLogin');
$router->add('GET', '/register', 'AuthController', 'register');
$router->add('POST', '/register', 'AuthController', 'processRegister');
$router->add('GET', '/logout', 'AuthController', 'logout');

// Registrasi Penjual
$router->get('/seller/register', function () {
    require BASE_PATH . '/resources/views/auth/seller_register.php';
});

// ==========================================
// 3. PROFIL & KELOLA PROFIL
// ==========================================

// 1. Profil Publik (Tanpa Sidebar - Untuk dilihat orang lain)
$router->add('GET', '/creator/profile', 'ProfileController', 'publicProfile');

// 2. Profil Dashboard Penjual (Dengan Sidebar)
$router->add('GET', '/dashboard', 'SellerDashboardController', 'index');
$router->add('GET', '/dashboard/assets', 'SellerDashboardController', 'assets');
$router->add('GET', '/dashboard/transactions', 'SellerDashboardController', 'transactions');
$router->add('GET', '/dashboard/profile', 'ProfileController', 'dashboardProfile');

$router->add('GET', '/dashboard/add-asset', 'SellerDashboardController', 'addAssetForm');
$router->add('POST', '/dashboard/add-asset', 'SellerDashboardController', 'storeAsset');
$router->add('POST', '/dashboard/edit-asset', 'SellerDashboardController', 'updateAsset');

// 3. Menampilkan Halaman Edit Profil (Ubah Profil)
$router->add('GET', '/dashboard/ubah-profil', 'ProfileController', 'edit');
$router->add('GET', '/dashboard/ubahprofile', 'ProfileController', 'edit');

// 4. Memproses Form Penyimpanan Profil
$router->add('POST', '/dashboard/ubah-profil', 'ProfileController', 'updateBuyer');
$router->add('POST', '/dashboard/ubah-profil-toko', 'ProfileController', 'updateSeller');

// ==========================================
// 4. KERANJANG, CHECKOUT & INVOICE
// ==========================================

$router->get('/cart', function () {
    (new \IndoFX\Controllers\CartController())->index();
});

$router->post('/cart/add', function () {
    (new \IndoFX\Controllers\CartController())->add();
});

$router->post('/cart/remove', function () {
    (new \IndoFX\Controllers\CartController())->remove();
});

$router->get('/checkout', function () {
    (new \IndoFX\Controllers\CheckoutController())->index();
});

$router->post('/checkout', function () {
    (new \IndoFX\Controllers\CheckoutController())->index();
});

$router->post('/checkout/process', function () {
    (new \IndoFX\Controllers\CheckoutController())->process();
});

$router->post('/webhook/midtrans', function () {
    (new \IndoFX\Controllers\WebhookController())->midtransCallback();
});

$router->get('/invoice', function () {
    (new \IndoFX\Controllers\UserController())->invoiceHistory();
});

// ==========================================
// 5. LIBRARY PENGGUNA & ULASAN (DATABASE)
// ==========================================

$router->get('/library', function () {
    (new \IndoFX\Controllers\LibraryController())->index();
});

$router->get('/library/detail', function () {
    (new \IndoFX\Controllers\LibraryController())->detail();
});

$router->get('/library/download', function () {
    (new \IndoFX\Controllers\LibraryController())->download();
});

$router->post('/api/library/add', function () {
    (new \IndoFX\Controllers\LibraryController())->claimFree();
});

$router->post('/api/reviews/add', function () {
    (new \IndoFX\Controllers\LibraryController())->submitReview();
});

// ==========================================
// 6. DASHBOARD ADMINISTRATOR (PROTEKSI ROLE)
// ==========================================

$checkAdminAccess = function () {
    if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
        header('Location: /login');
        exit;
    }
};

$router->add('GET', '/admin', function () use ($checkAdminAccess) {
    $checkAdminAccess();
    require __DIR__ . '/../resources/views/admin/dashboard.php';
});

$router->add('GET', '/admin/dashboard', function () use ($checkAdminAccess) {
    $checkAdminAccess();
    require __DIR__ . '/../resources/views/admin/dashboard.php';
});

$router->add('GET', '/admin/users', function () use ($checkAdminAccess) {
    $checkAdminAccess();
    require __DIR__ . '/../resources/views/admin/users.php';
});

$router->add('GET', '/admin/assets', function () use ($checkAdminAccess) {
    $checkAdminAccess();
    require __DIR__ . '/../resources/views/admin/assets.php';
});

$router->add('GET', '/admin/assets/review', function () use ($checkAdminAccess) {
    $checkAdminAccess();
    require __DIR__ . '/../resources/views/admin/asset_review.php';
});

$router->add('GET', '/admin/categories', function () use ($checkAdminAccess) {
    $checkAdminAccess();
    require __DIR__ . '/../resources/views/admin/categories.php';
});

$router->add('GET', '/admin/seller-requests', function () use ($checkAdminAccess) {
    $checkAdminAccess();
    require __DIR__ . '/../resources/views/admin/seller_requests.php';
});

$router->add('GET', '/admin/transactions', function () use ($checkAdminAccess) {
    $checkAdminAccess();
    require __DIR__ . '/../resources/views/admin/transactions.php';
});

