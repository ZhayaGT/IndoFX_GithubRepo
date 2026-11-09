<?php
if (!isset($_SESSION['user_id'])) {
    header('Location: /login');
    exit;
}

$userName = (string) ($_SESSION['name'] ?? $_SESSION['user_name'] ?? 'Pengguna');
$userKey  = (string) ($_SESSION['user_id'] ?? $_SESSION['name'] ?? 'default_user');
$userEmail = (string) ($_SESSION['email'] ?? ($userName . '@gmail.com'));
$directAssetId = $_GET['asset_id'] ?? $_GET['id'] ?? null;

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
    <title>Selesaikan Pembayaran - IndoFX</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">

    <style>
        body {
            background-color: var(--ifx-cream-bg, #FDECE2);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            font-family: 'Inter', sans-serif;
        }

        .navbar-checkout {
            background-color: #ED6B1D;
            padding: 12px 0;
            position: relative;
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

        .page-title {
            font-size: 1.85rem;
            font-weight: 800;
            color: #1A1A1A;
            letter-spacing: -0.5px;
        }

        .section-subtitle {
            font-size: 0.95rem;
            font-weight: 800;
            color: #1A1A1A;
            margin-bottom: 12px;
        }

        .order-summary-box {
            background-color: #ffffff;
            border: 1.5px solid #2B2129;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(43, 33, 41, 0.04);
            margin-bottom: 24px;
        }

        .order-summary-item {
            padding: 12px 18px;
            border-bottom: 1px solid #eee5df;
        }

        .order-summary-item:last-child {
            border-bottom: none;
        }

        .item-thumb-box {
            width: 78px;
            height: 52px;
            border-radius: 4px;
            border: 1px solid #2B2129;
            background-color: #3b2e39;
            overflow: hidden;
            flex-shrink: 0;
        }

        .item-thumb-box svg {
            width: 100%;
            height: 100%;
            display: block;
        }

        .item-name {
            font-size: 0.92rem;
            font-weight: 800;
            color: #1a1a1a;
            margin-bottom: 2px;
        }

        .item-author {
            font-size: 0.76rem;
            color: #555555;
            margin-bottom: 0;
        }

        .item-price {
            font-size: 0.95rem;
            font-weight: 800;
            color: #1a1a1a;
        }

        .badge-pipeline-tag {
            font-size: 0.62rem;
            font-weight: 700;
            padding: 1px 6px;
            border-radius: 3px;
            color: #ffffff;
            display: inline-block;
        }

        .tag-hdrp {
            background-color: #503d6d;
        }

        .tag-urp {
            background-color: #a82828;
        }

        .tag-builtin {
            background-color: #0b72b9;
        }

        .payment-method-box {
            background-color: #ffffff;
            border: 1.5px solid #2B2129;
            border-radius: 8px;
            padding: 18px 20px;
            box-shadow: 0 4px 12px rgba(43, 33, 41, 0.04);
        }

        .payment-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 14px;
            border: 1px solid #e2d7cf;
            border-radius: 6px;
            margin-bottom: 10px;
            background-color: #ffffff;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .payment-row:last-child {
            margin-bottom: 0;
        }

        .payment-row:hover,
        .payment-row.selected {
            border-color: #ED6B1D;
            background-color: #fff9f6;
        }

        .payment-label {
            font-size: 0.88rem;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 0;
        }

        .payment-logos {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .logo-badge {
            font-size: 0.72rem;
            font-weight: 800;
            padding: 2px 8px;
            border-radius: 4px;
            border: 1px solid #d0c0b6;
            background-color: #ffffff;
            color: #2b2129;
        }

        .detail-payment-card {
            background-color: #ffffff;
            border: 1.5px solid #2B2129;
            border-radius: 8px;
            padding: 24px;
            box-shadow: 0 4px 14px rgba(43, 33, 41, 0.04);
        }

        .detail-payment-title {
            font-size: 1.15rem;
            font-weight: 800;
            color: #1A1A1A;
            margin-bottom: 18px;
        }

        .detail-line {
            display: flex;
            justify-content: space-between;
            font-size: 0.85rem;
            color: #4A3B32;
            margin-bottom: 8px;
        }

        .detail-line-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.95rem;
            font-weight: 800;
            color: #1a1a1a;
            border-top: 1.5px solid #2B2129;
            padding-top: 14px;
            margin-top: 14px;
            margin-bottom: 12px;
        }

        .status-waiting {
            font-size: 0.78rem;
            font-weight: 600;
            color: #0b72b9;
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 18px;
        }

        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #0b72b9;
            display: inline-block;
        }

        .btn-buy {
            background-color: #ED6B1D;
            color: #ffffff !important;
            font-weight: 800;
            font-size: 1rem;
            border: none;
            border-radius: 6px;
            padding: 12px;
            width: 100%;
            text-align: center;
            text-decoration: none;
            display: block;
            cursor: pointer;
            transition: background-color 0.15s ease;
        }

        .btn-buy:hover {
            background-color: #d95d12;
        }

        .payment-disclaimer {
            font-size: 0.7rem;
            color: #555555;
            line-height: 1.4;
            margin-top: 14px;
            margin-bottom: 0;
        }
    </style>
