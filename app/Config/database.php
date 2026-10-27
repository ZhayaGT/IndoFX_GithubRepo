<?php

declare(strict_types=1);

/**
 * Konfigurasi koneksi PostgreSQL (PDO).
 * Milik: Back End.
 */

use IndoFX\Core\Env;

return [
    'driver'   => 'pgsql',
    'host'     => Env::get('DB_HOST', '127.0.0.1'),
    'port'     => (int) Env::get('DB_PORT', 5434),
    'database' => Env::get('DB_NAME', 'indofx'),
    'username' => Env::get('DB_USER', 'diaz'),
    'password' => Env::get('DB_PASSWORD', ''),
    'sslmode'  => Env::get('DB_SSLMODE', 'prefer'),
    'options'  => [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ],
];
