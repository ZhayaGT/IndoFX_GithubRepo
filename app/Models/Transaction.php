<?php

namespace IndoFX\Models;

use IndoFX\Core\Model;
use PDO;

class Transaction extends Model {
    
    // 1. Mengambil Induk Transaksi (Nota)
    public function getUserTransactions($buyerId) {
        $sql = "SELECT id, invoice_number, amount, payment_type, status, created_at
                FROM transactions 
                WHERE buyer_id = :buyer_id
                ORDER BY created_at DESC";
                
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':buyer_id', $buyerId, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 2. Mengambil Detail Item di Dalam Transaksi Tersebut
    public function getTransactionDetails($transactionId) {
        $sql = "SELECT td.price, a.title 
                FROM transaction_details td
                LEFT JOIN assets a ON td.asset_id = a.id
                WHERE td.transaction_id = :transaction_id";
                
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':transaction_id', $transactionId, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 3. Menyimpan Transaksi Baru (Status Pending) & Detail Itemnya
    public function createPendingTransaction($buyerId, $orderId, $grossAmount, $itemDetails) {
        try {
            $this->db->beginTransaction();

            // Simpan Induk Transaksi
            $sqlInduk = "INSERT INTO transactions (public_id, buyer_id, invoice_number, amount, status, created_at) 
                         VALUES (gen_random_uuid(), :buyer_id, :invoice_number, :amount, 'pending', NOW())";
            $stmtInduk = $this->db->prepare($sqlInduk);
            $stmtInduk->execute([
                ':buyer_id'       => $buyerId,
                ':invoice_number' => $orderId,
                ':amount'         => $grossAmount
            ]);

            $newTransactionId = $this->db->lastInsertId();

            // Simpan Detail Transaksi (Looping keranjang)
            $sqlDetail = "INSERT INTO transaction_details (transaction_id, asset_id, price) 
                          VALUES (:transaction_id, :asset_id, :price)";
            $stmtDetail = $this->db->prepare($sqlDetail);

            foreach ($itemDetails as $item) {
                if ($item['id'] !== 'FEE-ADMIN') {
                    $stmtDetail->execute([
                        ':transaction_id' => $newTransactionId,
                        ':asset_id'       => $item['id'],
                        ':price'          => $item['price']
                    ]);
                }
            }

            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    // 4. Memperbarui Status Transaksi dari Webhook / Cek Status Midtrans
    public function updateStatusByInvoice($invoiceNumber, $status, $paymentType) {
        $isPaid = in_array(strtolower($status), ['settlement', 'capture']);
        
        if ($isPaid) {
            $sql = "UPDATE transactions 
                    SET status = :status, 
                        payment_type = :payment_type, 
                        paid_at = NOW(),
                        updated_at = NOW() 
                    WHERE invoice_number = :invoice_number";
        } else {
            $sql = "UPDATE transactions 
                    SET status = :status, 
                        payment_type = :payment_type, 
                        updated_at = NOW() 
                    WHERE invoice_number = :invoice_number";
        }
                
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':status'         => $status,
            ':payment_type'   => $paymentType,
            ':invoice_number' => $invoiceNumber
        ]);
    }
}