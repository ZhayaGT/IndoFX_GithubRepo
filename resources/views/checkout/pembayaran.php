<?php
if (!isset($_SESSION['user_id'])) {
    header('Location: /login');
    exit;
}

$userName = (string) ($_SESSION['name'] ?? $_SESSION['user_name'] ?? 'Pengguna');
$userKey  = (string) ($_SESSION['user_id'] ?? $_SESSION['name'] ?? 'default_user');
$userEmail = (string) ($_SESSION['email'] ?? ($userName . '@gmail.com'));

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
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">

    <style>
        body {
            background-color: var(--ifx-cream-bg, #FAF0E6);
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

        /* CARD METODE PEMBAYARAN KHUSUS QRIS PERSIS FIGMA image_df847a */
        .qris-payment-card {
            background-color: #ffffff;
            border: 1.5px solid #2B2129;
            border-radius: 8px;
            padding: 14px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 12px rgba(43, 33, 41, 0.04);
            margin-bottom: 24px;
        }

        .qris-label-text {
            font-size: 0.95rem;
            font-weight: 700;
            color: #1A1A1A;
            margin: 0;
        }

        /* BADGE LOGO QRIS SESUAI GAMBAR ACUAN image_df6923 */
        .qris-official-badge {
            background-color: #FAF2E8;
            border: 1.5px solid #D5C7BB;
            border-radius: 10px;
            padding: 6px 14px;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            user-select: none;
        }

        .qris-badge-text {
            display: flex;
            flex-direction: column;
            line-height: 1.25;
            text-align: left;
        }

        .qris-badge-text span {
            color: #1A1A1A !important;
            font-size: 11px !important;
            font-weight: 600 !important;
            letter-spacing: -0.1px;
            font-family: 'Inter', -apple-system, sans-serif !important;
        }

        /* Detail Pembayaran Kanan */
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

        /* FOOTER PUTIH SOLID PENUH PERSIS GAMBAR image_df71bc */
        .footer-white-solid {
            background-color: #ffffff !important;
            border-top: 1px solid #ebdcd1;
            padding: 48px 0 24px 0;
            width: 100%;
            margin-top: auto;
        }

        .footer-white-brand {
            font-size: 1.25rem;
            font-weight: 800;
            color: #1A1A1A;
            margin-bottom: 6px;
        }

        .footer-white-desc {
            font-size: 0.85rem;
            color: #3C2A21;
            margin-bottom: 8px;
        }

        .footer-white-contact {
            font-size: 0.85rem;
            color: #1A1A1A;
            margin-bottom: 46px;
        }

        .footer-white-contact strong {
            font-weight: 800;
        }

        .footer-white-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.8rem;
            color: #1A1A1A;
            flex-wrap: wrap;
            gap: 12px;
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

    <!-- Header Navbar Oranye -->
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
                    <?php
                    $checkoutItems = is_array($checkoutItems ?? null) ? $checkoutItems : [];
                    $checkoutId = static function (array $item): string {
                        return (string)($item['public_id'] ?? $item['asset_public_id'] ?? $item['asset_id'] ?? $item['id'] ?? '');
                    };
                    $checkoutSubtotal = 0.0;
                    foreach ($checkoutItems as $item) $checkoutSubtotal += (float)($item['price'] ?? 0);
                    $checkoutFee = (float)($checkoutFee ?? 0);
                    $checkoutTotal = $checkoutSubtotal + $checkoutFee;
                    ?>
                    <div class="section-subtitle">Ringkasan Pesanan</div>
                    <div class="order-summary-box">
                        <?php if ($checkoutItems === []): ?>
                            <div class="p-4 text-muted">Tidak ada aset untuk diproses. <a href="/cart">Kembali ke keranjang</a></div>
                        <?php else: ?>
                            <?php foreach ($checkoutItems as $item):
                                $itemId = $checkoutId($item);
                                $pipelines = $item['render_pipeline'] ?? $item['pipelines'] ?? [];
                                $pipelines = is_array($pipelines) ? $pipelines : [$pipelines];
                            ?>
                                <div class="order-summary-item d-flex align-items-center justify-content-between flex-wrap gap-2">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="item-thumb-box"><img src="<?= htmlspecialchars((string)($item['image_url'] ?? $item['image'] ?? '/assets/img/placeholder.png'), ENT_QUOTES, 'UTF-8') ?>" class="w-100 h-100" style="object-fit:cover" alt=""></div>
                                        <div><div class="d-flex align-items-center gap-2 flex-wrap"><span class="item-name"><?= htmlspecialchars((string)($item['title'] ?? 'Aset IndoFX'), ENT_QUOTES, 'UTF-8') ?></span>
                                            <?php foreach ($pipelines as $pipeline): ?><span class="badge-pipeline-tag tag-urp"><?= htmlspecialchars((string)$pipeline, ENT_QUOTES, 'UTF-8') ?></span><?php endforeach; ?></div>
                                            <p class="item-author">Oleh <?= htmlspecialchars((string)($item['seller_name'] ?? $item['creator'] ?? 'Studio Kreator'), ENT_QUOTES, 'UTF-8') ?></p>
                                        </div>
                                    </div>
                                    <div class="item-price">Rp<?= number_format((float)($item['price'] ?? 0), 0, ',', '.') ?></div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <div class="section-subtitle">Pilih Metode Pembayaran</div>
                    <div class="qris-payment-card"><span class="qris-label-text">Bayar dengan QRIS</span><strong>QRIS</strong></div>
                </div>

                <div class="col-12 col-lg-4">
                    <form method="post" action="/checkout/process" class="detail-payment-card">
                        <div class="detail-payment-title">Detail Pembayaran</div>
                        <?php foreach ($checkoutItems as $item): ?><input type="hidden" name="asset_ids[]" value="<?= htmlspecialchars($checkoutId($item), ENT_QUOTES, 'UTF-8') ?>"><?php endforeach; ?>
                        <input type="hidden" name="payment_method" value="qris">
                        <div class="detail-line"><span>Subtotal Aset</span><span class="fw-bold text-dark">Rp<?= number_format($checkoutSubtotal, 0, ',', '.') ?></span></div>
                        <div class="detail-line"><span>Biaya Layanan</span><span class="fw-bold text-dark">Rp<?= number_format($checkoutFee, 0, ',', '.') ?></span></div>
                        <div class="detail-line"><span>Diskon</span><span class="fw-bold text-dark">- Rp0</span></div>
                        <div class="detail-line-total"><span>Total Bayar</span><span class="fs-5">Rp<?= number_format($checkoutTotal, 0, ',', '.') ?></span></div>
                        <div class="status-waiting"><span class="status-dot"></span><span>Menunggu Pembayaran</span></div>
                        <button type="submit" class="btn-buy" <?= $checkoutItems === [] ? 'disabled' : '' ?>>Beli</button>
                        <p class="payment-disclaimer">Catatan: Anda akan diarahkan ke gerbang pembayaran aman Midtrans.</p>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <!-- FOOTER SOLID PUTIH PENUH PERSIS GAMBAR image_df71bc -->
    <footer class="footer-white-solid">
        <div class="container-figma text-center">
            <h5 class="footer-white-brand">IndoFX</h5>
            <p class="footer-white-desc">
                Portal distribusi dan marketplace aset CG &amp; VFX berkualitas tinggi buatan lokal, dioptimalkan sepenuhnya untuk industri game Indonesia.
            </p>
            <div class="footer-white-contact">
                <strong>Kontak dan Email:</strong>
                <span class="ms-3 me-4">08123456xxxx</span>
                <span>helpdesk@indofx.com</span>
            </div>

            <div class="footer-white-bottom">
                <span>&copy; 2026 IndoFX. Hak Cipta Dilindungi Undang-Undang.</span>
                <span>Dibuat dengan bangga untuk Game Developer Indonesia</span>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dropdownBtn = document.getElementById('profileDropdownBtn');
            const dropdownMenu = document.getElementById('profileDropdownMenu');
            if (!dropdownBtn || !dropdownMenu) return;
            dropdownBtn.addEventListener('click', function(event) {
                event.stopPropagation();
                const isHidden = dropdownMenu.hasAttribute('hidden');
                dropdownMenu.toggleAttribute('hidden', !isHidden);
                dropdownBtn.setAttribute('aria-expanded', String(isHidden));
            });
            document.addEventListener('click', function(event) {
                if (!dropdownMenu.contains(event.target) && !dropdownBtn.contains(event.target)) {
                    dropdownMenu.setAttribute('hidden', '');
                    dropdownBtn.setAttribute('aria-expanded', 'false');
                }
            });
        });
    </script>
</body>
</html>