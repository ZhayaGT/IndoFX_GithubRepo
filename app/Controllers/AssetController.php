<?php

namespace IndoFX\Controllers;

use IndoFX\Core\Controller;

/**
 * Halaman detail aset.
 *
 * Data masih dummy dan dibaca dari dua berkas JSON di public/assets/data:
 *
 *   assets.json        -> kolom yang nanti berasal dari tabel `assets`
 *                         (title, slug, price, render_pipeline, unity_version, ...)
 *   asset-details.json -> isi khusus halaman detail, di-key oleh slug
 *                         (galeri, deskripsi, changelog, ulasan)
 *
 * Saat backend siap, loadDummyJson() diganti query ke tabel `assets` dan
 * `reviews`; bentuk array yang dikirim ke view tidak perlu berubah.
 */
class AssetController extends Controller
{
    /** Label kategori untuk ditampilkan di atas judul aset. */
    private const CATEGORY_LABELS = [
        'shader'   => 'Shader',
        'particle' => 'VFX Partikel',
        'model'    => 'Model 3D',
    ];

    public function detail()
    {
        $slug = isset($_GET['slug']) ? trim((string) $_GET['slug']) : '';

        if ($slug === '') {
            $this->notFound();
            return;
        }

        $asset = $this->findAsset($slug);
        $detail = $this->findDetail($slug);

        if ($asset === null || $detail === null) {
            $this->notFound();
            return;
        }

        $reviews = $detail['reviews'] ?? [];

        $this->view('asset/detail', [
            'title'         => $asset['title'] . ' - IndoFX',
            'asset'         => $asset,
            'detail'        => $detail,
            'categoryLabel' => self::CATEGORY_LABELS[$asset['category'] ?? ''] ?? ucfirst((string) ($asset['category'] ?? '')),
            'reviewSummary' => $this->summarizeReviews($reviews),
            'reviews'       => $reviews,
        ]);
    }

    /**
     * Cari satu aset pada data dummy berdasarkan slug.
     */
    private function findAsset(string $slug): ?array
    {
        foreach ($this->loadDummyJson('assets.json')['data'] ?? [] as $asset) {
            if (($asset['slug'] ?? null) === $slug) {
                return $asset;
            }
        }

        return null;
    }

    /**
     * Cari isi detail (galeri, deskripsi, changelog, ulasan) berdasarkan slug.
     */
    private function findDetail(string $slug): ?array
    {
        $details = $this->loadDummyJson('asset-details.json');

        return $details[$slug] ?? null;
    }

    /**
     * Baca berkas JSON dummy dari public/assets/data.
     */
    private function loadDummyJson(string $fileName): array
    {
        $path = BASE_PATH . '/public/assets/data/' . $fileName;

        if (!is_file($path)) {
            return [];
        }

        $contents = file_get_contents($path);
        if ($contents === false) {
            return [];
        }

        $decoded = json_decode($contents, true);

        return is_array($decoded) ? $decoded : [];
    }

    /**
     * Ringkasan rating untuk tab ulasan: rata-rata, jumlah ulasan, sebaran bintang.
     *
     * @param array<int, array<string, mixed>> $reviews
     * @return array{average: float, total: int, counts: array<int, int>}
     */
    private function summarizeReviews(array $reviews): array
    {
        $counts = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];
        $sum = 0;

        foreach ($reviews as $review) {
            $rating = (int) ($review['rating'] ?? 0);
            if (isset($counts[$rating])) {
                $counts[$rating]++;
            }
            $sum += $rating;
        }

        $total = count($reviews);

        return [
            'average' => $total > 0 ? $sum / $total : 0.0,
            'total'   => $total,
            'counts'  => $counts,
        ];
    }

    /**
     * Tampilkan halaman 404 untuk slug kosong atau tidak dikenal.
     */
    private function notFound(): void
    {
        http_response_code(404);
        require BASE_PATH . '/resources/views/errors/404.php';
    }
}
