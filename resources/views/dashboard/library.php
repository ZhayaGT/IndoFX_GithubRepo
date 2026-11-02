<?php
if (!isset($_SESSION['user_id'])) {
    header('Location: /login');
    exit;
}

$userKey  = (string)($_SESSION['user_id'] ?? $_SESSION['name'] ?? 'default_user');
$userName = (string) ($_SESSION['name'] ?? $_SESSION['user_name'] ?? 'Pengguna');
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Saya - IndoFX</title>

    <!-- Google Fonts & Bootstrap Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">

    <style>
        body {
            background-color: var(--indofx-cream-bg, #FAF0E6);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            font-family: 'Inter', sans-serif;
        }

        .page-title {
            font-size: 1.85rem;
            font-weight: 800;
            color: #1A1A1A;
            line-height: 1.2;
        }

        .page-subtitle {
            font-size: 0.88rem;
            color: #555555;
            margin-top: 4px;
        }

        .filter-btn-tab {
            background: none;
            border: none;
            color: #555555;
            font-size: 0.9rem;
            font-weight: 600;
            padding: 4px 10px;
            cursor: pointer;
            transition: color 0.15s ease;
        }

        .filter-btn-tab.active {
            font-weight: 800;
            color: #1A1A1A;
        }

        .library-item-card {
            background-color: #ffffff;
            border: 1px solid #d8c8bd;
            border-radius: 8px;
            padding: 14px 20px 14px 14px;
            display: flex;
            align-items: center;
            gap: 18px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
            margin-bottom: 12px;
            transition: transform 0.15s ease;
        }

        .library-item-thumb {
            width: 175px;
            height: 98px;
            flex-shrink: 0;
            border-radius: 6px;
            overflow: hidden;
            background-color: #1a2422;
        }

        .library-item-content {
            flex-grow: 1;
        }

        .status-badge-green {
            color: #16a34a;
            font-size: 0.78rem;
            font-weight: 800;
            letter-spacing: 0.5px;
        }

        .status-date-text {
            font-size: 0.76rem;
            color: #555555;
        }

        .library-asset-heading {
            font-size: 1.25rem;
            font-weight: 800;
            color: #1A1A1A;
        }

        .library-asset-creator {
            font-size: 0.78rem;
            color: #555555;
        }

        .badge-tag-custom {
            display: inline-block;
            font-size: 0.62rem;
            font-weight: 800;
            padding: 2px 7px;
            border-radius: 4px;
            color: #ffffff;
            line-height: 1.2;
        }

        .badge-hdrp {
            background-color: #4f378b;
        }

        .badge-urp {
            background-color: #a3161c;
        }

        .badge-builtin {
            background-color: #0088ff;
        }

        .btn-action-delete {
            background-color: #dc2626;
            color: #ffffff !important;
            font-size: 0.85rem;
            font-weight: 700;
            padding: 8px 22px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            transition: opacity 0.15s ease;
        }

        .btn-action-delete:hover {
            opacity: 0.88;
        }

        .btn-action-review {
            background-color: #1d70b8;
            color: #ffffff !important;
            font-size: 0.85rem;
            font-weight: 700;
            padding: 8px 18px;
            border-radius: 6px;
            text-decoration: none;
            border: none;
            display: inline-block;
            cursor: pointer;
            transition: opacity 0.15s ease;
        }

        .btn-action-review:hover {
            opacity: 0.88;
        }

        .empty-library-card {
            border: 1.5px dashed #4A3B32;
            border-radius: 8px;
            background-color: transparent;
            min-height: 420px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 40px 20px;
        }

        /* ==========================================
           MODAL POPUP ULASAN (FIGMA EXACT MATCH)
           ========================================== */
        .modal-custom-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background-color: rgba(0, 0, 0, 0.45);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.2s ease;
        }

        .modal-custom-backdrop.active {
            opacity: 1;
            visibility: visible;
        }

        .modal-review-card {
            background-color: #ffffff;
            width: 92%;
            max-width: 580px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            transform: translateY(15px);
            transition: transform 0.2s ease;
        }

        .modal-custom-backdrop.active .modal-review-card {
            transform: translateY(0);
        }

        .modal-review-header {
            padding: 20px 24px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #ededed;
        }

        .modal-review-title {
            font-size: 1.35rem;
            font-weight: 800;
            color: #111111;
            margin: 0;
        }

        .btn-modal-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            line-height: 1;
            color: #666666;
            cursor: pointer;
        }

        .modal-review-body {
            padding: 24px;
        }

        .star-rating-box {
            display: flex;
            gap: 12px;
            margin-top: 8px;
            margin-bottom: 20px;
            cursor: pointer;
        }

        .star-rating-box i {
            font-size: 1.9rem;
            color: #2b2623;
            transition: color 0.15s ease, transform 0.1s ease;
        }

        .star-rating-box i.bi-star-fill {
            color: #ED6B1D;
        }

        .feedback-textarea {
            width: 100%;
            height: 120px;
            border: 1px solid #c7b8af;
            border-radius: 6px;
            padding: 12px;
            font-size: 0.88rem;
            resize: none;
            outline: none;
        }

        .feedback-textarea:focus {
            border-color: #ED6B1D;
        }

        .modal-review-footer {
            padding: 16px 24px;
            border-top: 1px solid #ededed;
            display: flex;
            justify-content: flex-end;
            gap: 12px;
        }

        .btn-modal-cancel {
            background-color: #ffffff;
            border: 1.5px solid #d4c4b9;
            color: #1a1a1a;
            font-weight: 700;
            font-size: 0.88rem;
            padding: 9px 24px;
            border-radius: 6px;
            cursor: pointer;
        }

        .btn-modal-submit {
            background-color: #ED6B1D;
            border: none;
            color: #ffffff;
            font-weight: 700;
            font-size: 0.88rem;
            padding: 9px 22px;
            border-radius: 6px;
            cursor: pointer;
        }

        .btn-modal-submit:hover {
            background-color: #d85a0e;
        }

        /* Layar Sukses */
        .success-message-text {
            font-size: 1.4rem;
            font-weight: 700;
            color: #111111;
            padding: 40px 10px;
            text-align: center;
        }
    </style>
