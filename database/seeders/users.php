<?php

/**
 * Seeder pengguna awal untuk pengembangan lokal.
 *
 * Pemakaian:
 *   php database/seeders/users.php
 *
 * Akun baru dibuat; kredensial akun demo yang berubah diperbarui tanpa
 * menghapus akun atau data terkait.
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

    if ($existing !== false && $existing !== null) {
        $hash = (string) ($existing['password'] ?? '');
        $info = password_get_info($hash);
        $hashValid = ($info['algo'] ?? null) !== null;

        if ($hashValid && password_verify($user['password'], $hash)) {
            echo "Lewati (sudah ada): {$user['email']}\n";
            continue;
        }

        // Update in place so related assets, profiles, and transactions survive.
        $db->query(
            'UPDATE users SET password = :hash, updated_at = NOW() WHERE id = :id',
            [':hash' => password_hash($user['password'], PASSWORD_DEFAULT), ':id' => $existing['id']]
        );
        echo "Reset kredensial (data dipertahankan): {$user['email']}\n";
        continue;
    }

    $model->create($user['name'], $user['email'], password_hash($user['password'], PASSWORD_DEFAULT), $user['role']);
    echo "Dibuat: {$user['email']} ({$user['role']})\n";
}
