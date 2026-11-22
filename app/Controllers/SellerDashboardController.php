<?php

namespace IndoFX\Controllers;

use IndoFX\Core\Controller;
use IndoFX\Core\Database;
use PDO;

class SellerDashboardController extends Controller {

    private function requireSeller() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }
        if (($_SESSION['role'] ?? '') !== 'seller') {
            $this->redirect('/seller/register');
        }
    }

    // 1. Halaman Dashboard Utama (/dashboard -> dashboard/main.php)
    public function index() {
        $this->requireSeller();
        $db = Database::getInstance()->getConnection();
        $sellerId = (int) $_SESSION['user_id'];

        // Ambil nama toko
        $stmtStore = $db->prepare("
            SELECT COALESCE(sp.store_name, up.name, 'Toko Penjual') AS store_name
            FROM users u
            LEFT JOIN seller_profiles sp ON u.id = sp.user_id
            LEFT JOIN user_profiles up ON u.id = up.user_id
            WHERE u.id = :id LIMIT 1
        ");
        $stmtStore->execute([':id' => $sellerId]);
        $storeName = $stmtStore->fetchColumn() ?: ($_SESSION['store_name'] ?? $_SESSION['name'] ?? 'Penjual');

        // Ambil semua aset milik penjual + jumlah terjualnya
        $assets = $this->fetchSellerAssets($db, $sellerId);

        // Hitung statistik ringkasan
        $totalAssets     = count($assets);
        $publishedAssets = 0;
        $totalDownloads  = 0;

        foreach ($assets as $a) {
            if ($a['status'] === 'Disetujui') {
                $publishedAssets++;
            }
            $totalDownloads += (int) $a['download_count'];
        }

        // Ambil riwayat transaksi penjualan aset milik penjual ini (status lunas)
        $stmtSales = $db->prepare("
            SELECT 
                t.invoice_number,
                a.title AS asset_title,
                td.price,
                t.payment_type,
                t.status,
                COALESCE(t.paid_at, t.created_at) AS tx_date
            FROM transaction_details td
            INNER JOIN transactions t ON td.transaction_id = t.id
            INNER JOIN assets a ON td.asset_id = a.id
            WHERE a.seller_id = :seller_id
              AND LOWER(t.status) IN ('settlement', 'capture', 'success')
            ORDER BY COALESCE(t.paid_at, t.created_at) DESC
        ");
        $stmtSales->execute([':seller_id' => $sellerId]);
        $rawSales = $stmtSales->fetchAll(PDO::FETCH_ASSOC);

        $totalSold    = count($rawSales);
        $totalRevenue = 0;
        $recentSales  = [];

        foreach ($rawSales as $s) {
            $price = (float) $s['price'];
            $totalRevenue += $price;
            $recentSales[] = [
                'invoice_no'  => $s['invoice_number'],
                'asset_title' => $s['asset_title'],
                'price'       => $price,
                'date'        => date('d M Y', strtotime($s['tx_date']))
            ];
        }

        $this->view('dashboard/main', [
            'storeName'    => $storeName,
            'stats'        => [
                'total_assets'     => $totalAssets,
                'published_assets' => $publishedAssets,
                'total_sold'       => $totalSold,
                'total_downloads'  => $totalDownloads,
                'total_revenue'    => $totalRevenue
            ],
            'recentAssets' => array_slice($assets, 0, 5),
            'recentSales'  => array_slice($recentSales, 0, 5)
        ]);
    }

    // 2. Halaman Semua Aset Saya (/dashboard/assets -> dashboard/assets.php)
    public function assets() {
        $this->requireSeller();
        $db = Database::getInstance()->getConnection();
        $sellerId = (int) $_SESSION['user_id'];

        $stmtStore = $db->prepare("
            SELECT COALESCE(sp.store_name, up.name, 'Toko Penjual') AS store_name
            FROM users u
            LEFT JOIN seller_profiles sp ON u.id = sp.user_id
            LEFT JOIN user_profiles up ON u.id = up.user_id
            WHERE u.id = :id LIMIT 1
        ");
        $stmtStore->execute([':id' => $sellerId]);
        $storeName = $stmtStore->fetchColumn() ?: ($_SESSION['store_name'] ?? $_SESSION['name'] ?? 'Penjual');

        $assets = $this->fetchSellerAssets($db, $sellerId);

        $this->view('dashboard/dashboard', [
            'storeName' => $storeName,
            'assets'    => $assets
        ]);
    }

    // 4. Menampilkan Form Tambah / Edit Aset (/dashboard/add-asset)
    public function addAssetForm() {
        $this->requireSeller();
        $db = Database::getInstance()->getConnection();
        $sellerId = (int) $_SESSION['user_id'];

        // Ambil nama toko
        $stmtStore = $db->prepare("
            SELECT COALESCE(sp.store_name, up.name, 'Toko Penjual') AS store_name
            FROM users u
            LEFT JOIN seller_profiles sp ON u.id = sp.user_id
            LEFT JOIN user_profiles up ON u.id = up.user_id
            WHERE u.id = :id LIMIT 1
        ");
        $stmtStore->execute([':id' => $sellerId]);
        $storeName = $stmtStore->fetchColumn() ?: ($_SESSION['store_name'] ?? $_SESSION['name'] ?? 'Penjual');

        // Ambil daftar kategori langsung dari Supabase
        $categories = $db->query("SELECT id, name, slug FROM categories ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);

        // Cek apakah ini mode Edit (?edit_id=...)
        $editId = isset($_GET['edit_id']) ? (int) $_GET['edit_id'] : 0;
        $asset = null;
        $assetImage = null;
        $assetFile = null;

        if ($editId > 0) {
            $stmtAsset = $db->prepare("
                SELECT * FROM assets 
                WHERE id = :id AND seller_id = :seller_id AND deleted_at IS NULL 
                LIMIT 1
            ");
            $stmtAsset->execute([':id' => $editId, ':seller_id' => $sellerId]);
            $asset = $stmtAsset->fetch(PDO::FETCH_ASSOC);

            if (!$asset) {
                $_SESSION['error_msg'] = 'Aset tidak ditemukan atau bukan milik Anda.';
                $this->redirect('/dashboard/assets');
            }

            // Ambil gambar pratinjau utama
            $stmtImg = $db->prepare("SELECT * FROM asset_images WHERE asset_id = :id ORDER BY sort_order ASC, id ASC LIMIT 1");
            $stmtImg->execute([':id' => $editId]);
            $assetImage = $stmtImg->fetch(PDO::FETCH_ASSOC);

            // Ambil berkas paket terbaru
            $stmtFile = $db->prepare("SELECT * FROM asset_files WHERE asset_id = :id ORDER BY id DESC LIMIT 1");
            $stmtFile->execute([':id' => $editId]);
            $assetFile = $stmtFile->fetch(PDO::FETCH_ASSOC);
        }

        $this->view('dashboard/add_asset', [
            'storeName'  => $storeName,
            'categories' => $categories,
            'asset'      => $asset,
            'assetImage' => $assetImage,
            'assetFile'  => $assetFile
        ]);
    }

    // 3. Memproses Tambah Aset Baru (POST /dashboard/add-asset)
    public function storeAsset() {
        $this->requireSeller();
        $db = Database::getInstance()->getConnection();
        $sellerId = (int) $_SESSION['user_id'];

        $title        = trim($_POST['title'] ?? '');
        $categoryId   = (int) ($_POST['category_id'] ?? 0);
        $description  = trim($_POST['description'] ?? '');
        $howToUse     = trim($_POST['how_to_use'] ?? '');
        $unityVersion = trim($_POST['unity_version'] ?? '2022.3 LTS');
        $lisensi      = trim($_POST['lisensi'] ?? 'Standard Unity Asset Store EULA');
        $videoUrl     = trim($_POST['preview_video_url'] ?? '');
        $pricingType = $_POST['pricing_type'] ?? 'paid';
        $rawPrice    = (int) ($_POST['price'] ?? 0);

        if ($pricingType === 'paid' && $rawPrice < 5000) {
            $_SESSION['error_msg'] = 'Harga jual aset berbayar tidak boleh minus atau kurang dari Rp5.000.';
            $this->redirect('/dashboard/add-asset');
        }
        $price = ($pricingType === 'free') ? 0 : $rawPrice;

        $pipelines = $_POST['render_pipeline'] ?? ['URP'];
        $renderPipeline = is_array($pipelines) ? implode(', ', $pipelines) : 'URP';

        if ($title === '' || $categoryId <= 0 || $description === '') {
            $_SESSION['error_msg'] = 'Judul, Kategori, dan Deskripsi aset wajib diisi.';
            $this->redirect('/dashboard/add-asset');
        }

        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title), '-')) . '-' . time();

        try {
            $db->beginTransaction();

            // 1. Insert ke tabel assets (status awal: pending)
            $stmt = $db->prepare("
                INSERT INTO assets (
                    seller_id, category_id, title, slug, description, how_to_use,
                    price, render_pipeline, unity_version, status, preview_video_url,
                    lisensi, download_count, created_at, updated_at
                ) VALUES (
                    :seller_id, :category_id, :title, :slug, :description, :how_to_use,
                    :price, :render_pipeline, :unity_version, 'pending', :preview_video_url,
                    :lisensi, 0, NOW(), NOW()
                ) RETURNING id
            ");
            $stmt->execute([
                ':seller_id'         => $sellerId,
                ':category_id'       => $categoryId,
                ':title'             => $title,
                ':slug'              => $slug,
                ':description'       => $description,
                ':how_to_use'        => $howToUse,
                ':price'             => $price,
                ':render_pipeline'   => $renderPipeline,
                ':unity_version'     => $unityVersion,
                ':preview_video_url' => $videoUrl !== '' ? $videoUrl : null,
                ':lisensi'           => $lisensi
            ]);
            $assetId = (int) $stmt->fetchColumn();

            // 2. Upload Gambar Pratinjau (jika ada)
            if (isset($_FILES['preview_image']) && $_FILES['preview_image']['error'] === UPLOAD_ERR_OK) {
                $imgDir = BASE_PATH . '/public/uploads/asset_previews/';
                if (!is_dir($imgDir)) mkdir($imgDir, 0777, true);

                $imgExt = strtolower(pathinfo($_FILES['preview_image']['name'], PATHINFO_EXTENSION));
                $imgName = 'preview_' . $assetId . '_' . time() . '.' . $imgExt;
                if (move_uploaded_file($_FILES['preview_image']['tmp_name'], $imgDir . $imgName)) {
                    $db->prepare("
                        INSERT INTO asset_images (asset_id, image_path, sort_order, created_at, updated_at)
                        VALUES (:asset_id, :image_path, 1, NOW(), NOW())
                    ")->execute([
                        ':asset_id'   => $assetId,
                        ':image_path' => '/uploads/asset_previews/' . $imgName
                    ]);
                }
            }

            // 3. Upload Berkas Aset (.unitypackage / .zip / .rar)
            if (isset($_FILES['package_file']) && $_FILES['package_file']['error'] === UPLOAD_ERR_OK) {
                $pkgDir = BASE_PATH . '/public/uploads/asset_files/';
                if (!is_dir($pkgDir)) mkdir($pkgDir, 0777, true);

                $origName = $_FILES['package_file']['name'];
                $pkgExt   = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
                $pkgName  = 'asset_' . $assetId . '_' . time() . '.' . $pkgExt;
                if (move_uploaded_file($_FILES['package_file']['tmp_name'], $pkgDir . $pkgName)) {
                    $db->prepare("
                        INSERT INTO asset_files (
                            asset_id, file_path, original_name, mime_type, extension, file_size, version, created_at, updated_at
                        ) VALUES (
                            :asset_id, :file_path, :original_name, :mime_type, :extension, :file_size, '1.0', NOW(), NOW()
                        )
                    ")->execute([
                        ':asset_id'      => $assetId,
                        ':file_path'     => '/uploads/asset_files/' . $pkgName,
                        ':original_name' => $origName,
                        ':mime_type'     => $_FILES['package_file']['type'] ?: 'application/octet-stream',
                        ':extension'     => $pkgExt,
                        ':file_size'     => (int) $_FILES['package_file']['size']
                    ]);
                }
            }

            $db->commit();
            $_SESSION['success_msg'] = 'Aset baru berhasil diajukan dan sedang menunggu moderasi Admin.';
            $this->redirect('/dashboard/assets');
        } catch (\Exception $e) {
            $db->rollBack();
            $_SESSION['error_msg'] = 'Gagal menyimpan aset: ' . $e->getMessage();
            $this->redirect('/dashboard/add-asset');
        }
    }

    // 4. Memproses Edit & Perbarui Aset (POST /dashboard/edit-asset)
    public function updateAsset() {
        $this->requireSeller();
        $db = Database::getInstance()->getConnection();
        $sellerId = (int) $_SESSION['user_id'];
        $assetId  = (int) ($_POST['asset_id'] ?? 0);

        // Pastikan aset milik penjual ini
        $stmtOld = $db->prepare("SELECT * FROM assets WHERE id = :id AND seller_id = :seller_id LIMIT 1");
        $stmtOld->execute([':id' => $assetId, ':seller_id' => $sellerId]);
        $oldAsset = $stmtOld->fetch(PDO::FETCH_ASSOC);

        if (!$oldAsset) {
            $_SESSION['error_msg'] = 'Aset tidak ditemukan.';
            $this->redirect('/dashboard/assets');
        }

        $title        = trim($_POST['title'] ?? '');
        $categoryId   = (int) ($_POST['category_id'] ?? $oldAsset['category_id']);
        $description  = trim($_POST['description'] ?? '');
        $howToUse     = trim($_POST['how_to_use'] ?? '');
        $unityVersion = trim($_POST['unity_version'] ?? $oldAsset['unity_version']);
        $lisensi      = trim($_POST['lisensi'] ?? $oldAsset['lisensi']);
        $videoUrl     = trim($_POST['preview_video_url'] ?? '');
        $pricingType = $_POST['pricing_type'] ?? 'paid';
        $rawPrice    = (int) ($_POST['price'] ?? 0);

        if ($pricingType === 'paid' && $rawPrice < 5000) {
            $_SESSION['error_msg'] = 'Harga jual aset berbayar tidak boleh minus atau kurang dari Rp5.000.';
            $this->redirect('/dashboard/add-asset?edit_id=' . $assetId);
        }
        $newPrice = ($pricingType === 'free') ? 0 : $rawPrice;

        $pipelines = $_POST['render_pipeline'] ?? ['URP'];
        $renderPipeline = is_array($pipelines) ? implode(', ', $pipelines) : 'URP';

        // Re-moderasi: jika harga berubah atau ada file paket baru, status kembali jadi 'pending'
        $hasNewPackage = isset($_FILES['package_file']) && $_FILES['package_file']['error'] === UPLOAD_ERR_OK;
        $priceChanged  = ((int) $oldAsset['price'] !== $newPrice);
        $newStatus     = ($hasNewPackage || $priceChanged) ? 'pending' : $oldAsset['status'];

        try {
            $db->beginTransaction();

            $stmt = $db->prepare("
                UPDATE assets SET
                    category_id       = :category_id,
                    title             = :title,
                    description       = :description,
                    how_to_use        = :how_to_use,
                    price             = :price,
                    render_pipeline   = :render_pipeline,
                    unity_version     = :unity_version,
                    status            = :status,
                    preview_video_url = :preview_video_url,
                    lisensi           = :lisensi,
                    updated_at        = NOW()
                WHERE id = :id AND seller_id = :seller_id
            ");
            $stmt->execute([
                ':category_id'       => $categoryId,
                ':title'             => $title,
                ':description'       => $description,
                ':how_to_use'        => $howToUse,
                ':price'             => $newPrice,
                ':render_pipeline'   => $renderPipeline,
                ':unity_version'     => $unityVersion,
                ':status'            => $newStatus,
                ':preview_video_url' => $videoUrl !== '' ? $videoUrl : null,
                ':lisensi'           => $lisensi,
                ':id'                => $assetId,
                ':seller_id'         => $sellerId
            ]);

            // Jika upload gambar pratinjau baru
            if (isset($_FILES['preview_image']) && $_FILES['preview_image']['error'] === UPLOAD_ERR_OK) {
                $imgDir = BASE_PATH . '/public/uploads/asset_previews/';
                if (!is_dir($imgDir)) mkdir($imgDir, 0777, true);

                $imgExt  = strtolower(pathinfo($_FILES['preview_image']['name'], PATHINFO_EXTENSION));
                $imgName = 'preview_' . $assetId . '_' . time() . '.' . $imgExt;
                if (move_uploaded_file($_FILES['preview_image']['tmp_name'], $imgDir . $imgName)) {
                    $db->prepare("DELETE FROM asset_images WHERE asset_id = :id AND sort_order = 1")->execute([':id' => $assetId]);
                    $db->prepare("
                        INSERT INTO asset_images (asset_id, image_path, sort_order, created_at, updated_at)
                        VALUES (:asset_id, :image_path, 1, NOW(), NOW())
                    ")->execute([
                        ':asset_id'   => $assetId,
                        ':image_path' => '/uploads/asset_previews/' . $imgName
                    ]);
                }
            }

            // Jika upload berkas paket versi baru
            if ($hasNewPackage) {
                $pkgDir = BASE_PATH . '/public/uploads/asset_files/';
                if (!is_dir($pkgDir)) mkdir($pkgDir, 0777, true);

                $origName = $_FILES['package_file']['name'];
                $pkgExt   = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
                $pkgName  = 'asset_' . $assetId . '_' . time() . '.' . $pkgExt;
                if (move_uploaded_file($_FILES['package_file']['tmp_name'], $pkgDir . $pkgName)) {
                    $db->prepare("
                        INSERT INTO asset_files (
                            asset_id, file_path, original_name, mime_type, extension, file_size, version, created_at, updated_at
                        ) VALUES (
                            :asset_id, :file_path, :original_name, :mime_type, :extension, :file_size, '1.1', NOW(), NOW()
                        )
                    ")->execute([
                        ':asset_id'      => $assetId,
                        ':file_path'     => '/uploads/asset_files/' . $pkgName,
                        ':original_name' => $origName,
                        ':mime_type'     => $_FILES['package_file']['type'] ?: 'application/octet-stream',
                        ':extension'     => $pkgExt,
                        ':file_size'     => (int) $_FILES['package_file']['size']
                    ]);
                }
            }

            $db->commit();
            $_SESSION['success_msg'] = 'Perubahan aset berhasil disimpan!';
            $this->redirect('/dashboard/assets');
        } catch (\Exception $e) {
            $db->rollBack();
            $_SESSION['error_msg'] = 'Gagal memperbarui aset: ' . $e->getMessage();
            $this->redirect('/dashboard/add-asset?edit_id=' . $assetId);
        }
    }

    // Helper: Tarik daftar aset penjual beserta jumlah terjual & unduhan
    private function fetchSellerAssets(PDO $db, int $sellerId): array {
        $sql = "
            SELECT 
                a.id,
                a.public_id,
                a.title,
                a.slug,
                a.price,
                a.render_pipeline,
                a.status,
                COALESCE(a.download_count, 0) AS download_count,
                a.created_at,
                COALESCE(c.name, 'VFX Partikel') AS category_name,
                (
                    SELECT COUNT(td.id)
                    FROM transaction_details td
                    INNER JOIN transactions t ON td.transaction_id = t.id
                    WHERE td.asset_id = a.id
                      AND LOWER(t.status) IN ('settlement', 'capture', 'success')
                ) AS sold_count
            FROM assets a
            LEFT JOIN categories c ON a.category_id = c.id
            WHERE a.seller_id = :seller_id
              AND a.deleted_at IS NULL
            ORDER BY a.created_at DESC
        ";

        $stmt = $db->prepare($sql);
        $stmt->execute([':seller_id' => $sellerId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $formatted = [];
        foreach ($rows as $row) {
            $statusDb = strtolower((string) ($row['status'] ?? 'pending'));
            $mappedStatus = 'Pending';
            if (in_array($statusDb, ['published', 'approved', 'active', 'disetujui'])) {
                $mappedStatus = 'Disetujui';
            } elseif (in_array($statusDb, ['rejected', 'ditolak'])) {
                $mappedStatus = 'Ditolak';
            }

            $price = (float) ($row['price'] ?? 0);
            $pipelines = array_values(array_filter(array_map('trim', explode(',', $row['render_pipeline'] ?? 'Built-in'))));

            $formatted[] = [
                'id'             => (int) $row['id'],
                'public_id'      => $row['public_id'],
                'slug'           => $row['slug'],
                'title'          => $row['title'],
                'category'       => $row['category_name'],
                'pipelines'      => $pipelines,
                'price'          => $price,
                'price_text'     => $price <= 0 ? 'Gratis' : 'Rp' . number_format($price, 0, ',', '.'),
                'status'         => $mappedStatus,
                'sold_count'     => (int) $row['sold_count'],
                'download_count' => (int) $row['download_count'],
                'created_at'     => date('d M Y', strtotime($row['created_at']))
            ];
        }

        return $formatted;
    }

    // 5. Halaman Riwayat Transaksi Penjualan (/dashboard/transactions)
    public function transactions() {
        $this->requireSeller();
        $db = Database::getInstance()->getConnection();
        $sellerId = (int) $_SESSION['user_id'];

        // Ambil nama toko
        $stmtStore = $db->prepare("
            SELECT COALESCE(sp.store_name, up.name, 'Toko Penjual') AS store_name
            FROM users u
            LEFT JOIN seller_profiles sp ON u.id = sp.user_id
            LEFT JOIN user_profiles up ON u.id = up.user_id
            WHERE u.id = :id LIMIT 1
        ");
        $stmtStore->execute([':id' => $sellerId]);
        $storeName = $stmtStore->fetchColumn() ?: ($_SESSION['store_name'] ?? $_SESSION['name'] ?? 'Penjual');

        // Ambil seluruh transaksi penjualan aset milik penjual ini
        $stmt = $db->prepare("
            SELECT 
                t.invoice_number,
                t.midtrans_transaction_id,
                a.title AS asset_title,
                td.price,
                COALESCE(t.payment_type, 'QRIS') AS payment_type,
                t.status,
                COALESCE(t.paid_at, t.created_at) AS tx_date,
                COALESCE(up.name, 'Pembeli IndoFX') AS buyer_name,
                COALESCE(u.email, '-') AS buyer_email
            FROM transaction_details td
            INNER JOIN transactions t ON td.transaction_id = t.id
            INNER JOIN assets a ON td.asset_id = a.id
            LEFT JOIN users u ON t.buyer_id = u.id
            LEFT JOIN user_profiles up ON t.buyer_id = up.user_id
            WHERE a.seller_id = :seller_id
              AND LOWER(t.status) IN ('settlement', 'capture', 'success')
            ORDER BY COALESCE(t.paid_at, t.created_at) DESC
        ");
        $stmt->execute([':seller_id' => $sellerId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $transactions = [];
        foreach ($rows as $row) {
            $ts = strtotime($row['tx_date']);
            $transactions[] = [
                'invoice_no'     => $row['invoice_number'],
                'midtrans_ref'   => $row['midtrans_transaction_id'] ?: ('midtrans-' . strtolower($row['invoice_number'])),
                'asset_title'    => $row['asset_title'],
                'buyer_name'     => $row['buyer_name'],
                'buyer_email'    => $row['buyer_email'],
                'price'          => (float) $row['price'],
                'payment_method' => strtoupper(str_replace('_', ' ', $row['payment_type'])),
                'date'           => date('d M Y, H:i', $ts),
                'date_raw'       => date('Y-m-d', $ts)
            ];
        }

        $this->view('dashboard/transactions', [
            'storeName'    => $storeName,
            'transactions' => $transactions
        ]);
    }
}