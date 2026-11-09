<?php

namespace IndoFX\Controllers;

use IndoFX\Core\Controller;
use IndoFX\Models\User;
use IndoFX\Models\Asset;

class ProfileController extends Controller {

    // 1. Profil Publik & Profil Pribadi Pembeli
    public function publicProfile() {
        $userModel = new User();
        $assetModel = new Asset();

        $publicId = $_GET['id'] ?? '';
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
                // fungsi getPurchasedAssets/getLibrary 
                // Sementara menggunakan data kosong atau dummy karena struktur tabel transaksi belum ada
                $assetsList = []; 
            }
        }

        $this->view('creator/profile', [
            'user'         => $user,
            'assetsList'   => $assetsList,
            'isOwnProfile' => $isOwnProfile
        ]);
    }

    // 2. Menampilkan Halaman Edit Profil (ubahprofile.php)
    public function edit() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }

        $userModel = new User();
        $user = $userModel->getUserById($_SESSION['user_id']);

        // PERBAIKAN: Arahkan ke folder dashboard/, bukan profile/
        $this->view('dashboard/ubahprofile', [
            'user' => $user
        ]);
    }

    // 3. Memproses Form Edit Profil Pembeli
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
}