<?php

namespace IndoFX\Controllers;

use IndoFX\Core\Controller;
use IndoFX\Core\Database;
use IndoFX\Helpers\Storage;
use PDO;
class CommerceController extends Controller
{
    private const SERVICE_FEE = 2500;

    public function cart(): void
    {
        self::guardLogin();
        $this->view('cart/keranjang', [
            'cartItems' => $this->loadAssets($this->cartIds()),
        ]);
    }

    public function addToCart(): void
    {
        self::guardLogin();
        $assetId = trim((string) ($_POST['asset_id'] ?? ''));
        if ($assetId === '' || !$this->findAsset($assetId)) {
            http_response_code(422);
            echo 'Aset tidak valid.';
            return;
        }

        $ids = $this->cartIds();
        if (!in_array($assetId, $ids, true)) {
            $ids[] = $assetId;
            $_SESSION['cart_asset_ids'] = $ids;
        }

        $this->redirect('/cart');
    }

    public function removeFromCart(): void
    {
        self::guardLogin();
        $assetId = trim((string) ($_POST['asset_id'] ?? ''));
        $_SESSION['cart_asset_ids'] = array_values(array_filter(
            $this->cartIds(),
            static fn (string $id): bool => $id !== $assetId
        ));
        $this->redirect('/cart');
    }

    public function checkout(): void
    {
        self::guardLogin();
        $directId = trim((string) ($_GET['asset_id'] ?? ''));
        $ids = $directId !== '' ? [$directId] : $this->cartIds();
        $_SESSION['checkout_asset_ids'] = $ids;
        $items = $this->loadAssets($ids);

        $this->view('checkout/pembayaran', [
            'checkoutItems' => $items,
            'checkoutFee' => $items === [] ? 0 : self::SERVICE_FEE,
        ]);
    }

    public function processCheckout(): void
    {
        self::guardLogin();
        $ids = $this->postedOrCartIds();
        $items = $this->loadAssets($ids);

        if ($items === []) {
            $_SESSION['checkout_error'] = 'Pilih minimal satu aset.';
            $this->redirect('/cart');
        }

        $db = Database::getInstance();
        $userId = (int) $_SESSION['user_id'];
        $invoices = [];

        try {
            $db->beginTransaction();
            foreach ($items as $item) {
                $amount = (int) $item['price'];
                $status = $amount === 0 ? 'settlement' : 'pending';
                $invoice = 'INV-' . date('Ym') . '-' . strtoupper(bin2hex(random_bytes(4)));

                $stmt = $db->query(
                    "INSERT INTO transactions
                        (buyer_id, asset_id, invoice_number, amount, payment_type, status, paid_at)
                     VALUES (:buyer_id, :asset_id, :invoice, :amount, :payment_type, :status,
                             CASE WHEN :status_paid = 'settlement' THEN NOW() ELSE NULL END)
                     RETURNING id",
                    [
                        ':buyer_id' => $userId,
                        ':asset_id' => (int) $item['id'],
                        ':invoice' => $invoice,
                        ':amount' => $amount,
                        ':payment_type' => (string) ($_POST['payment_method'] ?? 'qris'),
                        ':status' => $status,
                        ':status_paid' => $status,
                    ]
                );
                $transactionId = (int) $stmt->fetchColumn();
                $invoices[] = $invoice;

                if ($status === 'settlement') {
                    $db->query(
                        "INSERT INTO entitlements (user_id, asset_id, transaction_id, source)
                         VALUES (:user_id, :asset_id, :transaction_id, :source)
                         ON CONFLICT DO NOTHING",
                        [
                            ':user_id' => $userId,
                            ':asset_id' => (int) $item['id'],
                            ':transaction_id' => $transactionId,
                            ':source' => $amount === 0 ? 'free' : 'purchase',
                        ]
                    );
                }
            }
            $db->commit();
        } catch (\Throwable $e) {
            if ($db->getConnection()->inTransaction()) {
                $db->rollback();
            }
            $_SESSION['checkout_error'] = 'Checkout gagal diproses.';
            $this->redirect('/checkout');
        }

        $_SESSION['cart_asset_ids'] = array_values(array_diff($this->cartIds(), $ids));
        $_SESSION['checkout_asset_ids'] = [];
        $_SESSION['last_invoice'] = $invoices[0] ?? null;
        $this->redirect('/invoice');
    }

    public function invoice(): void
    {
        self::guardLogin();
        $stmt = Database::getInstance()->getConnection()->prepare(
            "SELECT t.invoice_number, t.amount, t.payment_type, t.status, t.paid_at,
                    t.created_at, a.title AS asset_title
             FROM transactions t
             JOIN assets a ON a.id = t.asset_id
             WHERE t.buyer_id = :buyer_id
             ORDER BY t.created_at DESC"
        );
        $stmt->execute([':buyer_id' => (int) $_SESSION['user_id']]);
        $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($transactions as &$transaction) {
            $transaction['items'] = [[
                'title' => $transaction['asset_title'],
                'price' => (int) $transaction['amount'],
            ]];
            $transaction['status'] = $transaction['status'] === 'settlement' ? 'Berhasil' : 'Menunggu Pembayaran';
        }
        unset($transaction);
        $this->view('dashboard/invoice', ['transactions' => $transactions]);
    }

