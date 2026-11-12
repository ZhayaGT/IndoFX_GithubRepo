<?php

namespace IndoFX\Controllers;

use IndoFX\Core\Controller;
use IndoFX\Models\Cart;
use IndoFX\Models\Asset;
use Midtrans\Config;
use Midtrans\Snap;

class CheckoutController extends Controller {

    // Menampilkan Halaman Ringkasan Pembayaran
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }

        $checkoutItems = [];

        // KONDISI 1: Pengguna datang dari Keranjang Belanja (Tombol Submit form POST)
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['selected_items'])) {
            $cartModel = new Cart();
            // Tarik seluruh isi keranjang dari database
            $allCart = $cartModel->getUserCart($_SESSION['user_id']);
            
            // Cocokkan dan filter hanya barang yang kotaknya di-ceklis oleh pengguna
            $selectedIds = $_POST['selected_items'];
            foreach ($allCart as $item) {
                if (in_array($item['id'], $selectedIds)) {
                    $checkoutItems[] = $item;
                }
            }
        } 

        // KONDISI 2: Pengguna datang dari Halaman Detail (Tombol "Beli" Langsung via GET)
        elseif (isset($_GET['asset_id'])) {
            $assetModel = new Asset();
            $item = $assetModel->getAssetById($_GET['asset_id']);
            
            if ($item) {
                // Rakit datanya agar formatnya seragam dengan data dari keranjang
                $checkoutItems[] = [
                    'id'              => $item['id'],
                    'public_id'       => $item['public_id'],
                    'title'           => $item['title'],
                    'price'           => $item['price'],
                    'render_pipeline' => $item['render_pipeline'] ?? 'Built-in',
                    'thumbnail'       => $item['thumbnail'] ?? '',
                    'seller_name'     => !empty($item['store_name']) ? $item['store_name'] : ($item['seller_name'] ?? 'Kreator')
                ];
            }
        }

        // Tampilkan View, sekalian kirim Client Key Midtrans untuk Script JS nanti
        $this->view('checkout/pembayaran', [
            'checkoutItems' => $checkoutItems,
            'midtransClientKey' => $_ENV['MIDTRANS_CLIENT_KEY'] ?? getenv('MIDTRANS_CLIENT_KEY')
        ]);
    }

    // Memproses data ke gerbang pembayaran Midtrans (Untuk langkah selanjutnya)
    public function process() {
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['error' => 'Harap login terlebih dahulu.']);
            return;
        }

        // 1. Konfigurasi Midtrans dari .env
        Config::$serverKey = $_ENV['MIDTRANS_SERVER_KEY'] ?? getenv('MIDTRANS_SERVER_KEY');
        Config::$isProduction = ($_ENV['MIDTRANS_IS_PRODUCTION'] ?? getenv('MIDTRANS_IS_PRODUCTION')) === 'true';
        Config::$isSanitized = true;
        Config::$is3ds = true;
        // Menonaktifkan verifikasi cURL SSL untuk menghindari Error 20 (Hapus ketika sudah menggunakan vps)
        Config::$curlOptions[CURLOPT_SSL_VERIFYPEER] = false;
        Config::$curlOptions[CURLOPT_SSL_VERIFYHOST] = 0;

        $assetIds = $_POST['asset_ids'] ?? [];
        if (empty($assetIds)) {
            echo json_encode(['error' => 'Keranjang kosong.']);
            return;
        }

        // 2. Tarik Data Aset dari Database berdasarkan ID yang dikirim
        $assetModel = new Asset();
        $itemDetails = [];
        $grossAmount = 0;
        
        foreach ($assetIds as $id) {
            $item = $assetModel->getAssetByInternalId($id);
            if ($item) {
                $price = (int) $item['price'];
                $itemDetails[] = [
                    'id'       => $item['id'],
                    'price'    => $price,
                    'quantity' => 1,
                    'name'     => mb_strimwidth($item['title'], 0, 47, '...') // Midtrans max length = 50
                ];
                $grossAmount += $price;
            }
        }

        // 3. Tambahkan Biaya Layanan
        $feeAdmin = 2500;
        $itemDetails[] = [
            'id'       => 'FEE-ADMIN',
            'price'    => $feeAdmin,
            'quantity' => 1,
            'name'     => 'Biaya Layanan IndoFX'
        ];
        $grossAmount += $feeAdmin;

        // 4. Menyiapkan Data Transaksi untuk dikirim ke API Midtrans
        $orderId = 'IFX-' . time() . '-' . rand(100, 999);

        // 5. Data Pelanggan (Pembeli)
        $customerName  = $_SESSION['name'] ?? 'Pengguna IndoFX';
        $customerEmail = $_SESSION['email'] ?? 'guest@indofx.com';

        // 6. Data Pengiriman (Penjual) ditarik dari Database
        $firstAssetId = $assetIds[0] ?? null;
        
        $sellerName = 'Kreator IndoFX';
        $sellerEmail = 'support@indofx.com';
        $sellerPhone = '-';

        if ($firstAssetId) {
            $assetData = $assetModel->getAssetByInternalId($firstAssetId);
            
            if ($assetData) {
                // Prioritaskan nama toko, jika kosong gunakan nama asli profil
                $sellerName = !empty($assetData['store_name']) ? $assetData['store_name'] : $assetData['seller_name'];
                
                if (!empty($assetData['seller_email'])) $sellerEmail = $assetData['seller_email'];
                if (!empty($assetData['seller_phone'])) $sellerPhone = $assetData['seller_phone'];
            }
        }

        // 7. Merakit Data Transaksi Midtrans
        $transaction = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => $grossAmount,
            ],
            'item_details' => $itemDetails,
            
            'customer_details' => [
                'first_name' => $customerName,
                'email'      => $customerEmail,
            ],

            'custom_field1' => 'TOKO PENJUAL: ' . $sellerName,
            'custom_field2' => 'EMAIL PENJUAL: ' . $sellerEmail,
            'custom_field3' => 'NO. HP PENJUAL: ' . $sellerPhone
        ];

        // 8. Memintakan Snap Token ke Server Midtrans
        try {
            $snapToken = Snap::getSnapToken($transaction);
            
            // Simpan Order ID ke Session untuk validasi pembayaran nanti
            $_SESSION['pending_order_id'] = $orderId; 
            
            echo json_encode(['token' => $snapToken]);
        } catch (\Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}