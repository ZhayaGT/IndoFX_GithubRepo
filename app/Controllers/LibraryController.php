<?php

namespace IndoFX\Controllers;

use IndoFX\Core\Controller;
use IndoFX\Models\Library;

class LibraryController extends Controller {

    // Halaman utama Library Saya (/library)
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }

        $libModel = new Library();
        $rawItems = $libModel->getUserLibrary($_SESSION['user_id']);

        $supabaseUrl = $_ENV['SUPABASE_URL'] ?? getenv('SUPABASE_URL') ?? '';
        $storageBasePath = rtrim($supabaseUrl, '/') . '/storage/v1/object/public/asset-previews/catalog/';

        $libraryItems = [];
        foreach ($rawItems as $item) {
            $pipelines = array_values(array_filter(array_map('trim', explode(',', $item['render_pipeline'] ?? 'URP'))));
            $thumb = $item['thumbnail'] ?? '';
            $thumbUrl = empty($thumb) ? '' : (strpos($thumb, 'http') === 0 ? $thumb : $storageBasePath . $thumb);

            $isFree = ((int) $item['price'] === 0) || strtolower($item['source']) === 'free';

            $libraryItems[] = [
                'id'          => (int) $item['asset_id'],
                'title'       => $item['title'],
                'creator'     => $item['creator_name'],
                'category'    => strtoupper($item['category_name'] ?? 'ASET'),
                'pipelines'   => $pipelines,
                'type'        => $isFree ? 'Gratis' : 'Berbayar',
                'source_text' => $isFree ? 'GRATIS' : 'DIBELI',
                'date'        => date('d M Y', strtotime($item['granted_at'])),
                'thumbnail'   => $thumbUrl,
                'is_reviewed' => (bool) $item['is_reviewed']
            ];
        }

        // Sesuaikan path view ('dashboard/library' atau 'library')
        $this->view('dashboard/library', [
            'libraryItems' => $libraryItems
        ]);
    }

    // Halaman detail & unduh aset di Library (/library/detail?id=...)
    public function detail() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }

        $assetId = (int) ($_GET['id'] ?? 0);
        if ($assetId <= 0) {
            $this->redirect('/library');
        }

        $libModel = new Library();
        $asset = $libModel->getOwnedAssetDetail($_SESSION['user_id'], $assetId);

        if (!$asset) {
            // Cegah user mengakses halaman download aset yang belum dimiliki
            $this->redirect('/library');
        }

        $images = $libModel->getAssetImages($assetId);
        $files  = $libModel->getAssetFiles($assetId);
        $rawReviews = $libModel->getAssetReviews($assetId);

        $reviews = array_map(function ($r) {
            return [
                'user_name' => $r['user_name'],
                'rating'    => (int) $r['rating'],
                'title'     => $r['title'] ?? '',
                'comment'   => $r['comment'],
                'date'      => date('d F Y', strtotime($r['created_at']))
            ];
        }, $rawReviews);

        $this->view('dashboard/detail-library', [
            'asset'   => $asset,
            'images'  => $images,
            'files'   => $files,
            'reviews' => $reviews
        ]);
    }

    // API POST: Klaim aset gratis ke Library (/api/library/add)
    public function claimFree() {
        header('Content-Type: application/json');
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Silakan login terlebih dahulu']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $assetId = (int) ($input['id'] ?? $_POST['asset_id'] ?? 0);

        if ($assetId <= 0) {
            echo json_encode(['success' => false, 'message' => 'ID aset tidak valid']);
            return;
        }

        $asset = (new \IndoFX\Models\Asset())->getAssetByInternalId($assetId);
        if (!$asset || (int) $asset['price'] !== 0) {
            http_response_code(422);
            echo json_encode(['success' => false, 'message' => 'Aset gratis yang sudah diterbitkan tidak ditemukan.']);
            return;
        }
        $libModel = new Library();
        $libModel->grantEntitlement($_SESSION['user_id'], $assetId, 'free', null);

        echo json_encode(['success' => true, 'message' => 'Aset berhasil ditambahkan ke Library']);
    }

    // API POST: Simpan ulasan pembeli ke tabel reviews (/api/reviews/add)
    public function submitReview() {
        header('Content-Type: application/json');
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Silakan login terlebih dahulu']);
            return;
        }

        $input   = json_decode(file_get_contents('php://input'), true);
        $assetId = (int) ($input['asset_id'] ?? 0);
        $rating  = max(1, min(5, (int) ($input['rating'] ?? 5)));
        $title   = trim((string) ($input['title'] ?? ''));
        $comment = trim((string) ($input['comment'] ?? ''));

        if ($assetId <= 0 || $comment === '') {
            echo json_encode(['success' => false, 'message' => 'Feedback tidak boleh kosong']);
            return;
        }

        $libModel = new Library();
        // Pastikan user benar-benar pemilik aset ini
        if (!$libModel->getOwnedAssetDetail($_SESSION['user_id'], $assetId)) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Anda belum memiliki aset ini']);
            return;
        }

        try {
            $libModel->createReview($_SESSION['user_id'], $assetId, $rating, $title, $comment);
            echo json_encode(['success' => true, 'message' => 'Ulasan berhasil disimpan']);
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Anda sudah pernah mengulas aset ini']);
        }
    }

    // Endpoint Unduh Aset Asli + Catat ke download_logs (/library/download?id=...)
    public function download() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }

        $assetId = (int) ($_GET['id'] ?? 0);
        $libModel = new Library();
        $asset = $libModel->getOwnedAssetDetail($_SESSION['user_id'], $assetId);

        if (!$asset) {
            http_response_code(403);
            die('Anda tidak memiliki akses ke aset ini.');
        }

        $files = $libModel->getAssetFiles($assetId);
        if (empty($files)) {
            die('File aset belum tersedia di server.');
        }

        $latestFile = $files[0];
        $libModel->recordDownload($asset['entitlement_id'], $assetId, $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1');

        // Arahkan ke URL file di Supabase Storage (sesuaikan nama bucket file aset Anda)
        $supabaseUrl = rtrim($_ENV['SUPABASE_URL'] ?? getenv('SUPABASE_URL') ?? '', '/');
        $filePath = $latestFile['file_path'];
        $downloadUrl = (strpos($filePath, 'http') === 0)
            ? $filePath
            : $supabaseUrl . '/storage/v1/object/public/asset-files/' . ltrim($filePath, '/');

        header('Location: ' . $downloadUrl);
        exit;
    }
}