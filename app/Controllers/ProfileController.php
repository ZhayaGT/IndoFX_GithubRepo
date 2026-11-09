<?php

namespace IndoFX\Controllers;

use IndoFX\Core\Controller;
use IndoFX\Core\Database;
use IndoFX\Models\Asset;
use IndoFX\Models\SellerProfile;

class ProfileController extends Controller {

    // Halaman toko penjual dan profil pembeli, bisa dibuka tanpa login.
    public function show(): void {
        $publicId = trim((string) ($_GET['id'] ?? ''));

        if ($publicId === '') {
            $publicId = trim((string) ($_SESSION['public_id'] ?? ''));
        }
        if ($publicId === '') {
            $this->notFound();
            return;
        }

        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT id, public_id, name, bio, role, created_at FROM users WHERE public_id = :id::uuid LIMIT 1");
        $stmt->bindValue(':id', $publicId);
        $stmt->execute();
        $creator = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$creator) {
            $this->notFound();
            return;
        }

        $isSeller = $creator['role'] === 'seller';
        $profile = $isSeller ? (new SellerProfile())->find((int) $creator['id']) : null;
        $assetModel = new Asset();

        $this->view('creator/profile', [
            'creator'  => $creator,
            'profile'  => $profile,
            'isSeller' => $isSeller,
            'stats'    => $isSeller ? $assetModel->statsForSeller((int) $creator['id']) : [],
            'assets'   => $isSeller ? $assetModel->listForSeller((int) $creator['id'], ['status' => 'published']) : [],
        ]);
    }

    private function notFound(): void {
        http_response_code(404);
        require BASE_PATH . '/resources/views/errors/404.php';
    }
}