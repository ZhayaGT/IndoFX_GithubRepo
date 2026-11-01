<?php

namespace IndoFX\Models;

use IndoFX\Core\Model;
use PDO;

class User extends Model {
    // Mencari pengguna berdasarkan email.
    // Digunakan saat validasi Login atau mengecek duplikasi email saat Register.
    public function findByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
        // Query akan otomatis memanggil prepared statement dari Database.php
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
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
}