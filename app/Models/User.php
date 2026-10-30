<?php

namespace IndoFX\Models;

use IndoFX\Core\Model;

class User extends Model {
    // Mencari pengguna berdasarkan email.
    // Digunakan saat validasi Login atau mengecek duplikasi email saat Register.
    public function findByEmail($email) {
        $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
        // Query akan otomatis memanggil prepared statement dari Database.php
        $stmt = $this->db->query($sql, [':email' => $email]);
        return $stmt->fetch();
    }

    // Menyimpan pengguna baru (Register).
    public function create($name, $email, $passwordHash, $role = 'buyer') {
        $sql = "INSERT INTO users (name, email, password, role) 
                VALUES (:name, :email, :password, :role)";
        
        $this->db->query($sql, [
            ':name'     => $name,
            ':email'    => $email,
            ':password' => $passwordHash, // Untuk Memastikan controller mengirimkan password yang sudah di-hash
            ':role'     => $role
        ]);

        // Mengembalikan data user yang baru saja berhasil dibuat
        return $this->findByEmail($email);
    }
}