</head>

<body>

    <svg class="d-none">
        <defs>
            <g id="lib-art-forest">
                <rect width="175" height="98" fill="#1E2A28" />
                <ellipse cx="87" cy="55" rx="75" ry="25" fill="#4E6D66" opacity="0.45" />
                <polygon points="30,20 15,90 45,90" fill="#0E1615" />
                <polygon points="70,10 50,95 90,95" fill="#141E1C" />
                <polygon points="120,15 100,95 140,95" fill="#0E1615" />
                <circle cx="87" cy="40" r="4" fill="#E8F1EF" opacity="0.8" />
            </g>
            <g id="lib-art-castle">
                <rect width="175" height="98" fill="#241E24" />
                <ellipse cx="87" cy="55" rx="75" ry="25" fill="#433742" opacity="0.5" />
                <polygon points="45,25 35,90 55,90" fill="#151115" />
                <polygon points="87,15 72,95 102,95" fill="#100D10" />
                <polygon points="130,22 120,90 140,90" fill="#151115" />
                <circle cx="87" cy="38" r="4" fill="#C4B5A5" opacity="0.8" />
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

            <div class="d-flex justify-content-between align-items-end mb-4 flex-wrap gap-2">
                <div>
                    <h1 class="page-title mb-0">Library Saya</h1>
                    <p class="page-subtitle mb-0">Semua aset visual Unity yang telah Anda peroleh</p>
                </div>
                <div class="d-flex gap-2 align-items-center">
                    <button type="button" class="filter-btn-tab active" data-filter="all">Semua</button>
                    <button type="button" class="filter-btn-tab" data-filter="Berbayar">Berbayar</button>
                    <button type="button" class="filter-btn-tab" data-filter="Gratis">Gratis</button>
                </div>
            </div>

            <div id="libraryContainer"></div>

        </div>
    </main>

    <!-- ========================================================
         MODAL POPUP ULASAN (FIGMA EXACT MATCH)
         ======================================================== -->
    <div id="reviewModalBackdrop" class="modal-custom-backdrop">
        <div class="modal-review-card">
            <!-- Header Modal -->
            <div class="modal-review-header">
                <h4 class="modal-review-title">Ceritakan Pengalamanmu!</h4>
                <button type="button" class="btn-modal-close" onclick="closeReviewModal()">&times;</button>
            </div>

            <!-- Formulir Ulasan (Tampilan 1) -->
            <div id="modalFormStep">
                <div class="modal-review-body">
                    <label class="fw-bold text-dark small d-block">Rating (1-5)</label>
                    <div class="star-rating-box" id="starContainer">
                        <i class="bi bi-star" data-rating="1"></i>
                        <i class="bi bi-star" data-rating="2"></i>
                        <i class="bi bi-star" data-rating="3"></i>
                        <i class="bi bi-star" data-rating="4"></i>
                        <i class="bi bi-star" data-rating="5"></i>
                    </div>

                    <label class="fw-bold text-dark small d-block mb-2">Feedback</label>
                    <textarea id="feedbackText" class="feedback-textarea" placeholder="Ceritakan pengalamanmu selama menggunakan aset ini..."></textarea>
                </div>

                <div class="modal-review-footer">
                    <button type="button" class="btn-modal-cancel" onclick="closeReviewModal()">Batal</button>
                    <button type="button" id="btnSubmitReview" class="btn-modal-submit" onclick="submitReview()">Upload Ulasan</button>
                </div>
            </div>

            <!-- Tampilan Konfirmasi Sukses (Tampilan 2) -->
            <div id="modalSuccessStep" class="d-none">
                <div class="modal-review-body">
                    <div class="success-message-text">Terima kasih atas ulasan Anda!</div>
                </div>
                <div class="modal-review-footer">
                    <button type="button" class="btn-modal-cancel" onclick="finishReviewSuccess()">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/navbar-auth.js"></script>
    <script>
        const userKey = '<?= $userKey ?>';
        const storageKey = 'indofx_library_' + userKey;
        let selectedAssetId = null;
        let currentRating = 5;

        function getLibraryData() {
            try {
                return JSON.parse(localStorage.getItem(storageKey)) || [];
            } catch (e) {
                return [];
            }
        }

        function renderLibrary(filter = 'all') {
            const container = document.getElementById('libraryContainer');
            const items = getLibraryData();

            const filteredItems = items.filter(item => {
                if (filter === 'all') return true;
                return (item.type || 'Gratis') === filter;
            });

            if (filteredItems.length === 0) {
                container.innerHTML = `
                    <div class="empty-library-card">
                        <i class="bi bi-exclamation-circle text-dark fs-2 mb-2"></i>
                        <h5 class="fw-bold text-dark mb-1">Belum ada aset</h5>
                        <p class="text-muted small m-0">
                            Tambah aset dari <a href="/" class="text-dark fw-bold text-decoration-none">beranda</a> atau <a href="/catalog/explore" class="text-dark fw-bold text-decoration-none">katalog</a>
                        </p>
                    </div>
                `;
                return;
            }

            container.innerHTML = filteredItems.map(item => {
                const artId = item.art ? ('lib-art-' + item.art) : 'lib-art-forest';
                const pipelines = Array.isArray(item.pipelines) ? item.pipelines : ['URP', 'Built-In'];

                const badgesHtml = pipelines.map(p => {
                    const pLower = p.toLowerCase();
                    let cls = 'badge-urp';
                    if (pLower.includes('hdrp')) cls = 'badge-hdrp';
                    if (pLower.includes('built')) cls = 'badge-builtin';
                    return `<span class="badge-tag-custom ${cls}">${p}</span>`;
                }).join(' ');

                return `
                    <div class="library-item-card" data-type="${item.type || 'Gratis'}">
                        <div class="library-item-thumb">
                            <svg class="w-100 h-100 d-block" viewBox="0 0 175 98">
                                <use href="#${artId}" />
                            </svg>
                        </div>
                        <div class="library-item-content">
                            <div class="mb-1">
                                <span class="status-badge-green">${item.type === 'Gratis' ? 'DIBELI' : 'DIBELI'}</span>
                                <span class="status-date-text ms-2">Perolehan: ${item.date || '30 Oct 2026'}</span>
                            </div>
                            <div class="d-flex align-items-center flex-wrap gap-2 mb-1">
                                <h5 class="library-asset-heading m-0">${item.title}</h5>
                                <div class="d-flex gap-1">${badgesHtml}</div>
                            </div>
                            <p class="library-asset-creator m-0">Oleh ${item.creator || 'Rimba Digital'}</p>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <button type="button" class="btn-action-delete" onclick="removeItem('${item.id}')">
                                Hapus
                            </button>
                            <button type="button" class="btn-action-review" onclick="openReviewModal('${item.id}', '${item.title.replace(/'/g, "\\'")}')">
                                Beri Ulasan
                            </button>
                        </div>
                    </div>
                `;
            }).join('');
        }

        function removeItem(assetId) {
            if (!confirm('Apakah Anda yakin ingin menghapus aset ini dari Library Anda?')) return;
            let items = getLibraryData();
            items = items.filter(item => String(item.id) !== String(assetId));
            localStorage.setItem(storageKey, JSON.stringify(items));
            renderLibrary();
        }

        // ==========================================
        // LOGIKA POPUP MODAL ULASAN
        // ==========================================
        function openReviewModal(assetId, assetTitle) {
            selectedAssetId = assetId;
            document.getElementById('feedbackText').value = '';
            setRating(5);

            document.getElementById('modalFormStep').classList.remove('d-none');
            document.getElementById('modalSuccessStep').classList.add('d-none');
            document.getElementById('reviewModalBackdrop').classList.add('active');
        }

        function closeReviewModal() {
            document.getElementById('reviewModalBackdrop').classList.remove('active');
        }

        function setRating(val) {
            currentRating = val;
            const stars = document.querySelectorAll('#starContainer i');
            stars.forEach((star, idx) => {
                if (idx < val) {
                    star.className = 'bi bi-star-fill';
                } else {
                    star.className = 'bi bi-star';
                }
            });
        }

        document.querySelectorAll('#starContainer i').forEach(star => {
            star.addEventListener('click', function() {
                const rating = parseInt(this.getAttribute('data-rating'), 10);
                setRating(rating);
            });
        });

        // Simpan ulasan ke server & local public cache
        function submitReview() {
            const feedback = document.getElementById('feedbackText').value.trim();
            if (!feedback) {
                alert('Silakan tulis feedback ulasan Anda terlebih dahulu!');
                return;
            }

            const btn = document.getElementById('btnSubmitReview');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Mengunggah...';

            const payload = {
                asset_id: selectedAssetId,
                rating: currentRating,
                comment: feedback
            };

            // Simpan juga ke cache publik peramban agar instan
            const pubKey = 'indofx_public_reviews_' + selectedAssetId;
            let pubReviews = [];
            try {
                pubReviews = JSON.parse(localStorage.getItem(pubKey)) || [];
            } catch (e) {
                pubReviews = [];
            }
            pubReviews.unshift({
                user_name: '<?= htmlspecialchars($userName, ENT_QUOTES, 'UTF-8') ?>',
                rating: currentRating,
                comment: feedback,
                date: '<?= date("d F Y") ?>'
            });
            localStorage.setItem(pubKey, JSON.stringify(pubReviews));

            // Kirim ke server
            fetch('/api/reviews/add', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(payload)
                })
                .then(res => res.json())
                .then(() => {
                    btn.disabled = false;
                    btn.innerHTML = 'Upload Ulasan';
                    // Tampilkan Tampilan Sukses (Langkah 2)
                    document.getElementById('modalFormStep').classList.add('d-none');
                    document.getElementById('modalSuccessStep').classList.remove('d-none');
                })
                .catch(() => {
                    btn.disabled = false;
                    btn.innerHTML = 'Upload Ulasan';
                    document.getElementById('modalFormStep').classList.add('d-none');
                    document.getElementById('modalSuccessStep').classList.remove('d-none');
                });
        }

        function finishReviewSuccess() {
            closeReviewModal();
            // Arahkan ke halaman detail pada tab ulasan
            window.location.href = '/asset/detail?id=' + encodeURIComponent(selectedAssetId) + '#tab-ulasan';
        }

        document.querySelectorAll('.filter-btn-tab').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.filter-btn-tab').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                renderLibrary(this.getAttribute('data-filter'));
            });
        });

        document.addEventListener('DOMContentLoaded', () => renderLibrary());
    </script>
</body>

</html>