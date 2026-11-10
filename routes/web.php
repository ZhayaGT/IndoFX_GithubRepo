<?php

/** @var \IndoFX\Core\Router $router */

use IndoFX\Core\Database;

// ==========================================
// 1. BERANDA & KATALOG
// ==========================================
$router->add('GET', '/', 'HomeController', 'index');
$router->add('GET', '/catalog/explore', 'CatalogController', 'explore');

// Halaman Detail Aset (Arahkan ke AssetController)
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

// Pendaftaran penjual (upgrade role pembeli)
$router->get('/seller/register', 'SellerController', 'upgradeForm');
$router->post('/seller/register', 'SellerController', 'upgrade');

// Profil Publik Kreator
$router->add('GET', '/creator/profile', function () {
    require __DIR__ . '/../resources/views/creator/profile.php';
});

// ==========================================
// 3. DASHBOARD PENJUAL & PROFIL
// ==========================================
$router->get('/dashboard', 'SellerController', 'dashboard');
$router->get('/dashboard/assets', 'SellerController', 'assets');
$router->get('/dashboard/transactions', 'SellerController', 'transactions');
$router->get('/dashboard/profile', 'SellerController', 'profile');
$router->post('/dashboard/profile', 'SellerController', 'saveProfile');

// Form aset penjual
$router->get('/dashboard/add-asset', 'SellerAssetController', 'form');
$router->post('/dashboard/assets/save', 'SellerAssetController', 'save');

// Unduhan paket aset, wajib pemilik atau pembeli berhak
$router->get('/download', 'MediaController', 'download');

// Kelola Profil Pembeli (Format URL dengan strip: /dashboard/ubah-profil)
$router->get('/dashboard/ubah-profil', function () {
    \IndoFX\Core\Controller::guardLogin();
    require BASE_PATH . '/resources/views/dashboard/ubahprofile.php';
});

$router->post('/dashboard/ubah-profil', function () {
    \IndoFX\Core\Controller::guardLogin();

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

// Kelola Profil Pembeli (Format URL tanpa strip: /dashboard/ubahprofile)
$router->get('/dashboard/ubahprofile', function () {
    \IndoFX\Core\Controller::guardLogin();
    require BASE_PATH . '/resources/views/dashboard/ubahprofile.php';
});

$router->post('/dashboard/ubahprofile', function () {
    \IndoFX\Core\Controller::guardLogin();

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

    header('Location: /dashboard/ubahprofile?success=1');
    exit;
});

// ==========================================
// 4. KERANJANG, CHECKOUT & INVOICE
// ==========================================

// Keranjang Belanja
$router->get('/cart', function () {
    \IndoFX\Core\Controller::guardLogin();
    $cartPath = BASE_PATH . '/resources/views/cart/keranjang.php';
    if (!file_exists($cartPath)) {
        $cartPath = BASE_PATH . '/resources/views/keranjang.php';
    }
    require $cartPath;
});

// Checkout Pembayaran (Sesuai folder checkout/pembayaran.php)
$router->get('/checkout', function () {
    \IndoFX\Core\Controller::guardLogin();

    $checkoutCandidates = [
        BASE_PATH . '/resources/views/checkout/pembayaran.php',
        BASE_PATH . '/resources/views/checkout/checkout.php',
        BASE_PATH . '/resources/views/pembayaran.php',
        BASE_PATH . '/resources/views/checkout.php',
    ];

    foreach ($checkoutCandidates as $path) {
        if (file_exists($path)) {
            require $path;
            return;
        }
    }

    http_response_code(404);
    die('Berkas halaman checkout pembayaran tidak ditemukan.');
});

// Riwayat Transaksi & Invoice
$router->get('/invoice', function () {
    \IndoFX\Core\Controller::guardLogin();
    $invoicePath = BASE_PATH . '/resources/views/dashboard/invoice.php';
    if (!file_exists($invoicePath)) {
        $invoicePath = BASE_PATH . '/resources/views/invoice.php';
    }
    require $invoicePath;
});

// ==========================================
// 5. ALUR LIBRARY PENGGUNA TERISOLASI
// ==========================================

if (!function_exists('getUserLibraryFile')) {
    function getUserLibraryFile(string|int $userId): string
    {
        $dir = BASE_PATH . '/storage/app';
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        return $dir . '/library_' . preg_replace('/[^a-zA-Z0-9_-]/', '', (string)$userId) . '.json';
    }
}

// Halaman Tampilan Library
$router->get('/library', function () {
    \IndoFX\Core\Controller::guardLogin();

    $userId = $_SESSION['user_id'];
    $filePath = getUserLibraryFile($userId);
    $libraryItems = [];

    if (file_exists($filePath)) {
        $content = file_get_contents($filePath);
        $libraryItems = json_decode($content, true) ?: [];
    }

    $libCandidates = [
        BASE_PATH . '/resources/views/library.php',
        BASE_PATH . '/resources/views/dashboard/library.php'
    ];

    foreach ($libCandidates as $path) {
        if (file_exists($path)) {
            require $path;
            return;
        }
    }

    http_response_code(404);
    die('Berkas tampilan library tidak ditemukan.');
});

// API Tambah Aset ke Library
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
    $seller = $input['seller'] ?? ($input['creator'] ?? 'Rimba Digital');
    $image = $input['image'] ?? '/assets/img/forest-mist.jpg';
    $pipelines = $input['pipelines'] ?? ['URP', 'HDRP'];

    $filePath = getUserLibraryFile($userId);
    $currentData = [];
    if (file_exists($filePath)) {
        $currentData = json_decode(file_get_contents($filePath), true) ?: [];
    }

    foreach ($currentData as $item) {
        if ($item['id'] === $assetId) {
            echo json_encode(['success' => true, 'message' => 'Aset sudah ada di library Anda']);
            exit;
        }
    }

    $newItem = [
        'id' => $assetId,
        'title' => $title,
        'seller' => $seller,
        'creator' => $seller,
        'image' => $image,
        'pipelines' => $pipelines,
        'type' => $input['type'] ?? 'Gratis',
        'acquired_date' => date('d M Y')
    ];

    array_unshift($currentData, $newItem);
    file_put_contents($filePath, json_encode($currentData, JSON_PRETTY_PRINT));

    echo json_encode(['success' => true, 'message' => 'Berhasil ditambahkan ke library']);
    exit;
});

