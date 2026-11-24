<?php

namespace IndoFX\Controllers;

use IndoFX\Core\Controller;
use IndoFX\Models\User;
use IndoFX\Models\Asset;
use IndoFX\Models\Library; 

class ProfileController extends Controller {
    // 1. Profil Publik & Profil Pribadi Pembeli
    public function publicProfile() {
        ini_set('display_errors', 1);
        error_reporting(E_ALL);
    
        $userModel  = new User();
        $assetModel = new Asset();

        $publicId     = $_GET['id'] ?? '';
        $isOwnProfile = false;
        
        // Cek apakah ini profil milik sendiri atau orang lain
        if ($publicId !== '') {
            $user = $userModel->getUserByPublicId($publicId);
            if (isset($_SESSION['public_id']) && $_SESSION['public_id'] === $publicId) {
                $isOwnProfile = true;
            }
        } else {
            if (!isset($_SESSION['user_id'])) $this->redirect('/login');
            $user = $userModel->getUserById($_SESSION['user_id']);
            $isOwnProfile = true;
        }

        if (!$user) {
            echo "<h2 style='text-align:center; padding:50px;'>Profil tidak ditemukan!</h2>"; 
            return;
        }

        $assetsList = [];
        
        if ($user['role'] === 'seller') {
            // Jika dia penjual, tampilkan "Katalog" miliknya ke semua orang (publik)
            $assetsList = $assetModel->getAssetsBySellerId($user['id']);
        } else {
            // Jika dia pembeli, "Aset Dimiliki" HANYA boleh dilihat oleh dirinya sendiri
            if ($isOwnProfile) {
                $libModel   = new Library();
                $rawLibrary = $libModel->getUserLibrary($user['id']);

                // Samakan format key agar langsung cocok dengan card di creator/profile.php
                foreach ($rawLibrary as $item) {
                    $assetsList[] = [
                        'id'              => $item['asset_id'],
                        'public_id'       => $item['asset_public_id'],
                        'title'           => $item['title'],
                        'seller_name'     => $item['creator_name'],
                        'thumbnail'       => $item['thumbnail'],
                        'render_pipeline' => $item['render_pipeline'] ?? 'Built-In'
                    ];
                }
            }
        }

        $this->view('creator/profile', [
            'user'         => $user,
            'assetsList'   => $assetsList,
            'isOwnProfile' => $isOwnProfile
        ]);
    }

    // 2. Memproses Form Edit Profil Pembeli
    public function updateBuyer() {
        if (!isset($_SESSION['user_id'])) $this->redirect('/login');

        $userId = $_SESSION['user_id'];
        $name   = trim($_POST['name'] ?? '');
        $bio    = trim($_POST['bio'] ?? '');

        $userModel = new User();
        $userModel->updateBuyerProfile($userId, $name, $bio);

        $_SESSION['name'] = $name;
        $_SESSION['bio']  = $bio;
        $_SESSION['success_msg'] = "Profil pembeli berhasil diperbarui!";

        $this->redirect('/dashboard/ubah-profil');
    }

