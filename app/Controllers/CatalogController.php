<?php

namespace IndoFX\Controllers;

use IndoFX\Core\Controller;
use IndoFX\Models\Asset;

class CatalogController extends Controller {
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

        $filteredAssets = $assetModel->getFilteredAssets($filters);

        $this->view('catalog/explore', [
            'title'       => 'Jelajahi Katalog IndoFX',
            'assets'      => $filteredAssets,
            'totalAssets' => count($filteredAssets),
            'filters'     => $filters // Kirim kembali ke view agar filter tetap tercentang
        ]);
    }
}