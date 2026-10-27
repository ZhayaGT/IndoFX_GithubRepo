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

    public function explore()
    {
        // Filter desain hanya Shader dan Particle; slug tabel categories dipetakan di sini.
        $categoryMap = [
            'shader'   => 'vfx-and-shaders',
            'particle' => 'vfx-and-shaders',
        ];

        $picked = array_values(array_filter((array) ($_GET['kategori'] ?? []), static fn ($v) => isset($categoryMap[$v])));
        $slugs = array_values(array_unique(array_map(static fn ($v) => $categoryMap[$v], $picked)));

        $filter = [
            'q'        => trim((string) ($_GET['q'] ?? '')),
            'gratis'   => ($_GET['gratis'] ?? '') === '1',
            'category' => $slugs,
            'pipeline' => array_values(array_filter((array) ($_GET['pipeline'] ?? []))),
            'unity'    => trim((string) ($_GET['unity'] ?? '')),
            'priceMin' => trim((string) ($_GET['harga_min'] ?? '')),
            'priceMax' => trim((string) ($_GET['harga_max'] ?? '')),
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
            'filter'          => $filter,
            'total'           => $total,
            'page'            => $page,
            'totalPages'      => $totalPages,
            'sortOptions'     => self::SORT_OPTIONS,
            'pickedCategories' => $picked,
        ]);
    }
}