    // 3. Menampilkan Halaman Edit Profil (ubahprofile.php)
    public function edit() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }

        $userModel = new User();
        $user = $userModel->getUserById($_SESSION['user_id']);

        // Petakan kolom database ke key yang dibaca oleh ubahprofile.php
        $sellerProfile = [
            'store_name'        => $user['store_name'] ?? $user['name'] ?? '',
            'contact_email'     => $user['contact_email'] ?? $user['email'] ?? '',
            'bio'               => $user['bio'] ?? '',
            'portfolio_url'     => $user['portfolio_url'] ?? '',
            'website_url'       => $user['website_url'] ?? '',
            'dana_number'       => $user['payout_number'] ?? '',
            'dana_account_name' => $user['payout_account_name'] ?? ''
        ];

        $this->view('dashboard/ubahprofile', [
            'user'          => $user,
            'sellerProfile' => $sellerProfile
        ]);
    }

    // 4. Menampilkan Halaman Edit Profil Penjual dengan Sidebar (dashboard/profile.php)
    public function dashboardProfile() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }
        if (($_SESSION['role'] ?? '') !== 'seller') {
            $this->redirect('/dashboard/ubah-profil');
        }

        $userModel = new User();
        $user = $userModel->getUserById($_SESSION['user_id']);

        $sellerProfile = [
            'store_name'          => $user['store_name'] ?? '',
            'contact_email'       => $user['contact_email'] ?? $user['email'] ?? '',
            'bio'                 => $user['bio'] ?? '',
            'portfolio_url'       => $user['portfolio_url'] ?? '',
            'website_url'         => $user['website_url'] ?? '',
            'payout_number'       => $user['payout_number'] ?? '',
            'payout_account_name' => $user['payout_account_name'] ?? ''
        ];

        $this->view('dashboard/profile', [
            'user'          => $user,
            'sellerProfile' => $sellerProfile
        ]);
    }  

    // 5. Memproses Form Edit Profil Penjual (/dashboard/ubah-profil-toko)
    public function updateSeller() {
        self::guardSeller();

        $userId      = (int) $_SESSION['user_id'];
        $storeName   = trim($_POST['store_name'] ?? '');
        $contactEmail = trim($_POST['contact_email'] ?? '');
        $bio         = trim($_POST['bio'] ?? '');
        $portfolio   = trim($_POST['portfolio_url'] ?? '');
        $website     = trim($_POST['website_url'] ?? '');
        $danaNumber  = trim($_POST['dana_number'] ?? '');
        $danaName    = trim($_POST['dana_account_name'] ?? '');

        if ($storeName === '' || mb_strlen($storeName) > 150 || strlen($contactEmail) > 254 || !filter_var($contactEmail, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error_msg'] = 'Isi nama toko (maksimal 150 karakter) dan email kontak yang valid.';
            $this->redirect('/dashboard/profile');
        }

        $db = \IndoFX\Core\Database::getInstance()->getConnection();
        try {
            $db->beginTransaction();
            $stmtBio = $db->prepare("UPDATE user_profiles SET bio = :bio, updated_at = NOW() WHERE user_id = :id");
            $stmtBio->execute([':bio' => $bio, ':id' => $userId]);

            $stmtSeller = $db->prepare("
                UPDATE seller_profiles SET
                    store_name = :store_name,
                    contact_email = :contact_email,
                    portfolio_url = :portfolio_url,
                    website_url = :website_url,
                    payout_number = :payout_number,
                    payout_account_name = :payout_account_name,
                    updated_at = NOW()
                WHERE user_id = :id
            ");
            $stmtSeller->execute([
                ':store_name'          => $storeName,
                ':contact_email'       => $contactEmail,
                ':portfolio_url'       => $portfolio !== '' ? $portfolio : null,
                ':website_url'         => $website !== '' ? $website : null,
                ':payout_number'       => $danaNumber !== '' ? $danaNumber : null,
                ':payout_account_name' => $danaName !== '' ? $danaName : null,
                ':id'                  => $userId
            ]);
            if ($stmtSeller->rowCount() !== 1) {
                throw new \RuntimeException('Profil toko tidak ditemukan.');
            }
            $db->commit();
        } catch (\Throwable $e) {
            if ($db->inTransaction()) $db->rollBack();
            error_log('Seller profile update failed: ' . $e->getMessage());
            $_SESSION['error_msg'] = 'Profil toko gagal disimpan. Silakan coba kembali.';
            $this->redirect('/dashboard/profile');
        }

        $_SESSION['store_name']  = $storeName;
        $_SESSION['bio']         = $bio;
        $_SESSION['success_msg'] = "Profil toko penjual berhasil diperbarui!";

        $this->redirect('/dashboard/profile');
    }
}
