<?php

namespace IndoFX\Controllers;

use IndoFX\Core\Controller;
use IndoFX\Models\Asset;

class CatalogController extends Controller {
    public function explore() {
        $assetModel = new Asset();
        $allAssets = $assetModel->getAllAssets();

        $this->view('catalog/explore', [
            'title' => 'Jelajahi Katalog IndoFX',
            'assets' => $allAssets,
            'totalAssets' => count($allAssets),
        ]);
    }
}