// API Hapus Aset dari Library
$router->post('/api/library/remove', function () {
    header('Content-Type: application/json');

    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Unauthorized']);
        exit;
    }

    $userId = $_SESSION['user_id'];
    $input = json_decode(file_get_contents('php://input'), true);
    $assetId = (string)($input['id'] ?? '');

    $filePath = getUserLibraryFile($userId);
    if (file_exists($filePath)) {
        $currentData = json_decode(file_get_contents($filePath), true) ?: [];
        $currentData = array_values(array_filter($currentData, function ($item) use ($assetId) {
            return (string)($item['id'] ?? '') !== $assetId;
        }));
        file_put_contents($filePath, json_encode($currentData, JSON_PRETTY_PRINT));
    }

    echo json_encode(['success' => true, 'message' => 'Aset berhasil dihapus']);
    exit;
});

// ==========================================
// 6. API ULASAN PUBLIK ASET
// ==========================================

$router->post('/api/reviews/add', function () {
    header('Content-Type: application/json');

    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Silakan login terlebih dahulu']);
        exit;
    }

    $input = json_decode(file_get_contents('php://input'), true);
    $assetId = preg_replace('/[^a-zA-Z0-9_-]/', '', (string)($input['asset_id'] ?? 'free-forest-mist'));
    $rating  = (int) ($input['rating'] ?? 5);
    $comment = trim((string) ($input['comment'] ?? ''));

    if ($comment === '') {
        echo json_encode(['success' => false, 'message' => 'Feedback tidak boleh kosong']);
        exit;
    }

    $storageDir = BASE_PATH . '/storage/app';
    if (!is_dir($storageDir)) {
        mkdir($storageDir, 0777, true);
    }

    $reviewFile = $storageDir . '/reviews_' . $assetId . '.json';
    $reviews = [];
    if (file_exists($reviewFile)) {
        $reviews = json_decode(file_get_contents($reviewFile), true) ?: [];
    }

    $userName = (string) ($_SESSION['name'] ?? $_SESSION['user_name'] ?? 'Pengguna IndoFX');

    array_unshift($reviews, [
        'id'        => uniqid('rev_'),
        'user_name' => $userName,
        'rating'    => $rating,
        'comment'   => $comment,
        'date'      => date('d F Y')
    ]);

    file_put_contents($reviewFile, json_encode($reviews, JSON_PRETTY_PRINT));

    echo json_encode(['success' => true, 'message' => 'Ulasan berhasil disimpan']);
    exit;
});

$router->get('/api/reviews', function () {
    header('Content-Type: application/json');
    $assetId = preg_replace('/[^a-zA-Z0-9_-]/', '', (string)($_GET['asset_id'] ?? 'free-forest-mist'));
    $reviewFile = BASE_PATH . '/storage/app/reviews_' . $assetId . '.json';

    $reviews = [];
    if (file_exists($reviewFile)) {
        $reviews = json_decode(file_get_contents($reviewFile), true) ?: [];
    }

    echo json_encode(['success' => true, 'data' => $reviews]);
    exit;
});

// Halaman Detail Aset Milik User di Library (Untuk Unduh)
$router->get('/library/detail', function () {
    \IndoFX\Core\Controller::guardLogin();

    $detailLibCandidates = [
        BASE_PATH . '/resources/views/dashboard/detail-library.php',
        BASE_PATH . '/resources/views/detail-library.php',
    ];

    foreach ($detailLibCandidates as $path) {
        if (file_exists($path)) {
            require $path;
            return;
        }
    }

    http_response_code(404);
    die('Berkas detail-library.php tidak ditemukan.');
});
