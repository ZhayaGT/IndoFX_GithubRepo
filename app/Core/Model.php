<?php

namespace IndoFX\Core;

class Model {
    protected $db;

    public function __construct() {
        // Mengambil koneksi PDO asli dari Database Singleton
        $this->db = Database::getInstance()->getConnection();
    }
}