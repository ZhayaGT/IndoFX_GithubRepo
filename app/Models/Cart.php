<?php

namespace IndoFX\Models;

use IndoFX\Core\Model;
use PDO;

class Cart extends Model {
    // 1. Mengambil semua isi keranjang berdasarkan ID User
    public function getUserCart($userId) {
        $sql = "SELECT a.id, a.public_id, a.title, a.price, a.render_pipeline,
                       ci.added_at, ai.image_path as thumbnail, 
                       p.name as seller_name
                FROM cart_items ci
                JOIN assets a ON ci.asset_id = a.id
                LEFT JOIN asset_images ai ON a.id = ai.asset_id AND ai.sort_order = 1
                LEFT JOIN users u ON a.seller_id = u.id
                LEFT JOIN user_profiles p ON u.id = p.user_id
                WHERE ci.user_id = :user_id
                ORDER BY ci.added_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 2. Menghapus item dari keranjang (Dipanggil oleh Modal Hapus)
    public function removeCartItem($userId, $assetId) {
        $sql = "DELETE FROM cart_items WHERE user_id = :user_id AND asset_id = :asset_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':user_id'  => $userId,
            ':asset_id' => $assetId
        ]);
    }

    // 3. Menambahkan item ke keranjang (Untuk tombol di halaman Detail)
    public function addToCart($userId, $assetId) {
        // ON CONFLICT DO NOTHING: Jika aset sudah ada di keranjang, diamkan saja tanpa menyebabkan error
        $sql = "INSERT INTO cart_items (user_id, asset_id) 
                VALUES (:user_id, :asset_id) 
                ON CONFLICT (user_id, asset_id) DO NOTHING";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':user_id'  => $userId,
            ':asset_id' => $assetId
        ]);
    }
}