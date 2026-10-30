<?php
if (!isset($_SESSION['user_id'])) {
    header('Location: /login');
    exit;
}

$userName  = (string) ($_SESSION['name'] ?? 'Pengguna');
$userEmail = (string) ($_SESSION['email'] ?? 'user@email.com');

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
    <title>Riwayat Transaksi &amp; Invoice - IndoFX</title>

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

        .navbar-history {
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

        .transaction-table-card {
            background-color: #ffffff;
            border: 1.5px solid #2B2129;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(43, 33, 41, 0.04);
        }

        .table-custom {
            margin-bottom: 0;
            font-size: 0.85rem;
        }

        .table-custom thead th {
            background-color: #f8f6f4;
            color: #555555;
            font-weight: 700;
            font-size: 0.8rem;
            padding: 12px 16px;
            border-bottom: 1.5px solid #2B2129;
        }

        .table-custom tbody td {
            padding: 14px 16px;
            vertical-align: middle;
            color: #1a1a1a;
            border-bottom: 1px solid #eee5df;
        }

        .table-custom tbody tr:last-child td {
            border-bottom: none;
        }

        .badge-status {
            font-weight: 700;
            font-size: 0.72rem;
            padding: 4px 10px;
            border-radius: 6px;
            display: inline-block;
        }

        .badge-berhasil {
            background-color: #38a169;
            color: #ffffff;
        }

        .badge-pending {
            background-color: #ed8936;
            color: #ffffff;
        }

        .badge-gagal {
            background-color: #e53e3e;
            color: #ffffff;
        }

        .link-invoice {
            color: #0d6efd;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
        }

        .link-invoice:hover {
            text-decoration: underline;
        }

        .invoice-card {
            background-color: #ffffff;
            border: 1.5px solid #2B2129;
            border-radius: 8px;
            padding: 24px;
            box-shadow: 0 4px 14px rgba(43, 33, 41, 0.04);
        }

        .invoice-title {
            font-size: 1.25rem;
            font-weight: 800;
            color: #1A1A1A;
            letter-spacing: -0.3px;
        }

        .invoice-code {
            font-size: 0.95rem;
            font-weight: 700;
            color: #1a1a1a;
            margin-top: 8px;
        }

        .invoice-date {
            font-size: 0.75rem;
            color: #666666;
            margin-bottom: 16px;
        }

        .invoice-meta-label {
            font-size: 0.68rem;
            font-weight: 700;
            color: #888888;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        .invoice-meta-val {
            font-size: 0.85rem;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 1px;
        }

        .invoice-meta-sub {
            font-size: 0.78rem;
            color: #555555;
            margin-bottom: 14px;
        }

        .invoice-total-text {
            font-size: 0.95rem;
            font-weight: 700;
            color: #1a1a1a;
        }

        .invoice-total-amount {
            font-size: 1.15rem;
            font-weight: 800;
            color: #0b72b9;
        }

        .btn-print-invoice {
            background-color: #000000;
            color: #ffffff;
            font-size: 0.85rem;
            font-weight: 700;
            border: none;
            border-radius: 6px;
            padding: 10px;
            width: 100%;
            transition: opacity 0.15s ease;
        }

        .btn-print-invoice:hover {
            opacity: 0.85;
            color: #ffffff;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-history sticky-top">
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
            <h1 class="page-title mb-4">Riwayat Transaksi</h1>

            <div class="row g-4 align-items-start">
                <div class="col-12 col-lg-8">
                    <div class="transaction-table-card">
                        <div class="table-responsive">
                            <table class="table table-custom">
                                <thead>
                                    <tr>
                                        <th>No. Invoice</th>
                                        <th>Nama Aset</th>
                                        <th>Tanggal</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="fw-bold">INV-20260115-0001</td>
                                        <td>Volumetric Rain &amp; Storm URP</td>
                                        <td>15 Jan 2026</td>
                                        <td class="fw-bold">Rp150.000</td>
                                        <td><span class="badge-status badge-berhasil">Berhasil</span></td>
                                        <td><a href="javascript:void(0)" class="link-invoice">Lihat Invoice</a></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">INV-20260112-0004</td>
                                        <td>Stylized Fire Starter Kit</td>
                                        <td>12 Jan 2026</td>
                                        <td class="fw-bold">Gratis</td>
                                        <td><span class="badge-status badge-berhasil">Berhasil</span></td>
                                        <td><a href="javascript:void(0)" class="link-invoice">Lihat Invoice</a></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">INV-20260115-0001</td>
                                        <td>Volumetric Rain &amp; Storm URP</td>
                                        <td>15 Jan 2026</td>
                                        <td class="fw-bold">Rp150.000</td>
                                        <td><span class="badge-status badge-pending">Pending</span></td>
                                        <td><a href="javascript:void(0)" class="link-invoice">Lihat Invoice</a></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">INV-20260115-0001</td>
                                        <td>Volumetric Rain &amp; Storm URP</td>
                                        <td>15 Jan 2026</td>
                                        <td class="fw-bold">Rp150.000</td>
                                        <td><span class="badge-status badge-gagal">Gagal</span></td>
                                        <td><a href="javascript:void(0)" class="link-invoice">Lihat Invoice</a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-4">
                    <div class="invoice-card">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h2 class="invoice-title mb-0">INVOICE</h2>
                            <span class="badge-status badge-berhasil">Berhasil</span>
                        </div>

                        <div class="invoice-code">INV-20260115-0001</div>
                        <div class="invoice-date">Tanggal: 15 Januari 2026, 14:22 WIB</div>

                        <hr class="my-3 text-muted">

                        <div class="invoice-meta-label">PEMBELI</div>
                        <div class="invoice-meta-val"><?= htmlspecialchars($userName, ENT_QUOTES, 'UTF-8') ?></div>
                        <div class="invoice-meta-sub"><?= htmlspecialchars($userEmail, ENT_QUOTES, 'UTF-8') ?></div>

                        <div class="invoice-meta-label">METODE PEMBAYARAN</div>
                        <div class="invoice-meta-sub text-dark fw-bold mb-3">QRIS (GoPay)</div>

                        <hr class="my-3 text-muted">

                        <div class="d-flex justify-content-between text-muted small fw-bold mb-1">
                            <span>Item</span>
                            <span>Harga</span>
                        </div>
                        <div class="d-flex justify-content-between small fw-bold mb-3">
                            <span>Volumetric Rain &amp; Storm URP</span>
                            <span>Rp150.000</span>
                        </div>

                        <div class="d-flex justify-content-between align-items-center pt-2 mb-4">
                            <span class="invoice-total-text">Total Pembayaran</span>
                            <span class="invoice-total-amount">Rp150.000</span>
                        </div>

                        <button type="button" class="btn-print-invoice" onclick="window.print()">Cetak Invoice</button>
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