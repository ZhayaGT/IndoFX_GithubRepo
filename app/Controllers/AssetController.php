<?php

namespace IndoFX\Controllers;

use IndoFX\Core\Controller;
use IndoFX\Models\Asset;
use IndoFX\Models\Library;

class AssetController extends Controller {

    public function detail() {
        // 1. Tangkap public_id dari URL
        $publicId = isset($_GET['id']) ? trim((string) $_GET['id']) : '';

        if ($publicId === '') {
            echo "<div style='padding:50px; text-align:center;'><h2>Error: ID Aset tidak ditemukan!</h2><p>Pastikan Anda mengklik aset dari halaman Beranda atau Eksplorasi.</p></div>";
            return;
        }

        // 2. Tarik data aset dari Supabase
        $assetModel = new Asset();
        $asset = $assetModel->getAssetById($publicId);

        if (!$asset) {
            echo "<div style='padding:50px; text-align:center;'><h2>Error: Aset tidak ditemukan!</h2><p>ID Aset: " . htmlspecialchars($publicId) . "</p></div>";
            return;
        }

        $internalId = (int) $asset['id'];
        $libModel   = new Library();

        // 3. Cek apakah user yang login sudah memiliki aset ini (tabel entitlements)
        $isOwned = false;
        if (isset($_SESSION['user_id'])) {
            $ownedData = $libModel->getOwnedAssetDetail($_SESSION['user_id'], $internalId);
            $isOwned   = !empty($ownedData);
        }

        // 4. Galeri gambar
        $galleryImages = $assetModel->getAssetGallery($internalId);
        if (empty($galleryImages)) {
            $galleryImages = [''];
        }

        // 5. Format file & Changelog dinamis dari tabel asset_files
        $assetFiles = $libModel->getAssetFiles($internalId);
        $fileFormatsArray = $assetModel->getAssetFileFormats($internalId);
        $fileFormatString = !empty($fileFormatsArray) ? implode(', ', $fileFormatsArray) : '.unitypackage';

        $changelogList = [];
        foreach ($assetFiles as $file) {
            $rawNotes = trim((string) ($file['changelog'] ?? ''));
            $notesArray = $rawNotes !== ''
                ? array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $rawNotes))))
                : ['Rilis paket aset versi ' . ($file['version'] ?? '1.0.0') . '.'];

            $changelogList[] = [
                'version' => $file['version'] ?? '1.0.0',
                'date'    => date('d F Y', strtotime($file['created_at'] ?? 'now')),
                'notes'   => $notesArray
            ];
        }

        // 6. Ulasan & Ringkasan Rating dinamis dari tabel reviews
        $rawReviews = $libModel->getAssetReviews($internalId);
        $reviews = [];
        $counts = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
        $sumRating = 0;

        foreach ($rawReviews as $r) {
            $score = max(1, min(5, (int) $r['rating']));
            $counts[$score]++;
            $sumRating += $score;

            $reviews[] = [
                'name'   => $r['user_name'] ?? 'Pengguna IndoFX',
                'date'   => date('d F Y', strtotime($r['created_at'])),
                'rating' => $score,
                'title'  => $r['title'] ?? '',
                'body'   => $r['comment'] ?? ''
            ];
        }

        $totalReviews = count($reviews);
        $reviewSummary = [
            'average' => $totalReviews > 0 ? round($sumRating / $totalReviews, 1) : 0,
            'total'   => $totalReviews,
            'counts'  => $counts
        ];

        // 7. Susun array $detail
        $detail = [
            'file_format'    => $fileFormatString,
            'unity_versions' => $asset['unity_version'] ?? 'Semua Versi',
            'pipelines'      => array_values(array_filter(array_map('trim', explode(',', $asset['render_pipeline'] ?? '')))),
            'gallery'        => $galleryImages,
            'description'    => [
                $asset['description'] ?? 'Deskripsi lengkap belum tersedia untuk aset ini.'
            ],
            'changelog'      => $changelogList
        ];

        // 8. Kirim ke view detail.php
        $this->view('asset/detail', [
            'title'         => htmlspecialchars($asset['title'] ?? 'Detail') . ' - IndoFX',
            'asset'         => $asset,
            'detail'        => $detail,
            'categoryLabel' => $asset['category_name'] ?? 'Kategori',
            'isOwned'       => $isOwned,
            'reviewSummary' => $reviewSummary,
            'reviews'       => $reviews,
        ]);
    }
}