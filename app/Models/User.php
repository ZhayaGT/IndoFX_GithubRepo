<?php

namespace IndoFX\Models;

use IndoFX\Core\Model;
use PDO;

class User extends Model {
    // Mencari pengguna berdasarkan email.
    // Digunakan saat validasi Login atau mengecek duplikasi email saat Register.
    public function findByEmail($email) {
        $sql = "SELECT u.*, COALESCE(p.name, u.email) AS name, p.bio, s.store_name
                FROM users u
                LEFT JOIN user_profiles p ON u.id = p.user_id
                LEFT JOIN seller_profiles s ON u.id = s.user_id
                WHERE u.email = :email 
                LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Menyimpan pengguna baru (Register).
    public function create($name, $email, $passwordHash, $role = 'buyer') {
        $sql = "INSERT INTO users (email, password, role) 
                VALUES (:email, :password, :role)
                RETURNING id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':email'    => $email,
            ':password' => $passwordHash,
            ':role'     => $role
        ]);

        $newUserId = $stmt->fetchColumn();
        $sqlprofile = "INSERT INTO user_profiles (user_id, name) 
                       VALUES (:user_id, :name)";
        $stmtProfile = $this->db->prepare($sqlprofile);
        $stmtProfile->execute([
            ':user_id' => $newUserId,
            ':name'    => $name,
        ]);

        // Mengembalikan data user yang baru saja berhasil dibuat
        return $this->findByEmail($email);
    }

    // Memperbarui token Remember Me di database
    public function updateRememberToken($userId, $token) {
        $sql = "UPDATE users SET remember_token = :token WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':token' => $token,
            ':id'    => $userId
        ]);
    }

    // Mencari user berdasarkan token yang ada di cookie
    public function findByRememberToken($token) {
        $sql = "SELECT u.*, COALESCE(p.name, u.email) AS name, p.bio, s.store_name FROM users u LEFT JOIN user_profiles p ON p.user_id = u.id LEFT JOIN seller_profiles s ON s.user_id = u.id WHERE u.remember_token = :token LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':token' => $token]);
        return $stmt->fetch(PDO::FETCH_ASSOC); 
    }

    // Mengambil user berdasarkan ID Internal (Untuk Session/Dashboard)
    public function getUserById($id) {
        $sql = "SELECT u.id, u.public_id, u.email, u.role, u.is_active, COALESCE(p.name, u.email) AS name, p.bio,
                       s.store_name, s.portfolio_url, s.website_url, 
                       s.payout_number, s.payout_account_name
                FROM users u
                LEFT JOIN user_profiles p ON u.id = p.user_id
                LEFT JOIN seller_profiles s ON u.id = s.user_id
                WHERE u.id = :id 
                LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Mengambil user berdasarkan UUID Publik (Untuk URL Publik)
    public function getUserByPublicId($publicId) {
        $sql = "SELECT u.id, u.public_id, u.email, u.role, u.is_active, COALESCE(p.name, u.email) AS name, p.bio, s.store_name, s.portfolio_url, s.website_url
                FROM users u
                LEFT JOIN user_profiles p ON u.id = p.user_id
                LEFT JOIN seller_profiles s ON u.id = s.user_id
                WHERE u.public_id = CAST(:public_id AS uuid)
                LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':public_id', $publicId, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update Profil Pembeli
    public function updateBuyerProfile($id, $name, $bio) {
        $sql = "UPDATE user_profiles 
                SET name = :name, bio = :bio, updated_at = NOW() 
                WHERE user_id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':name' => $name,
            ':bio'  => $bio,
            ':id'   => $id
        ]);
    }

    // Update Profil Penjual
    public function updateSellerProfile($id, $data) {
        $sql = "UPDATE seller_profiles SET 
                store_name = :store_name, 
                address = :address,
                reason = :reason,
                portfolio_url = :portfolio_url,
                website_url = :website_url, 
                payout_number = :payout_number,
                payout_account_name = :payout_account_name,
                updated_at = NOW() 
                WHERE user_id = :id";
        $stmt = $this->db->prepare($sql);
        $data[':id'] = $id; 
        return $stmt->execute($data);
    }

    // Menyimpan Pengajuan Daftar Jadi Penjual (Mendukung User Baru & User yang Sudah Login)
    public function createSellerApplication($data, $existingUserId = null) {
        try {
            $this->db->beginTransaction();

            $userId = $existingUserId;

            // Jika belum login, buat akun baru di tabel users & user_profiles
            if (!$userId) {
                $sqlUser = "INSERT INTO users (email, password, role) 
                            VALUES (:email, :password, 'buyer') 
                            RETURNING id";
                $stmtUser = $this->db->prepare($sqlUser);
                $stmtUser->execute([
                    ':email'    => $data['email'],
                    ':password' => $data['password_hash']
                ]);
                $userId = $stmtUser->fetchColumn();

                $sqlProfile = "INSERT INTO user_profiles (user_id, name) VALUES (:user_id, :name)";
                $this->db->prepare($sqlProfile)->execute([
                    ':user_id' => $userId,
                    ':name'    => $data['store_name']
                ]);
            }

            // Simpan atau perbarui pengajuan di tabel seller_profiles
            $sqlSeller = "INSERT INTO seller_profiles (
                              user_id, store_name, address, reason, portfolio_url,
                              sample_asset_path, sample_asset_desc, status, created_at, updated_at
                          ) VALUES (
                              :user_id, :store_name, :address, :reason, :portfolio_url,
                              :sample_asset_path, :sample_asset_desc, 'pending', NOW(), NOW()
                          )
                          ON CONFLICT (user_id) DO UPDATE SET
                              store_name        = EXCLUDED.store_name,
                              address           = EXCLUDED.address,
                              reason            = EXCLUDED.reason,
                              portfolio_url    = EXCLUDED.portfolio_url,
                              sample_asset_path = EXCLUDED.sample_asset_path,
                              sample_asset_desc = EXCLUDED.sample_asset_desc,
                              status            = 'pending',
                              reject_reason     = NULL,
                              reviewed_by       = NULL,
                              reviewed_at       = NULL,
                              updated_at        = NOW()";

            $stmtSeller = $this->db->prepare($sqlSeller);
            $stmtSeller->execute([
                ':user_id'           => $userId,
                ':store_name'        => $data['store_name'],
                ':address'           => $data['address'],
                ':reason'            => $data['reason'],
                ':portfolio_url'    => $data['portfolio_url'],
                ':sample_asset_path' => $data['sample_asset_path'],
                ':sample_asset_desc' => $data['sample_asset_desc']
            ]);

            $this->db->commit();
            return $this->getUserById($userId);
        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}