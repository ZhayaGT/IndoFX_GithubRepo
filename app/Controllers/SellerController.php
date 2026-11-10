<?php

namespace IndoFX\Controllers;

use IndoFX\Core\Controller;
use IndoFX\Core\Database;
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
}
