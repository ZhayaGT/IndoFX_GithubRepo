<?php

namespace IndoFX\Models;

use IndoFX\Core\Model;
use PDO;

class SellerProfile extends Model {
    // Profil toko satu penjual; null bila belum pernah disimpan.
    public function find(int $userId): ?array {
        $stmt = $this->db->prepare("SELECT * FROM seller_profiles WHERE user_id = :id");
        $stmt->bindValue(':id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    // Simpan atau perbarui profil toko dalam satu pernyataan.
    public function upsert(int $userId, array $data): void {
        $stmt = $this->db->prepare(
            "INSERT INTO seller_profiles
                (user_id, store_name, address, reason, portfolio_url, website_url, payout_number, payout_account_name)
             VALUES (:user_id, :store_name, :address, :reason, :portfolio_url, :website_url, :payout_number, :payout_account_name)
             ON CONFLICT (user_id) DO UPDATE SET
                store_name = EXCLUDED.store_name,
                address = EXCLUDED.address,
                reason = EXCLUDED.reason,
                portfolio_url = EXCLUDED.portfolio_url,
                website_url = EXCLUDED.website_url,
                payout_number = EXCLUDED.payout_number,
                payout_account_name = EXCLUDED.payout_account_name,
                updated_at = NOW()"
        );

        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':store_name', $data['store_name'] ?? '');
        $stmt->bindValue(':address', $data['address'] ?? null);
        $stmt->bindValue(':reason', $data['reason'] ?? null);
        $stmt->bindValue(':portfolio_url', $data['portfolio_url'] ?? null);
        $stmt->bindValue(':website_url', $data['website_url'] ?? null);
        $stmt->bindValue(':payout_number', $data['payout_number'] ?? null);
        $stmt->bindValue(':payout_account_name', $data['payout_account_name'] ?? null);
        $stmt->execute();
    }
}