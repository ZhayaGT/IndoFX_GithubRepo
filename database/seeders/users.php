<?php

/**
 * Seeder pengguna awal untuk pengembangan lokal.
 *
 * Pemakaian:
 *   php database/seeders/users.php
 *
 * Seeder bersifat idempoten:
 *   - Akun yang belum ada akan dibuat.
 *   - Akun seed yang sudah ada dengan hash password valid akan dilewati.
 *   - Akun seed dengan hash rusak (mis. berisi teks error) atau password yang
 *     tidak sesuai akan direset hash-nya memakai password default di bawah.
 *     Hash di-update di tempat (bukan hapus baris) agar aset yang terhubung
 *     ke akun tersebut tidak ikut terhapus oleh ON DELETE CASCADE.
 */

declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__, 2));

require BASE_PATH . '/vendor/autoload.php';

if (file_exists(BASE_PATH . '/.env')) {
    Dotenv\Dotenv::createImmutable(BASE_PATH)->safeLoad();
}

use IndoFX\Core\Database;
use IndoFX\Models\User;

$users = [
    ['name' => 'Admin IndoFX', 'email' => 'admin@indofx.id', 'password' => 'admin12345', 'role' => 'admin'],
    ['name' => 'Penjual Demo', 'email' => 'seller@indofx.id', 'password' => 'seller12345', 'role' => 'seller'],
    ['name' => 'Pembeli Demo', 'email' => 'buyer@indofx.id', 'password' => 'buyer12345', 'role' => 'buyer'],
];

$model = new User();
$db = Database::getInstance();

foreach ($users as $user) {
    $existing = $model->findByEmail($user['email']);

    if ($existing !== null) {
        $hash = (string) ($existing['password_hash'] ?? '');
        $info = password_get_info($hash);
        $hashValid = ($info['algo'] ?? null) !== null;

        if ($hashValid && password_verify($user['password'], $hash)) {
            echo "Lewati (sudah ada): {$user['email']}\n";
            continue;
        }

        // Hash rusak atau password tidak sesuai seed: reset hash di tempat.
        $db->query(
            'UPDATE users SET password_hash = :hash, updated_at = NOW() WHERE email = :email',
            [
                ':hash'  => password_hash($user['password'], PASSWORD_DEFAULT),
                ':email' => $user['email'],
            ]
        );
        echo "Reset kredensial: {$user['email']}\n";
        continue;
    }

    $model->create($user['name'], $user['email'], password_hash($user['password'], PASSWORD_DEFAULT), $user['role']);
    echo "Dibuat: {$user['email']} ({$user['role']})\n";
}
