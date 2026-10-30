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

// ==========================================
// ALUR LIBRARY PENGGUNA TERISOLASI PER USER
// ==========================================

// Fungsi pembantu penyimpanan library per akun
if (!function_exists('getUserLibraryFile')) {
    function getUserLibraryFile(string|int $userId): string
    {
        $dir = BASE_PATH . '/storage/app';
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        return $dir . '/library_' . preg_replace('/[^a-zA-Z0-9_-]/', '', $userId) . '.json';
    }
}

// 1. Halaman Tampilan Library
$router->get('/library', function () {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /login');
        exit;
    }

    $userId = $_SESSION['user_id'];
    $filePath = getUserLibraryFile($userId);
    $libraryItems = [];

    if (file_exists($filePath)) {
        $content = file_get_contents($filePath);
        $libraryItems = json_decode($content, true) ?: [];
    }

    require BASE_PATH . '/resources/views/library.php';
});

// 2. API Tambah Aset Gratis ke Library
$router->post('/api/library/add', function () {
    header('Content-Type: application/json');

    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Silakan login terlebih dahulu']);
        exit;
    }

    $userId = $_SESSION['user_id'];
    $input = json_decode(file_get_contents('php://input'), true);

    $assetId = $input['id'] ?? 'free-forest-mist';
    $title = $input['title'] ?? 'Forest Mist & Fog Volume';
    $seller = $input['seller'] ?? 'Rimba Digital';
    $image = $input['image'] ?? '/assets/img/forest-mist.jpg';
    $pipelines = $input['pipelines'] ?? ['URP', 'HDRP'];
    $isFree = true;

    $filePath = getUserLibraryFile($userId);
    $currentData = [];
    if (file_exists($filePath)) {
        $currentData = json_decode(file_get_contents($filePath), true) ?: [];
    }

    // Periksa apakah aset sudah pernah ditambahkan
    foreach ($currentData as $item) {
        if ($item['id'] === $assetId) {
            echo json_encode(['success' => true, 'message' => 'Aset sudah ada di library Anda']);
            exit;
        }
    }

    // Tambahkan item baru ke daftar milik user
    $newItem = [
        'id' => $assetId,
        'title' => $title,
        'seller' => $seller,
        'image' => $image,
        'pipelines' => $pipelines,
        'type' => 'Gratis',
        'acquired_date' => date('d M Y')
    ];

    array_unshift($currentData, $newItem);
    file_put_contents($filePath, json_encode($currentData, JSON_PRETTY_PRINT));

    echo json_encode(['success' => true, 'message' => 'Berhasil ditambahkan ke library']);
    exit;
});

// 3. API Hapus Aset dari Library
$router->post('/api/library/remove', function () {
    header('Content-Type: application/json');

    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Unauthorized']);
        exit;
    }

    $userId = $_SESSION['user_id'];
    $input = json_decode(file_get_contents('php://input'), true);
    $assetId = $input['id'] ?? '';

    $filePath = getUserLibraryFile($userId);
    if (file_exists($filePath)) {
        $currentData = json_decode(file_get_contents($filePath), true) ?: [];
        $currentData = array_values(array_filter($currentData, function ($item) use ($assetId) {
            return $item['id'] !== $assetId;
        }));
        file_put_contents($filePath, json_encode($currentData, JSON_PRETTY_PRINT));
    }

    echo json_encode(['success' => true, 'message' => 'Aset berhasil dihapus']);
    exit;
});
