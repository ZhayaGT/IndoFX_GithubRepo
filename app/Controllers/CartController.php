<?php

namespace IndoFX\Controllers;

use IndoFX\Core\Controller;
use IndoFX\Models\Cart;

class CartController extends Controller {
    // 1. Menampilkan Halaman Keranjang
    public function index() {
        // Wajib login untuk melihat keranjang
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }

        $cartModel = new Cart();
        
        // Menarik data keranjang milik user yang sedang login
        $cartItems = $cartModel->getUserCart($_SESSION['user_id']);

        // Mengirim data ke View (Sesuaikan string 'keranjang' atau 'cart/keranjang' dengan lokasi file Anda di folder views)
        $this->view('cart/keranjang', [
            'cartItems' => $cartItems
        ]);
    }

    // 2. Memproses Penambahan ke Keranjang (Dari Halaman Detail)
    public function add() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }

        $assetId = $_POST['asset_id'] ?? null;
        
        if ($assetId) {
            $cartModel = new Cart();
            $cartModel->addToCart($_SESSION['user_id'], $assetId);
        }

        // Arahkan ke halaman keranjang setelah berhasil ditambahkan
        $this->redirect('/cart');
    }

    // 3. Memproses Penghapusan dari Keranjang (Dari Modal Hapus)
    public function remove() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }

        $assetId = $_POST['asset_id'] ?? null;
        
        if ($assetId) {
            $cartModel = new Cart();
            $cartModel->removeCartItem($_SESSION['user_id'], $assetId);
        }

        // Refresh halaman keranjang setelah dihapus
        $this->redirect('/cart');
    }
}