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

        /* Filter Kategori Sesuai Figma */
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

        /* Kartu Baris Aset Horizontal Sesuai Figma */
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
    </style>
</head>

<body>

    <!-- SVG Templates untuk Kartu Library -->
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
            <g id="lib-art-water">
                <rect width="175" height="98" fill="#14232C" />
                <ellipse cx="87" cy="55" rx="75" ry="25" fill="#234657" opacity="0.5" />
                <polygon points="45,25 35,90 55,90" fill="#0C171E" />
                <polygon points="87,15 72,95 102,95" fill="#081015" />
                <polygon points="130,22 120,90 140,90" fill="#0C171E" />
                <circle cx="87" cy="38" r="4" fill="#64B5F6" opacity="0.8" />
            </g>
            <g id="lib-art-fire">
                <rect width="175" height="98" fill="#2D1A14" />
                <ellipse cx="87" cy="55" rx="75" ry="25" fill="#572B1B" opacity="0.5" />
                <polygon points="45,25 35,90 55,90" fill="#190E0A" />
                <polygon points="87,15 72,95 102,95" fill="#110906" />
                <polygon points="130,22 120,90 140,90" fill="#190E0A" />
                <circle cx="87" cy="38" r="4" fill="#FF8A65" opacity="0.8" />
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

            <!-- Header Halaman & Filter Kanan -->
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

            <!-- Wadah Daftar Kartu Aset -->
            <div id="libraryContainer"></div>

        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/navbar-auth.js"></script>
    <script>
        const userKey = '<?= $userKey ?>';
        const storageKey = 'indofx_library_' + userKey;

        function getLibraryData() {
            try {
                return JSON.parse(localStorage.getItem(storageKey)) || [];
            } catch (e) {
                return [];
            }
        }

        // Render semua kartu aset yang dimiliki user
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
                const artId = item.art ? ('lib-art-' + item.art) : 'lib-art-castle';
                const pipelines = Array.isArray(item.pipelines) ? item.pipelines : ['URP', 'HDRP'];

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
                                <span class="status-badge-green">${(item.type === 'Gratis' ? 'DIBELI' : 'DIBELI')}</span>
                                <span class="status-date-text ms-2">Perolehan: ${item.date || '15 Jan 2026'}</span>
                            </div>
                            <div class="d-flex align-items-center flex-wrap gap-2 mb-1">
                                <h5 class="library-asset-heading m-0">${item.title}</h5>
                                <div class="d-flex gap-1">${badgesHtml}</div>
                            </div>
                            <p class="library-asset-creator m-0">Oleh ${item.creator || 'Arga Studio'}</p>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <button type="button" class="btn-action-delete" onclick="removeItem('${item.id}')">
                                Hapus
                            </button>
                            <a href="/asset/detail?id=${encodeURIComponent(item.id)}#ulasan" class="btn-action-review">
                                Beri Ulasan
                            </a>
                        </div>
                    </div>
                `;
            }).join('');
        }

        // Hapus aset berdasarkan ID uniknya
        function removeItem(assetId) {
            if (!confirm('Apakah Anda yakin ingin menghapus aset ini dari Library Anda?')) return;
            let items = getLibraryData();
            items = items.filter(item => String(item.id) !== String(assetId));
            localStorage.setItem(storageKey, JSON.stringify(items));
            renderLibrary();
        }

        // Event listener filter Semua / Berbayar / Gratis
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