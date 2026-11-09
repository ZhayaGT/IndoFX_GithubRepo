<?php

namespace IndoFX\Controllers;

use IndoFX\Core\Controller;
use IndoFX\Core\Database;
use IndoFX\Models\Asset;
use IndoFX\Models\SellerProfile;

class SellerController extends Controller {

    // Halaman pendaftaran penjual: tamu melihat ajakan login, pembeli melihat form.
    public function upgradeForm(): void {
        if (($_SESSION['role'] ?? '') === 'seller') {
            $this->redirect('/dashboard');
        }

        $this->view('auth/seller_register', [
            'error' => $_SESSION['seller_error'] ?? null,
        ]);

        unset($_SESSION['seller_error']);
    }

    // Pembeli menjadi penjual: role dan profil toko disimpan sekaligus.
    public function upgrade(): void {
        self::guardLogin();

        if (($_SESSION['role'] ?? '') === 'seller') {
            $this->redirect('/dashboard');
        }

        $storeName = trim((string) ($_POST['store_name'] ?? ''));

        if ($storeName === '' || mb_strlen($storeName) > 150) {
            $_SESSION['seller_error'] = 'Nama toko wajib diisi, maksimal 150 karakter.';
            $this->redirect('/seller/register');
        }

        $userId = (int) $_SESSION['user_id'];
        $profile = [
            'store_name'    => $storeName,
            'address'       => trim((string) ($_POST['address'] ?? '')),
            'reason'        => trim((string) ($_POST['reason'] ?? '')),
            'portfolio_url' => trim((string) ($_POST['portfolio_url'] ?? '')),
        ];

        $db = Database::getInstance();

        try {
            $db->beginTransaction();
            $db->query("UPDATE users SET role = 'seller', updated_at = NOW() WHERE id = :id", [':id' => $userId]);
            (new SellerProfile())->upsert($userId, $profile);
            $db->commit();
        } catch (\Throwable $e) {
            $db->rollback();
            $_SESSION['seller_error'] = 'Pendaftaran penjual gagal disimpan. Coba lagi.';
            $this->redirect('/seller/register');
        }

        $_SESSION['role'] = 'seller';
        $_SESSION['store_name'] = $storeName;

        $this->redirect('/dashboard');
    }

    // Ringkasan penjualan: angka statistik, aset terbaru, transaksi terbaru.
    public function dashboard(): void {
        self::guardSeller();

        $sellerId = (int) $_SESSION['user_id'];
        $assetModel = new Asset();

        $this->view('dashboard/main', [
            'storeName'    => $this->storeName(),
            'initials'     => $this->initials($this->storeName()),
            'stats'        => $assetModel->statsForSeller($sellerId),
            'assets'       => array_slice($assetModel->listForSeller($sellerId), 0, 5),
            'transactions' => array_slice($assetModel->transactionsForSeller($sellerId), 0, 5),
        ]);
    }

    // Daftar seluruh aset penjual dengan filter pencarian.
    public function assets(): void {
        self::guardSeller();

        $sellerId = (int) $_SESSION['user_id'];
        $assetModel = new Asset();

        $this->view('dashboard/dashboard', [
            'storeName'  => $this->storeName(),
            'initials'   => $this->initials($this->storeName()),
            'assets'     => $assetModel->listForSeller($sellerId, [
                'q'           => $_GET['q'] ?? '',
                'status'      => $_GET['status'] ?? '',
                'category_id' => $_GET['category_id'] ?? '',
            ]),
            'categories' => $assetModel->categories(),
            'filter'     => [
                'q'           => trim((string) ($_GET['q'] ?? '')),
                'status'      => trim((string) ($_GET['status'] ?? '')),
                'category_id' => (int) ($_GET['category_id'] ?? 0),
            ],
        ]);
    }

    // Riwayat transaksi beserta ringkasan pendapatan.
    public function transactions(): void {
        self::guardSeller();

        $transactions = (new Asset())->transactionsForSeller((int) $_SESSION['user_id']);

        $income = 0;
        foreach ($transactions as $row) {
            if ($row['status'] === 'settlement') {
                $income += (int) $row['amount'];
            }
        }

        $this->view('dashboard/transactions', [
            'storeName'    => $this->storeName(),
            'initials'     => $this->initials($this->storeName()),
            'transactions' => $transactions,
            'income'       => $income,
            'count'        => count($transactions),
            'average'      => $transactions === [] ? 0 : (int) round($income / count($transactions)),
        ]);
    }

    // Form profil toko berisi data seller_profiles dan bio pengguna.
    public function profile(): void {
        self::guardSeller();

        $userId = (int) $_SESSION['user_id'];
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT name, email, bio FROM users WHERE id = :id");
        $stmt->bindValue(':id', $userId, \PDO::PARAM_INT);
        $stmt->execute();

        $this->view('dashboard/profile', [
            'storeName' => $this->storeName(),
            'initials'  => $this->initials($this->storeName()),
            'profile'   => (new SellerProfile())->find($userId),
            'user'      => $stmt->fetch(\PDO::FETCH_ASSOC) ?: [],
            'error'     => $_SESSION['profile_error'] ?? null,
            'saved'     => isset($_GET['saved']),
        ]);

        unset($_SESSION['profile_error']);
    }

    // Simpan profil toko dan bio kreator.
    public function saveProfile(): void {
        self::guardSeller();

        $storeName = trim((string) ($_POST['store_name'] ?? ''));

        if ($storeName === '' || mb_strlen($storeName) > 150) {
            $_SESSION['profile_error'] = 'Nama toko wajib diisi, maksimal 150 karakter.';
            $this->redirect('/dashboard/profile');
        }

        $userId = (int) $_SESSION['user_id'];

        (new SellerProfile())->upsert($userId, [
            'store_name'          => $storeName,
            'address'             => trim((string) ($_POST['address'] ?? '')),
            'reason'              => trim((string) ($_POST['reason'] ?? '')),
            'portfolio_url'       => trim((string) ($_POST['portfolio_url'] ?? '')),
            'website_url'         => trim((string) ($_POST['website_url'] ?? '')),
            'payout_number'       => trim((string) ($_POST['payout_number'] ?? '')),
            'payout_account_name' => trim((string) ($_POST['payout_account_name'] ?? '')),
        ]);

        Database::getInstance()->query(
            "UPDATE users SET bio = :bio, updated_at = NOW() WHERE id = :id",
            [':bio' => trim((string) ($_POST['bio'] ?? '')), ':id' => $userId]
        );

        $_SESSION['store_name'] = $storeName;

        $this->redirect('/dashboard/profile?saved=1');
    }

    private function storeName(): string {
        $name = trim((string) ($_SESSION['store_name'] ?? ''));

        return $name !== '' ? $name : (string) ($_SESSION['name'] ?? 'Toko Saya');
    }

    private function initials(string $name): string {
        $initials = '';

        foreach (array_slice(preg_split('/\s+/', trim($name)) ?: [], 0, 2) as $word) {
            $initials .= mb_strtoupper(mb_substr($word, 0, 1));
        }

        return $initials !== '' ? $initials : 'AS';
    }
}