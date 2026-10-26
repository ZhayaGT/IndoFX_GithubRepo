<?php

declare(strict_types=1);

namespace IndoFX\Models;

use IndoFX\Core\Model;

/**
 * Akses tabel `users`.
 * Nama kolom mengikuti database/schema.sql (password_hash, bukan password).
 */
class User extends Model
{
    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->query(
            'SELECT id, name, email, password_hash, role, is_active
             FROM users
             WHERE email = :email
             LIMIT 1',
            [':email' => $email]
        );

        $user = $stmt->fetch();

        return $user === false ? null : $user;
    }

    public function findById(string $id): ?array
    {
        $stmt = $this->db->query(
            'SELECT id, name, email, password_hash, role, is_active
             FROM users
             WHERE id = :id
             LIMIT 1',
            [':id' => $id]
        );

        $user = $stmt->fetch();

        return $user === false ? null : $user;
    }

    /**
     * Menyimpan pengguna baru dan mengembalikan baris yang baru dibuat.
     */
    public function create(string $name, string $email, string $passwordHash, string $role = 'buyer'): array
    {
        $this->db->query(
            'INSERT INTO users (name, email, password_hash, role)
             VALUES (:name, :email, :password_hash, :role)',
            [
                ':name'          => $name,
                ':email'         => $email,
                ':password_hash' => $passwordHash,
                ':role'          => $role,
            ]
        );

        return $this->findByEmail($email) ?? [];
    }
}
