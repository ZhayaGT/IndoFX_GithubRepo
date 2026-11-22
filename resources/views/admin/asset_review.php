<?php
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: /login');
    exit;
}

$adminName = (string) ($_SESSION['name'] ?? 'Admin IndoFX');
$adminInitials = 'AP';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="admin-csrf" content="<?= htmlspecialchars($_SESSION['admin_csrf'], ENT_QUOTES, 'UTF-8') ?>">
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
                        <p class="text-muted small" id="previewMessage">Memuat pratinjau…</p>
                        <div class="review-main-preview">
                            <img id="mainPreviewImg" hidden alt="Preview Aset">
                        </div>

                        <!-- 3 Gambar Thumbnail Galeri -->
                        <div class="review-thumb-grid">
                            <div class="review-thumb-item">
                                <img id="thumbImg1" hidden alt="Thumb 1">
                            </div>
                            <div class="review-thumb-item">
                                <img id="thumbImg2" hidden alt="Thumb 2">
                            </div>
                            <div class="review-thumb-item">
                                <img id="thumbImg3" hidden alt="Thumb 3">
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
                                    <div class="fw-bold text-dark" id="reviewFileNameDisplay">—</div>
                                    <div class="text-muted small" id="reviewFileSizeDate">—</div>
                                </div>
                            </div>
                            <button type="button" class="btn-review-download" onclick="downloadPackage()">
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
                        <div class="review-price-val" id="sidebarPriceVal">—</div>

                        <table class="review-spec-table">
                            <tr>
                                <td>Versi Unity Minimum:</td>
                                <td id="sidebarUnityVer">—</td>
                            </tr>
                            <tr>
                                <td>Render Pipeline:</td>
                                <td id="sidebarPipeline">—</td>
                            </tr>
                            <tr>
                                <td>Tanggal Pengajuan:</td>
                                <td id="sidebarSubmissionDate">—</td>
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

    <footer class="footer-main">
        <div class="container-figma">
            <div class="footer-center-content">
                <h5 class="footer-brand-title-center">IndoFX</h5>
                <p class="footer-brand-desc-center">
                    Portal distribusi dan marketplace aset CG &amp; VFX berkualitas tinggi buatan lokal, dioptimalkan sepenuhnya untuk industri game Indonesia.
                </p>
                <!-- Baris Kontak dan Email sesuai desain baru -->
                <div style="font-size: 0.85rem; color: #1a1a1a; margin-top: 10px;">
                    <strong style="font-weight: 800;">Kontak dan Email:</strong>
                    <span style="margin: 0 16px;">08123456xxxx</span>
                    <span>helpdesk@indofx.com</span>
                </div>
            </div>
            <div class="footer-bottom-bar">
                <span>&copy; 2026 IndoFX. Hak Cipta Dilindungi Undang-Undang.</span>
                <span>Dibuat dengan bangga untuk Game Developer Indonesia</span>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Helper escape output HTML -->
    <script src="/assets/js/indofx-utils.js"></script>

    <!-- Koneksi data admin -->
    <script src="/assets/js/admin-api.js"></script>

    <!-- ENGINE PENINJAUAN ASET: 100% DINAMIS MENGGUNAKAN TEKS ASLI PENJUAL -->
    <script>
        let currentAsset = null;
        let allQueue = [];
        let modalReject = null;

        function getAssetIdFromUrl() {
            const params = new URLSearchParams(window.location.search);
            return params.get('id');
        }

        async function loadAssetDetail() {
            try {
            const assetId = getAssetIdFromUrl();
            allQueue = (await AdminApi.data()).assets;
            currentAsset = allQueue.find(a => String(a.id) === String(assetId));
            if (!currentAsset) throw new Error('Aset tidak ditemukan. Kembali ke daftar moderasi.');
            document.querySelectorAll('[onclick="approveAndPublishAsset()"], [onclick="openRejectDialog()"]').forEach(button => button.disabled = currentAsset.status !== 'Menunggu');
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

            const preview = document.getElementById('mainPreviewImg');
            preview.hidden = !currentAsset.image;
            if (currentAsset.image) preview.src = currentAsset.image;
            document.getElementById('previewMessage').textContent = currentAsset.image ? '' : 'Gambar pratinjau belum tersedia.';
            [1, 2, 3].forEach((number, index) => {
                const thumb = document.getElementById('thumbImg' + number);
                const source = currentAsset.images[index];
                thumb.parentElement.hidden = !source;
                thumb.hidden = !source;
                if (source) {
                    thumb.src = source;
                    thumb.alt = currentAsset.title;
                    thumb.parentElement.onclick = () => { preview.src = source; };
                }
            });

            // Pipeline Badge
            const pipe = currentAsset.pipeline || (Array.isArray(currentAsset.pipelines) ? currentAsset.pipelines[0] : 'URP');
            const pipeBadge = document.getElementById('badgePipelineHeader');
            pipeBadge.innerText = pipe;
            pipeBadge.className = 'badge-pipe-admin ' + (pipe.toLowerCase().includes('hdrp') ? 'badge-pipe-hdrp' : (pipe.toLowerCase().includes('built') ? 'badge-pipe-builtin' : 'badge-pipe-urp'));

            // Status Badge
            const isPublished = AdminApi.status(currentAsset.status) === 'Published';
            const isRejected = AdminApi.status(currentAsset.status) === 'Ditolak';
            let statusBadgeHtml = `<span class="badge-status-review">Pending Review</span>`;
            if (isPublished) statusBadgeHtml = `<span class="badge-status-published">Published</span>`;
            if (isRejected) statusBadgeHtml = `<span class="badge-status-blocked">Ditolak</span>`;
            document.getElementById('sidebarStatusBadge').innerHTML = statusBadgeHtml;

            // Berkas Unity
            const cleanFileName = currentAsset.file_info ? currentAsset.file_info.replace(/^File:\s*/i, '') : ((currentAsset.title.toLowerCase().replace(/[^a-z0-9]/g, '_')) + '.unitypackage');
            document.getElementById('reviewFileNameDisplay').innerText = cleanFileName;
            document.getElementById('reviewFileSizeDate').innerText = 'Ukuran: ' + currentAsset.file_size + ' | Diunggah: ' + (currentAsset.date || currentAsset.created_at || 'Hari ini');

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
            const pCount = allQueue.filter(q => AdminApi.status(q.status) === 'Menunggu').length;
            document.getElementById('sidebarModerationBadge').innerText = pCount;

            } catch (error) { AdminApi.showError(error); }
        }

        // AKSI 1: ADMIN SETUJUI & PUBLIKASIKAN (TERUSKAN DESKRIPSI & PANDUAN KE KATALOG PUBLIK)
        async function approveAndPublishAsset() {
            try {
                if (!currentAsset) return;
                await AdminApi.save('asset.review', {id: currentAsset.id, decision: 'approve', reason: document.getElementById('rejectAssetReasonInput').value.trim()});
                window.location.href = '/admin/assets';
            } catch (error) { AdminApi.showError(error); }
        }

        // AKSI 2: ADMIN TOLAK PENGAJUAN
        function openRejectDialog() {
            document.getElementById('rejectAssetReasonInput').value = '';
            modalReject.show();
        }

        async function confirmRejectAsset() {
            try {
                if (!currentAsset) return;
                await AdminApi.save('asset.review', {id: currentAsset.id, decision: 'reject', reason: document.getElementById('rejectAssetReasonInput').value.trim()});
                window.location.href = '/admin/assets';
            } catch (error) { AdminApi.showError(error); }
        }

        function downloadPackage() {
            if (!currentAsset?.file_id) { AdminApi.showError(new Error('Berkas belum tersedia.')); return; }
            window.location.href = '/admin/download?id=' + encodeURIComponent(currentAsset.file_id);
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