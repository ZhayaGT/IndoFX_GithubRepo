<?php

namespace IndoFX\Controllers;

use IndoFX\Core\Controller;
use IndoFX\Models\Asset;

class CatalogController extends Controller
{
    private const PER_PAGE = 6;

    private const SORT_OPTIONS = [
        'terbaru'      => 'Terbaru',
        'populer'      => 'Terpopuler',
        'harga_rendah' => 'Harga: Rendah ke Tinggi',
        'harga_tinggi' => 'Harga: Tinggi ke Rendah',
    ];

    private const PIPELINE_OPTIONS = [
        'Universal Render Pipeline (URP)' => 'URP',
        'Built-in / URP'                  => 'Built-in / URP',
        'Any'                             => 'Any',
        'Any (UI Canvas)'                 => 'Any (UI Canvas)',
    ];

    public function explore()
    {
        $filter = [
            'q'        => trim((string) ($_GET['q'] ?? '')),
            'gratis'   => ($_GET['gratis'] ?? '') === '1',
            'category' => trim((string) ($_GET['kategori'] ?? '')),
            'pipeline' => trim((string) ($_GET['pipeline'] ?? '')),
            'sort'     => trim((string) ($_GET['sort'] ?? 'terbaru')),
        ];

        $page = max(1, (int) ($_GET['page'] ?? 1));
        $model = new Asset();

        $total = $model->count($filter);
        $totalPages = max(1, (int) ceil($total / self::PER_PAGE));
        $page = min($page, $totalPages);

        $assets = $model->search($filter + [
            'limit'  => self::PER_PAGE,
            'offset' => ($page - 1) * self::PER_PAGE,
        ]);

        $this->view('catalog/explore', [
            'title'           => 'Jelajahi Katalog IndoFX',
            'assets'          => $assets,
            'categories'      => $model->categories(),
            'filter'          => $filter,
            'total'           => $total,
            'page'            => $page,
            'totalPages'      => $totalPages,
            'sortOptions'     => self::SORT_OPTIONS,
            'pipelineOptions' => self::PIPELINE_OPTIONS,
        ]);
    }
}
