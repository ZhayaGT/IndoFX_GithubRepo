<?php

namespace IndoFX\Models;

use IndoFX\Core\Model;
use PDO;

class Asset extends Model {
    // Halaman beranda
    // Mengambil 4 aset terbaru untuk section "Aset CG & VFX Terbaru"
    public function getLatestAssets($limit = 4) {
        $sql = "SELECT a.*, ai.image_path as thumbnail, COALESCE(sp.store_name, u.name) as seller_name, u.public_id as seller_public_id
                FROM assets a 
                LEFT JOIN asset_images ai ON a.id = ai.asset_id AND ai.sort_order = 1 
                LEFT JOIN users u ON a.seller_id = u.id
                LEFT JOIN seller_profiles sp ON sp.user_id = u.id
                WHERE a.status = 'published' AND a.deleted_at IS NULL
                ORDER BY a.created_at DESC LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Mengambil 4 aset gratis untuk section "Aset Gratis Unggulan"
    public function getFreeAssets($limit = 4) {
        $sql = "SELECT a.*, ai.image_path as thumbnail, COALESCE(sp.store_name, u.name) as seller_name, u.public_id as seller_public_id
                FROM assets a 
                LEFT JOIN asset_images ai ON a.id = ai.asset_id AND ai.sort_order = 1 
                LEFT JOIN users u ON a.seller_id = u.id
                LEFT JOIN seller_profiles sp ON sp.user_id = u.id
                WHERE a.price = 0 AND a.status = 'published' AND a.deleted_at IS NULL 
                ORDER BY a.created_at DESC LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Halaman Explore
    // Mengambil semua data aset, diurutkan dari yang terbaru untuk ditampilkan pada halaman explore
    public function getAllAssets() {
        $sql = "SELECT a.*, ai.image_path as thumbnail, COALESCE(sp.store_name, u.name) as seller_name, u.public_id as seller_public_id
                FROM assets a 
                LEFT JOIN asset_images ai ON a.id = ai.asset_id AND ai.sort_order = 1 
                LEFT JOIN users u ON a.seller_id = u.id
                LEFT JOIN seller_profiles sp ON sp.user_id = u.id
                ORDER BY a.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getFilteredAssets($filters = []) {
        [$where, $params] = $this->buildFilterWhere($filters);

        // Urutkan (Sorting)
        $sort = $filters['sort'] ?? 'terbaru';
        if ($sort === 'harga_rendah') {
            $order = 'a.price ASC';
        } elseif ($sort === 'harga_tinggi') {
            $order = 'a.price DESC';
        } elseif ($sort === 'populer') {
            $order = 'a.download_count DESC';
        } else {
            $order = 'a.created_at DESC';
        }

        $sql = "SELECT a.*, c.name as category_name, c.slug as category_slug, ai.image_path as thumbnail, COALESCE(sp.store_name, u.name) as seller_name, u.public_id as seller_public_id
                FROM assets a
                LEFT JOIN categories c ON a.category_id = c.id
                LEFT JOIN asset_images ai ON a.id = ai.asset_id AND ai.sort_order = 1
                LEFT JOIN users u ON a.seller_id = u.id
                LEFT JOIN seller_profiles sp ON sp.user_id = u.id
                WHERE 1=1" . $where . " ORDER BY " . $order;

        // Pagination hanya aktif bila limit diminta.
        if (isset($filters['limit'])) {
            $sql .= " LIMIT :limit OFFSET :offset";
            $params[':limit'] = (int) $filters['limit'];
            $params[':offset'] = (int) ($filters['offset'] ?? 0);
        }

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $type = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
            $stmt->bindValue($key, $value, $type);
        }

        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Jumlah aset untuk filter yang sama, dipakai menghitung halaman.
    public function countFilteredAssets($filters = []) {
        [$where, $params] = $this->buildFilterWhere($filters);

        $sql = "SELECT COUNT(*)
                FROM assets a
                LEFT JOIN categories c ON a.category_id = c.id
                WHERE 1=1" . $where;

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $type = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
            $stmt->bindValue($key, $value, $type);
        }

        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    // Mengembalikan [klausa WHERE, parameter] untuk filter katalog.
    private function buildFilterWhere(array $filters): array {
        $where = " AND a.status = 'published' AND a.deleted_at IS NULL";
        $params = [];

        // 1. Pencarian Kata Kunci (Berdasarkan title)
        if (!empty($filters['q'])) {
            $where .= " AND a.title ILIKE :q";
            $params[':q'] = '%' . $filters['q'] . '%';
        }

        // 2. Filter Kategori (Mencocokkan slug kategori)
        if (!empty($filters['category']) && is_array($filters['category'])) {
            $placeholders = [];
            foreach ($filters['category'] as $i => $cat) {
                $key = ":cat_$i";
                $placeholders[] = $key;
                $params[$key] = $cat;
            }
            $where .= " AND c.slug IN (" . implode(',', $placeholders) . ")";
        }

        // 3. Filter Harga
        if (isset($filters['is_free']) && $filters['is_free'] === '1') {
            $where .= " AND a.price = 0";
        } else {
            if (isset($filters['min_price']) && $filters['min_price'] !== '') {
                $where .= " AND a.price >= :min_price";
                $params[':min_price'] = (int) $filters['min_price'];
            }
            if (isset($filters['max_price']) && $filters['max_price'] !== '') {
                $where .= " AND a.price <= :max_price";
                $params[':max_price'] = (int) $filters['max_price'];
            }
        }

        // 4. Filter Render Pipeline (Pencarian Sub-string)
        if (!empty($filters['pipeline']) && is_array($filters['pipeline'])) {
            $pipelineConditions = [];

            foreach ($filters['pipeline'] as $i => $pipe) {
                $key = ":pipe_$i";
                $pipelineConditions[] = "a.render_pipeline ILIKE " . $key;
                $params[$key] = '%' . $pipe . '%';
            }

            $pipelineConditions[] = "a.render_pipeline ILIKE '%Any%'";
            $where .= " AND (" . implode(' OR ', $pipelineConditions) . ")";
        }

        // 5. Filter Unity Version
        if (!empty($filters['unity_version']) && $filters['unity_version'] !== 'Semua Versi') {
            $where .= " AND a.unity_version ILIKE :unity_version";
            $params[':unity_version'] = '%' . $filters['unity_version'] . '%';
        }

        return [$where, $params];
    }

    // Halaman Detail Aset
    // Mengambil satu aset beserta nama penjual dan nama kategori berdasarkan ID
    public function getAssetById($publicId) {
        $sql = "SELECT a.*, COALESCE(sp.store_name, u.name) as seller_name, u.public_id as seller_public_id, c.name as category_name, c.slug as category_slug
                FROM assets a
                LEFT JOIN users u ON a.seller_id = u.id
                LEFT JOIN seller_profiles sp ON sp.user_id = u.id
                LEFT JOIN categories c ON a.category_id = c.id
                WHERE a.public_id = :public_id::uuid
                LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':public_id', $publicId, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Mengambil semua path gambar untuk galeri berdasarkan ID aset
    public function getAssetGallery($assetId) {
        $sql = "SELECT image_path 
                FROM asset_images 
                WHERE asset_id = :asset_id 
                ORDER BY sort_order ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':asset_id', $assetId, PDO::PARAM_INT); 
        $stmt->execute();
        
        $gallery = [];
        while ($row = $stmt->fetch()) {
             $gallery[] = $row['image_path'];
        }
        return $gallery;
    }

    // Mengambil semua format/ekstensi file unik dari tabel asset_files
    public function getAssetFileFormats($assetId) {
        // Menggunakan DISTINCT agar jika ada 5 file .zip, tulisan .zip hanya muncul 1x
        $sql = "SELECT DISTINCT extension 
                FROM asset_files 
                WHERE asset_id = :asset_id 
                AND extension IS NOT NULL";
                
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':asset_id', $assetId, PDO::PARAM_INT); 
        $stmt->execute();
        
        $formats = [];
        while ($row = $stmt->fetch()) {
             $ext = trim($row['extension']);
             
             // Otomatis tambahkan titik (.) di depan jika di database hanya tertulis 'zip'
             if (!empty($ext) && strpos($ext, '.') !== 0) {
                 $ext = '.' . $ext;
             }
             
             if (!empty($ext)) {
                 $formats[] = strtolower($ext);
             }
        }
        
        return $formats;
    }

    // Daftar kategori untuk dropdown form aset.
    public function categories(): array {
        $stmt = $this->db->prepare("SELECT id, name, slug FROM categories ORDER BY name");
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Daftar aset milik satu penjual, dipakai dashboard.
    public function listForSeller(int $sellerId, array $filter = []): array {
        $where = " AND a.seller_id = :seller_id AND a.deleted_at IS NULL";
        $params = [':seller_id' => $sellerId];

        if (trim((string) ($filter['q'] ?? '')) !== '') {
            $where .= " AND a.title ILIKE :q";
            $params[':q'] = '%' . trim((string) $filter['q']) . '%';
        }
        if (trim((string) ($filter['status'] ?? '')) !== '') {
            $where .= " AND a.status = :status";
            $params[':status'] = trim((string) $filter['status']);
        }
        if (!empty($filter['category_id'])) {
            $where .= " AND a.category_id = :category_id";
            $params[':category_id'] = (int) $filter['category_id'];
        }

        $sql = "SELECT a.*, c.name as category_name, ai.image_path as thumbnail,
                       COALESCE(sp.store_name, u.name) as seller_name, u.public_id as seller_public_id
                FROM assets a
                LEFT JOIN categories c ON a.category_id = c.id
                LEFT JOIN asset_images ai ON a.id = ai.asset_id AND ai.sort_order = 1
                LEFT JOIN users u ON a.seller_id = u.id
                LEFT JOIN seller_profiles sp ON sp.user_id = u.id
                WHERE 1=1" . $where . "
                ORDER BY a.created_at DESC";

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Satu aset tanpa saring status, dipakai form edit dan pemeriksaan pemilik.
    public function findByPublicId(string $publicId): ?array {
        $stmt = $this->db->prepare("SELECT * FROM assets WHERE public_id = :public_id::uuid LIMIT 1");
        $stmt->bindValue(':public_id', $publicId, PDO::PARAM_STR);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    // Aset baru selalu masuk status review sampai disetujui admin.
    public function createForSeller(int $sellerId, array $data): array {
        $sql = "INSERT INTO assets
                    (seller_id, category_id, title, slug, description, how_to_use,
                     price, render_pipeline, unity_version, status, preview_video_url)
                VALUES (:seller_id, :category_id, :title, :slug, :description, :how_to_use,
                        :price, :render_pipeline, :unity_version, 'review', :preview_video_url)
                RETURNING id, public_id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':seller_id', $sellerId, PDO::PARAM_INT);
        $stmt->bindValue(':category_id', (int) $data['category_id'], PDO::PARAM_INT);
        $stmt->bindValue(':title', $data['title']);
        $stmt->bindValue(':slug', $data['slug']);
        $stmt->bindValue(':description', $data['description']);
        $stmt->bindValue(':how_to_use', $data['how_to_use'] ?? null);
        $stmt->bindValue(':price', (int) $data['price'], PDO::PARAM_INT);
        $stmt->bindValue(':render_pipeline', $data['render_pipeline']);
        $stmt->bindValue(':unity_version', $data['unity_version']);
        $stmt->bindValue(':preview_video_url', $data['preview_video_url'] ?? null);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    }

    // Perubahan aset mengembalikan status ke review agar ditinjau ulang.
    public function updateForSeller(int $assetId, int $sellerId, array $data): bool {
        $sql = "UPDATE assets SET
                    category_id = :category_id,
                    title = :title,
                    slug = :slug,
                    description = :description,
                    how_to_use = :how_to_use,
                    price = :price,
                    render_pipeline = :render_pipeline,
                    unity_version = :unity_version,
                    preview_video_url = :preview_video_url,
                    status = 'review',
                    updated_at = NOW()
                WHERE id = :id AND seller_id = :seller_id AND deleted_at IS NULL";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':category_id', (int) $data['category_id'], PDO::PARAM_INT);
        $stmt->bindValue(':title', $data['title']);
        $stmt->bindValue(':slug', $data['slug']);
        $stmt->bindValue(':description', $data['description']);
        $stmt->bindValue(':how_to_use', $data['how_to_use'] ?? null);
        $stmt->bindValue(':price', (int) $data['price'], PDO::PARAM_INT);
        $stmt->bindValue(':render_pipeline', $data['render_pipeline']);
        $stmt->bindValue(':unity_version', $data['unity_version']);
        $stmt->bindValue(':preview_video_url', $data['preview_video_url'] ?? null);
        $stmt->bindValue(':id', $assetId, PDO::PARAM_INT);
        $stmt->bindValue(':seller_id', $sellerId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    // Galeri aset diganti seluruhnya agar urutan gambar tetap rapi.
    public function replaceImages(int $assetId, array $paths): void {
        $delete = $this->db->prepare("DELETE FROM asset_images WHERE asset_id = :id");
        $delete->bindValue(':id', $assetId, PDO::PARAM_INT);
        $delete->execute();

        $insert = $this->db->prepare(
            "INSERT INTO asset_images (asset_id, image_path, sort_order) VALUES (:asset_id, :image_path, :sort_order)"
        );

        $order = 1;
        foreach ($paths as $path) {
            $insert->bindValue(':asset_id', $assetId, PDO::PARAM_INT);
            $insert->bindValue(':image_path', $path);
            $insert->bindValue(':sort_order', $order, PDO::PARAM_INT);
            $insert->execute();
            $order++;
        }
    }

    // Satu baris berkas paket dengan versi awal.
    public function addFile(int $assetId, array $meta): void {
        $sql = "INSERT INTO asset_files
                    (asset_id, file_path, original_name, mime_type, extension, file_size, version)
                VALUES (:asset_id, :file_path, :original_name, :mime_type, :extension, :file_size, '1.0.0')";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':asset_id', $assetId, PDO::PARAM_INT);
        $stmt->bindValue(':file_path', $meta['file_path']);
        $stmt->bindValue(':original_name', $meta['original_name']);
        $stmt->bindValue(':mime_type', $meta['mime_type']);
        $stmt->bindValue(':extension', $meta['extension']);
        $stmt->bindValue(':file_size', (int) $meta['file_size'], PDO::PARAM_INT);
        $stmt->execute();
    }

    // Angka ringkas untuk kartu statistik dashboard penjual.
    public function statsForSeller(int $sellerId): array {
        $stmt = $this->db->prepare(
            "SELECT
                COUNT(*) AS total,
                COUNT(*) FILTER (WHERE status = 'published') AS published,
                COUNT(*) FILTER (WHERE status = 'review') AS review,
                COALESCE(SUM(download_count), 0) AS downloads
             FROM assets
             WHERE seller_id = :id AND deleted_at IS NULL"
        );
        $stmt->bindValue(':id', $sellerId, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];

        $sales = $this->db->prepare(
            "SELECT COUNT(*) AS sold, COALESCE(SUM(t.amount), 0) AS revenue
             FROM transactions t
             JOIN assets a ON a.id = t.asset_id
             WHERE a.seller_id = :id AND t.status = 'settlement'"
        );
        $sales->bindValue(':id', $sellerId, PDO::PARAM_INT);
        $sales->execute();
        $salesRow = $sales->fetch(PDO::FETCH_ASSOC) ?: [];

        return [
            'total'     => (int) ($row['total'] ?? 0),
            'published' => (int) ($row['published'] ?? 0),
            'review'    => (int) ($row['review'] ?? 0),
            'downloads' => (int) ($row['downloads'] ?? 0),
            'sold'      => (int) ($salesRow['sold'] ?? 0),
            'revenue'   => (int) ($salesRow['revenue'] ?? 0),
        ];
    }

    // Riwayat transaksi aset milik satu penjual.
    public function transactionsForSeller(int $sellerId): array {
        $sql = "SELECT t.invoice_number, t.amount, t.payment_type, t.status, t.paid_at, t.created_at,
                       a.title AS asset_title
                FROM transactions t
                JOIN assets a ON a.id = t.asset_id
                WHERE a.seller_id = :id
                ORDER BY t.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $sellerId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Berkas paket terakhir, dipakai form edit untuk menampilkan berkas aktif.
    public function latestFileFor(int $assetId): ?array {
        $stmt = $this->db->prepare(
            "SELECT original_name, extension, file_size, created_at
             FROM asset_files
             WHERE asset_id = :id
             ORDER BY created_at DESC, id DESC
             LIMIT 1"
        );
        $stmt->bindValue(':id', $assetId, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    // Slug unik: dasar, lalu dasar-2, dasar-3, sampai jalan terakhir acak.
    public function uniqueSlug(string $title, int $excludeId = 0): string {
        $base = slugify($title);
        $check = $this->db->prepare("SELECT 1 FROM assets WHERE slug = :slug AND id <> :exclude_id LIMIT 1");

        for ($i = 1; $i <= 200; $i++) {
            $candidate = $i === 1 ? $base : $base . '-' . $i;

            $check->bindValue(':slug', $candidate, PDO::PARAM_STR);
            $check->bindValue(':exclude_id', $excludeId, PDO::PARAM_INT);
            $check->execute();

            if ($check->fetchColumn() === false) {
                return $candidate;
            }
        }

        return $base . '-' . uniqid();
    }
}