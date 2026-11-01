<?php

namespace IndoFX\Controllers;

use IndoFX\Core\Controller;
use IndoFX\Models\Asset;

class CatalogController extends Controller {
    public function explore() {
        $assetModel = new \IndoFX\Models\Asset();
        
        // Tangkap parameter URL
        $filters = [
            'q'             => $_GET['q'] ?? '',
            'category'      => $_GET['category'] ?? [],
            'min_price'     => $_GET['min_price'] ?? '',
            'max_price'     => $_GET['max_price'] ?? '',
            'is_free'       => isset($_GET['price_filter_radio']) && $_GET['price_filter_radio'] === 'free' ? '1' : '0',
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