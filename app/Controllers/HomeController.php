<?php

namespace IndoFX\Controllers;

use IndoFX\Core\Controller;

class HomeController extends Controller
{
    public function index()
    {
        // Menampilkan halaman beranda katalog
        $this->view('catalog/index', [
            'title' => 'Beranda IndoFX',
        ]);
    }
}
