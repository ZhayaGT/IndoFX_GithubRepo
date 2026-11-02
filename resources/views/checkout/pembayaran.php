<?php
if (!isset($_SESSION['user_id'])) {
    header('Location: /login');
    exit;
}

$userName = (string) ($_SESSION['name'] ?? 'Pengguna');

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

        .item-thumb-img {
            width: 78px;
            height: 52px;
            border-radius: 4px;
            object-fit: cover;
            border: 1px solid #2B2129;
            background-color: #3b2e39;
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

        .payment-row:hover {
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
            color: #ffffff;
            font-weight: 800;
            font-size: 1rem;
            border: none;
            border-radius: 6px;
            padding: 12px;
            width: 100%;
            text-align: center;
            text-decoration: none;
            display: block;
            transition: background-color 0.15s ease;
        }

        .btn-buy:hover {
            background-color: #d95d12;
            color: #ffffff;
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
                    <div class="order-summary-box">
                        <?php for ($i = 0; $i < 6; $i++): ?>
                            <div class="order-summary-item d-flex align-items-center justify-content-between flex-wrap gap-2">
                                <div class="d-flex align-items-center gap-3">
                                    <img src="https://images.unsplash.com/photo-1518709268805-4e9042af9f23?w=200&auto=format&fit=crop&q=60"
                                        alt="Aset" class="item-thumb-img">
                                    <div>
                                        <div class="d-flex align-items-center gap-2 flex-wrap">
                                            <span class="item-name">Volumetric Rain &amp; Storm URP</span>
                                            <span class="badge-pipeline-tag tag-urp">URP</span>
                                            <span class="badge-pipeline-tag tag-hdrp">HDRP</span>
                                            <span class="badge-pipeline-tag tag-builtin">Built-in</span>
                                        </div>
                                        <p class="item-author">Oleh Arga Studio</p>
                                    </div>
                                </div>
                                <div class="item-price">Rp250.000</div>
                            </div>
                        <?php endfor; ?>
                    </div>

                    <div class="section-subtitle">Pilih Metode Pembayaran</div>
                    <div class="payment-method-box">
                        <label class="payment-row">
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

                        <label class="payment-row">
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

                        <label class="payment-row">
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
                            <span class="fw-bold text-dark">Rp250.000</span>
                        </div>
                        <div class="detail-line">
                            <span>Biaya Layanan</span>
                            <span class="fw-bold text-dark">Rp2.500</span>
                        </div>
                        <div class="detail-line">
                            <span>Diskon</span>
                            <span class="fw-bold text-dark">- Rp0</span>
                        </div>

                        <div class="detail-line-total">
                            <span>Total Bayar</span>
                            <span class="fs-5">Rp502.500</span>
                        </div>

                        <div class="status-waiting">
                            <span class="status-dot"></span>
                            <span>Menunggu Pembayaran</span>
                        </div>

                        <!-- Klik Beli menuju halaman Invoice -->
                        <a href="/invoice" class="btn-buy">Beli</a>

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
        document.addEventListener('DOMContentLoaded', function() {
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