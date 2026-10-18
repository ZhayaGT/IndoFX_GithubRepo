<?php

namespace app\Core;

use PDO;
use PDOException;

class Database {
    // Menyimpan instance (pola Singleton)
    private static $instance = null;
    private $connection;

    // Konstruktor private agar tidak bisa diinisialisasi sembarangan (Encapsulation)
    private function __construct() {
        // Mengambil konfigurasi dari .env
        $host = getenv('DB_HOST') ?: '127.0.0.1';
        $port = getenv('DB_PORT') ?: '5432';
        $db = getenv('DB_NAME') ?: '';
        $user = getenv('DB_USER') ?: '';
        $pass = getenv('DB_PASSWORD') ?: '';

        // DSN untuk PostgreSQL
        $dsn = "pgsql:host=$host;port=$port;dbname=$db";

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false, // Memaksa penggunaan native prepared statements
        ];

        try {
            $this->connection = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            // Dalam mode production pesan error dilog
            die("Koneksi database gagal: " . $e->getMessage());
        }
    }

    // Mengambil instance singleton
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self ::$instance;
    }

    // Fungsi untuk menjalankan query dengan prepared statement
    public function query($sql, $params = []) {
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    // Fungsi untuk kontrol transaksi SQL
    public function beginTransaction() {
        return $this->connection->beginTransaction();
    }

    public function commit() {
        return $this->connection->commit();
    }

    public function rollback() {
        return $this->connection->rollback();
    }
}