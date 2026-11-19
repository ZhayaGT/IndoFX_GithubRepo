<?php

namespace IndoFX\Controllers;

use IndoFX\Core\Controller;
use IndoFX\Models\Asset; 

class AssetController extends Controller {

    public function detail() {
        // 1. Tangkap ID dari URL (bukan slug lagi)
        $publicId = isset($_GET['id']) ? trim((string) $_GET['id']) : '';

        if ($publicId === '') {
            echo "<div style='padding:50px; text-align:center;'><h2>Error: ID Aset tidak ditemukan!</h2><p>Pastikan Anda mengklik aset dari halaman Beranda atau Eksplorasi.</p></div>";
            return;
        }

        // 2. Panggil Model Database
        $assetModel = new Asset();
        $asset = $assetModel->getAssetById($publicId); // Menggunakan public_id untuk keamanan

        // 3. Jika aset tidak ada di database Supabase
        if (!$asset) {
            echo "<div style='padding:50px; text-align:center;'><h2>Error: Aset tidak ditemukan!</h2><p>ID Aset: " . htmlspecialchars($publicId) . "</p></div>";
            return;
        }

        // Aset yang belum disetujui hanya boleh dilihat pemiliknya.
        $isOwner = isset($_SESSION['user_id']) && (int) $_SESSION['user_id'] === (int) $asset['seller_id'];
        $isVisible = $asset['status'] === 'published' && $asset['deleted_at'] === null;

        if (!$isVisible && !$isOwner) {
            $this->notFound();
            return;
        }

        // 3. Ambil galeri gambar dari Supabase (atau gunakan array kosong jika belum ada)
        $internalId = $asset['id']; 
        $galleryImages = $assetModel->getAssetGallery($internalId);

        if (empty($galleryImages)) {
             $galleryImages = ['']; 
        }

        // Mengambil format dari asset_files 
        $fileFormatsArray = $assetModel->getAssetFileFormats($internalId);
        // Jika tabel kosong, beri nilai default fallback.
        $fileFormatString = !empty($fileFormatsArray) ? implode(', ', $fileFormatsArray) : '.unitypackage';

        // 5. Susun data detail (Data asli database digabung data sementara untuk ulasan)
        $detail = [
            'file_format'    => $fileFormatString,
            'unity_versions' => $asset['unity_version'] ?? 'Semua Versi',
            'pipelines'      => explode(',', $asset['render_pipeline'] ?? ''),
            'gallery'        => $galleryImages,
            'description'    => [
                $asset['description'] ?? 'Deskripsi lengkap belum tersedia untuk aset ini.'
            ],
            'changelog'      => [
                [
                    'version' => '1.0.0',
                    'date'    => date('d F Y', strtotime($asset['created_at'] ?? 'now')),
                    'notes'   => ['Rilis awal aset ke marketplace.']
                ]
            ],
            'reviews'        => [] // Kosongkan sementara sampai fitur ulasan dibuat
        ];

        // 6. Lempar semua data asli ke view detail.php
        $this->view('asset/detail', [
            'title'         => htmlspecialchars($asset['title'] ?? 'Detail') . ' - IndoFX',
            'asset'         => $asset,
            'detail'        => $detail,
            'categoryLabel' => $asset['category_name'] ?? 'Kategori',
            'reviewSummary' => ['average' => 0, 'total' => 0, 'counts' => [5=>0, 4=>0, 3=>0, 2=>0, 1=>0]],
            'reviews'       => [],
        ]);
    }

    private function notFound(): void
    {
        http_response_code(404);
        require BASE_PATH . '/resources/views/errors/404.php';
    }
}