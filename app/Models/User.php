<?php

namespace IndoFX\Models;

use IndoFX\Core\Model;

class User extends Model
{
    public function findByEmail($email)
    {
        $stmt = $this->db->query(
            'SELECT id, name, email, password, role FROM users WHERE email = :email LIMIT 1',
            [':email' => $email]
        );

        return $stmt->fetch();
    }

    public function create($name, $email, $passwordHash, $role = 'buyer')
    {
        $this->db->query(
            'INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)',
            [
                ':name'     => $name,
                ':email'    => $email,
                ':password' => $passwordHash,
                ':role'     => $role,
            ]
        );

        return $this->findByEmail($email);
    }
}
