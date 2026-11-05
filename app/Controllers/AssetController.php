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
            echo "<div style='padding:50px; text-align:center;'><h2>Error: Aset tidak ditemukan!</h2><p>ID Aset: " . htmlspecialchars($id) . "</p></div>";
            return;
        }

        // 3. Ambil galeri gambar dari Supabase (atau gunakan array kosong jika belum ada)
        $internalId = $asset['id']; 
        $galleryImages = $assetModel->getAssetGallery($internalId);

        if (empty($galleryImages)) {
             $galleryImages = ['']; 
        }

        // 5. Susun data detail (Data asli database digabung data sementara untuk ulasan)
        $detail = [
            'file_format'    => $asset['file_format'] ?? '.unitypackage',
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
}