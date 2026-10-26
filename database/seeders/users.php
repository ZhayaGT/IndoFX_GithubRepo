<?php

/**
 * Seeder pengguna awal untuk pengembangan lokal.
 *
 * Pemakaian:
 *   php database/seeders/users.php
 */

declare(strict_types=1);

define('BASE_PATH', dirname(__DIR__, 2));

require BASE_PATH . '/vendor/autoload.php';

if (file_exists(BASE_PATH . '/.env')) {
    Dotenv\Dotenv::createImmutable(BASE_PATH)->load();
}

$users = [
    ['name' => 'Admin IndoFX', 'email' => 'admin@indofx.id', 'password' => 'admin12345', 'role' => 'admin'],
    ['name' => 'Penjual Demo', 'email' => 'seller@indofx.id', 'password' => 'seller12345', 'role' => 'seller'],
    ['name' => 'Pembeli Demo', 'email' => 'buyer@indofx.id', 'password' => 'buyer12345', 'role' => 'buyer'],
];

$model = new IndoFX\Models\User();

foreach ($users as $user) {
    if ($model->findByEmail($user['email']) !== null) {
        echo "Lewati (sudah ada): {$user['email']}\n";
        continue;
    }

    $model->create($user['name'], $user['email'], password_hash($user['password'], PASSWORD_DEFAULT), $user['role']);
    echo "Dibuat: {$user['email']} ({$user['role']})\n";
}
