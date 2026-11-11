<?php
if (!isset($_SESSION['user_id'])) {
    header('Location: /login');
    exit;
}

$userName = (string) ($_SESSION['name'] ?? $_SESSION['user_name'] ?? 'Pengguna');
$userKey  = (string) ($_SESSION['user_id'] ?? $_SESSION['name'] ?? 'default_user');

$initials = '';
$words = preg_split('/\s+/', trim($userName)) ?: [];
foreach (array_slice($words, 0, 2) as $word) {
    $initials .= mb_strtoupper(mb_substr($word, 0, 1));
}
if ($initials === '') {
    $initials = 'U';
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Saya - IndoFX</title>

    <!-- Google Fonts & Bootstrap Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- CSS terpusat murni tanpa tag style tambahan -->
    <link rel="stylesheet" href="/assets/css/style.css">
</head>

<body class="d-flex flex-column min-vh-100">

    <!-- SVG Thumbnails -->
    <svg class="d-none">
        <defs>
            <g id="lib-art-castle">
                <rect width="175" height="98" fill="#241E24" />
                <ellipse cx="87" cy="55" rx="75" ry="25" fill="#433742" opacity="0.5" />
                <polygon points="45,25 35,90 55,90" fill="#151115" />
                <polygon points="87,15 72,95 102,95" fill="#100D10" />
                <polygon points="130,22 120,90 140,90" fill="#151115" />
            </g>
            <g id="lib-art-water">
                <rect width="175" height="98" fill="#14232C" />
                <ellipse cx="87" cy="55" rx="75" ry="25" fill="#234657" opacity="0.5" />
                <polygon points="45,25 35,90 55,90" fill="#0C171E" />
                <polygon points="87,15 72,95 102,95" fill="#081015" />
                <polygon points="130,22 120,90 140,90" fill="#0C171E" />
            </g>
            <g id="lib-art-forest">
                <rect width="175" height="98" fill="#1E2A28" />
                <ellipse cx="87" cy="55" rx="75" ry="25" fill="#4E6D66" opacity="0.45" />
                <polygon points="45,25 35,90 55,90" fill="#141E1C" />
                <polygon points="260,50 200,380 320,380" fill="#182421" />
                <polygon points="130,22 120,90 140,90" fill="#141E1C" />
            </g>
        </defs>
    </svg>

    <!-- Header Navbar -->
    <nav class="navbar navbar-orange sticky-top">
        <div class="container-figma d-flex justify-content-between align-items-center w-100">
            <a class="navbar-brand m-0 d-flex align-items-center gap-2 text-decoration-none" href="/">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2L20.66 7V17L12 22L3.34 17V7L12 2Z" fill="#ED6B1D" stroke="#1A1A1A" stroke-width="1.8" stroke-linejoin="round" />
                    <path d="M12 2L20.66 7L12 12L3.34 7L12 2Z" fill="#FDBA74" stroke="#1A1A1A" stroke-width="1.5" stroke-linejoin="round" />
                    <path d="M12 12V22" stroke="#1A1A1A" stroke-width="1.5" />
                    <path d="M12 12L20.66 7" stroke="#1A1A1A" stroke-width="1.5" />
                    <path d="M12 12L3.34 7" stroke="#1A1A1A" stroke-width="1.5" />
                    <path d="M12 4.5L16.5 7L12 9.5L7.5 7L12 4.5Z" fill="#FED7AA" stroke="#1A1A1A" stroke-width="1" stroke-linejoin="round" />
                </svg>
                <span class="brand-text text-white fw-bold">IndoFX</span>
            </a>

            <div class="d-flex align-items-center gap-3">
                <?php
                $authPartial = BASE_PATH . '/resources/views/partials/navbar-auth.php';
                if (file_exists($authPartial)) {
                    require $authPartial;
                }
                ?>
            </div>
        </div>
    </nav>

    <!-- Konten Utama Library -->
    <main class="flex-grow-1 py-5">
        <div class="container-figma">
            <div class="d-flex justify-content-between align-items-sm-end align-items-start mb-4 flex-wrap gap-2">
                <div>
                    <h1 class="library-title mb-1">Library Saya</h1>
                    <p class="library-sub mb-0">Semua aset visual Unity yang telah Anda peroleh</p>
                </div>

                <div class="filter-nav-tabs d-flex align-items-center gap-3">
                    <button type="button" class="filter-tab-btn active" onclick="setFilter('semua', this)">Semua</button>
                    <button type="button" class="filter-tab-btn" onclick="setFilter('berbayar', this)">Berbayar</button>
                    <button type="button" class="filter-tab-btn" onclick="setFilter('gratis', this)">Gratis</button>
                </div>
            </div>

            <div id="libraryContainer"></div>
        </div>
    </main>

    <!-- Footer Resmi IndoFX Membentang Penuh di Dasar Halaman -->
    <?php
    $footerPartial = BASE_PATH . '/resources/views/partials/footer.php';
    if (file_exists($footerPartial)) {
        require $footerPartial;
    } else {
    ?>
        <footer class="footer-main mt-auto">
            <div class="container-figma">
                <div class="footer-center-content">
                    <h5 class="footer-brand-title-center">IndoFX</h5>
                    <p class="footer-brand-desc-center">
                        Portal distribusi dan marketplace aset CG &amp; VFX berkualitas tinggi buatan lokal, dioptimalkan sepenuhnya untuk industri game Indonesia.
                    </p>
                    <div class="footer-contact-row">
                        <strong>Kontak dan Email:</strong>
                        <span class="ms-3 me-3">08123456xxxx</span>
                        <span>helpdesk@indofx.com</span>
                    </div>
                </div>
                <div class="footer-bottom-bar">
                    <span>&copy; 2026 IndoFX. Hak Cipta Dilindungi Undang-Undang.</span>
                    <span>Dibuat dengan bangga untuk Game Developer Indonesia</span>
                </div>
            </div>
        </footer>
    <?php } ?>

    <!-- Modal Ulasan Persis Figma -->
    <div class="modal fade modal-review-figma" id="reviewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 530px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ceritakan Pengalamanmu!</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Tampilan 1: Form Ulasan -->
                <div id="reviewFormView">
                    <div class="modal-body">
                        <input type="hidden" id="reviewAssetId">

                        <!-- Rating Bintang Oranye Interaktif -->
                        <div class="mb-3">
                            <label class="form-label-figma">Rating (1-5)</label>
                            <div class="interactive-stars-box" id="starRatingGroup">
                                <i class="bi bi-star" data-rating="1" onclick="selectStar(1)" onmouseover="hoverStar(1)" onmouseleave="resetStarHover()"></i>
                                <i class="bi bi-star" data-rating="2" onclick="selectStar(2)" onmouseover="hoverStar(2)" onmouseleave="resetStarHover()"></i>
                                <i class="bi bi-star" data-rating="3" onclick="selectStar(3)" onmouseover="hoverStar(3)" onmouseleave="resetStarHover()"></i>
                                <i class="bi bi-star" data-rating="4" onclick="selectStar(4)" onmouseover="hoverStar(4)" onmouseleave="resetStarHover()"></i>
                                <i class="bi bi-star" data-rating="5" onclick="selectStar(5)" onmouseover="hoverStar(5)" onmouseleave="resetStarHover()"></i>
                            </div>
                        </div>

                        <!-- Judul Ulasan -->
                        <div class="mb-3">
                            <label class="form-label-figma">Judul Ulasan</label>
                            <input type="text" class="form-control input-review-figma" id="reviewTitleInput" placeholder="Contoh: Mudah dipelajari untuk prototipe">
                        </div>

                        <!-- Feedback -->
                        <div class="mb-2">
                            <label class="form-label-figma">Feedback</label>
                            <textarea class="form-control input-review-figma" id="reviewCommentInput" rows="4" placeholder="Ceritakan pengalamanmu selama menggunakan aset ini..."></textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn-modal-upload" onclick="submitUserReview()">Upload Ulasan</button>
                    </div>
                </div>

                <!-- Tampilan 2: Notifikasi Sukses -->
                <div id="reviewSuccessView" class="d-none">
                    <div class="modal-body">
                        <h4 class="review-success-message">Terima kasih atas ulasan Anda!</h4>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/navbar-auth.js"></script>
    <script>
        const userKey = '<?= $userKey ?>';
        const userName = '<?= htmlspecialchars($userName, ENT_QUOTES, 'UTF-8') ?>';
        const storageKey = 'indofx_library_' + userKey;
        const reviewedStorageKey = 'indofx_reviewed_assets_' + userKey;

        let currentFilter = 'semua';
        let currentSelectedRating = 5;

        function getLibraryData() {
            try {
                return JSON.parse(localStorage.getItem(storageKey)) || [];
            } catch (e) {
                return [];
            }
        }

        function getReviewedAssets() {
            try {
                return JSON.parse(localStorage.getItem(reviewedStorageKey)) || [];
            } catch (e) {
                return [];
            }
        }

        function openAssetDetail(asset) {
            const params = new URLSearchParams({
                id: asset.id || '1',
                title: asset.title || 'Aset IndoFX',
                creator: asset.creator || 'Arga Studio',
                category: asset.category || (asset.type === 'Gratis' ? 'PARTIKEL' : 'SHADER'),
                pipeline: Array.isArray(asset.pipelines) ? asset.pipelines.join(', ') : (asset.pipeline || 'URP, HDRP'),
                art: asset.art || 'castle'
            });
            window.location.href = '/library/detail?' + params.toString();
        }

        function renderLibrary() {
            const container = document.getElementById('libraryContainer');
            const items = getLibraryData();
            const reviewedList = getReviewedAssets();

            let filtered = items;
            if (currentFilter === 'berbayar') {
                filtered = items.filter(i => (i.type || 'Berbayar').toLowerCase() !== 'gratis');
            } else if (currentFilter === 'gratis') {
                filtered = items.filter(i => (i.type || '').toLowerCase() === 'gratis');
            }

            if (filtered.length === 0) {
                container.innerHTML = `
                    <div class="empty-library-card">
                        <i class="bi bi-box-seam fs-1 text-secondary mb-2"></i>
                        <h5 class="fw-bold mb-1" style="color: var(--indofx-dark, #2b2623);">Belum ada aset di Library Anda</h5>
                        <p class="text-muted small mb-3">Temukan dan tambahkan aset visual pilihan Anda melalui katalog.</p>
                        <a href="/catalog/explore" class="btn btn-hero-orange">Jelajahi Katalog</a>
                    </div>
                `;
                return;
            }

            container.innerHTML = filtered.map(item => {
                const artId = item.art ? ('lib-art-' + item.art) : 'lib-art-castle';
                const pipelines = Array.isArray(item.pipelines) ? item.pipelines : ['HDRP'];

                const badgesHtml = pipelines.map(p => {
                    let cls = 'badge-urp';
                    if (p.toLowerCase().includes('hdrp')) cls = 'badge-hdrp';
                    if (p.toLowerCase().includes('built')) cls = 'badge-builtin';
                    return `<span class="badge-tag-custom ${cls}">${p}</span>`;
                }).join(' ');

                const itemJson = JSON.stringify(item).replace(/"/g, '&quot;');
                const alreadyReviewed = reviewedList.includes(String(item.id));

                const actionButtonHtml = alreadyReviewed ? `
                    <button type="button" class="btn-already-reviewed" disabled onclick="event.stopPropagation()">
                        Sudah Diulas
                    </button>
                ` : `
                    <button type="button" class="btn-action-delete" onclick="event.stopPropagation(); removeLibraryItem('${item.id}')">
                        Hapus
                    </button>
                    <button type="button" class="btn-action-review" onclick="event.stopPropagation(); openReviewModal('${item.id}', '${item.title.replace(/'/g, "\\'")}')">
                        Beri Ulasan
                    </button>
                `;

                return `
                    <div class="library-item-card" onclick="openAssetDetail(${itemJson})" title="Klik untuk membuka detail dan unduh aset">
                        <div class="library-item-thumb">
                            <svg viewBox="0 0 175 98"><use href="#${artId}" /></svg>
                        </div>
                        <div class="library-item-content">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="status-badge-blue">DIBELI</span>
                                <span class="text-muted small">Perolehan: ${item.date || '02 Nov 2026'}</span>
                            </div>
                            <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                                <h5 class="library-asset-heading">${item.title}</h5>
                                <div>${badgesHtml}</div>
                            </div>
                            <p class="text-muted small m-0">Oleh ${item.creator || 'Arga Studio'}</p>
                        </div>
                        <div class="library-actions-wrap">
                            ${actionButtonHtml}
                        </div>
                    </div>
                `;
            }).join('');
        }

        function setFilter(filterType, el) {
            currentFilter = filterType;
            document.querySelectorAll('.filter-tab-btn').forEach(btn => btn.classList.remove('active'));
            el.classList.add('active');
            renderLibrary();
        }

        function removeLibraryItem(assetId) {
            if (!confirm('Hapus aset ini dari Library Anda?')) return;
            let items = getLibraryData();
            items = items.filter(i => String(i.id) !== String(assetId));
            localStorage.setItem(storageKey, JSON.stringify(items));
            renderLibrary();
        }

        function updateStarUI(rating) {
            const stars = document.querySelectorAll('#starRatingGroup i');
            stars.forEach((star, index) => {
                if (index < rating) {
                    star.className = 'bi bi-star-fill';
                } else {
                    star.className = 'bi bi-star';
                }
            });
        }

        function selectStar(rating) {
            currentSelectedRating = rating;
            updateStarUI(rating);
        }

        function hoverStar(rating) {
            updateStarUI(rating);
        }

        function resetStarHover() {
            updateStarUI(currentSelectedRating);
        }

        function openReviewModal(assetId, assetTitle) {
            const reviewedList = getReviewedAssets();
            if (reviewedList.includes(String(assetId))) {
                alert('Anda sudah pernah memberikan ulasan untuk aset ini.');
                return;
            }

            document.getElementById('reviewAssetId').value = assetId;
            document.getElementById('reviewTitleInput').value = '';
            document.getElementById('reviewCommentInput').value = '';

            document.getElementById('reviewFormView').classList.remove('d-none');
            document.getElementById('reviewSuccessView').classList.add('d-none');

            selectStar(5);

            const modal = new bootstrap.Modal(document.getElementById('reviewModal'));
            modal.show();
        }

        function submitUserReview() {
            const assetId = document.getElementById('reviewAssetId').value;
            const title = document.getElementById('reviewTitleInput').value.trim();
            const comment = document.getElementById('reviewCommentInput').value.trim();

            if (!comment) {
                alert('Silakan tuliskan feedback pengalaman Anda.');
                return;
            }

            const revStorageKey = 'indofx_public_reviews_' + assetId;
            let reviews = [];
            try {
                reviews = JSON.parse(localStorage.getItem(revStorageKey)) || [];
            } catch (e) {
                reviews = [];
            }

            const now = new Date();
            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            const dateStr = `${now.getDate()} ${months[now.getMonth()]} ${now.getFullYear()}`;

            reviews.unshift({
                user_name: userName,
                rating: currentSelectedRating,
                title: title,
                comment: comment,
                date: dateStr
            });

            localStorage.setItem(revStorageKey, JSON.stringify(reviews));

            let reviewedList = getReviewedAssets();
            if (!reviewedList.includes(String(assetId))) {
                reviewedList.push(String(assetId));
                localStorage.setItem(reviewedStorageKey, JSON.stringify(reviewedList));
            }

            document.getElementById('reviewFormView').classList.add('d-none');
            document.getElementById('reviewSuccessView').classList.remove('d-none');

            renderLibrary();
        }

        document.addEventListener('DOMContentLoaded', renderLibrary);
    </script>
</body>

</html>