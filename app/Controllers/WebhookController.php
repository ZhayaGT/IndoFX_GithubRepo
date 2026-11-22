<?php

namespace IndoFX\Controllers;

use IndoFX\Core\Controller;
use IndoFX\Models\Transaction;

class WebhookController extends Controller {

    public function midtransCallback() {
        // 1. Ambil data JSON yang dikirimkan oleh Midtrans secara otomatis
        $json = file_get_contents('php://input');
        $notification = json_decode($json);
        
        if (!$notification) {
            http_response_code(400);
            echo "Invalid JSON";
            return;
        }

        // 2. Verifikasi Keamanan (Signature Key)
        $serverKey = $_ENV['MIDTRANS_SERVER_KEY'] ?? getenv('MIDTRANS_SERVER_KEY');
        $orderId = $notification->order_id;
        $statusCode = $notification->status_code;
        $grossAmount = $notification->gross_amount;
    
        $signatureKey = hash("sha512", $orderId . $statusCode . $grossAmount . $serverKey);
        
        if ($signatureKey !== $notification->signature_key) {
            http_response_code(403);
            echo "Invalid Signature";
            return;
        }
        
        // 3. Ambil status transaksi dari notifikasi
        $transactionStatus = $notification->transaction_status;
        $paymentType = $notification->payment_type ?? 'qris';
        
        // 4. Update status di database melalui Model
        $txModel = new Transaction();
        $txModel->updateStatusByInvoice($orderId, $transactionStatus, $paymentType);
        
        // 5. Logika Sukses (Beri hak akses aset kepada pembeli)
        if (in_array(strtolower($transactionStatus), ['settlement', 'capture'])) {
            $libModel = new \IndoFX\Models\Library();
            $libModel->grantFromInvoice($orderId);
        }

        // 6. Logika Sukses (Beri hak akses aset kepada pembeli)
        if ($transactionStatus == 'settlement' || $transactionStatus == 'capture') {
            // -- Nanti bisa tambahkan insert ke tabel entitlements di sini
        }

        // 7. Balas dengan status 200 OK ke Midtrans
        http_response_code(200);
        echo "OK";
    }
}