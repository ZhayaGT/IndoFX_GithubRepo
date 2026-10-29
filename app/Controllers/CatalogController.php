<?php

namespace IndoFX\Controllers;

use IndoFX\Core\Controller;

class CatalogController extends Controller
{
    public function explore()
    {
        $this->view('catalog/explore', [
            'title' => 'Jelajahi Katalog IndoFX',
        ]);
    }
}
