<?php

namespace IndoFX\Models;

use IndoFX\Core\Model;
use DomainException;
use PDO;

class Admin extends Model
{
    public function __construct(?PDO $connection = null)
    {
        if ($connection !== null) $this->db = $connection;
        else parent::__construct();
    }

    public function users(): array
    {
        return $this->db->query("SELECT u.id, u.email, u.role, u.is_active, u.created_at,
            COALESCE(NULLIF(p.name, ''), u.email) AS name, s.store_name,
            (SELECT COUNT(*) FROM assets a WHERE a.seller_id = u.id AND a.deleted_at IS NULL) AS asset_count
            FROM users u LEFT JOIN user_profiles p ON p.user_id = u.id
            LEFT JOIN seller_profiles s ON s.user_id = u.id ORDER BY u.id DESC")->fetchAll();
    }

    public function assets(): array
    {
        return $this->db->query("SELECT a.*, c.name AS category,
            COALESCE(NULLIF(s.store_name, ''), p.name, u.email) AS studio,
            (SELECT image_path FROM asset_images WHERE asset_id = a.id ORDER BY sort_order, id LIMIT 1) AS image,
            (SELECT json_agg(image_path ORDER BY sort_order, id) FROM asset_images WHERE asset_id=a.id) AS images,
            f.original_name, f.file_size, f.id AS file_id
            FROM assets a JOIN users u ON u.id = a.seller_id
            LEFT JOIN user_profiles p ON p.user_id = u.id
            LEFT JOIN seller_profiles s ON s.user_id = u.id
            LEFT JOIN categories c ON c.id = a.category_id
            LEFT JOIN LATERAL (SELECT id, original_name, file_size FROM asset_files
                WHERE asset_id = a.id ORDER BY created_at DESC, id DESC LIMIT 1) f ON TRUE
            WHERE a.deleted_at IS NULL ORDER BY a.created_at DESC, a.id DESC")->fetchAll();
    }

    public function categories(): array
    {
        return $this->db->query("SELECT c.*, c.description AS desc,
            (SELECT COUNT(*) FROM assets a WHERE a.category_id = c.id AND a.deleted_at IS NULL) AS asset_count
            FROM categories c ORDER BY c.name")->fetchAll();
    }

    public function sellers(): array
    {
        return $this->db->query("SELECT s.*, u.email, COALESCE(p.name, u.email) AS full_name
            FROM seller_profiles s JOIN users u ON u.id = s.user_id
            LEFT JOIN user_profiles p ON p.user_id = u.id ORDER BY s.created_at DESC, s.user_id DESC")->fetchAll();
    }

    public function transactions(): array
    {
        return $this->db->query("SELECT t.*, COALESCE(p.name, u.email) AS buyer, u.email AS buyer_email,
            COALESCE(items.asset_title, 'Tidak ada detail aset') AS asset_title,
            COALESCE(items.seller, '-') AS seller, items.subtotal AS subtotal
            FROM transactions t JOIN users u ON u.id = t.buyer_id
            LEFT JOIN user_profiles p ON p.user_id = u.id
            LEFT JOIN LATERAL (
                SELECT string_agg(a.title, ', ' ORDER BY d.id) AS asset_title,
                    string_agg(DISTINCT COALESCE(s.store_name, sp.name, su.email), ', ') AS seller,
                    SUM(d.price) AS subtotal
                FROM transaction_details d JOIN assets a ON a.id = d.asset_id
                JOIN users su ON su.id = a.seller_id
                LEFT JOIN user_profiles sp ON sp.user_id = su.id
                LEFT JOIN seller_profiles s ON s.user_id = su.id WHERE d.transaction_id = t.id
            ) items ON TRUE ORDER BY t.created_at DESC, t.id DESC")->fetchAll();
    }

    public function saveCategory(array $data): void
    {
        $name = trim((string) ($data['name'] ?? ''));
        $slug = trim((string) ($data['slug'] ?? ''));
        $icon = (string) ($data['icon'] ?? 'bi-tags');
        $description = trim((string) ($data['desc'] ?? ''));
        if ($name === '' || mb_strlen($name) > 100 || !preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/D', $slug)
            || strlen($slug) > 100 || !preg_match('/^bi-[a-z0-9-]{1,40}$/D', $icon) || mb_strlen($description) > 2000) {
            throw new DomainException('Isi nama, slug, ikon, dan deskripsi kategori dengan benar.');
        }
        $params = ['name' => $name, 'slug' => $slug, 'icon' => $icon, 'description' => $description];
        if (!empty($data['id'])) {
            $params['id'] = $this->id($data['id']);
            $stmt = $this->db->prepare('UPDATE categories SET name=:name, slug=:slug, icon=:icon,
                description=:description, updated_at=NOW() WHERE id=:id');
        } else {
            $stmt = $this->db->prepare('INSERT INTO categories (name, slug, icon, description)
                VALUES (:name, :slug, :icon, :description)');
        }
        $stmt->execute($params);
        if ($stmt->rowCount() === 0) throw new DomainException('Kategori tidak ditemukan.');
    }

    public function deleteCategory(mixed $id): void
    {
        // A category in use must stay linked to its assets, including archived assets.
        $stmt = $this->db->prepare('DELETE FROM categories c WHERE c.id=:id
            AND NOT EXISTS (SELECT 1 FROM assets a WHERE a.category_id=c.id)');
        $stmt->execute(['id' => $this->id($id)]);
        if (!$stmt->rowCount()) throw new DomainException('Kategori tidak ditemukan atau masih digunakan oleh aset.');
    }

    public function review(string $type, array $data, int $adminId): void
    {
        $id = $this->id($data['id'] ?? null);
        $decision = $data['decision'] ?? '';
        $reason = trim((string) ($data['reason'] ?? ''));
        if (!in_array($decision, ['approve', 'reject'], true) || mb_strlen($reason) > 2000
            || ($decision === 'reject' && $reason === '')) {
            throw new DomainException('Pilih keputusan yang valid dan isi alasan penolakan (maksimal 2000 karakter).');
        }
        $table = $type === 'asset' ? 'assets' : 'seller_profiles';
        $key = $type === 'asset' ? 'id' : 'user_id';
        $this->db->beginTransaction();
        try {
            $extra = $type === 'asset' ? ' AND deleted_at IS NULL' : '';
            $stmt = $this->db->prepare("SELECT * FROM $table WHERE $key=:id $extra FOR UPDATE");
            $stmt->execute(['id' => $id]);
            $submission = $stmt->fetch();
            if (!$submission || $submission['status'] !== 'pending') throw new DomainException('Pengajuan tidak ditemukan atau sudah diproses. Muat ulang halaman.');
            if ($type === 'seller') {
                $stmt = $this->db->prepare('SELECT role, is_active FROM users WHERE id=:id FOR UPDATE');
                $stmt->execute(['id' => $id]);
                $user = $stmt->fetch();
                if (!$user || $user['role'] === 'admin' || !$user['is_active']) {
                    throw new DomainException('Pengajuan akun admin atau akun diblokir tidak dapat diproses.');
                }
            }
            $newStatus = $decision === 'reject' ? 'rejected' : ($type === 'asset' ? 'published' : 'approved');
            $stmt = $this->db->prepare("UPDATE $table SET status=:status, reject_reason=:reason,
                reviewed_by=:admin, reviewed_at=NOW(), updated_at=NOW() WHERE $key=:id");
            $stmt->execute(['status' => $newStatus, 'reason' => $decision === 'reject' ? $reason : null,
                'admin' => $adminId, 'id' => $id]);
            if ($type === 'seller' && $decision === 'approve') {
                $stmt = $this->db->prepare("UPDATE users SET role='seller', updated_at=NOW() WHERE id=:id");
                $stmt->execute(['id' => $id]);
            }
            $this->db->commit();
        } catch (\Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function updateUser(array $data): void
    {
        $id = $this->id($data['id'] ?? null);
        $role = $data['role'] ?? '';
        $status = $data['status'] ?? '';
        if (!in_array($role, ['buyer', 'seller'], true) || !in_array($status, ['active', 'blocked'], true)) {
            throw new DomainException('Role atau status akun tidak valid.');
        }
        $stmt = $this->db->prepare("UPDATE users u SET role=:role, is_active=:active,
            remember_token=NULL, updated_at=NOW() WHERE id=:id AND role <> 'admin'
            AND (:requested_role <> 'seller' OR EXISTS
                (SELECT 1 FROM seller_profiles s WHERE s.user_id=u.id AND s.status='approved'))");
        $stmt->bindValue(':role', $role);
        $stmt->bindValue(':requested_role', $role);
        $stmt->bindValue(':active', $status === 'active', PDO::PARAM_BOOL);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        if (!$stmt->rowCount()) throw new DomainException('Akun tidak ditemukan, merupakan admin, atau belum mendapat persetujuan penjual.');
    }

    private function id(mixed $value): int
    {
        $id = filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        if ($id === false) throw new DomainException('ID tidak valid.');
        return $id;
    }
}
