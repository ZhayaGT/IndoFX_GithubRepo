<?php

namespace IndoFX\Core;

use PDO;
use PDOException;
use PDOStatement;

class Database {
    // Variabel statis untuk menyimpan instance dengan pola Singleton
    private static ?self $instance = null;
    private ?PDO $connection = null;

    // Constructor dibuat private agar tidak bisa diinisialisasi sembarangan
    private function __construct() {
        // Mengambil konfigurasi dari .env
        $host = Env::get('DB_HOST') ?: '127.0.0.1';
        $port = Env::get('DB_PORT') ?: '5432';
        $db   = Env::get('DB_NAME') ?: '';
        $user = Env::get('DB_USER') ?: '';
        $pass = Env::get('DB_PASSWORD') ?: '';
        $sslmode = Env::get('DB_SSLMODE') ?: 'prefer';

        // DSN khusus untuk PostgreSQL
        $dsn = "pgsql:host={$host};port={$port};dbname={$db};sslmode={$sslmode}";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false, // Memaksa penggunaan native prepared statements
        ];

        try {
            $this->connection = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            // Dalam mode production pesan error ini dilog
            die("Koneksi database gagal: " . $e->getMessage());
        }
    }

    // Mengambil instance singleton
    public static function getInstance(): self {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection(): PDO {
        return $this->connection;
    }

    // Fungsi utama untuk menjalankan query dengan prepared statement
    public function query(string $sql, array $params = []): PDOStatement {
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    // Mengambil ID terakhir setelah INSERT
    public function lastInsertId() {
        return $this->connection->lastInsertId();
    }

    // --- Kontrol Transaksi SQL ---
    public function beginTransaction(): bool {
        return $this->connection->beginTransaction();
    }

    public function commit(): bool {
        return $this->connection->commit();
    }

    public function rollback(): bool {
        return $this->connection->rollBack();
    }
}