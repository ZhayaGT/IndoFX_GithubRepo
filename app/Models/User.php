<?php

namespace IndoFX\Models;

use IndoFX\Core\Model;
use PDO;

class User extends Model {
    // Mencari pengguna berdasarkan email.
    // Digunakan saat validasi Login atau mengecek duplikasi email saat Register.
    public function findByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($sql);

        // Menggunakan bindValue untuk keamanan ekstra
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Menyimpan pengguna baru (Register).
    public function create($name, $email, $passwordHash, $role = 'buyer') {
        $sql = "INSERT INTO users (name, email, password, role) 
                VALUES (:name, :email, :password, :role)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':name'     => $name,
            ':email'    => $email,
            ':password' => $passwordHash,
            ':role'     => $role
        ]);

        // Mengembalikan data user yang baru saja berhasil dibuat
        return $this->findByEmail($email);
    }

    // Memperbarui token Remember Me di database
    public function updateRememberToken($userId, $token) {
        $stmt = $this->db->prepare("UPDATE users SET remember_token = :token WHERE id = :id");
        $stmt->execute([
            ':token' => $token,
            ':id'    => $userId
        ]);
    }

    // Mencari user berdasarkan token yang ada di cookie
    public function findByRememberToken($token) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE remember_token = :token LIMIT 1");
        $stmt->execute([':token' => $token]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}