<?php

namespace IndoFX\Controllers;

use IndoFX\Core\Controller;
use IndoFX\Core\Database;

class MediaController extends Controller {

    // Unduhan paket aset: pemilik aset atau pembeli yang punya entitlement.
    public function download(): void {
        $publicId = trim((string) ($_GET['id'] ?? ''));

        if ($publicId === '') {
            $this->notFound();
            return;
        }

        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare(
            "SELECT f.*, a.id AS asset_id, a.seller_id
             FROM asset_files f
             JOIN assets a ON a.id = f.asset_id
             WHERE f.public_id = :id::uuid
             LIMIT 1"
        );
        $stmt->bindValue(':id', $publicId);
        $stmt->execute();
        $file = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$file) {
            $this->notFound();
            return;
        }

        if (!$this->mayDownload($db, $file)) {
            http_response_code(403);
            echo 'Anda belum memiliki aset ini.';
            return;
        }

        $absolutePath = BASE_PATH . '/' . $file['file_path'];

        if (!is_file($absolutePath)) {
            $this->notFound();
            return;
        }

        header('Content-Type: ' . $file['mime_type']);
        header('Content-Disposition: attachment; filename="' . basename($file['original_name']) . '"');
        header('Content-Length: ' . (string) filesize($absolutePath));
        readfile($absolutePath);
    }

    private function mayDownload(\PDO $db, array $file): bool {
        $userId = (int) ($_SESSION['user_id'] ?? 0);

        if ($userId === 0) {
            return false;
        }
        if ($userId === (int) $file['seller_id']) {
            return true;
        }

        $stmt = $db->prepare("SELECT 1 FROM entitlements WHERE user_id = :user_id AND asset_id = :asset_id LIMIT 1");
        $stmt->bindValue(':user_id', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':asset_id', (int) $file['asset_id'], \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchColumn() !== false;
    }

    private function notFound(): void {
        http_response_code(404);
        require BASE_PATH . '/resources/views/errors/404.php';
    }
}