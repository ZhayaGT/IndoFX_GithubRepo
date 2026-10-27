<?php

namespace IndoFX\Models;

use IndoFX\Core\Model;

class Asset extends Model
{
    private const LIST_QUERY = "
        SELECT a.id, a.slug, a.title, a.description, a.price,
               a.render_pipeline, a.unity_version, a.created_at,
               c.name AS category_name, c.slug AS category_slug,
               u.name AS seller_name,
               (SELECT i.image_path FROM asset_images i
                 WHERE i.asset_id = a.id
                 ORDER BY i.sort_order, i.id
                 LIMIT 1) AS image_path,
               (SELECT COUNT(*) FROM transactions t
                 WHERE t.asset_id = a.id AND t.status = 'settlement') AS terjual
        FROM assets a
        JOIN categories c ON c.id = a.category_id
        JOIN users u ON u.id = a.seller_id
        WHERE a.status = 'published' AND a.deleted_at IS NULL
    ";

    private const SORT_ORDERS = [
        'terbaru'      => 'a.created_at DESC, a.id DESC',
        'populer'      => 'terjual DESC, a.created_at DESC',
        'harga_rendah' => 'a.price ASC, a.id DESC',
        'harga_tinggi' => 'a.price DESC, a.id DESC',
    ];

    /**
     * Daftar aset terbit sesuai filter katalog.
     *
     * @param array{q?:string, gratis?:bool, category?:string, pipeline?:string, sort?:string, limit?:int, offset?:int} $filter
     * @return array<int, array<string, mixed>>
     */
    public function search(array $filter = []): array
    {
        [$where, $params] = $this->buildWhere($filter);

        $order = self::SORT_ORDERS[$filter['sort'] ?? 'terbaru'] ?? self::SORT_ORDERS['terbaru'];
        $limit = max(1, (int) ($filter['limit'] ?? 12));
        $offset = max(0, (int) ($filter['offset'] ?? 0));

        $sql = self::LIST_QUERY . $where . ' ORDER BY ' . $order . ' LIMIT :limit OFFSET :offset';

        $stmt = $this->db->query($sql, $params + [':limit' => $limit, ':offset' => $offset]);

        return $stmt->fetchAll();
    }

    /**
     * Jumlah aset terbit untuk filter yang sama, dipakai untuk paginasi.
     */
    public function count(array $filter = []): int
    {
        [$where, $params] = $this->buildWhere($filter);

        $sql = "SELECT COUNT(*)
                FROM assets a
                JOIN categories c ON c.id = a.category_id
                JOIN users u ON u.id = a.seller_id
                WHERE a.status = 'published' AND a.deleted_at IS NULL" . $where;

        return (int) $this->db->query($sql, $params)->fetchColumn();
    }

    public function findBySlug(string $slug): ?array
    {
        $stmt = $this->db->query(
            self::LIST_QUERY . ' AND a.slug = :slug LIMIT 1',
            [':slug' => $slug]
        );

        $asset = $stmt->fetch();

        return $asset ?: null;
    }

    /**
     * Semua gambar aset, urut sesuai sort_order.
     *
     * @return array<int, string>
     */
    public function images(int $assetId): array
    {
        $stmt = $this->db->query(
            'SELECT image_path FROM asset_images WHERE asset_id = :id ORDER BY sort_order, id',
            [':id' => $assetId]
        );

        return array_column($stmt->fetchAll(), 'image_path');
    }

    /**
     * Berkas unduhan terbaru milik aset (format file diambil dari ekstensi).
     */
    public function latestFile(int $assetId): ?array
    {
        $stmt = $this->db->query(
            'SELECT original_name, extension, file_size
             FROM asset_files WHERE asset_id = :id
             ORDER BY created_at DESC, id DESC LIMIT 1',
            [':id' => $assetId]
        );

        $file = $stmt->fetch();

        return $file ?: null;
    }

    /**
     * Semua kategori untuk sidebar filter.
     *
     * @return array<int, array<string, mixed>>
     */
    public function categories(): array
    {
        return $this->db->query('SELECT id, name, slug FROM categories ORDER BY name')->fetchAll();
    }

    /**
     * Aset terbaru untuk beranda.
     *
     * @return array<int, array<string, mixed>>
     */
    public function latest(int $limit = 4): array
    {
        return $this->search(['sort' => 'terbaru', 'limit' => $limit]);
    }

    /**
     * Aset gratis paling banyak diunduh untuk beranda.
     *
     * @return array<int, array<string, mixed>>
     */
    public function freePopular(int $limit = 4): array
    {
        return $this->search(['gratis' => true, 'sort' => 'populer', 'limit' => $limit]);
    }

    /**
     * @return array{0:string, 1:array<string, mixed>}
     */
    private function buildWhere(array $filter): array
    {
        $conditions = [];
        $params = [];

        $keyword = trim((string) ($filter['q'] ?? ''));
        if ($keyword !== '') {
            $conditions[] = '(a.title ILIKE :keyword OR a.description ILIKE :keyword)';
            $params[':keyword'] = '%' . $keyword . '%';
        }

        if (!empty($filter['gratis'])) {
            $conditions[] = 'a.price = 0';
        }

        $category = trim((string) ($filter['category'] ?? ''));
        if ($category !== '') {
            $conditions[] = 'c.slug = :category';
            $params[':category'] = $category;
        }

        $pipeline = trim((string) ($filter['pipeline'] ?? ''));
        if ($pipeline !== '') {
            $conditions[] = 'a.render_pipeline = :pipeline';
            $params[':pipeline'] = $pipeline;
        }

        return [$conditions === [] ? '' : ' AND ' . implode(' AND ', $conditions), $params];
    }
}
