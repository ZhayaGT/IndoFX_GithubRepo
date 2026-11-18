<?php

/** @var \IndoFX\Core\Router $router */

use IndoFX\Core\Controller;
use IndoFX\Core\Database;

$router->add('GET', '/', 'HomeController', 'index');
$router->add('GET', '/catalog/explore', 'CatalogController', 'explore');
$router->add('GET', '/asset/detail', 'AssetController', 'detail');
$router->add('GET', '/asset', 'AssetController', 'detail');

$router->add('GET', '/login', 'AuthController', 'login');
$router->add('POST', '/login', 'AuthController', 'processLogin');
$router->add('GET', '/register', 'AuthController', 'register');
$router->add('POST', '/register', 'AuthController', 'processRegister');
$router->add('GET', '/logout', 'AuthController', 'logout');

$router->get('/seller/register', 'SellerController', 'upgradeForm');
$router->post('/seller/register', 'SellerController', 'upgrade');
$router->get('/creator/profile', 'ProfileController', 'show');

$router->get('/dashboard', 'SellerController', 'dashboard');
$router->get('/dashboard/assets', 'SellerController', 'assets');
$router->get('/dashboard/transactions', 'SellerController', 'transactions');
$router->get('/dashboard/add-asset', 'SellerAssetController', 'form');
$router->post('/dashboard/assets/save', 'SellerAssetController', 'save');
$router->get('/dashboard/profile', 'SellerController', 'profile');
$router->post('/dashboard/profile', 'SellerController', 'saveProfile');
$router->get('/download', 'MediaController', 'download');

$router->get('/dashboard/ubah-profil', function (): void {
    Controller::guardLogin();
    require BASE_PATH . '/resources/views/dashboard/ubahprofile.php';
});
$router->post('/dashboard/ubah-profil', function (): void {
    Controller::guardLogin();
    updateBuyerProfile('/dashboard/ubah-profil');
});
$router->get('/dashboard/ubahprofile', function (): void {
    Controller::guardLogin();
    require BASE_PATH . '/resources/views/dashboard/ubahprofile.php';
});
$router->post('/dashboard/ubahprofile', function (): void {
    Controller::guardLogin();
    updateBuyerProfile('/dashboard/ubahprofile');
});

if (!function_exists('updateBuyerProfile')) {
    function updateBuyerProfile(string $redirect): void
    {
        $name = trim((string) ($_POST['name'] ?? ''));
        $bio = trim((string) ($_POST['bio'] ?? ''));
        if ($name !== '') {
            Database::getInstance()->query(
                'UPDATE users SET name = :name, bio = :bio, updated_at = NOW() WHERE id = :id',
                [':name' => $name, ':bio' => $bio, ':id' => (int) $_SESSION['user_id']]
            );
            $_SESSION['name'] = $name;
            $_SESSION['bio'] = $bio;
        }
        header('Location: ' . $redirect . '?success=1');
        exit;
    }
}

$router->get('/cart', 'CommerceController', 'cart');
$router->post('/cart/add', 'CommerceController', 'addToCart');
$router->post('/cart/remove', 'CommerceController', 'removeFromCart');
$router->get('/checkout', 'CommerceController', 'checkout');
$router->post('/checkout/process', 'CommerceController', 'processCheckout');
$router->get('/invoice', 'CommerceController', 'invoice');
$router->get('/library', 'CommerceController', 'library');
$router->get('/library/detail', 'CommerceController', 'libraryDetail');

foreach (['dashboard', 'assets', 'categories', 'users', 'seller-requests', 'transactions'] as $adminPage) {
    $router->get('/admin/' . $adminPage, function () use ($adminPage): void {
        Controller::guardAdmin();
        $view = $adminPage === 'seller-requests' ? 'seller_requests' : $adminPage;
        require BASE_PATH . '/resources/views/admin/' . $view . '.php';
    });
}
$router->get('/admin/assets/review', function (): void {
    Controller::guardAdmin();
    require BASE_PATH . '/resources/views/admin/asset_review.php';
});

$router->post('/api/reviews/add', function (): void {
    header('Content-Type: application/json');
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Silakan login terlebih dahulu']);
        return;
    }
    $input = json_decode(file_get_contents('php://input'), true) ?: [];
    $assetId = preg_replace('/[^a-zA-Z0-9_-]/', '', (string) ($input['asset_id'] ?? ''));
    $comment = trim((string) ($input['comment'] ?? ''));
    $rating = max(1, min(5, (int) ($input['rating'] ?? 5)));
    if ($assetId === '' || $comment === '') {
        http_response_code(422);
        echo json_encode(['success' => false, 'message' => 'Aset dan feedback wajib diisi']);
        return;
    }
    $dir = BASE_PATH . '/storage/app';
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
    $path = $dir . '/reviews_' . $assetId . '.json';
    $reviews = is_file($path) ? (json_decode(file_get_contents($path), true) ?: []) : [];
    array_unshift($reviews, [
        'id' => uniqid('rev_', true),
        'user_name' => (string) ($_SESSION['name'] ?? 'Pengguna IndoFX'),
        'rating' => $rating,
        'comment' => $comment,
        'date' => date('d F Y'),
    ]);
    file_put_contents($path, json_encode($reviews, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);
    echo json_encode(['success' => true, 'data' => $reviews[0]]);
});

$router->get('/api/reviews', function (): void {
    header('Content-Type: application/json');
    $assetId = preg_replace('/[^a-zA-Z0-9_-]/', '', (string) ($_GET['asset_id'] ?? ''));
    $path = BASE_PATH . '/storage/app/reviews_' . $assetId . '.json';
    $reviews = is_file($path) ? (json_decode(file_get_contents($path), true) ?: []) : [];
    echo json_encode(['success' => true, 'data' => $reviews]);
});
