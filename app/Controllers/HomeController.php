<?php

namespace IndoFX\Controllers;

use IndoFX\Core\Controller;

class HomeController extends Controller {
    public function index() {
        // Menampilkan halaman utama (Landing Page)
        require_once BASE_PATH . '/resources/views/catalog/index.php';
    }
}