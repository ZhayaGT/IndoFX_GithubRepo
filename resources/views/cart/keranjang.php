<?php
// 1. Validasi Sesi Pengguna
if (!isset($_SESSION['user_id'])) {
    header('Location: /login');
    exit;
}

// 2. Data Pengguna untuk Header
$userKey  = (string)($_SESSION['user_id'] ?? $_SESSION['name'] ?? 'default_user');
$userName = (string)($_SESSION['name'] ?? $_SESSION['user_name'] ?? 'Pengguna');

$initials = '';
$words = preg_split('/\s+/', trim($userName)) ?: [];
foreach (array_slice($words, 0, 2) as $word) {
    $initials .= mb_strtoupper(mb_substr($word, 0, 1));
}
if ($initials === '') $initials = 'U';

// 3. Konfigurasi URL Gambar Supabase (Dinamis dari .env)
$supabaseUrl = $_ENV['SUPABASE_URL'] ?? $_SERVER['SUPABASE_URL'] ?? getenv('SUPABASE_URL') ?? '';
$storageBasePath = rtrim($supabaseUrl, '/') . '/storage/v1/object/public/asset-previews/catalog/';

// 4. Data Keranjang dari Controller (Misal: $cartItems ditarik dari database)
// Jika $cartItems belum dikirim dari Controller, kita set sebagai array kosong agar tidak error
$cartData = $cartItems ?? []; 
$isCartEmpty = empty($cartData);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang - IndoFX</title>

    <!-- Google Fonts & Bootstrap Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">

    <style>
        :root {
            --indofx-cream-bg: #FAF0E6;
            --indofx-text-main: #3C2A21;
            --indofx-border: #4A3B32;
            --indofx-orange: #ED6B1D;
            --indofx-danger: #9E1B1B;
        }

        body {
            background-color: var(--indofx-cream-bg, #FAF0E6);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            font-family: 'Inter', sans-serif;
            color: var(--indofx-text-main);
        }

        /* ========================================================
           NAVBAR FIGMA (KHUSUS CART: LOGO + CART + PROFILE)
           ======================================================== */
        .navbar-figma-cart {
            background-color: #ED6B1D;
            padding: 12px 0;
            position: sticky;
            top: 0;
            z-index: 1030;
        }

        .avatar-circle-header {
            width: 36px;
            height: 36px;
            background-color: #CFCFCF;
            color: #2D2D2D;
            font-weight: 700;
            font-size: 0.85rem;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .profile-dropdown-wrapper {
            position: relative;
        }

        .btn-profile-trigger {
            background: transparent;
            border: none;
            padding: 0;
            display: flex;
            align-items: center;
            gap: 8px;
            color: #ffffff;
            cursor: pointer;
        }

        .profile-custom-menu {
            position: absolute;
            top: calc(100% + 12px);
            right: 0;
            background-color: #ffffff;
            border: 1px solid #e0d0c5;
            border-radius: 8px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.12);
            min-width: 200px;
            z-index: 1050;
            display: none;
            padding: 6px 0;
        }

        .profile-custom-menu.show {
            display: block;
        }

        .profile-custom-item {
            display: block;
            padding: 9px 18px;
            color: #2b2129;
            text-decoration: none;
            font-size: 0.9rem;
            transition: background-color 0.15s ease;
        }

        .profile-custom-item:hover {
            background-color: #fdf5f0;
            color: #ed6b1d;
        }

        .profile-custom-item.fw-bold {
            border-bottom: 1px solid #f0e6df;
            margin-bottom: 4px;
            color: #1a1a1a;
        }

        /* ========================================================
           KONTEN KERANJANG
           ======================================================== */
        .cart-page-title {
            font-size: 1.85rem;
            font-weight: 800;
            color: var(--indofx-text-main);
            line-height: 1.2;
        }

        .cart-page-sub {
            font-size: 0.88rem;
            color: #7A685D;
            margin-top: 4px;
        }

        .cart-item-card {
            background-color: #ffffff;
            border: 1.5px solid var(--indofx-border);
            border-radius: 8px;
            padding: 12px 18px 12px 14px;
            display: flex;
            align-items: center;
            gap: 16px;
            box-shadow: 0 4px 12px rgba(60, 42, 33, 0.04);
            margin-bottom: 12px;
            transition: transform 0.15s ease;
        }

        .cart-item-thumb {
            width: 175px;
            height: 98px;
            flex-shrink: 0;
            border-radius: 6px;
            overflow: hidden;
            background-color: #241E24;
        }

        .cart-item-content {
            flex-grow: 1;
        }

        .cart-status-line {
            font-size: 0.74rem;
            margin-bottom: 2px;
        }

        .cart-status-badge {
            color: #16a34a;
            font-size: 0.76rem;
            font-weight: 800;
            letter-spacing: 0.5px;
        }

        .cart-asset-title {
            font-size: 1.15rem;
            font-weight: 800;
            color: var(--indofx-text-main);
        }

        .cart-asset-creator {
            font-size: 0.76rem;
            color: #7A685D;
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

        .badge-hdrp { background-color: #4f378b; }
        .badge-urp { background-color: #a3161c; }
        .badge-builtin { background-color: #0088ff; }

        .cart-checkbox {
            width: 18px;
            height: 18px;
            accent-color: #0d6efd;
            cursor: pointer;
        }

        .btn-cart-delete {
            background-color: #dc2626;
            color: #ffffff !important;
            font-size: 0.82rem;
            font-weight: 700;
            padding: 7px 18px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            transition: opacity 0.15s ease;
            text-decoration: none;
        }

        .btn-cart-delete:hover {
            opacity: 0.88;
        }

        .btn-cart-checkout {
            background-color: var(--indofx-orange);
            color: #ffffff !important;
            font-weight: 700;
            font-size: 0.95rem;
            padding: 10px 32px;
            border-radius: 6px;
            text-decoration: none;
            border: none;
            display: inline-block;
            box-shadow: 0 4px 12px rgba(237, 107, 29, 0.3);
            transition: all 0.15s ease;
            cursor: pointer;
        }

        .btn-cart-checkout:hover {
            background-color: #d85a0e;
            transform: translateY(-1px);
        }

        .empty-cart-card {
            border: 1.5px dashed var(--indofx-border);
            border-radius: 8px;
            background-color: transparent;
            min-height: 380px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 40px 20px;
        }

        /* ========================================================
           MODAL KONFIRMASI HAPUS
           ======================================================== */
        .modal-delete-figma .modal-content {
            border: 1px solid #e0d0c5;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            background-color: #ffffff;
            padding: 24px 20px 20px 20px;
            text-align: center;
        }

        .delete-icon-triangle {
            color: var(--indofx-danger);
            font-size: 3.2rem;
            line-height: 1;
            margin-bottom: 12px;
        }

        .modal-delete-title {
            font-size: 1.25rem;
            font-weight: 800;
            color: #1A1A1A;
            margin-bottom: 14px;
        }

        .delete-info-box {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            color: var(--indofx-danger);
            margin-bottom: 24px;
        }

        .btn-cancel-delete {
            background-color: #ffffff;
            color: #1A1A1A;
            border: 1.5px solid #2B2129;
            font-weight: 700;
            font-size: 0.95rem;
            padding: 8px 24px;
            border-radius: 8px;
            cursor: pointer;
        }

        .btn-confirm-delete {
            background-color: #ffffff;
            color: var(--indofx-danger) !important;
            border: 1.5px solid var(--indofx-danger);
            font-weight: 700;
            font-size: 0.95rem;
            padding: 8px 20px;
            border-radius: 8px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
    </style>
</head>

<body>
    <!-- SVG Fallback untuk Gambar yang Gagal Dimuat -->
    <svg class="d-none">
        <defs>
            <g id="cart-art-castle">
                <rect width="175" height="98" fill="#241E24" />
                <ellipse cx="87" cy="55" rx="75" ry="25" fill="#433742" opacity="0.5" />
                <polygon points="45,25 35,90 55,90" fill="#151115" />
                <polygon points="87,15 72,95 102,95" fill="#100D10" />
                <polygon points="130,22 120,90 140,90" fill="#151115" />
                <circle cx="87" cy="38" r="4" fill="#C4B5A5" opacity="0.8" />
            </g>
        </defs>
    </svg>

    <!-- Header Navbar Sesuai Figma -->
    <nav class="navbar-figma-cart">
        <div class="container-figma d-flex justify-content-between align-items-center w-100">
            <a class="navbar-brand m-0 d-flex align-items-center gap-2 text-decoration-none" href="/">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none">
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
                <a href="/cart" class="text-white text-decoration-none d-flex align-items-center" title="Keranjang">
                    <i class="bi bi-cart3 fs-4"></i>
                </a>

                <div class="profile-dropdown-wrapper">
                    <button type="button" class="btn-profile-trigger" id="profileDropdownBtn" aria-expanded="false">
                        <span class="avatar-circle-header"><?= htmlspecialchars($initials, ENT_QUOTES, 'UTF-8') ?></span>
                        <span class="fw-bold fs-6"><?= htmlspecialchars($userName, ENT_QUOTES, 'UTF-8') ?></span>
                    </button>

                    <div class="profile-custom-menu" id="profileDropdownMenu">
                        <a href="/creator/profile?id=<?= $_SESSION['public_id'] ?? '' ?>" class="profile-custom-item fw-bold">
                            <?= htmlspecialchars($userName, ENT_QUOTES, 'UTF-8') ?>
                        </a>
                        <a href="/dashboard/ubah-profil" class="profile-custom-item">Edit Profile</a>
                        <a href="/invoice" class="profile-custom-item">Histori Pembelian</a>
                        <a href="/logout" class="profile-custom-item text-danger">Keluar</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Konten Utama Keranjang (Form untuk Submit ke Checkout) -->
    <main class="flex-grow-1 py-5">
        <div class="container-figma">
            <div class="mb-4">
                <h1 class="cart-page-title mb-0">Keranjang</h1>
                <p class="cart-page-sub mb-0">Semua aset visual Unity yang Anda Ingin Beli</p>
            </div>

            <!-- Form dibungkus untuk mengirim data Array ID yang dichecklist ke endpoint Checkout -->
            <form action="/checkout" method="POST" id="cartForm">
                
                <div id="cartListContainer">
                    <?php if ($isCartEmpty): ?>
                        <div class="empty-cart-card">
                            <i class="bi bi-cart-x text-dark fs-1 mb-2"></i>
                            <h5 class="fw-bold text-dark mb-1">Keranjang Belanja Anda Kosong</h5>
                            <p class="text-muted small m-0 mb-3">Temukan aset visual pilihan untuk game Anda di Katalog</p>
                            <a href="/catalog/explore" class="btn btn-hero-orange">Jelajahi Aset</a>
                        </div>
                    <?php else: ?>
                        <!-- Loop Render Kartu Keranjang dari Database PHP -->
                        <?php foreach ($cartData as $item): ?>
                            <div class="cart-item-card" id="cart-item-<?= htmlspecialchars($item['id'], ENT_QUOTES, 'UTF-8') ?>">
                                <div class="cart-item-thumb">
                                    <?php 
                                        $thumbPath = $item['thumbnail'] ?? '';
                                        $finalThumbUrl = empty($thumbPath) 
                                            ? '' 
                                            : ((strpos($thumbPath, 'http') === 0) ? $thumbPath : $storageBasePath . $thumbPath);
                                    ?>
                                    <?php if ($finalThumbUrl): ?>
                                        <img src="<?= htmlspecialchars($finalThumbUrl, ENT_QUOTES, 'UTF-8') ?>" 
                                             style="width: 100%; height: 100%; object-fit: cover;" 
                                             onerror="this.style.display='none'; this.nextElementSibling.style.display='block';" alt="Thumb">
                                        <svg style="display: none; width: 100%; height: 100%;" viewBox="0 0 175 98"><use href="#cart-art-castle" /></svg>
                                    <?php else: ?>
                                        <svg class="w-100 h-100 d-block" viewBox="0 0 175 98"><use href="#cart-art-castle" /></svg>
                                    <?php endif; ?>
                                </div>

                                <div class="cart-item-content">
                                    <div class="cart-status-line">
                                        <!-- Jika belum dibeli (di dalam keranjang), tidak ada label DIBELI, namun karena format Figma meminta label tersebut: -->
                                        <span class="cart-status-badge">DIBELI</span> 
                                        <span class="text-muted ms-2">
                                            Perolehan: <?= htmlspecialchars(date('d M Y', strtotime($item['added_at'] ?? 'now'))) ?>
                                        </span>
                                    </div>

                                    <div class="d-flex align-items-center flex-wrap gap-2 mb-1">
                                        <h5 class="cart-asset-title m-0">
                                            <?= htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8') ?>
                                        </h5>
                                        <div class="d-flex gap-1">
                                            <?php 
                                                $pipelines = explode(',', $item['render_pipeline'] ?? 'URP');
                                                foreach ($pipelines as $pipe): 
                                                    $p = trim($pipe);
                                                    $cls = 'badge-urp';
                                                    if (stripos($p, 'HDRP') !== false) $cls = 'badge-hdrp';
                                                    if (stripos($p, 'Built') !== false) $cls = 'badge-builtin';
                                            ?>
                                                <span class="badge-tag-custom <?= $cls ?>"><?= htmlspecialchars($p, ENT_QUOTES, 'UTF-8') ?></span>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>

                                    <p class="cart-asset-creator m-0">
                                        Oleh <?= htmlspecialchars($item['seller_name'] ?? 'Kreator', ENT_QUOTES, 'UTF-8') ?>
                                    </p>
                                </div>

                                <div class="d-flex align-items-center gap-3">
                                    <!-- Checkbox ini yang akan dikirim ke Checkout (name="selected_items[]") -->
                                    <input type="checkbox" name="selected_items[]" class="cart-checkbox" checked value="<?= htmlspecialchars($item['id'], ENT_QUOTES, 'UTF-8') ?>">
                                    
                                    <!-- Tombol Hapus: Akan membuka Modal Konfirmasi (mengoper ID) -->
                                    <button type="button" class="btn-cart-delete" onclick="openDeleteModal('<?= htmlspecialchars($item['id'], ENT_QUOTES, 'UTF-8') ?>')">
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <?php if (!$isCartEmpty): ?>
                    <div id="cartFooterArea" class="d-flex justify-content-end align-items-center mt-4">
                        <button type="submit" class="btn-cart-checkout">Checkout</button>
                    </div>
                <?php endif; ?>
                
            </form>
        </div>
    </main>

    <!-- Modal Konfirmasi Hapus Sesuai Desain Figma -->
    <div class="modal fade modal-delete-figma" id="deleteConfirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 380px;">
            <div class="modal-content">
                <div class="delete-icon-triangle">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>

                <h4 class="modal-delete-title">Hapus Aset dari Keranjang?</h4>

                <div class="delete-info-box">
                    <i class="bi bi-info-circle"></i>
                    <span>Anda dapat menambahkannya<br>kembali kapan saja.</span>
                </div>

                <!-- Form khusus untuk menghapus (Metode POST ke endpoint penghapusan) -->
                <form action="/cart/remove" method="POST" class="delete-modal-actions w-100">
                    <input type="hidden" name="asset_id" id="deleteTargetId" value="">
                    
                    <button type="button" class="btn-cancel-delete" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn-confirm-delete">
                        <i class="bi bi-trash3"></i>
                        <span>Ya, Hapus</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer-main" id="tentang">
        <div class="container-figma">
            <div class="footer-center-content">
                <h5 class="footer-brand-title-center">IndoFX</h5>
                <p class="footer-brand-desc-center">
                    Portal distribusi dan marketplace aset CG &amp; VFX berkualitas tinggi buatan lokal, dioptimalkan sepenuhnya untuk industri game Indonesia.
                </p>
            </div>
            <div class="footer-bottom-bar">
                <span>&copy; 2026 IndoFX. Hak Cipta Dilindungi.</span>
                <span>Dibuat dengan bangga untuk Game Developer Indonesia</span>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Membuka modal konfirmasi hapus dan menyisipkan ID aset ke input form tersembunyi
        function openDeleteModal(assetId) {
            document.getElementById('deleteTargetId').value = assetId;
            const modalEl = document.getElementById('deleteConfirmModal');
            const modal = new bootstrap.Modal(modalEl);
            modal.show();
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Dropdown Profil Header
            const dropdownBtn = document.getElementById('profileDropdownBtn');
            const dropdownMenu = document.getElementById('profileDropdownMenu');

            if (dropdownBtn && dropdownMenu) {
                dropdownBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    dropdownMenu.classList.toggle('show');
                });

                document.addEventListener('click', function(e) {
                    if (!dropdownMenu.contains(e.target) && !dropdownBtn.contains(e.target)) {
                        dropdownMenu.classList.remove('show');
                    }
                });
            }

            // Validasi sebelum submit form checkout
            const cartForm = document.getElementById('cartForm');
            if (cartForm) {
                cartForm.addEventListener('submit', function(e) {
                    const checkedBoxes = document.querySelectorAll('.cart-checkbox:checked');
                    if (checkedBoxes.length === 0) {
                        e.preventDefault();
                        alert('Silakan pilih minimal satu aset yang ingin dibeli (centang kotak).');
                    }
                });
            }
        });
    </script>
</body>
</html>