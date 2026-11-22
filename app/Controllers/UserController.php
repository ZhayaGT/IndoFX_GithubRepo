<?php

namespace IndoFX\Controllers;

use IndoFX\Core\Controller;
use IndoFX\Models\Transaction;

class UserController extends Controller {

    public function invoiceHistory() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }

        $txModel = new Transaction();
        
        // Menarik semua induk transaksi
        $rawTransactions = $txModel->getUserTransactions($_SESSION['user_id']);
        
        // Konfigurasi Midtrans untuk cek status transaksi pending di localhost
        \Midtrans\Config::$serverKey = $_ENV['MIDTRANS_SERVER_KEY'] ?? getenv('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = ($_ENV['MIDTRANS_IS_PRODUCTION'] ?? getenv('MIDTRANS_IS_PRODUCTION')) === 'true';
        \Midtrans\Config::$curlOptions = [
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_HTTPHEADER     => []
        ];

        $formattedTransactions = [];

        foreach ($rawTransactions as $tx) {
            // Jika di database masih pending, cek status terbaru langsung ke Midtrans
            if (strtolower($tx['status']) === 'pending') {
                try {
                    $statusMidtrans = \Midtrans\Transaction::status($tx['invoice_number']);
                    $newStatus      = $statusMidtrans->transaction_status ?? 'pending';
                    $paymentType    = $statusMidtrans->payment_type ?? ($tx['payment_type'] ?? 'qris');

                    if ($newStatus !== 'pending') {
                        $txModel->updateStatusByInvoice($tx['invoice_number'], $newStatus, $paymentType);
                        $tx['status']       = $newStatus;
                        $tx['payment_type'] = $paymentType;

                        if (in_array(strtolower($newStatus), ['settlement', 'capture'])) {
                            $libModel = new \IndoFX\Models\Library();
                            $libModel->grantFromInvoice($tx['invoice_number']);
                        }
                    }
                } catch (\Exception $e) {
                    // Biarkan tetap pending jika transaksi belum diproses di Midtrans
                }
            }

            // Menarik detail barang untuk setiap transaksi berdasarkan ID
            $rawItems = $txModel->getTransactionDetails($tx['id']);
            $items = [];
            
            foreach ($rawItems as $item) {
                $items[] = [
                    'title' => $item['title'] ?? 'Aset Terhapus',
                    'price' => (float) $item['price']
                ];
            }

            // Format status Midtrans
            $statusDb = strtolower($tx['status']);
            $status = 'Tertunda';
            if (in_array($statusDb, ['settlement', 'capture', 'success'])) $status = 'Berhasil';
            elseif (in_array($statusDb, ['cancel', 'expire', 'deny', 'failed'])) $status = 'Gagal';

            // Masukkan ke array
            $formattedTransactions[] = [
                'invoice_no'     => $tx['invoice_number'],
                'short_date'     => date('d M Y', strtotime($tx['created_at'])),
                'full_date'      => date('d F Y, H:i T', strtotime($tx['created_at'])),
                'total'          => (float) $tx['amount'],
                'status'         => $status,
                'buyer_name'     => $_SESSION['name'] ?? 'Pengguna',
                'buyer_email'    => $_SESSION['email'] ?? 'guest@indofx.com',
                'payment_method' => strtoupper($tx['payment_type'] ?? 'QRIS'),
                'items'          => $items
            ];
        }

        $this->view('dashboard/invoice', [
            'transactions'      => $formattedTransactions,
            'selected_order_id' => $_GET['order_id'] ?? null
        ]);
    }
}