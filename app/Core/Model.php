<?php

namespace IndoFX\Core;

class Model {
    protected $db;

    public function __construct() {
        // Memanggil Singleton Database PDO yang sudah kita buat di chore sebelumnya
        $this->db = Database::getInstance();
    }
}