</head>

<body>

    <svg class="d-none">
        <defs>
            <g id="chk-art-castle">
                <rect width="175" height="98" fill="#241E24" />
                <ellipse cx="87" cy="55" rx="75" ry="25" fill="#433742" opacity="0.5" />
                <polygon points="45,25 35,90 55,90" fill="#151115" />
                <polygon points="87,15 72,95 102,95" fill="#100D10" />
                <polygon points="130,22 120,90 140,90" fill="#151115" />
                <circle cx="87" cy="38" r="4" fill="#C4B5A5" opacity="0.8" />
            </g>
            <g id="chk-art-water">
                <rect width="175" height="98" fill="#14232C" />
                <ellipse cx="87" cy="55" rx="75" ry="25" fill="#234657" opacity="0.5" />
                <polygon points="45,25 35,90 55,90" fill="#0C171E" />
                <polygon points="87,15 72,95 102,95" fill="#081015" />
                <polygon points="130,22 120,90 140,90" fill="#0C171E" />
                <circle cx="87" cy="38" r="4" fill="#64B5F6" opacity="0.8" />
            </g>
            <g id="chk-art-fire">
                <rect width="175" height="98" fill="#2D1A14" />
                <ellipse cx="87" cy="55" rx="75" ry="25" fill="#572B1B" opacity="0.5" />
                <polygon points="45,25 35,90 55,90" fill="#190E0A" />
                <polygon points="87,15 72,95 102,95" fill="#110906" />
                <polygon points="130,22 120,90 140,90" fill="#190E0A" />
                <circle cx="87" cy="38" r="4" fill="#FF8A65" opacity="0.8" />
            </g>
        </defs>
    </svg>

    <nav class="navbar navbar-checkout sticky-top">
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
                <a href="/cart" class="text-white text-decoration-none d-flex align-items-center" title="Keranjang">
                    <i class="bi bi-cart3 fs-4"></i>
                </a>

                <div class="profile-dropdown-wrapper">
                    <button type="button" class="btn-profile-trigger" id="profileDropdownBtn" aria-expanded="false">
                        <span class="avatar-circle-header"><?= htmlspecialchars($initials, ENT_QUOTES, 'UTF-8') ?></span>
                        <span class="fw-bold fs-6"><?= htmlspecialchars($userName, ENT_QUOTES, 'UTF-8') ?></span>
                    </button>

                    <div class="profile-custom-menu" id="profileDropdownMenu">
                        <a href="/creator/profile?me=1" class="profile-custom-item fw-bold">
                            <?= htmlspecialchars($userName, ENT_QUOTES, 'UTF-8') ?>
                        </a>
                        <a href="/dashboard/ubah-profil" class="profile-custom-item">
                            Edit Profile
                        </a>
                        <a href="/invoice" class="profile-custom-item">
                            Histori Pembelian
                        </a>
                        <a href="/#tentang" class="profile-custom-item">
                            Dukungan
                        </a>
                        <a href="/logout" class="profile-custom-item text-danger">
                            Keluar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-grow-1 py-4">
        <div class="container-figma">
            <h1 class="page-title mb-4">Selesaikan Pembayaran</h1>

            <div class="row g-4 align-items-start">
                <div class="col-12 col-lg-8">
                    <div class="section-subtitle">Ringkasan Pesanan</div>
                    <div class="order-summary-box" id="orderSummaryContainer"></div>

                    <div class="section-subtitle">Pilih Metode Pembayaran</div>
                    <div class="payment-method-box">
                        <label class="payment-row selected" onclick="choosePayment(this)">
                            <div class="d-flex align-items-center gap-2">
                                <input type="radio" name="payment_method" value="ewallet" class="form-check-input m-0" checked>
                                <span class="payment-label">E-wallet</span>
                            </div>
                            <div class="payment-logos">
                                <span class="logo-badge" style="color: #00a5cf;">gopay</span>
                                <span class="logo-badge" style="color: #e53e3e;">QRIS</span>
                                <span class="logo-badge" style="color: #ee4d2d;">ShopeePay</span>
                                <span class="logo-badge" style="color: #118eea;">DANA</span>
                                <span class="logo-badge" style="color: #4285f4;">GPay</span>
                            </div>
                        </label>

                        <label class="payment-row" onclick="choosePayment(this)">
                            <div class="d-flex align-items-center gap-2">
                                <input type="radio" name="payment_method" value="card" class="form-check-input m-0">
                                <span class="payment-label">Bayar dengan kartu</span>
                            </div>
                            <div class="payment-logos">
                                <span class="logo-badge" style="color: #1a1f71;">VISA</span>
                                <span class="logo-badge" style="color: #eb001b;">Mastercard</span>
                                <span class="logo-badge" style="color: #0079be;">JCB</span>
                                <span class="logo-badge" style="color: #002663;">AMEX</span>
                            </div>
                        </label>

                        <label class="payment-row" onclick="choosePayment(this)">
                            <div class="d-flex align-items-center gap-2">
                                <input type="radio" name="payment_method" value="bank" class="form-check-input m-0">
                                <span class="payment-label">Transfer Bank</span>
                            </div>
                            <div class="payment-logos">
                                <span class="logo-badge" style="color: #005eac;">BCA</span>
                                <span class="logo-badge" style="color: #f15a24;">BNI</span>
                                <span class="logo-badge" style="color: #00529c;">BRIVA</span>
                                <span class="logo-badge" style="color: #003d79;">Mandiri</span>
                                <span class="logo-badge" style="color: #008139;">Permata</span>
                                <span class="logo-badge" style="color: #7b2182;">CIMB</span>
                                <span class="logo-badge" style="color: #00a39d;">BSI</span>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="col-12 col-lg-4">
                    <div class="detail-payment-card">
                        <div class="detail-payment-title">Detail Pembayaran</div>

                        <div class="detail-line">
                            <span>Subtotal Aset</span>
                            <span class="fw-bold text-dark" id="calcSubtotal">Rp0</span>
                        </div>
                        <div class="detail-line">
                            <span>Biaya Layanan</span>
                            <span class="fw-bold text-dark" id="calcAdmin">Rp2.500</span>
                        </div>
                        <div class="detail-line">
                            <span>Diskon</span>
                            <span class="fw-bold text-dark">- Rp0</span>
                        </div>

                        <div class="detail-line-total">
                            <span>Total Bayar</span>
                            <span class="fs-5" id="calcTotal">Rp0</span>
                        </div>

                        <div class="status-waiting">
                            <span class="status-dot"></span>
                            <span>Menunggu Pembayaran</span>
                        </div>

                        <button type="button" id="btnSubmitPayment" class="btn-buy" onclick="handlePaymentSubmit()">Beli</button>

                        <p class="payment-disclaimer">
                            Catatan: Anda akan diarahkan ke gerbang pembayaran aman Midtrans untuk simulasi transfer (Sandbox/Simulasi Medis).
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="footer-creator-center py-5 mt-auto">
        <div class="container text-center">
            <h5 class="fw-bold mb-2 text-dark">IndoFX</h5>
            <p class="text-muted small mb-3 mx-auto" style="max-width: 540px;">
                Portal distribusi dan marketplace aset CG &amp; VFX berkualitas tinggi buatan lokal, dioptimalkan sepenuhnya untuk industri game Indonesia.
            </p>
            <div class="d-flex justify-content-between align-items-center text-muted small pt-3 border-top flex-wrap gap-2">
                <span>&copy; 2026 IndoFX. Hak Cipta Dilindungi Undang-Undang.</span>
                <span>Dibuat dengan bangga untuk Game Developer Indonesia</span>
            </div>
        </div>
    </footer>

    <script>
        const userKey = '<?= $userKey ?>';
        const directAssetId = '<?= htmlspecialchars((string)$directAssetId, ENT_QUOTES, 'UTF-8') ?>';

        const catalogDB = {
            '1': {
                id: '1',
                title: 'Volumetric Rain & Storm URP',
                creator: 'Arga Studio',
                price: 150000,
                art: 'castle',
                pipelines: ['URP', 'HDRP', 'Built-in']
            },
            '2': {
                id: '2',
                title: 'Stylized Toon Water Shader',
                creator: 'Nusantara Digital',
                price: 75000,
                art: 'water',
                pipelines: ['URP', 'Built-in']
            },
            '3': {
                id: '3',
                title: 'Explosion VFX Pack Vol. 1',
                creator: 'Bayu Pratama',
                price: 200000,
                art: 'fire',
                pipelines: ['HDRP']
            },
            '4': {
                id: '4',
                title: 'Lowpoly Indonesian Village Pack',
                creator: 'Citra CG',
                price: 350000,
                art: 'castle',
                pipelines: ['URP', 'HDRP', 'Built-in']
            }
        };

        let activeItems = [];
        const feeAdmin = 2500;

        function loadCheckoutData() {
            const urlParams = new URLSearchParams(window.location.search);
            const paramTitle = urlParams.get('title');
            const paramPrice = urlParams.get('price');
            const paramCreator = urlParams.get('creator');
            const paramPipeline = urlParams.get('pipeline');

            // 1. PRIORITAS UTAMA: Jika URL membawa parameter lengkap (dari klik Beli di detail.php)
            if (paramTitle) {
                let cleanPrice = 0;
                if (paramPrice) {
                    cleanPrice = parseFloat(paramPrice.replace(/[^0-9]/g, '')) || 0;
                }
                const pipes = paramPipeline ? [paramPipeline] : ['Built-in'];

                activeItems = [{
                    id: directAssetId || ('asset-' + Date.now()),
                    title: paramTitle,
                    creator: paramCreator || detectCreatorByTitle(paramTitle) || 'Studio Kreator',
                    price: cleanPrice,
                    art: 'castle',
                    pipelines: pipes
                }];
                renderOrderSummary();
                return;
            }

            // 2. PRIORITAS KEDUA: Cek di database katalog publik LocalStorage
            if (directAssetId) {
                try {
                    const publicCatalog = JSON.parse(localStorage.getItem('indofx_public_catalog')) || [];
                    const found = publicCatalog.find(item => String(item.id) === String(directAssetId));
                    if (found) {
                        let cleanPrice = 0;
                        if (typeof found.price === 'number') {
                            cleanPrice = found.price;
                        } else if (typeof found.price === 'string') {
                            cleanPrice = parseFloat(found.price.replace(/[^0-9]/g, '')) || 0;
                        }

                        activeItems = [{
                            id: found.id,
                            title: found.title,
                            creator: found.creator || found.seller || detectCreatorByTitle(found.title) || 'Studio Kreator',
                            price: cleanPrice,
                            art: found.category === 'Shader' ? 'water' : 'castle',
                            pipelines: Array.isArray(found.pipelines) ? found.pipelines : [found.render_pipeline || 'Built-in']
                        }];
                        renderOrderSummary();
                        return;
                    }
                } catch (e) {}

                if (catalogDB[directAssetId]) {
                    activeItems = [catalogDB[directAssetId]];
                    renderOrderSummary();
                    return;
                }
            }

            // 3. PRIORITAS KETIGA: Cek dari keranjang checkout (sessionStorage)
            try {
                activeItems = JSON.parse(sessionStorage.getItem('indofx_checkout_items')) || [];
            } catch (e) {
                activeItems = [];
            }

            if (activeItems.length === 0) {
                activeItems = [catalogDB['1']];
            }

            renderOrderSummary();
        }

        // Fungsi pendeteksi otomatis pemilik aset berdasarkan database lokal toko
        function detectCreatorByTitle(title) {
            let foundOwner = '';
            Object.keys(localStorage).forEach(k => {
                if (k.startsWith('indofx_seller_assets_')) {
                    try {
                        const items = JSON.parse(localStorage.getItem(k)) || [];
                        const matched = items.find(it => it.title === title);
                        if (matched) {
                            const suffix = k.replace('indofx_seller_assets_', '');
                            const prof = JSON.parse(localStorage.getItem('indofx_seller_profile_' + suffix) || '{}');
                            foundOwner = prof.store_name || suffix;
                        }
                    } catch (e) {}
                }
            });
            return foundOwner;
        }

        function renderOrderSummary() {
            const container = document.getElementById('orderSummaryContainer');
            let subtotal = 0;

            container.innerHTML = activeItems.map(item => {
                const price = parseFloat(item.price) || 0;
                subtotal += price;

                const artSvg = item.art ? ('chk-art-' + item.art) : 'chk-art-castle';
                const pipelines = Array.isArray(item.pipelines) ? item.pipelines : ['URP', 'HDRP', 'Built-in'];

                const badgeHtml = pipelines.map(p => {
                    let cls = 'tag-urp';
                    if (p.toLowerCase().includes('hdrp')) cls = 'tag-hdrp';
                    if (p.toLowerCase().includes('built')) cls = 'tag-builtin';
                    return `<span class="badge-pipeline-tag ${cls}">${p}</span>`;
                }).join(' ');

                return `
                    <div class="order-summary-item d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div class="d-flex align-items-center gap-3">
                            <div class="item-thumb-box">
                                <svg viewBox="0 0 175 98"><use href="#${artSvg}" /></svg>
                            </div>
                            <div>
                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                    <span class="item-name">${item.title}</span>
                                    ${badgeHtml}
                                </div>
                                <p class="item-author">Oleh ${item.creator || 'Arga Studio'}</p>
                            </div>
                        </div>
                        <div class="item-price">Rp${price.toLocaleString('id-ID')}</div>
                    </div>
                `;
            }).join('');

            const grandTotal = subtotal + feeAdmin;

            document.getElementById('calcSubtotal').innerText = 'Rp' + subtotal.toLocaleString('id-ID');
            document.getElementById('calcTotal').innerText = 'Rp' + grandTotal.toLocaleString('id-ID');
        }

        function choosePayment(el) {
            document.querySelectorAll('.payment-row').forEach(r => {
                r.classList.remove('selected');
                const radio = r.querySelector('input[type="radio"]');
                if (radio) radio.checked = false;
            });
            el.classList.add('selected');
            const radio = el.querySelector('input[type="radio"]');
            if (radio) radio.checked = true;
        }

        // PROSES PEMBAYARAN CERDAS: Otomatis mendeteksi toko pemilik aset & mencatat penjualan
        function handlePaymentSubmit() {
            const btn = document.getElementById('btnSubmitPayment');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses Pembayaran...';

            setTimeout(() => {
                // 1. Simpan semua item yang dibeli ke Library akun pembeli aktif
                const libKey = 'indofx_library_' + userKey;
                let currentLibrary = [];
                try {
                    currentLibrary = JSON.parse(localStorage.getItem(libKey)) || [];
                } catch (e) {
                    currentLibrary = [];
                }

                activeItems.forEach(item => {
                    const exists = currentLibrary.some(l => String(l.id) === String(item.id) || l.title === item.title);
                    if (!exists) {
                        currentLibrary.unshift({
                            id: item.id,
                            title: item.title,
                            creator: item.creator,
                            type: 'Berbayar',
                            date: '<?= date("d M Y") ?>',
                            pipelines: item.pipelines || ['URP', 'HDRP', 'Built-in'],
                            art: item.art || 'castle'
                        });
                    }
                });
                localStorage.setItem(libKey, JSON.stringify(currentLibrary));

                // 2. Data Waktu & No Invoice Unik
                const now = new Date();
                const randNum = Math.floor(1000 + Math.random() * 9000);
                const yyyy = now.getFullYear();
                const mm = String(now.getMonth() + 1).padStart(2, '0');
                const dd = String(now.getDate()).padStart(2, '0');
                const invoiceNo = `INV-${yyyy}${mm}${dd}-${randNum}`;

                const selectedRadio = document.querySelector('input[name="payment_method"]:checked')?.value || 'ewallet';
                let paymentMethodLabel = 'QRIS/GoPay';
                if (selectedRadio === 'card') paymentMethodLabel = 'Kartu Kredit / Debit';
                if (selectedRadio === 'bank') paymentMethodLabel = 'BCA Virtual Account';

                const subtotal = activeItems.reduce((acc, curr) => acc + (parseFloat(curr.price) || 0), 0);
                const totalBayar = subtotal + feeAdmin;

                const monthsIndoShort = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                const monthsIndo = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                const fullDateStr = `${now.getDate()} ${monthsIndo[now.getMonth()]} ${yyyy}, ${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')} WIB`;
                const shortDateStr = `${now.getDate()} ${monthsIndoShort[now.getMonth()]} ${yyyy}, ${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')}`;
                const rawDateStr = `${yyyy}-${mm}-${dd}`;

                // 3. Simpan Riwayat Transaksi ke Invoice Pembeli
                const txKey = 'indofx_transactions_' + userKey;
                let transactions = [];
                try {
                    transactions = JSON.parse(localStorage.getItem(txKey)) || [];
                } catch (e) {
                    transactions = [];
                }

                const newTransaction = {
                    invoice_no: invoiceNo,
                    full_date: fullDateStr,
                    short_date: shortDateStr,
                    buyer_name: '<?= htmlspecialchars($userName, ENT_QUOTES, 'UTF-8') ?>',
                    buyer_email: '<?= htmlspecialchars($userEmail, ENT_QUOTES, 'UTF-8') ?>',
                    payment_method: paymentMethodLabel,
                    status: 'Berhasil',
                    items: activeItems.map(i => ({
                        id: i.id,
                        title: i.title,
                        price: parseFloat(i.price) || 0
                    })),
                    subtotal: subtotal,
                    fee: feeAdmin,
                    total: totalBayar
                };

                transactions.unshift(newTransaction);
                localStorage.setItem(txKey, JSON.stringify(transactions));

                // 4. SMART SELLER ATTRIBUTION: Cari pemilik aset di seluruh LocalStorage & catat penjualan
                activeItems.forEach(item => {
                    const priceVal = parseFloat(item.price) || 0;
                    const saleRecord = {
                        invoice_no: invoiceNo,
                        asset_id: item.id,
                        asset_title: item.title,
                        price: priceVal,
                        payment_method: paymentMethodLabel,
                        date: shortDateStr,
                        date_raw: rawDateStr,
                        status: 'Settlement',
                        buyer_name: '<?= htmlspecialchars($userName, ENT_QUOTES, 'UTF-8') ?>'
                    };

                    let targetKeys = new Set();

                    // Dari kreator eksplisit jika ada
                    if (item.creator) {
                        targetKeys.add('indofx_seller_sales_' + item.creator.toLowerCase().replace(/[^a-z0-9]/g, ''));
                        targetKeys.add('indofx_seller_sales_' + item.creator.trim());
                    }

                    // Pindai semua aset toko penjual di sistem
                    Object.keys(localStorage).forEach(key => {
                        if (key.startsWith('indofx_seller_assets_')) {
                            try {
                                let sAssets = JSON.parse(localStorage.getItem(key)) || [];
                                const hasAsset = sAssets.some(sa => String(sa.id) === String(item.id) || sa.title === item.title);
                                if (hasAsset) {
                                    const sellerSuffix = key.replace('indofx_seller_assets_', '');
                                    targetKeys.add('indofx_seller_sales_' + sellerSuffix);
                                    targetKeys.add('indofx_seller_sales_' + sellerSuffix.toLowerCase().replace(/[^a-z0-9]/g, ''));

                                    // Periksa juga nama toko dari profil penjual tersebut
                                    const rawProf = localStorage.getItem('indofx_seller_profile_' + sellerSuffix);
                                    if (rawProf) {
                                        const p = JSON.parse(rawProf);
                                        if (p.store_name) {
                                            targetKeys.add('indofx_seller_sales_' + p.store_name.toLowerCase().replace(/[^a-z0-9]/g, ''));
                                            targetKeys.add('indofx_seller_sales_' + p.store_name.trim());
                                        }
                                    }

                                    // Perbarui jumlah unduhan/terjual langsung pada aset toko tersebut
                                    sAssets = sAssets.map(sa => {
                                        if (String(sa.id) === String(item.id) || sa.title === item.title) {
                                            const currentCount = parseInt(String(sa.downloads || '0').replace(/[^0-9]/g, ''), 10) || 0;
                                            sa.downloads = (currentCount + 1) + 'x Terjual';
                                        }
                                        return sa;
                                    });
                                    localStorage.setItem(key, JSON.stringify(sAssets));
                                }
                            } catch (e) {}
                        }
                    });

                    // Simpan ke seluruh kunci toko penjual terkait
                    targetKeys.forEach(tKey => {
                        let sales = [];
                        try {
                            sales = JSON.parse(localStorage.getItem(tKey)) || [];
                        } catch (e) {
                            sales = [];
                        }
                        const exists = sales.some(s => s.invoice_no === invoiceNo && s.asset_title === item.title);
                        if (!exists) {
                            sales.unshift(saleRecord);
                            localStorage.setItem(tKey, JSON.stringify(sales));
                        }
                    });
                });

                // 5. Hapus aset yang dibeli dari Keranjang belanja akun pembeli
                const cartKey = 'indofx_cart_' + userKey;
                try {
                    let currentCart = JSON.parse(localStorage.getItem(cartKey)) || [];
                    const boughtIds = activeItems.map(i => String(i.id));
                    currentCart = currentCart.filter(c => !boughtIds.includes(String(c.id)));
                    localStorage.setItem(cartKey, JSON.stringify(currentCart));
                } catch (e) {}

                // 6. Bersihkan sesi sementara checkout & arahkan ke halaman invoice
                sessionStorage.removeItem('indofx_checkout_items');
                window.location.href = '/invoice';
            }, 800);
        }

        document.addEventListener('DOMContentLoaded', function() {
            loadCheckoutData();

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
        });
    </script>
</body>

</html>