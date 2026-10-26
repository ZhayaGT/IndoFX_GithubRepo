<?php

declare(strict_types=1);

namespace IndoFX\Core;

/**
 * Basis seluruh model.
 * Menyediakan koneksi PDO bersama dari singleton Database.
 */
abstract class Model
{
    protected Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }
}
