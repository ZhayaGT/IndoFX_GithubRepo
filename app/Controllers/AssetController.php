<?php

namespace IndoFX\Controllers;

use IndoFX\Core\Controller;
use IndoFX\Helpers\Storage;
use IndoFX\Models\Asset;

class AssetController extends Controller
{
    public function detail()
    {
        $slug = trim((string) ($_GET['slug'] ?? ''));

        if ($slug === '') {
            $this->notFound();
            return;
        }

        $model = new Asset();
        $asset = $model->findBySlug($slug);

        if ($asset === null) {
            $this->notFound();
            return;
        }

        $file = $model->latestFile((int) $asset['id']);

        $this->view('asset/detail', [
            'title'     => $asset['title'] . ' - IndoFX',
            'asset'     => $asset,
            'gallery'   => array_map([Storage::class, 'image'], $model->images((int) $asset['id'])),
            'file'      => $file,
            'fileFormat' => $file ? '.' . $file['extension'] : '-',
            'paragraphs' => array_filter(array_map('trim', explode("\n", (string) $asset['description']))),
            'howToUse'  => array_filter(array_map('trim', explode("\n", (string) ($asset['how_to_use'] ?? '')))),
        ]);
    }

    private function notFound(): void
    {
        http_response_code(404);
        require BASE_PATH . '/resources/views/errors/404.php';
    }
}
