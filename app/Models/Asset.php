<?php

namespace IndoFX\Models;

use IndoFX\Core\Model;
use PDO;

class Asset extends Model {
    // Mengambil 4 aset terbaru untuk section "Aset CG & VFX Terbaru"
    public function getLatestAssets($limit = 4) {
        $stmt = $this->db->prepare("SELECT * FROM assets ORDER BY created_at DESC LIMIT :limit");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Mengambil 4 aset gratis untuk section "Aset Gratis Unggulan"
    public function getFreeAssets($limit = 4) {
        // kolom harga bernama 'price' dan aset gratis harganya 0
        $stmt = $this->db->prepare("SELECT * FROM assets WHERE price = 0 ORDER BY created_at DESC LIMIT :limit");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Mengambil semua data aset, diurutkan dari yang terbaru untuk ditampilkan pada halaman explore
    public function getAllAssets() {
        $stmt = $this->db->prepare("SELECT * FROM assets ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }
}