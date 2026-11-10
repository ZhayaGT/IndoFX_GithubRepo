<?php
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: /login');
    exit;
}

$adminName = 'Super Admin IndoFX';
$adminInitials = 'AP';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Review Aset - IndoFX</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/admin.css">

    <style>
        .section-text-dynamic {
            white-space: pre-line;
            line-height: 1.7;
            color: #2b2623;
            font-size: 0.88rem;
        }
    </style>
</head>

<body class="admin-body">

    <!-- 1. Header Navbar Oranye Admin -->
    <nav class="navbar navbar-admin sticky-top">
        <div class="navbar-admin-container">
            <a class="navbar-brand m-0 d-flex align-items-center gap-2 text-decoration-none" href="/admin/dashboard">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path d="M12 2L20.66 7V17L12 22L3.34 17V7L12 2Z" fill="#ED6B1D" stroke="#1A1A1A" stroke-width="1.8" stroke-linejoin="round" />
                    <path d="M12 2L20.66 7L12 12L3.34 7L12 2Z" fill="#FDBA74" stroke="#1A1A1A" stroke-width="1.5" stroke-linejoin="round" />
                    <path d="M12 12V22" stroke="#1A1A1A" stroke-width="1.5" />
                    <path d="M12 12L20.66 7" stroke="#1A1A1A" stroke-width="1.5" />
                    <path d="M12 12L3.34 7" stroke="#1A1A1A" stroke-width="1.5" />
                    <path d="M12 4.5L16.5 7L12 9.5L7.5 7L12 4.5Z" fill="#FED7AA" stroke="#1A1A1A" stroke-width="1" stroke-linejoin="round" />
                </svg>
                <span class="brand-text text-white fw-bold fs-6">IndoFX</span>
            </a>

            <div class="admin-dropdown-wrap">
                <button type="button" class="btn-admin-profile" id="adminDropdownBtn">
                    <span class="avatar-admin-badge"><?= htmlspecialchars($adminInitials) ?></span>
                    <span><?= htmlspecialchars($adminName) ?></span>
                </button>

                <div class="admin-dropdown-menu" id="adminDropdownMenu">
                    <a href="/logout" class="admin-logout-btn text-danger">Keluar</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- 2. Kontainer Utama -->
    <div class="admin-layout-container">
        <!-- Sidebar Kiri -->
        <aside class="admin-sidebar">
            <nav class="nav flex-column">
                <a href="/admin/dashboard" class="admin-sidebar-link">
                    <span><i class="bi bi-speedometer2 me-2"></i>Ringkasan Sistem</span>
                </a>
                <a href="/admin/assets" class="admin-sidebar-link active">
                    <span><i class="bi bi-card-checklist me-2"></i>Moderasi Aset</span>
                    <span class="admin-sidebar-badge" id="sidebarModerationBadge">0</span>
                </a>
                <a href="/admin/categories" class="admin-sidebar-link">
                    <span><i class="bi bi-tags me-2"></i>Kelola Kategori</span>
                </a>
                <a href="/admin/users" class="admin-sidebar-link">
                    <span><i class="bi bi-people-fill me-2"></i>Kelola Pengguna</span>
                </a>
                <a href="/admin/seller-requests" class="admin-sidebar-link">
                    <span><i class="bi bi-person-badge me-2"></i>Pengajuan Penjual</span>
                    <span class="admin-sidebar-badge" id="sidebarSellerBadge">0</span>
                </a>
                <a href="/admin/transactions" class="admin-sidebar-link">
                    <span><i class="bi bi-currency-dollar me-2"></i>Monitor Transaksi</span>
                </a>
            </nav>
        </aside>

        <!-- Konten Peninjauan Dinamis -->
        <main class="admin-main-content">
            <!-- Breadcrumb -->
            <div class="review-breadcrumb">
                <a href="/admin/dashboard">Dashboard</a> / <a href="/admin/assets">Moderasi Aset</a> / <span>Detail Review</span>
            </div>

            <h1 class="admin-page-title mb-4" id="reviewHeaderTitle">Detail Review: Memuat Data Aset...</h1>

            <div class="row g-4">
                <!-- Kolom Kiri: Detail Aset, Preview, Deskripsi & Berkas -->
                <div class="col-12 col-xl-8">
                    <!-- Kartu 1: Gambar Preview -->
                    <div class="review-card">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="review-card-title mb-0">Detail Aset</h5>
                            <span class="badge-pipe-admin badge-pipe-urp" id="badgePipelineHeader">URP</span>
                        </div>

                        <!-- Gambar Utama 16:9 -->
                        <div class="review-main-preview">
                            <img id="mainPreviewImg" src="https://images.unsplash.com/photo-1518709268805-4e9042af9f23?auto=format&fit=crop&w=1200&q=80" alt="Preview Aset">
                        </div>

                        <!-- 3 Gambar Thumbnail Galeri -->
                        <div class="review-thumb-grid">
                            <div class="review-thumb-item">
                                <img id="thumbImg1" src="https://images.unsplash.com/photo-1518709268805-4e9042af9f23?auto=format&fit=crop&w=400&q=80" alt="Thumb 1">
                            </div>
                            <div class="review-thumb-item">
                                <img id="thumbImg2" src="https://images.unsplash.com/photo-1550745165-9bc0b252726f?auto=format&fit=crop&w=400&q=80" alt="Thumb 2">
                            </div>
                            <div class="review-thumb-item">
                                <img id="thumbImg3" src="https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=400&q=80" alt="Thumb 3">
                            </div>
                        </div>

                        <!-- Link Video Preview URL -->
                        <div class="review-video-box">
                            <div class="review-video-label">Video Preview URL:</div>
                            <a href="#" target="_blank" class="review-video-link" id="reviewVideoLink">Tidak ada URL video</a>
                        </div>
                    </div>

                    <!-- Kartu 2: Deskripsi & Panduan Penggunaan Dinamis (Murni Input Penjual) -->
                    <div class="review-card">
                        <h5 class="review-card-title">Deskripsi &amp; Panduan Penggunaan</h5>

                        <div class="mb-4">
                            <div class="seller-label mb-1 fw-bold text-dark">Deskripsi Aset:</div>
                            <div class="section-text-dynamic" id="reviewAssetDescription">
                                Memuat deskripsi...
                            </div>
                        </div>

                        <hr style="border: none; border-top: 1px solid #ebdcd1; margin: 20px 0;">

                        <div>
                            <div class="seller-label mb-1 fw-bold text-dark">Panduan Penggunaan:</div>
                            <div class="review-guide-box">
                                <div class="section-text-dynamic" id="reviewAssetHowToUse">
                                    Memuat panduan penggunaan...
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kartu 3: Berkas Utama Package Unity -->
                    <div class="review-card">
                        <h5 class="review-card-title">Berkas Utama Package Unity (.unitypackage / .zip)</h5>

                        <div class="review-file-box">
                            <div class="d-flex align-items-center gap-3">
                                <div class="review-file-icon">
                                    <i class="bi bi-box-seam"></i>
                                </div>
                                <div>
                                    <div class="fw-bold text-dark" id="reviewFileNameDisplay">package_asset.unitypackage</div>
                                    <div class="text-muted small" id="reviewFileSizeDate">Ukuran: 18.5 MB | Diunggah: Hari ini</div>
                                </div>
                            </div>
                            <button type="button" class="btn-review-download" onclick="simulateDownloadPackage()">
                                <i class="bi bi-download"></i> Unduh &amp; Periksa Berkas
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Ringkasan Harga, Spek & Tombol Keputusan -->
                <div class="col-12 col-xl-4">
                    <div class="review-card">
                        <h4 class="fw-bold text-dark mb-1 fs-5" id="sidebarAssetTitle">Nama Aset</h4>
                        <div class="text-muted small mb-3" id="sidebarCreatorName">Oleh Kreator</div>

                        <div class="review-price-label text-muted small">Harga Aset:</div>
                        <div class="review-price-val" id="sidebarPriceVal">Rp 150.000</div>

                        <table class="review-spec-table">
                            <tr>
                                <td>Versi Unity Minimum:</td>
                                <td id="sidebarUnityVer">2022.3 LTS</td>
                            </tr>
                            <tr>
                                <td>Render Pipeline:</td>
                                <td id="sidebarPipeline">URP</td>
                            </tr>
                            <tr>
                                <td>Tanggal Pengajuan:</td>
                                <td id="sidebarSubmissionDate">Hari ini</td>
                            </tr>
                        </table>

                        <div class="mb-1">
                            <span class="seller-label mb-1">Status Moderasi Saat Ini:</span>
                            <div id="sidebarStatusBadge"><span class="badge-status-review">Pending Review</span></div>
                        </div>
                    </div>

                    <!-- Kartu Keputusan Admin -->
                    <div class="review-card">
                        <h5 class="review-card-title">Keputusan Admin</h5>

                        <button type="button" class="btn-decision-approve" onclick="approveAndPublishAsset()">
                            <i class="bi bi-check2"></i> Setujui &amp; Publish Aset
                        </button>
                        <button type="button" class="btn-decision-reject" onclick="openRejectDialog()">
                            <i class="bi bi-x-lg"></i> Tolak Pengajuan Aset
                        </button>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- MODAL PENOLAKAN ASET -->
    <div class="modal fade" id="modalRejectAsset" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 440px;">
            <div class="modal-content modal-category-card">
                <div class="modal-category-header">
                    <h5 class="modal-category-title text-danger">Tolak Pengajuan Aset</h5>
                </div>
                <div class="modal-category-body">
                    <p class="text-muted small mb-2">Tuliskan alasan atau catatan perbaikan untuk kreator:</p>
                    <textarea id="rejectAssetReasonInput" class="seller-textarea" style="height: 100px;" placeholder="Contoh: Lampiran file demo scene corrupt, mohon re-export..."></textarea>
                </div>
                <div class="modal-category-footer">
                    <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn-modal-confirm-delete" onclick="confirmRejectAsset()">
                        <i class="bi bi-x-lg"></i> Konfirmasi Tolak
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer-admin">
        <div class="footer-admin-content">
            <h5 class="footer-admin-title">IndoFX</h5>
            <p class="footer-admin-desc">
                Portal distribusi dan marketplace aset CG &amp; VFX berkualitas tinggi buatan lokal, dioptimalkan sepenuhnya untuk industri game Indonesia.
            </p>
        </div>
        <div class="footer-admin-bottom">
            <span>&copy; 2026 IndoFX. Hak Cipta Dilindungi Undang-Undang.</span>
            <span>Dibuat dengan bangga untuk Game Developer Indonesia</span>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- ENGINE PENINJAUAN ASET: 100% DINAMIS MENGGUNAKAN TEKS ASLI PENJUAL -->
    <script>
        let currentAsset = null;
        let allQueue = [];
        let modalReject = null;

        function getAssetIdFromUrl() {
            const params = new URLSearchParams(window.location.search);
            return params.get('id');
        }

        function loadAssetDetail() {
            const assetId = getAssetIdFromUrl();

            try {
                allQueue = JSON.parse(localStorage.getItem('indofx_moderation_queue')) || [];
            } catch (e) {
                allQueue = [];
            }

            // Temukan aset spesifik dari antrean moderasi
            if (assetId) {
                currentAsset = allQueue.find(a => String(a.id) === String(assetId) || a.title === assetId);
            }

            if (!currentAsset && allQueue.length > 0) {
                currentAsset = allQueue[0];
            }

            if (!currentAsset) {
                currentAsset = {
                    id: 'mod-demo',
                    title: 'Asset Tanpa Judul',
                    pipeline: 'URP',
                    pipelines: ['URP'],
                    category: 'VFX Partikel',
                    file_info: 'File: asset_package.unitypackage (14 MB)',
                    studio: 'naufalhaidarazmi',
                    creator: 'naufalhaidarazmi',
                    unity_ver: '2022.3 LTS',
                    price: 'Gratis',
                    price_num: 0,
                    date: 'Hari ini',
                    status: 'Pending Review',
                    description: '',
                    how_to_use: ''
                };
            }

            // Cross-check jika description / how_to_use tersimpan di storage toko penjual
            if (!currentAsset.description || !currentAsset.how_to_use) {
                const sKey = currentAsset.seller_storage_key || ('indofx_seller_assets_' + (currentAsset.studio || currentAsset.creator || 'naufalhaidarazmi'));
                try {
                    const sAssets = JSON.parse(localStorage.getItem(sKey)) || [];
                    const foundInSeller = sAssets.find(s => String(s.id) === String(currentAsset.id) || s.title === currentAsset.title);
                    if (foundInSeller) {
                        if (!currentAsset.description && foundInSeller.description) currentAsset.description = foundInSeller.description;
                        if (!currentAsset.how_to_use && foundInSeller.how_to_use) currentAsset.how_to_use = foundInSeller.how_to_use;
                        if (!currentAsset.image && foundInSeller.image) currentAsset.image = foundInSeller.image;
                        if (!currentAsset.video_url && foundInSeller.video_url) currentAsset.video_url = foundInSeller.video_url;
                    }
                } catch (e) {}
            }

            // Tampilkan Judul & Meta Informasi
            document.getElementById('reviewHeaderTitle').innerText = 'Detail Review: ' + currentAsset.title;
            document.getElementById('sidebarAssetTitle').innerText = currentAsset.title;
            document.getElementById('sidebarCreatorName').innerText = 'Oleh ' + (currentAsset.studio || currentAsset.creator || 'Studio Kreator');
            document.getElementById('sidebarPriceVal').innerText = currentAsset.price || (currentAsset.price_num === 0 ? 'Gratis' : 'Rp' + Number(currentAsset.price_num).toLocaleString('id-ID'));
            document.getElementById('sidebarUnityVer').innerText = currentAsset.unity_ver || currentAsset.unity_version || '2022.3 LTS';
            document.getElementById('sidebarPipeline').innerText = currentAsset.pipeline || (Array.isArray(currentAsset.pipelines) ? currentAsset.pipelines.join(', ') : 'URP');
            document.getElementById('sidebarSubmissionDate').innerText = currentAsset.date || currentAsset.created_at || 'Hari ini';

            // 1. TAMPILKAN DESKRIPSI ASLI DARI INPUT PENJUAL
            const descEl = document.getElementById('reviewAssetDescription');
            if (currentAsset.description && currentAsset.description.trim() !== '') {
                descEl.innerText = currentAsset.description.trim();
            } else {
                descEl.innerText = 'Penjual tidak mencantumkan deskripsi khusus.';
            }

            // 2. TAMPILKAN PANDUAN PENGGUNAAN ASLI DARI INPUT PENJUAL (BUKAN TEMPLATE LAGI)
            const howToUseEl = document.getElementById('reviewAssetHowToUse');
            if (currentAsset.how_to_use && currentAsset.how_to_use.trim() !== '') {
                howToUseEl.innerText = currentAsset.how_to_use.trim();
            } else {
                howToUseEl.innerText = 'Penjual tidak mencantumkan langkah panduan penggunaan khusus.';
            }

            // Preview Gambar
            if (currentAsset.image && (currentAsset.image.startsWith('data:') || currentAsset.image.includes('/'))) {
                document.getElementById('mainPreviewImg').src = currentAsset.image;
                document.getElementById('thumbImg1').src = currentAsset.image;
            }

            // Pipeline Badge
            const pipe = currentAsset.pipeline || (Array.isArray(currentAsset.pipelines) ? currentAsset.pipelines[0] : 'URP');
            const pipeBadge = document.getElementById('badgePipelineHeader');
            pipeBadge.innerText = pipe;
            pipeBadge.className = 'badge-pipe-admin ' + (pipe.toLowerCase().includes('hdrp') ? 'badge-pipe-hdrp' : (pipe.toLowerCase().includes('built') ? 'badge-pipe-builtin' : 'badge-pipe-urp'));

            // Status Badge
            const isPublished = (currentAsset.status === 'Published' || currentAsset.status === 'Disetujui');
            const isRejected = (currentAsset.status === 'Ditolak');
            let statusBadgeHtml = `<span class="badge-status-review">Pending Review</span>`;
            if (isPublished) statusBadgeHtml = `<span class="badge-status-published">Published</span>`;
            if (isRejected) statusBadgeHtml = `<span class="badge-status-blocked">Ditolak</span>`;
            document.getElementById('sidebarStatusBadge').innerHTML = statusBadgeHtml;

            // Berkas Unity
            const cleanFileName = currentAsset.file_info ? currentAsset.file_info.replace(/^File:\s*/i, '') : ((currentAsset.title.toLowerCase().replace(/[^a-z0-9]/g, '_')) + '.unitypackage');
            document.getElementById('reviewFileNameDisplay').innerText = cleanFileName;
            document.getElementById('reviewFileSizeDate').innerText = 'Ukuran: 18.5 MB | Diunggah: ' + (currentAsset.date || currentAsset.created_at || 'Hari ini');

            // Video Link
            const videoEl = document.getElementById('reviewVideoLink');
            if (currentAsset.video_url && currentAsset.video_url.trim() !== '') {
                videoEl.href = currentAsset.video_url.trim();
                videoEl.innerText = currentAsset.video_url.trim();
            } else {
                videoEl.removeAttribute('href');
                videoEl.innerText = 'Tidak ada URL video pratinjau yang dicantumkan';
                videoEl.classList.remove('review-video-link');
                videoEl.classList.add('text-muted');
            }

            // Update Badge Sidebar
            const pCount = allQueue.filter(q => q.status === 'Pending' || q.status === 'Pending Review').length;
            document.getElementById('sidebarModerationBadge').innerText = pCount;
        }

        // AKSI 1: ADMIN SETUJUI & PUBLIKASIKAN (TERUSKAN DESKRIPSI & PANDUAN KE KATALOG PUBLIK)
        function approveAndPublishAsset() {
            if (!currentAsset) return;

            // Update Antrean Moderasi Admin
            allQueue = allQueue.map(item => {
                if (String(item.id) === String(currentAsset.id) || item.title === currentAsset.title) {
                    item.status = 'Published';
                }
                return item;
            });
            localStorage.setItem('indofx_moderation_queue', JSON.stringify(allQueue));

            // Update Status di Ruang Penjual
            const creatorUsername = currentAsset.studio || currentAsset.creator || currentAsset.seller || 'naufalhaidarazmi';
            const sellerStorageKey = currentAsset.seller_storage_key || ('indofx_seller_assets_' + creatorUsername);
            try {
                let sellerAssets = JSON.parse(localStorage.getItem(sellerStorageKey)) || [];
                sellerAssets = sellerAssets.map(sa => {
                    if (String(sa.id) === String(currentAsset.id) || sa.title === currentAsset.title) {
                        return {
                            ...sa,
                            status: 'Disetujui'
                        };
                    }
                    return sa;
                });
                localStorage.setItem(sellerStorageKey, JSON.stringify(sellerAssets));
            } catch (e) {}

            // Masukkan Lengkap ke indofx_public_catalog
            let publicCatalog = [];
            try {
                publicCatalog = JSON.parse(localStorage.getItem('indofx_public_catalog')) || [];
            } catch (e) {
                publicCatalog = [];
            }

            publicCatalog = publicCatalog.filter(c => String(c.id) !== String(currentAsset.id) && c.title !== currentAsset.title);

            const rawPrice = (typeof currentAsset.price_num === 'number') ?
                currentAsset.price_num :
                (parseFloat(String(currentAsset.price).replace(/[^0-9]/g, '')) || 0);

            const pipelinesArray = Array.isArray(currentAsset.pipelines) && currentAsset.pipelines.length > 0 ?
                currentAsset.pipelines : [currentAsset.pipeline || 'URP'];

            publicCatalog.unshift({
                id: currentAsset.id,
                title: currentAsset.title,
                creator: creatorUsername,
                price: rawPrice,
                price_text: rawPrice === 0 ? 'Gratis' : ('Rp' + rawPrice.toLocaleString('id-ID')),
                category: currentAsset.category || 'VFX Partikel',
                pipelines: pipelinesArray,
                unity_version: currentAsset.unity_ver || currentAsset.unity_version || '2022.3 LTS',
                format: currentAsset.file_info ? (currentAsset.file_info.includes('.zip') ? '.zip' : '.unitypackage') : '.unitypackage',
                downloads: '0x Terjual',
                image: currentAsset.image || 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?auto=format&fit=crop&w=800&q=80',
                rating: 5.0,
                reviews_count: 0,
                published_at: new Date().toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'short',
                    year: 'numeric'
                }),
                // KUNCI PENTING: DITERUSKAN ASLI DARI INPUT PENJUAL
                description: currentAsset.description || '',
                how_to_use: currentAsset.how_to_use || ''
            });
            localStorage.setItem('indofx_public_catalog', JSON.stringify(publicCatalog));

            alert('Sukses! Aset "' + currentAsset.title + '" telah disetujui dan kartu aset kini sudah tayang di Katalog & Explore!');
            window.location.href = '/admin/assets';
        }

        // AKSI 2: ADMIN TOLAK PENGAJUAN
        function openRejectDialog() {
            document.getElementById('rejectAssetReasonInput').value = '';
            modalReject.show();
        }

        function confirmRejectAsset() {
            const reason = document.getElementById('rejectAssetReasonInput').value.trim();
            if (!reason) {
                alert('Silakan tuliskan alasan penolakan terlebih dahulu.');
                document.getElementById('rejectAssetReasonInput').focus();
                return;
            }

            allQueue = allQueue.map(item => {
                if (String(item.id) === String(currentAsset.id) || item.title === currentAsset.title) {
                    item.status = 'Ditolak';
                    item.reject_reason = reason;
                }
                return item;
            });
            localStorage.setItem('indofx_moderation_queue', JSON.stringify(allQueue));

            const creatorUsername = currentAsset.studio || currentAsset.creator || currentAsset.seller || 'naufalhaidarazmi';
            const sellerStorageKey = currentAsset.seller_storage_key || ('indofx_seller_assets_' + creatorUsername);
            try {
                let sellerAssets = JSON.parse(localStorage.getItem(sellerStorageKey)) || [];
                sellerAssets = sellerAssets.map(sa => {
                    if (String(sa.id) === String(currentAsset.id) || sa.title === currentAsset.title) {
                        return {
                            ...sa,
                            status: 'Ditolak',
                            reject_reason: reason
                        };
                    }
                    return sa;
                });
                localStorage.setItem(sellerStorageKey, JSON.stringify(sellerAssets));
            } catch (e) {}

            try {
                let publicCatalog = JSON.parse(localStorage.getItem('indofx_public_catalog')) || [];
                publicCatalog = publicCatalog.filter(c => String(c.id) !== String(currentAsset.id) && c.title !== currentAsset.title);
                localStorage.setItem('indofx_public_catalog', JSON.stringify(publicCatalog));
            } catch (e) {}

            modalReject.hide();
            alert('Pengajuan aset ditolak. Catatan perbaikan telah dikirimkan ke penjual.');
            window.location.href = '/admin/assets';
        }

        function simulateDownloadPackage() {
            alert('Memulai unduhan berkas package ' + document.getElementById('reviewFileNameDisplay').innerText + ' untuk verifikasi...');
        }

        document.addEventListener('DOMContentLoaded', function() {
            modalReject = new bootstrap.Modal(document.getElementById('modalRejectAsset'));
            loadAssetDetail();

            // Dropdown Profil Admin
            const btn = document.getElementById('adminDropdownBtn');
            const menu = document.getElementById('adminDropdownMenu');
            if (btn && menu) {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    menu.classList.toggle('show');
                });
                document.addEventListener('click', function(e) {
                    if (!menu.contains(e.target) && !btn.contains(e.target)) {
                        menu.classList.remove('show');
                    }
                });
            }
        });
    </script>
</body>

</html>