<?php

namespace IndoFX\Models;

use IndoFX\Core\Model;
use PDO;

class User extends Model {
    // Mencari pengguna berdasarkan email.
    // Digunakan saat validasi Login atau mengecek duplikasi email saat Register.
    public function findByEmail($email) {
        $sql = "SELECT u.*, p.name, p.bio 
                FROM users u
                LEFT JOIN user_profiles p ON u.id = p.user_id
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
        $sql = "SELECT * FROM users WHERE remember_token = :token LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':token' => $token]);
        return $stmt->fetch(PDO::FETCH_ASSOC); 
    }

    // Mengambil user berdasarkan ID Internal (Untuk Session/Dashboard)
    public function getUserById($id) {
        $sql = "SELECT u.id, u.public_id, u.email, u.role, p.name, p.bio, s.store_name, s.portofolio_url, s.website_url
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
        $sql = "SELECT u.id, u.public_id, u.email, u.role, p.name, p.bio, s.store_name, s.portofolio_url, s.website_url
                FROM users u
                LEFT JOIN user_profiles p ON u.id = p.user_id
                LEFT JOIN seller_profiles s ON u.id = s.user_id
                WHERE u.public_id = :public_id::uuid
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
                portofolio_url = :portofolio_url,
                website_url = :website_url, 
                payout_number = :payout_number,
                payout_account_name = :payout_account_name,
                updated_at = NOW() 
                WHERE user_id = :id";
        $stmt = $this->db->prepare($sql);
        $data[':id'] = $id; 
        return $stmt->execute($data);
    }
}