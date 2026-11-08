<?php

namespace IndoFX\Controllers;

use IndoFX\Core\Controller;
use IndoFX\Models\Asset;

class HomeController extends Controller {
    public function index() {
        // 1. Inisialisasi modal Asset
        $assetModel = new Asset();

        // 2. Ambil data dari Supabase
        $latestAssets= $assetModel->getlatestAssets(4);
        $freeAssets= $assetModel->getFreeAssets(4);

        // Menampilkan halaman beranda katalog
        $this->view('catalog/index', [
            'title' => 'Beranda IndoFX',
            'latestAssets' => $latestAssets,
            'freeAssets' => $freeAssets,
        ]);
    }
}