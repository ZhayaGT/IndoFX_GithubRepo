<?php

namespace IndoFX\Controllers;

use IndoFX\Core\Controller;
use IndoFX\Core\Database;
use IndoFX\Models\Admin;
use IndoFX\Models\User;
use DomainException;
use PDOException;

class AdminController extends Controller
{
    private function requireAdmin(bool $api = false): void
    {
        $user = isset($_SESSION['user_id']) ? (new User())->getUserById($_SESSION['user_id']) : null;
        if (!$user || !$user['is_active'] || $user['role'] !== 'admin') {
            if ($api) $this->json(['message' => 'Akses hanya untuk admin aktif.'], 403);
            $this->redirect($user ? '/' : '/login');
        }
        $_SESSION['admin_csrf'] ??= bin2hex(random_bytes(32));
    }

    public function page(): void
    {
        $this->requireAdmin();
        $pages = ['/admin' => 'dashboard', '/admin/dashboard' => 'dashboard',
            '/admin/assets' => 'assets', '/admin/assets/review' => 'asset_review',
            '/admin/categories' => 'categories', '/admin/users' => 'users',
            '/admin/seller-requests' => 'seller_requests', '/admin/transactions' => 'transactions'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->view('admin/' . $pages[$path]);
    }

    public function data(): void
    {
        $this->requireAdmin(true);
        try {
            $model = new Admin();
            $users = array_map(function ($u) {
                return ['id' => (int) $u['id'], 'name' => $u['store_name'] ?: $u['name'], 'email' => $u['email'],
                    'role' => ['admin' => 'Admin', 'seller' => 'Penjual', 'buyer' => 'Pembeli'][$u['role']],
                    'status' => $u['is_active'] ? 'Aktif' : 'Diblokir', 'join_date' => $this->date($u['created_at']),
                    'stats' => $u['asset_count'] . ' Aset', 'verified' => $u['role'] === 'seller',
                    'inits' => mb_strtoupper(mb_substr($u['name'], 0, 2)), 'badge_bg' => '#FCE8DC', 'badge_color' => '#B34E0B'];
            }, $model->users());
            $assets = array_map(function ($a) {
                return ['id' => (int) $a['id'], 'public_id' => $a['public_id'], 'title' => $a['title'],
                    'category' => $a['category'] ?? '-', 'studio' => $a['studio'], 'pipeline' => $a['render_pipeline'] ?? '',
                    'unity_ver' => $a['unity_version'] ?? '-', 'price_num' => (int) $a['price'],
                    'price' => $a['price'] ? 'Rp' . number_format($a['price'], 0, ',', '.') : 'Gratis',
                    'date' => $this->date($a['created_at']), 'time' => date('H:i', strtotime($a['created_at'])),
                    'status' => ['pending' => 'Menunggu', 'published' => 'Published', 'approved' => 'Published',
                        'rejected' => 'Ditolak'][$a['status']] ?? $a['status'],
                    'description' => $a['description'] ?? '', 'how_to_use' => $a['how_to_use'] ?? '',
                    'video_url' => $this->url($a['preview_video_url'] ?? ''), 'image' => $this->url($a['image'] ?? ''),
                    'images' => array_values(array_filter(array_map(fn ($url) => $this->url($url), json_decode($a['images'] ?? '[]', true)))),
                    'file_info' => $a['original_name'] ?? 'Belum ada berkas',
                    'file_size' => $a['file_size'] ? round($a['file_size'] / 1048576, 2) . ' MB' : '-',
                    'file_id' => $a['file_id'], 'reject_reason' => $a['reject_reason'] ?? ''];
            }, $model->assets());
            $sellers = array_map(function ($s) {
                return ['id' => (int) $s['user_id'], 'name' => $s['store_name'], 'full_name' => $s['full_name'],
                    'email' => $s['email'], 'address' => $s['address'], 'reason' => $s['reason'],
                    'portfolio' => $s['portfolio_url'], 'attachment' => basename($s['sample_asset_path'] ?? ''),
                    'attachment_size' => '-', 'asset_desc' => $s['sample_asset_desc'],
                    'submission_date' => $this->date($s['created_at']),
                    'status' => ['pending' => 'Menunggu', 'approved' => 'Diterima', 'rejected' => 'Ditolak'][$s['status']] ?? $s['status'],
                    'reject_reason' => $s['reject_reason'] ?? '', 'inits' => mb_strtoupper(mb_substr($s['store_name'], 0, 2)),
                    'bg' => '#FCE8DC', 'color' => '#B34E0B'];
            }, $model->sellers());
            $categories = array_map(function ($c) {
                $c['count'] = $c['asset_count'] . ' Aset';
                return $c;
            }, $model->categories());
            $transactions = array_map(function ($t) {
                $paid = in_array(strtolower($t['status']), ['settlement', 'capture', 'success'], true);
                return ['id' => (int) $t['id'], 'order_id' => $t['invoice_number'],
                    'time' => $this->date($t['created_at']) . ' ' . date('H:i', strtotime($t['created_at'])),
                    'asset_title' => $t['asset_title'], 'seller' => $t['seller'], 'buyer' => $t['buyer'],
                    'buyer_email' => $t['buyer_email'], 'method' => $t['payment_type'] ?: '-',
                    'total_bayar' => (int) $t['amount'], 'fee' => $t['subtotal'] === null ? null : max(0, (int) $t['amount'] - (int) $t['subtotal']),
                    'status' => $paid ? 'Settlement' : (strtolower($t['status']) === 'pending' ? 'Pending' : ucfirst($t['status'])),
                    'database_status' => $t['status'], 'ref_id' => $t['midtrans_transaction_id'] ?: '-',
                    'record' => ['invoice_number' => $t['invoice_number'], 'status' => $t['status'],
                        'amount' => (int) $t['amount'], 'payment_type' => $t['payment_type'],
                        'midtrans_transaction_id' => $t['midtrans_transaction_id'], 'paid_at' => $t['paid_at']]];
            }, $model->transactions());
            $this->json(compact('users', 'assets', 'sellers', 'categories', 'transactions'));
        } catch (\Throwable $e) {
            error_log((string) $e);
            $this->json(['message' => 'Data admin gagal dimuat. Periksa koneksi database dan migrasi admin.'], 500);
        }
    }

    public function save(): void
    {
        $this->requireAdmin(true);
        $token = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        if (!is_string($token) || !hash_equals($_SESSION['admin_csrf'], $token)) {
            $this->json(['message' => 'Sesi form kedaluwarsa. Muat ulang halaman.'], 419);
        }
        $data = json_decode(file_get_contents('php://input'), true);
        if (!is_array($data)) $this->json(['message' => 'Data permintaan tidak valid.'], 422);
        $model = new Admin();
        try {
            switch ($data['action'] ?? '') {
                case 'category.save': $model->saveCategory($data); break;
                case 'category.delete': $model->deleteCategory($data['id'] ?? null); break;
                case 'user.update': $model->updateUser($data); break;
                case 'asset.review': $model->review('asset', $data, (int) $_SESSION['user_id']); break;
                case 'seller.review': $model->review('seller', $data, (int) $_SESSION['user_id']); break;
                default: throw new DomainException('Aksi admin tidak dikenal.');
            }
            $this->json(['message' => 'Perubahan berhasil disimpan.']);
        } catch (DomainException $e) {
            $this->json(['message' => $e->getMessage()], 422);
        } catch (PDOException $e) {
            error_log((string) $e);
            $message = $e->getCode() === '23505' ? 'Nama atau slug tersebut sudah digunakan.' : 'Perubahan gagal disimpan.';
            $this->json(['message' => $message], 409);
        }
    }

    public function download(): void
    {
        $this->requireAdmin();
        $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        if (!$id) { http_response_code(404); exit('Berkas tidak ditemukan.'); }
        $sql = ($_GET['type'] ?? '') === 'sample'
            ? 'SELECT sample_asset_path AS file_path FROM seller_profiles WHERE user_id=:id'
            : 'SELECT file_path FROM asset_files WHERE id=:id';
        $stmt = Database::getInstance()->getConnection()->prepare($sql);
        $stmt->execute(['id' => $id]);
        $path = $stmt->fetchColumn();
        if (!$path) { http_response_code(404); exit('Berkas tidak ditemukan.'); }
        $relative = ltrim($path, '/');
        if (str_starts_with($relative, 'uploads/')) $relative = 'public/' . $relative;
        $absolute = realpath(BASE_PATH . '/' . $relative);
        $allowed = false;
        foreach (['public/uploads', 'storage/uploads'] as $folder) {
            $root = realpath(BASE_PATH . '/' . $folder);
            if ($root && $absolute && str_starts_with($absolute, $root . DIRECTORY_SEPARATOR)) $allowed = true;
        }
        if (!$allowed || !is_file($absolute)) { http_response_code(404); exit('Berkas lokal tidak tersedia pada server ini.'); }
        header('Content-Type: application/octet-stream');
        header('X-Content-Type-Options: nosniff');
        header('Content-Disposition: attachment; filename="' . rawurlencode(basename($absolute)) . '"');
        header('Content-Length: ' . filesize($absolute));
        readfile($absolute);
    }

    private function date(string $value): string { return date('d M Y', strtotime($value)); }

    private function url(string $value): string
    {
        if (str_starts_with($value, 'uploads/') || str_starts_with($value, 'assets/')) $value = '/' . $value;
        return str_starts_with($value, '/') && !str_starts_with($value, '//')
            || preg_match('~^https?://~i', $value) ? $value : '';
    }

    private function json(array $data, int $status = 200): never
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-store');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
        exit;
    }
}
