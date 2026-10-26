<?php

declare(strict_types=1);

namespace IndoFX\Core;

use PDO;
use PDOException;
use RuntimeException;

/**
 * Koneksi PostgreSQL tunggal (singleton) dengan prepared statement native.
 */
class Database
{
    private static ?Database $instance = null;

    private PDO $connection;

    private function __construct()
    {
        $host = (string) Env::get('DB_HOST', '127.0.0.1');
        $port = (int) Env::get('DB_PORT', 5432);
        $name = (string) Env::get('DB_NAME', 'indofx');
        $user = (string) Env::get('DB_USER', 'indofx');
        $pass = (string) Env::get('DB_PASSWORD', '');
        $ssl  = (string) Env::get('DB_SSLMODE', 'prefer');

        $dsn = "pgsql:host={$host};port={$port};dbname={$name};sslmode={$ssl}";

        try {
            $this->connection = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            throw new RuntimeException('Koneksi database gagal: ' . $e->getMessage(), 0, $e);
        }
    }

    public static function getInstance(): Database
    {
        return self::$instance ??= new Database();
    }

    public function query(string $sql, array $params = []): \PDOStatement
    {
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);

        return $stmt;
    }

    public function beginTransaction(): bool
    {
        return $this->connection->beginTransaction();
    }

    public function commit(): bool
    {
        return $this->connection->commit();
    }

    public function rollback(): bool
    {
        return $this->connection->rollBack();
    }
}
