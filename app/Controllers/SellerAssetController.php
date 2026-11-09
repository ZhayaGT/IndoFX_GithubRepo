<?php

namespace IndoFX\Controllers;

use IndoFX\Core\Controller;
use IndoFX\Core\Database;
use IndoFX\Helpers\Upload;
use IndoFX\Models\Asset;

class SellerAssetController extends Controller {
    private const MAX_IMAGE_BYTES   = 5 * 1024 * 1024;
    private const MAX_PACKAGE_BYTES = 200 * 1024 * 1024;
    private const PIPELINES         = ['Built-in', 'URP', 'HDRP'];

    // Satu form untuk tambah dan edit; mode ditentukan query edit_id.
    public function form(): void {
        self::guardSeller();

        $editId   = trim((string) ($_GET['edit_id'] ?? ''));
        $asset    = null;
        $gallery  = [];
        $file     = null;
        $categories = (new Asset())->categories();

        if ($editId !== '') {
            $asset = (new Asset())->findByPublicId($editId);

            if ($asset === null) {
                $this->notFound();
                return;
            }
            if ((int) $asset['seller_id'] !== (int) $_SESSION['user_id']) {
                http_response_code(403);
                echo 'Aset ini bukan milik Anda.';
                return;
            }

            $gallery = (new Asset())->getAssetGallery((int) $asset['id']);
            $file    = (new Asset())->latestFileFor((int) $asset['id']);
        }

        $this->view('dashboard/add_asset', [
            'mode'       => $editId === '' ? 'create' : 'edit',
            'asset'      => $asset,
            'gallery'    => $gallery,
            'file'       => $file,
            'categories' => $categories,
            'storeName'  => $this->storeName(),
            'error'      => $_SESSION['asset_error'] ?? null,
        ]);

        unset($_SESSION['asset_error']);
    }

    // Simpan aset; semua baris dan berkas masuk atau batal bersama.
    public function save(): void {
        self::guardSeller();

        $editId = trim((string) ($_POST['id'] ?? ''));

        // $_POST kosong padahal ada body: unggahan dipotong batas server.
        if ((int) ($_SERVER['CONTENT_LENGTH'] ?? 0) > 0 && $_POST === []) {
            $_SESSION['asset_error'] = 'Berkas terlalu besar untuk batas server. Jalankan server dengan php.upload.ini.';
            $this->redirect($this->formPath($editId));
        }

        $data = [
            'title'            => trim((string) ($_POST['title'] ?? '')),
            'category_id'      => (int) ($_POST['category_id'] ?? 0),
            'description'      => trim((string) ($_POST['description'] ?? '')),
            'unity_version'    => trim((string) ($_POST['unity_version'] ?? '')),
            'render_pipeline'  => implode(', ', array_values(array_intersect(
                self::PIPELINES,
                array_map('strval', (array) ($_POST['render_pipeline'] ?? []))
            ))),
            'price'            => trim((string) ($_POST['price'] ?? '')) === '' ? 0 : (int) $_POST['price'],
            'preview_video_url' => trim((string) ($_POST['preview_video_url'] ?? '')),
            'how_to_use'       => trim((string) ($_POST['how_to_use'] ?? '')),
        ];

        $assetModel = new Asset();
        $error = $this->validate($data, $assetModel, $editId === '');

        if ($error !== null) {
            $_SESSION['asset_error'] = $error;
            $this->redirect($this->formPath($editId));
        }

        $this->persist($assetModel, $editId, $data);
    }

    // Validasi input teks dan tipe berkas sebelum transaksi dibuka.
    private function validate(array $data, Asset $assetModel, bool $isCreate): ?string {
        if ($data['title'] === '' || mb_strlen($data['title']) > 150) {
            return 'Judul aset wajib diisi, maksimal 150 karakter.';
        }

        $categoryIds = array_map(static fn (array $row): int => (int) $row['id'], $assetModel->categories());
        if (!in_array($data['category_id'], $categoryIds, true)) {
            return 'Kategori aset wajib dipilih.';
        }
        if ($data['price'] < 0) {
            return 'Harga aset tidak boleh negatif.';
        }
        if ($data['unity_version'] === '') {
            return 'Versi Unity wajib dipilih.';
        }
        if ($data['render_pipeline'] === '') {
            return 'Pilih minimal satu render pipeline.';
        }

        $image = Upload::validateImage($_FILES['image'] ?? [], self::MAX_IMAGE_BYTES);
        if (!$image['ok'] && $isCreate) {
            return $image['error'];
        }
        if (!$image['ok'] && ($_FILES['image']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
            return $image['error'];
        }

        $package = Upload::validatePackage($_FILES['package'] ?? [], self::MAX_PACKAGE_BYTES);
        if ($isCreate && !$package['ok']) {
            return $package['error'];
        }
        if (!$package['ok'] && ($_FILES['package']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
            return $package['error'];
        }

        return null;
    }

    private function persist(Asset $assetModel, string $editId, array $data): void {
        $sellerId = (int) $_SESSION['user_id'];
        $db = Database::getInstance();
        $moved = [];

        try {
            $db->beginTransaction();

            if ($editId === '') {
                $data['slug'] = $assetModel->uniqueSlug($data['title']);
                $row = $assetModel->createForSeller($sellerId, $data);
                $assetId = (int) $row['id'];
                $publicId = (string) $row['public_id'];
            } else {
                $existing = $assetModel->findByPublicId($editId);

                if ($existing === null || (int) $existing['seller_id'] !== $sellerId) {
                    $db->rollback();
                    http_response_code(403);
                    echo 'Aset ini bukan milik Anda.';
                    return;
                }

                $assetId = (int) $existing['id'];
                $publicId = (string) $existing['public_id'];
                $data['slug'] = $assetModel->uniqueSlug($data['title'], $assetId);

                if (!$assetModel->updateForSeller($assetId, $sellerId, $data)) {
                    $db->rollback();
                    http_response_code(403);
                    echo 'Aset ini bukan milik Anda.';
                    return;
                }
            }

            $image = Upload::image($_FILES['image'] ?? [], self::MAX_IMAGE_BYTES);

            if ($image['ok']) {
                $moved[] = 'public/' . $image['name'];
                $assetModel->replaceImages($assetId, [$image['name']]);
            }

            $package = Upload::package($_FILES['package'] ?? [], self::MAX_PACKAGE_BYTES, $publicId);

            if ($package['ok']) {
                $moved[] = $package['file_path'];
                $assetModel->addFile($assetId, $package);
            }

            $db->commit();
        } catch (\Throwable $e) {
            $db->rollback();

            foreach ($moved as $path) {
                @unlink(BASE_PATH . '/' . $path);
            }

            $_SESSION['asset_error'] = 'Aset gagal disimpan: ' . $e->getMessage();
            $this->redirect($this->formPath($editId));
        }

        $this->redirect('/dashboard/assets');
    }

    private function formPath(string $editId): string {
        return $editId === '' ? '/dashboard/add-asset' : '/dashboard/add-asset?edit_id=' . urlencode($editId);
    }

    private function storeName(): string {
        return trim((string) ($_SESSION['store_name'] ?? '')) ?: (string) ($_SESSION['name'] ?? 'Toko Saya');
    }

    private function notFound(): void {
        http_response_code(404);
        require BASE_PATH . '/resources/views/errors/404.php';
    }
}