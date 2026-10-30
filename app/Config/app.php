<?php

declare(strict_types=1);

// Konfigurasi umum aplikasi.
// Milik: Back End.

use IndoFX\Core\Env;

return [
    'name'  => Env::get('APP_NAME', 'IndoFX'),
    'env'   => Env::get('APP_ENV', 'local'),
    'debug' => Env::bool('APP_DEBUG', false),
    'url'   => Env::get('APP_URL', 'http://localhost:8000'),
    'timezone' => Env::get('APP_TIMEZONE', 'Asia/Jakarta'),

    'session' => [
        'name'     => Env::get('SESSION_NAME', 'indofx_session'),
        'lifetime' => (int) Env::get('SESSION_LIFETIME', 120),
    ],

    'upload' => [
        'allowed_extensions' => ['unitypackage', 'zip'],
        'max_size'           => 200 * 1024 * 1024, // 200 MB
    ],
];
