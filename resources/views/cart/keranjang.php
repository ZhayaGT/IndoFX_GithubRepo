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
    <title>Keranjang - IndoFX</title>

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

        .cart-page-title {
            font-size: 1.85rem;
            font-weight: 800;
            color: #1A1A1A;
            line-height: 1.2;
        }

        .cart-page-sub {
            font-size: 0.88rem;
            color: #555555;
            margin-top: 4px;
        }

        /* Kartu Keranjang Persis Desain Figma */
        .cart-item-card {
            background-color: #ffffff;
            border: 1.5px solid #2B2129;
            border-radius: 8px;
            padding: 12px 18px 12px 14px;
            display: flex;
            align-items: center;
            gap: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
            margin-bottom: 12px;
            transition: transform 0.15s ease;
        }

        .cart-item-thumb {
            width: 175px;
            height: 98px;
            flex-shrink: 0;
            border-radius: 6px;
            overflow: hidden;
            background-color: #241e24;
        }

        .cart-item-thumb img,
        .cart-item-thumb svg {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
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
            color: #1A1A1A;
        }

        .cart-asset-creator {
            font-size: 0.76rem;
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

        /* Checkbox Biru Figma */
        .cart-checkbox {
            width: 18px;
            height: 18px;
            accent-color: #0d6efd;
            cursor: pointer;
        }

        /* Tombol Hapus Merah Figma */
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
        }

        .btn-cart-delete:hover {
            opacity: 0.88;
        }

        /* Tombol Checkout Oranye */
        .btn-cart-checkout {
            background-color: #ED6B1D;
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
        }

        .btn-cart-checkout:hover {
            background-color: #d85a0e;
            transform: translateY(-1px);
        }

        /* Kotak Kosong saat Keranjang Baru/Bersih */
        .empty-cart-card {
            border: 1.5px dashed #4A3B32;
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
    </style>
</head>

<body>

    <!-- SVG Gambar Castle Sesuai Figma -->
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
            <g id="cart-art-water">
                <rect width="175" height="98" fill="#14232C" />
                <ellipse cx="87" cy="55" rx="75" ry="25" fill="#234657" opacity="0.5" />
                <polygon points="45,25 35,90 55,90" fill="#0C171E" />
                <polygon points="87,15 72,95 102,95" fill="#081015" />
                <polygon points="130,22 120,90 140,90" fill="#0C171E" />
                <circle cx="87" cy="38" r="4" fill="#64B5F6" opacity="0.8" />
            </g>
            <g id="cart-art-fire">
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

    <!-- Konten Utama Keranjang -->
    <main class="flex-grow-1 py-5">
        <div class="container-figma">

            <!-- Judul & Deskripsi Sesuai Screenshot -->
            <div class="mb-4">
                <h1 class="cart-page-title mb-0">Keranjang</h1>
                <p class="cart-page-sub mb-0">Semua aset visual Unity yang Anda Ingin Beli</p>
            </div>

            <!-- Wadah Daftar Kartu Keranjang Dinamis -->
            <div id="cartListContainer"></div>

            <!-- Area Checkout Bawah Kanan -->
            <div id="cartFooterArea" class="d-flex justify-content-end align-items-center mt-4">
                <button type="button" class="btn-cart-checkout" onclick="proceedCheckout()">Checkout</button>
            </div>

        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/js/navbar-auth.js"></script>
    <script>
        const userKey = '<?= $userKey ?>';
        const cartStorageKey = 'indofx_cart_' + userKey;

        // Ambil data keranjang khusus akun aktif
        function getCartData() {
            try {
                return JSON.parse(localStorage.getItem(cartStorageKey)) || [];
            } catch (e) {
                return [];
            }
        }

        // Render tampilan keranjang secara dinamis
        function renderCart() {
            const listContainer = document.getElementById('cartListContainer');
            const footerArea = document.getElementById('cartFooterArea');
            const items = getCartData();

            // KONDISI 1: Keranjang Kosong (misal akun baru)
            if (items.length === 0) {
                if (footerArea) footerArea.style.display = 'none';
                listContainer.innerHTML = `
                    <div class="empty-cart-card">
                        <i class="bi bi-cart-x text-dark fs-1 mb-2"></i>
                        <h5 class="fw-bold text-dark mb-1">Keranjang Belanja Anda Kosong</h5>
                        <p class="text-muted small m-0 mb-3">
                            Temukan aset visual pilihan untuk game Anda di <a href="/catalog/explore" class="text-dark fw-bold text-decoration-none">Katalog</a>
                        </p>
                        <a href="/catalog/explore" class="btn btn-hero-orange">Jelajahi Aset</a>
                    </div>
                `;
                return;
            }

            // KONDISI 2: Ada aset di dalam keranjang
            if (footerArea) footerArea.style.display = 'flex';

            listContainer.innerHTML = items.map(item => {
                const artSvg = item.art ? ('cart-art-' + item.art) : 'cart-art-castle';
                const pipelines = Array.isArray(item.pipelines) ? item.pipelines : ['HDRP', 'URP', 'Built-In'];

                const badgesHtml = pipelines.map(p => {
                    const pLower = p.toLowerCase();
                    let cls = 'badge-urp';
                    if (pLower.includes('hdrp')) cls = 'badge-hdrp';
                    if (pLower.includes('built')) cls = 'badge-builtin';
                    return `<span class="badge-tag-custom ${cls}">${p}</span>`;
                }).join(' ');

                return `
                    <div class="cart-item-card" id="cart-item-${item.id}">
                        <!-- 1. Thumbnail Gambar Sesuai Mockup -->
                        <div class="cart-item-thumb">
                            <svg class="w-100 h-100 d-block" viewBox="0 0 175 98">
                                <use href="#${artSvg}" />
                            </svg>
                        </div>

                        <!-- 2. Informasi Aset -->
                        <div class="cart-item-content">
                            <div class="cart-status-line">
                                <span class="cart-status-badge">DIBELI</span>
                                <span class="text-muted ms-2">Perolehan: ${item.date || '15 Jan 2026'}</span>
                            </div>

                            <div class="d-flex align-items-center flex-wrap gap-2 mb-1">
                                <h5 class="cart-asset-title m-0">${item.title}</h5>
                                <div class="d-flex gap-1">${badgesHtml}</div>
                            </div>

                            <p class="cart-asset-creator m-0">Oleh ${item.creator || 'Arga Studio'}</p>
                        </div>

                        <!-- 3. Checkbox Biru & Tombol Hapus Merah -->
                        <div class="d-flex align-items-center gap-3">
                            <input type="checkbox" class="cart-checkbox" checked value="${item.id}">
                            <button type="button" class="btn-cart-delete" onclick="removeCartItem('${item.id}')">
                                Hapus
                            </button>
                        </div>
                    </div>
                `;
            }).join('');
        }

        function proceedCheckout() {
            const items = getCartData();
            if (items.length === 0) {
                alert('Keranjang Anda kosong!');
                return;
            }

            // 1. Ambil ID aset yang kotak centangnya (checkbox) aktif
            const checkedBoxes = document.querySelectorAll('.cart-checkbox:checked');
            if (checkedBoxes.length === 0) {
                alert('Pilih minimal satu aset yang ingin di-checkout!');
                return;
            }

            const selectedIds = Array.from(checkedBoxes).map(cb => cb.value);
            const checkoutItems = items.filter(item => selectedIds.includes(String(item.id)));

            // 2. Simpan item yang dipilih ke sessionStorage untuk dibaca oleh checkout/pembayaran
            sessionStorage.setItem('indofx_checkout_items', JSON.stringify(checkoutItems));

            // 3. Pindah ke halaman checkout
            window.location.href = '/checkout';
        }

        document.addEventListener('DOMContentLoaded', () => renderCart());
    </script>
</body>

</html>