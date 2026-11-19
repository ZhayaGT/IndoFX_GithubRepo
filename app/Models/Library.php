<?php

namespace IndoFX\Models;

use IndoFX\Core\Model;
use PDO;

class Library extends Model {

    // 1. Ambil semua aset di Library milik user (dari tabel entitlements)
    public function getUserLibrary($userId) {
        $sql = "SELECT 
                    e.id AS entitlement_id,
                    e.asset_id,
                    e.source,
                    e.granted_at,
                    a.public_id AS asset_public_id,
                    a.title,
                    a.price,
                    a.render_pipeline,
                    a.unity_version,
                    c.name AS category_name,
                    COALESCE(sp.store_name, up.name, 'Kreator IndoFX') AS creator_name,
                    (
                        SELECT ai.image_path 
                        FROM asset_images ai 
                        WHERE ai.asset_id = a.id 
                        ORDER BY ai.sort_order ASC, ai.id ASC 
                        LIMIT 1
                    ) AS thumbnail,
                    CASE WHEN r.id IS NOT NULL THEN true ELSE false END AS is_reviewed
                FROM entitlements e
                INNER JOIN assets a ON e.asset_id = a.id
                LEFT JOIN categories c ON a.category_id = c.id
                LEFT JOIN seller_profiles sp ON a.seller_id = sp.user_id
                LEFT JOIN user_profiles up ON a.seller_id = up.user_id
                LEFT JOIN reviews r ON r.asset_id = a.id AND r.user_id = e.user_id
                WHERE e.user_id = :user_id
                ORDER BY e.granted_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 2. Cek kepemilikan & ambil detail lengkap aset untuk halaman detail-library.php
    public function getOwnedAssetDetail($userId, $assetId) {
        $sql = "SELECT 
                    e.id AS entitlement_id,
                    e.source,
                    e.granted_at,
                    a.id,
                    a.public_id,
                    a.title,
                    a.description,
                    a.how_to_use,
                    a.price,
                    a.render_pipeline,
                    a.unity_version,
                    a.lisensi,
                    c.name AS category_name,
                    u.public_id AS seller_public_id,
                    COALESCE(sp.store_name, up.name, 'Kreator IndoFX') AS creator_name
                FROM entitlements e
                INNER JOIN assets a ON e.asset_id = a.id
                LEFT JOIN categories c ON a.category_id = c.id
                LEFT JOIN users u ON a.seller_id = u.id
                LEFT JOIN seller_profiles sp ON a.seller_id = sp.user_id
                LEFT JOIN user_profiles up ON a.seller_id = up.user_id
                WHERE e.user_id = :user_id AND a.id = :asset_id
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':user_id'  => $userId,
            ':asset_id' => $assetId
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 3. Ambil galeri gambar aset
    public function getAssetImages($assetId) {
        $sql = "SELECT image_path FROM asset_images WHERE asset_id = :asset_id ORDER BY sort_order ASC, id ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':asset_id' => $assetId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    // 4. Ambil daftar versi file aset (paling baru di urutan pertama)
    public function getAssetFiles($assetId) {
        $sql = "SELECT id, file_path, original_name, extension, file_size, version, changelog, created_at
                FROM asset_files 
                WHERE asset_id = :asset_id 
                ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':asset_id' => $assetId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 5. Ambil semua ulasan publik untuk suatu aset
    public function getAssetReviews($assetId) {
        $sql = "SELECT r.id, r.rating, r.title, r.comment, r.created_at,
                       COALESCE(up.name, 'Pengguna IndoFX') AS user_name
                FROM reviews r
                LEFT JOIN user_profiles up ON r.user_id = up.user_id
                WHERE r.asset_id = :asset_id
                ORDER BY r.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':asset_id' => $assetId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 6. Berikan hak akses aset (klaim gratis atau pembelian)
    public function grantEntitlement($userId, $assetId, $source = 'free', $transactionId = null) {
        $sql = "INSERT INTO entitlements (public_id, user_id, asset_id, transaction_id, source, granted_at, created_at, updated_at)
                VALUES (gen_random_uuid(), :user_id, :asset_id, :transaction_id, :source, NOW(), NOW(), NOW())
                ON CONFLICT (user_id, asset_id) DO NOTHING";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':user_id'        => $userId,
            ':asset_id'       => $assetId,
            ':transaction_id' => $transactionId,
            ':source'         => $source
        ]);
    }

    // 7. Otomatis masukkan semua item transaksi yang sudah lunas ke entitlements
    public function grantFromInvoice($invoiceNumber) {
        $sql = "INSERT INTO entitlements (public_id, user_id, asset_id, transaction_id, source, granted_at, created_at, updated_at)
                SELECT gen_random_uuid(), t.buyer_id, td.asset_id, t.id, 'purchase', NOW(), NOW(), NOW()
                FROM transactions t
                INNER JOIN transaction_details td ON td.transaction_id = t.id
                WHERE t.invoice_number = :invoice_number
                ON CONFLICT (user_id, asset_id) DO NOTHING";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':invoice_number' => $invoiceNumber]);
    }

    // 8. Simpan ulasan pembeli ke tabel reviews
    public function createReview($userId, $assetId, $rating, $title, $comment) {
        $sql = "INSERT INTO reviews (public_id, user_id, asset_id, rating, title, comment, created_at, updated_at)
                VALUES (gen_random_uuid(), :user_id, :asset_id, :rating, :title, :comment, NOW(), NOW())";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':user_id'  => $userId,
            ':asset_id' => $assetId,
            ':rating'   => $rating,
            ':title'    => $title !== '' ? $title : null,
            ':comment'  => $comment
        ]);
    }

    // 9. Catat unduhan ke download_logs dan tambah download_count di assets
    public function recordDownload($entitlementId, $assetId, $ipAddress) {
        $sqlLog = "INSERT INTO download_logs (public_id, entitlement_id, ip_address, downloaded_at)
                   VALUES (gen_random_uuid(), :entitlement_id, :ip_address, NOW())";
        $this->db->prepare($sqlLog)->execute([
            ':entitlement_id' => $entitlementId,
            ':ip_address'     => $ipAddress
        ]);

        $sqlCount = "UPDATE assets SET download_count = COALESCE(download_count, 0) + 1 WHERE id = :asset_id";
        $this->db->prepare($sqlCount)->execute([':asset_id' => $assetId]);
    }
}