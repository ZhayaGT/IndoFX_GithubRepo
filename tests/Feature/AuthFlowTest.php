<?php

declare(strict_types=1);

namespace IndoFX\Tests\Feature;

use PDO;
use PHPUnit\Framework\TestCase;

/**
 * Uji alur autentikasi (login, register, logout) lewat HTTP.
 *
 * Server dijalankan sekali per kelas dengan `php -S` pada port acak dan
 * memakai database uji yang skemanya dimuat ulang setiap test.
 */
class AuthFlowTest extends TestCase
{
    private static $server = null;
    private static string $baseUrl = '';
    private static string $dbName = '';
    private static ?PDO $pdo = null;

    public static function setUpBeforeClass(): void
    {
        $host = (string) (\IndoFX\Core\Env::get('DB_HOST', '127.0.0.1'));
        $port = (string) (\IndoFX\Core\Env::get('DB_PORT', 5432));
        $user = (string) (\IndoFX\Core\Env::get('DB_USER', 'indofx'));
        $pass = (string) (\IndoFX\Core\Env::get('DB_PASSWORD', ''));

        self::$dbName = 'indofx_test_' . bin2hex(random_bytes(4));

        $admin = new PDO("pgsql:host={$host};port={$port};dbname=postgres", $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);
        $admin->exec('CREATE DATABASE ' . self::$dbName);
        $admin = null;

        self::$pdo = new PDO("pgsql:host={$host};port={$port};dbname=" . self::$dbName, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);
        self::$pdo->exec((string) file_get_contents(BASE_PATH . '/database/Script marketplace_game_assets.sql'));

        $appPort = self::freePort();
        self::$baseUrl = "http://127.0.0.1:{$appPort}";

        $env = array_merge(getenv(), [
            'DB_NAME'      => self::$dbName,
            'APP_DEBUG'    => 'true',
            'SESSION_NAME' => 'indofx_test_session',
        ]);

        $command = [PHP_BINARY, '-S', "127.0.0.1:{$appPort}", '-t', BASE_PATH . '/public'];
        self::$server = proc_open($command, [
            0 => ['pipe', 'r'],
            1 => ['file', '/dev/null', 'w'],
            2 => ['file', '/dev/null', 'w'],
        ], $pipes, BASE_PATH, $env);

        self::waitForServer();
    }

    public static function tearDownAfterClass(): void
    {
        if (is_resource(self::$server)) {
            proc_terminate(self::$server);
            proc_close(self::$server);
        }

        if (self::$pdo !== null) {
            $host = (string) (\IndoFX\Core\Env::get('DB_HOST', '127.0.0.1'));
            $port = (string) (\IndoFX\Core\Env::get('DB_PORT', 5432));
            $user = (string) (\IndoFX\Core\Env::get('DB_USER', 'indofx'));
            $pass = (string) (\IndoFX\Core\Env::get('DB_PASSWORD', ''));

            self::$pdo = null;

            $admin = new PDO("pgsql:host={$host};port={$port};dbname=postgres", $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);
            $admin->exec('DROP DATABASE IF EXISTS ' . self::$dbName . ' WITH (FORCE)');
        }
    }

    protected function setUp(): void
    {
        self::$pdo->exec('DELETE FROM users');
    }

    public function testRegisterCreatesBuyerWithHashedPassword(): void
    {
        $jar = [];

        $response = $this->request('POST', '/register', $jar, [
            'name'     => 'Budi Santoso',
            'email'    => 'Budi@Example.com',
            'password' => 'rahasia123',
        ]);

        self::assertSame(302, $response['status']);
        self::assertSame('/login', $response['headers']['location']);

        $row = self::$pdo->query('SELECT name, email, role, password FROM users')->fetch(PDO::FETCH_ASSOC);

        self::assertSame('Budi Santoso', $row['name']);
        self::assertSame('budi@example.com', $row['email']);
        self::assertSame('buyer', $row['role']);
        self::assertTrue(password_verify('rahasia123', $row['password']));
        self::assertStringNotContainsString('rahasia123', $row['password']);

        // Pesan sukses dan email terisi di halaman login.
        $page = $this->request('GET', '/login', $jar)['body'];
        self::assertStringContainsString('Registrasi berhasil!', $page);
        self::assertStringContainsString('value="budi@example.com"', $page);
    }

    public function testRegisterRejectsDuplicateEmail(): void
    {
        $this->insertUser('ada@example.com', 'rahasia123');

        $jar = [];
        $response = $this->request('POST', '/register', $jar, [
            'name'     => 'Kedua',
            'email'    => 'ada@example.com',
            'password' => 'rahasia123',
        ]);

        self::assertSame('/register', $response['headers']['location']);
        self::assertSame(1, (int) self::$pdo->query('SELECT COUNT(*) FROM users')->fetchColumn());

        $page = $this->request('GET', '/register', $jar)['body'];
        self::assertStringContainsString('Email sudah terdaftar!', $page);
        self::assertStringContainsString('value="ada@example.com"', $page);
    }

