<?php

declare(strict_types=1);

namespace IndoFX\Tests\Feature;

use DomainException;
use IndoFX\Models\Admin;
use PDO;
use PHPUnit\Framework\TestCase;

final class AdminBackendTest extends TestCase
{
    private PDO $db;
    private Admin $admin;
    private string $schema;

    protected function setUp(): void
    {
        $dsn = getenv('ADMIN_TEST_DSN');
        if (!$dsn) $this->markTestSkipped('Set ADMIN_TEST_DSN to a disposable local PostgreSQL database.');
        if (!str_contains($dsn, 'host=127.0.0.1;')) {
            $this->fail('Admin tests only accept a local PostgreSQL connection.');
        }
        $this->db = new PDO($dsn, getenv('ADMIN_TEST_USER') ?: null, null,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
        $this->schema = 'admin_test_' . bin2hex(random_bytes(6));
        $this->db->exec('CREATE SCHEMA ' . $this->schema);
        $this->db->exec('SET search_path TO ' . $this->schema);
        $this->db->exec(file_get_contents(BASE_PATH . '/tests/fixtures/admin.sql'));
        $this->db->exec("INSERT INTO users (email,role) VALUES ('admin@test.invalid','admin'),
            ('buyer@test.invalid','buyer'), ('seller@test.invalid','seller')");
        $this->db->exec("INSERT INTO seller_profiles (user_id,store_name) VALUES (2,'Applicant')");
        $this->db->exec("INSERT INTO categories (name,slug) VALUES ('Shader','shader')");
        $this->db->exec('INSERT INTO assets (seller_id,category_id) VALUES (3,1)');
        $this->admin = new Admin($this->db);
    }

    protected function tearDown(): void
    {
        if (isset($this->schema, $this->db)) {
            if ($this->db->inTransaction()) $this->db->rollBack();
            $this->db->exec('DROP SCHEMA ' . $this->schema . ' CASCADE');
        }
    }

    public function testSellerApprovalPersistsRoleAndReviewer(): void
    {
        $this->admin->review('seller', ['id' => 2, 'decision' => 'approve'], 1);
        self::assertSame('seller', $this->db->query('SELECT role FROM users WHERE id=2')->fetchColumn());
        $row = $this->db->query('SELECT * FROM seller_profiles WHERE user_id=2')->fetch();
        self::assertSame('approved', $row['status']);
        self::assertEquals(1, $row['reviewed_by']);
        self::assertNotNull($row['reviewed_at']);
    }

    public function testAssetApprovalPublishesAndCannotBeReviewedTwice(): void
    {
        $this->admin->review('asset', ['id' => 1, 'decision' => 'approve'], 1);
        self::assertSame('published', $this->db->query('SELECT status FROM assets WHERE id=1')->fetchColumn());
        $this->expectException(DomainException::class);
        $this->admin->review('asset', ['id' => 1, 'decision' => 'reject', 'reason' => 'stale request'], 1);
    }

    public function testRejectionRequiresReasonAndPersistsIt(): void
    {
        try {
            $this->admin->review('asset', ['id' => 1, 'decision' => 'reject'], 1);
            self::fail('Empty rejection reason accepted.');
        } catch (DomainException) {
            self::assertSame('pending', $this->db->query('SELECT status FROM assets WHERE id=1')->fetchColumn());
        }
        $this->admin->review('asset', ['id' => 1, 'decision' => 'reject', 'reason' => 'Perbaiki lisensi'], 1);
        self::assertSame('Perbaiki lisensi', $this->db->query('SELECT reject_reason FROM assets WHERE id=1')->fetchColumn());
    }

    public function testCategoryInUseCannotBeDeleted(): void
    {
        $this->expectException(DomainException::class);
        $this->admin->deleteCategory(1);
    }

    public function testCategoryChangesArePersisted(): void
    {
        $this->admin->saveCategory(['name' => 'Audio', 'slug' => 'audio']);
        $id = $this->db->query("SELECT id FROM categories WHERE slug='audio'")->fetchColumn();
        $this->admin->saveCategory(['id' => $id, 'name' => 'Sound', 'slug' => 'sound']);
        self::assertSame('Sound', $this->db->query("SELECT name FROM categories WHERE slug='sound'")->fetchColumn());
        $this->admin->deleteCategory($id);
        self::assertEquals(1, $this->db->query('SELECT COUNT(*) FROM categories')->fetchColumn());
    }

    public function testAdminCannotBeBlockedOrDemoted(): void
    {
        $this->expectException(DomainException::class);
        $this->admin->updateUser(['id' => 1, 'role' => 'buyer', 'status' => 'blocked']);
    }

    public function testSellerPromotionRequiresApprovedApplication(): void
    {
        $this->expectException(DomainException::class);
        $this->admin->updateUser(['id' => 2, 'role' => 'seller', 'status' => 'active']);
    }

    public function testBlockingClearsRememberToken(): void
    {
        $this->db->exec("UPDATE users SET remember_token='test-token' WHERE id=2");
        $this->admin->updateUser(['id' => 2, 'role' => 'buyer', 'status' => 'blocked']);
        $user = $this->db->query('SELECT * FROM users WHERE id=2')->fetch();
        self::assertFalse($user['is_active']);
        self::assertNull($user['remember_token']);
    }
}
