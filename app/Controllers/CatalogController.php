<?php

namespace IndoFX\Controllers;

use IndoFX\Core\Controller;
use IndoFX\Models\Asset;

class CatalogController extends Controller {
    // Jumlah aset per halaman katalog.
    private const PER_PAGE = 6;

    public function explore() {
        $assetModel = new \IndoFX\Models\Asset();
        
        // Pengecekan status gratis dari URL (?is_free=1) maupun Form Radio (?price_filter_radio=free)
        $isFree = (isset($_GET['is_free']) && $_GET['is_free'] == '1') || 
                  (isset($_GET['price_filter_radio']) && $_GET['price_filter_radio'] === 'free') 
                  ? '1' : '0';

        // Tangkap parameter URL
        $filters = [
            'q'             => $_GET['q'] ?? '',
            'category'      => $_GET['category'] ?? [],
            'min_price'     => $_GET['min_price'] ?? '',
            'max_price'     => $_GET['max_price'] ?? '',
            'is_free'       => $isFree,
            'pipeline'      => $_GET['pipeline'] ?? [],
            'unity_version' => $_GET['unity_version'] ?? '',
            'sort'          => $_GET['sort'] ?? 'terbaru',
        ];

        $page = max(1, (int) ($_GET['page'] ?? 1));

        $total = $assetModel->countFilteredAssets($filters);
        $totalPages = max(1, (int) ceil($total / self::PER_PAGE));
        $page = min($page, $totalPages);

        $filteredAssets = $assetModel->getFilteredAssets($filters + [
            'limit'  => self::PER_PAGE,
            'offset' => ($page - 1) * self::PER_PAGE,
        ]);

        $this->view('catalog/explore', [
            'title'       => 'Jelajahi Katalog IndoFX',
            'assets'      => $filteredAssets,
            'totalAssets' => $total,
            'filters'     => $filters, // Kirim kembali ke view agar filter tetap tercentang
            'page'        => $page,
            'totalPages'  => $totalPages,
        ]);
    }
}