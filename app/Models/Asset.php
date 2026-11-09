<?php

namespace IndoFX\Models;

use IndoFX\Core\Model;
use PDO;

class Asset extends Model {
    // Halaman beranda
    // Mengambil 4 aset terbaru untuk section "Aset CG & VFX Terbaru"
    public function getLatestAssets($limit = 4) {
        $sql = "SELECT a.*, ai.image_path as thumbnail, p.name as seller_name, u.public_id as seller_public_id
                FROM assets a 
                LEFT JOIN asset_images ai ON a.id = ai.asset_id AND ai.sort_order = 1 
                LEFT JOIN user_profiles p ON a.seller_id = p.user_id
                LEFT JOIN users u ON a.seller_id = u.id
                ORDER BY a.created_at DESC LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Mengambil 4 aset gratis untuk section "Aset Gratis Unggulan"
    public function getFreeAssets($limit = 4) {
        $sql = "SELECT a.*, ai.image_path as thumbnail, p.name as seller_name, u.public_id as seller_public_id
                FROM assets a 
                LEFT JOIN asset_images ai ON a.id = ai.asset_id AND ai.sort_order = 1 
                LEFT JOIN user_profiles p ON a.seller_id = p.user_id
                LEFT JOIN users u ON a.seller_id = u.id
                WHERE a.price = 0 
                ORDER BY a.created_at DESC LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Halaman Explore
    // Mengambil semua data aset, diurutkan dari yang terbaru untuk ditampilkan pada halaman explore
    public function getAllAssets() {
        $sql = "SELECT a.*, ai.image_path as thumbnail, p.name as seller_name, u.public_id as seller_public_id
                FROM assets a 
                LEFT JOIN asset_images ai ON a.id = ai.asset_id AND ai.sort_order = 1 
                LEFT JOIN user_profiles p ON a.seller_id = p.user_id
                LEFT JOIN users u ON a.seller_id = u.id
                ORDER BY a.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getFilteredAssets($filters = []) {
        // Melakukan JOIN dengan tabel categories, asset_images, DAN users
        $sql = "SELECT a.*, c.name as category_name, c.slug as category_slug, ai.image_path as thumbnail, p.name as seller_name, u.public_id as seller_public_id 
                FROM assets a 
                LEFT JOIN categories c ON a.category_id = c.id 
                LEFT JOIN asset_images ai ON a.id = ai.asset_id AND ai.sort_order = 1 
                LEFT JOIN user_profiles p ON a.seller_id = p.user_id
                LEFT JOIN users u ON a.seller_id = u.id
                WHERE 1=1";
                
        $params = [];

        // 1. Pencarian Kata Kunci (Berdasarkan title)
        if (!empty($filters['q'])) {
            $sql .= " AND a.title ILIKE :q";
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
            $sql .= " AND c.slug IN (" . implode(',', $placeholders) . ")";
        }

        // 3. Filter Harga
        if (isset($filters['is_free']) && $filters['is_free'] === '1') {
            $sql .= " AND a.price = 0";
        } else {
            if (isset($filters['min_price']) && $filters['min_price'] !== '') {
                $sql .= " AND a.price >= :min_price";
                $params[':min_price'] = (int) $filters['min_price'];
            }
            if (isset($filters['max_price']) && $filters['max_price'] !== '') {
                $sql .= " AND a.price <= :max_price";
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
            $sql .= " AND (" . implode(' OR ', $pipelineConditions) . ")";
        }

        // 5. Filter Unity Version
        if (!empty($filters['unity_version']) && $filters['unity_version'] !== 'Semua Versi') {
            $sql .= " AND a.unity_version ILIKE :unity_version";
            $params[':unity_version'] = '%' . $filters['unity_version'] . '%';
        }

        // 6. Urutkan (Sorting)
        $sort = $filters['sort'] ?? 'terbaru';
        if ($sort === 'harga_rendah') {
            $sql .= " ORDER BY a.price ASC";
        } elseif ($sort === 'harga_tinggi') {
            $sql .= " ORDER BY a.price DESC";
        } elseif ($sort === 'populer') {
            $sql .= " ORDER BY a.download_count DESC";
        } else {
            $sql .= " ORDER BY a.created_at DESC";
        }

        if (isset($filters['limit']) && isset($filters['offset'])) {
            $sql .= " LIMIT :limit OFFSET :offset";
            $params[':limit'] = (int) $filters['limit'];
            $params[':offset'] = (int) $filters['offset'];
        }

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $type = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
            $stmt->bindValue($key, $value, $type);
        }
        
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Fungsi baru untuk menghitung total keseluruhan aset yang lolos filter
    public function getTotalFilteredAssetsCount($filters = []) {
        $sql = "SELECT COUNT(a.id) as total
                FROM assets a 
                LEFT JOIN categories c ON a.category_id = c.id 
                WHERE 1=1";
                
        $params = [];

        if (!empty($filters['q'])) {
            $sql .= " AND a.title ILIKE :q";
            $params[':q'] = '%' . $filters['q'] . '%';
        }

        if (!empty($filters['category']) && is_array($filters['category'])) {
            $placeholders = [];
            foreach ($filters['category'] as $i => $cat) {
                $key = ":cat_$i";
                $placeholders[] = $key;
                $params[$key] = $cat; 
            }
            $sql .= " AND c.slug IN (" . implode(',', $placeholders) . ")";
        }

        if (isset($filters['is_free']) && $filters['is_free'] === '1') {
            $sql .= " AND a.price = 0";
        } else {
            if (isset($filters['min_price']) && $filters['min_price'] !== '') {
                $sql .= " AND a.price >= :min_price";
                $params[':min_price'] = (int) $filters['min_price'];
            }
            if (isset($filters['max_price']) && $filters['max_price'] !== '') {
                $sql .= " AND a.price <= :max_price";
                $params[':max_price'] = (int) $filters['max_price'];
            }
        }

        if (!empty($filters['pipeline']) && is_array($filters['pipeline'])) {
            $pipelineConditions = [];
            foreach ($filters['pipeline'] as $i => $pipe) {
                $key = ":pipe_$i";
                $pipelineConditions[] = "a.render_pipeline ILIKE " . $key;
                $params[$key] = '%' . $pipe . '%';
            }
            $pipelineConditions[] = "a.render_pipeline ILIKE '%Any%'";
            $sql .= " AND (" . implode(' OR ', $pipelineConditions) . ")";
        }

        if (!empty($filters['unity_version']) && $filters['unity_version'] !== 'Semua Versi') {
            $sql .= " AND a.unity_version ILIKE :unity_version";
            $params[':unity_version'] = '%' . $filters['unity_version'] . '%';
        }

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $type = is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR;
            $stmt->bindValue($key, $value, $type);
        }
        
        $stmt->execute();
        $result = $stmt->fetch();
        return $result ? (int) $result['total'] : 0;
    }

    // Halaman Detail Aset
    // Mengambil satu aset beserta nama penjual dan nama kategori berdasarkan ID
    public function getAssetById($publicId) {
        $sql = "SELECT a.*, p.name as seller_name, u.public_id as seller_public_id, c.name as category_name, c.slug as category_slug
                FROM assets a
                LEFT JOIN users u ON a.seller_id = u.id
                LEFT JOIN user_profiles p ON a.seller_id = p.user_id
                LEFT JOIN seller_profiles s ON a.seller_id = s.user_id
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

    // Mengambil semua aset yang dibuat oleh seorang Penjual (Kreator)
    public function getAssetsBySellerId($sellerId) {
        $sql = "SELECT a.*, ai.image_path as thumbnail, p.name as seller_name 
                FROM assets a 
                LEFT JOIN asset_images ai ON a.id = ai.asset_id AND ai.sort_order = 1
                LEFT JOIN user_profiles p ON a.seller_id = p.user_id 
                LEFT JOIN users u ON a.seller_id = u.id
                WHERE a.seller_id = :seller_id
                ORDER BY a.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':seller_id', $sellerId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}