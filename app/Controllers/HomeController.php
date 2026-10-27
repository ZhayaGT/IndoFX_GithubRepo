<?php

namespace IndoFX\Controllers;

use IndoFX\Core\Controller;
use IndoFX\Models\Asset;

class HomeController extends Controller
{
    public function index()
    {
        $model = new Asset();

        $this->view('catalog/index', [
            'title'      => 'Beranda IndoFX',
            'latest'     => $model->latest(4),
            'freeAssets' => $model->freePopular(4),
        ]);
    }
}