    public function testLoginStoresSessionAndShowsBuyerNavbar(): void
    {
        $this->insertUser('pembeli@example.com', 'rahasia123', 'Pembeli Uji');

        $jar = [];
        $response = $this->request('POST', '/login', $jar, [
            'email'    => 'pembeli@example.com',
            'password' => 'rahasia123',
        ]);

        self::assertSame(302, $response['status']);
        self::assertSame('/', $response['headers']['location']);

        $home = $this->request('GET', '/', $jar)['body'];
        self::assertStringContainsString('data-auth-state="pembeli"', $home);
        self::assertStringContainsString('id="nav-guest" class="navbar-auth-group" hidden', $home);
        self::assertStringContainsString('Pembeli Uji', $home);
        self::assertStringNotContainsString('btn-hero-cream', $home);
    }

    public function testLoginRejectsWrongPassword(): void
    {
        $this->insertUser('pembeli@example.com', 'rahasia123');

        $jar = [];
        $response = $this->request('POST', '/login', $jar, [
            'email'    => 'pembeli@example.com',
            'password' => 'salah',
        ]);

        self::assertSame('/login', $response['headers']['location']);

        $page = $this->request('GET', '/login', $jar)['body'];
        self::assertStringContainsString('Email atau password salah!', $page);
        self::assertStringContainsString('value="pembeli@example.com"', $page);
    }

    public function testLogoutClearsSession(): void
    {
        $this->insertUser('pembeli@example.com', 'rahasia123', 'Pembeli Uji');

        $jar = [];
        $this->request('POST', '/login', $jar, [
            'email'    => 'pembeli@example.com',
            'password' => 'rahasia123',
        ]);

        $response = $this->request('GET', '/logout', $jar);

        self::assertSame(302, $response['status']);
        self::assertSame('/login', $response['headers']['location']);

        $home = $this->request('GET', '/', $jar)['body'];
        self::assertStringContainsString('data-auth-state="guest"', $home);
        self::assertStringContainsString('btn-hero-cream', $home);
    }

    public function testLoggedInUserIsRedirectedAwayFromAuthPages(): void
    {
        $this->insertUser('pembeli@example.com', 'rahasia123');

        $jar = [];
        $this->request('POST', '/login', $jar, [
            'email'    => 'pembeli@example.com',
            'password' => 'rahasia123',
        ]);

        foreach (['/login', '/register'] as $path) {
            $response = $this->request('GET', $path, $jar);
            self::assertSame(302, $response['status'], $path);
            self::assertSame('/', $response['headers']['location'], $path);
        }
    }

    private function insertUser(string $email, string $password, string $name = 'Pengguna Uji', bool $active = true): void
    {
        $stmt = self::$pdo->prepare(
            'INSERT INTO users (name, email, password, role)
             VALUES (:name, :email, :hash, :role)'
        );
        $stmt->execute([
            ':name'  => $name,
            ':email' => $email,
            ':hash'  => password_hash($password, PASSWORD_DEFAULT),
            ':role'  => 'buyer',
        ]);
    }

    /**
     * @param array<string, string> $jar
     * @param array<string, string> $data
     * @return array{status: int, headers: array<string, string>, body: string}
     */
    private function request(string $method, string $path, array &$jar, array $data = []): array
    {
        $handle = curl_init(self::$baseUrl . $path);
        curl_setopt_array($handle, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => true,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_TIMEOUT        => 10,
            CURLOPT_COOKIE         => $this->cookieHeader($jar),
        ]);

        if ($method === 'POST') {
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_POSTFIELDS, http_build_query($data));
        }

        $raw = (string) curl_exec($handle);
        $status = (int) curl_getinfo($handle, CURLINFO_RESPONSE_CODE);
        $headerSize = (int) curl_getinfo($handle, CURLINFO_HEADER_SIZE);

        $headerBlock = substr($raw, 0, $headerSize);
        $body = substr($raw, $headerSize);

        $headers = [];
        foreach (explode("\r\n", $headerBlock) as $line) {
            if (stripos($line, 'set-cookie:') === 0) {
                [$pair] = explode(';', substr($line, 11), 2);
                [$name, $value] = array_pad(explode('=', trim($pair), 2), 2, '');
                $jar[$name] = $value;
            } elseif (str_contains($line, ':')) {
                [$name, $value] = explode(':', $line, 2);
                $headers[strtolower(trim($name))] = trim($value);
            }
        }

        return ['status' => $status, 'headers' => $headers, 'body' => $body];
    }

    /** @param array<string, string> $jar */
    private function cookieHeader(array $jar): string
    {
        $pairs = [];
        foreach ($jar as $name => $value) {
            $pairs[] = "{$name}={$value}";
        }

        return implode('; ', $pairs);
    }

    private static function freePort(): int
    {
        $socket = stream_socket_server('tcp://127.0.0.1:0');
        $name = stream_socket_get_name($socket, false);
        fclose($socket);

        return (int) substr((string) $name, strrpos((string) $name, ':') + 1);
    }

    private static function waitForServer(): void
    {
        for ($i = 0; $i < 100; $i++) {
            $connection = @fsockopen('127.0.0.1', (int) parse_url(self::$baseUrl, PHP_URL_PORT), $errno, $errstr, 0.1);
            if ($connection !== false) {
                fclose($connection);
                return;
            }
            usleep(100000);
        }

        self::fail('Server pengembangan tidak merespons.');
    }
}
