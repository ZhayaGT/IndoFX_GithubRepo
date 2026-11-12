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
 *     tidak sesuai akan direset. Jika akun sudah punya aset, hash di-update
 *     di tempat agar aset tidak terhapus oleh ON DELETE CASCADE; jika belum
 *     punya aset, baris lama dihapus lalu dibuat ulang.
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

        $assetCount = (int) $db
            ->query('SELECT COUNT(*) FROM assets WHERE seller_id = :id', [':id' => $existing['id']])
            ->fetchColumn();

        if ($assetCount > 0) {
            // Pertahankan baris agar aset yang terhubung tidak ikut terhapus.
            $db->query(
                'UPDATE users SET password_hash = :hash, updated_at = NOW() WHERE id = :id',
                [
                    ':hash' => password_hash($user['password'], PASSWORD_DEFAULT),
                    ':id'   => $existing['id'],
                ]
            );
            echo "Reset kredensial (aset dipertahankan): {$user['email']}\n";
            continue;
        }

        $db->query('DELETE FROM users WHERE id = :id', [':id' => $existing['id']]);
        echo "Hapus akun demo lama: {$user['email']}\n";
    }

    $model->create($user['name'], $user['email'], password_hash($user['password'], PASSWORD_DEFAULT), $user['role']);
    echo "Dibuat: {$user['email']} ({$user['role']})\n";
}