    public function library(): void
    {
        self::guardLogin();
        $stmt = Database::getInstance()->getConnection()->prepare(
            "SELECT e.asset_id, e.granted_at, a.public_id, a.title, a.price,
                    a.render_pipeline, a.unity_version, a.description,
                    ai.image_path, u.name AS creator, COALESCE(sp.store_name, u.name) AS seller_name
             FROM entitlements e
             JOIN assets a ON a.id = e.asset_id
             LEFT JOIN asset_images ai ON ai.asset_id = a.id AND ai.sort_order = 1
             JOIN users u ON u.id = a.seller_id
             LEFT JOIN seller_profiles sp ON sp.user_id = u.id
             WHERE e.user_id = :user_id
             ORDER BY e.granted_at DESC"
        );
        $stmt->execute([':user_id' => (int) $_SESSION['user_id']]);
        $libraryItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($libraryItems as &$item) {
            $item['image_url'] = Storage::url($item['image_path'] ?? '');
            $item['acquired_at'] = $item['granted_at'] ?? '';
        }
        unset($item);
        $this->view('dashboard/library', ['libraryItems' => $libraryItems]);
    }

    public function libraryDetail(): void
    {
        self::guardLogin();
        $assetId = trim((string) ($_GET['id'] ?? ''));
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare(
            "SELECT a.*, a.public_id, COALESCE(sp.store_name, u.name) AS seller_name,
                    u.name AS creator, c.name AS category_name,
                    ai.image_path, f.public_id AS file_public_id, f.original_name,
                    f.extension AS file_extension
             FROM assets a
             JOIN users u ON u.id = a.seller_id
             LEFT JOIN seller_profiles sp ON sp.user_id = u.id
             LEFT JOIN categories c ON c.id = a.category_id
             LEFT JOIN asset_images ai ON ai.asset_id = a.id AND ai.sort_order = 1
             LEFT JOIN asset_files f ON f.asset_id = a.id
             WHERE a.public_id = :public_id::uuid
               AND (a.seller_id = :user_id OR EXISTS (
                    SELECT 1 FROM entitlements e WHERE e.asset_id = a.id AND e.user_id = :ent_user_id
               ))
             ORDER BY f.created_at DESC NULLS LAST
             LIMIT 1"
        );
        $stmt->execute([
            ':public_id' => $assetId,
            ':user_id' => (int) $_SESSION['user_id'],
            ':ent_user_id' => (int) $_SESSION['user_id'],
        ]);
        $libraryAsset = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$libraryAsset) {
            http_response_code(403);
            echo 'Aset tidak tersedia di library Anda.';
            return;
        }
        $libraryAsset['image_url'] = Storage::url($libraryAsset['image_path'] ?? '');
        $libraryAsset['file_format'] = !empty($libraryAsset['file_extension']) ? '.' . ltrim((string) $libraryAsset['file_extension'], '.') : '.unitypackage';

        $this->view('dashboard/detail-library', ['libraryAsset' => $libraryAsset]);
    }

    private function cartIds(): array
    {
        return array_values(array_unique(array_filter(array_map('strval', (array) ($_SESSION['cart_asset_ids'] ?? [])))));
    }

    private function postedOrCartIds(): array
    {
        $posted = (array) ($_POST['asset_ids'] ?? []);
        $ids = array_values(array_unique(array_filter(array_map('strval', $posted))));
        return $ids !== [] ? $ids : $this->cartIds();
    }

    private function findAsset(string $publicId): ?array
    {
        $stmt = Database::getInstance()->getConnection()->prepare(
            "SELECT a.*, ai.image_path, COALESCE(sp.store_name, u.name) AS seller_name
             FROM assets a
             JOIN users u ON u.id = a.seller_id
             LEFT JOIN seller_profiles sp ON sp.user_id = u.id
             LEFT JOIN asset_images ai ON ai.asset_id = a.id AND ai.sort_order = 1
             WHERE a.public_id = :public_id::uuid AND a.status = 'published' AND a.deleted_at IS NULL
             LIMIT 1"
        );
        $stmt->execute([':public_id' => $publicId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    private function loadAssets(array $publicIds): array
    {
        $items = [];
        foreach ($publicIds as $publicId) {
            $asset = $this->findAsset($publicId);
            if ($asset !== null) {
                $asset['public_id'] = $publicId;
                $asset['image_url'] = Storage::url($asset['image_path'] ?? '');
                $items[] = $asset;
            }
        }
        return $items;
    }
}
