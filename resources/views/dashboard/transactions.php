<?php
$storeName    = $storeName ?? 'Toko Saya';
$initials     = $initials ?? 'AS';
$transactions = $transactions ?? [];
$income       = $income ?? 0;
$count        = $count ?? 0;
$average      = $average ?? 0;

$keyword     = trim((string) ($_GET['q'] ?? ''));
$paymentType = trim((string) ($_GET['payment_type'] ?? ''));
$date        = trim((string) ($_GET['date'] ?? ''));
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Transaksi Penjualan - IndoFX</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/dashboard.css">
</head>

<body class="dashboard-body">

    <nav class="navbar navbar-orange sticky-top">
        <div class="container-fluid px-4 d-flex justify-content-between align-items-center">
            <a class="navbar-brand m-0 d-flex align-items-center gap-2 text-decoration-none" href="/">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2L20.66 7V17L12 22L3.34 17V7L12 2Z" fill="#ED6B1D" stroke="#1A1A1A" stroke-width="1.8" stroke-linejoin="round" />
                    <path d="M12 2L20.66 7L12 12L3.34 7L12 2Z" fill="#FDBA74" stroke="#1A1A1A" stroke-width="1.5" stroke-linejoin="round" />
                    <path d="M12 12V22" stroke="#1A1A1A" stroke-width="1.5" />
                    <path d="M12 12L20.66 7" stroke="#1A1A1A" stroke-width="1.5" />
                    <path d="M12 12L3.34 7" stroke="#1A1A1A" stroke-width="1.5" />
                    <path d="M12 4.5L16.5 7L12 9.5L7.5 7L12 4.5Z" fill="#FED7AA" stroke="#1A1A1A" stroke-width="1" stroke-linejoin="round" />
                </svg>
                <span class="brand-text">IndoFX</span>
            </a>

            <a href="/" class="d-flex align-items-center gap-2 text-white text-decoration-none" title="Kembali ke Beranda">
                <span class="avatar-circle"><?= htmlspecialchars($initials, ENT_QUOTES, 'UTF-8') ?></span>
                <span class="fw-bold fs-7 tracking-wide"><?= htmlspecialchars(mb_strtoupper($storeName), ENT_QUOTES, 'UTF-8') ?></span>
            </a>
        </div>
    </nav>

    <div class="dashboard-layout-container">

        <aside class="dashboard-sidebar">
            <ul class="nav flex-column sidebar-nav-list">
                <li class="nav-item">
                    <a href="/dashboard" class="sidebar-link">
                        <i class="bi bi-grid-fill"></i>
                        <span>Dashboard Utama</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/dashboard/assets" class="sidebar-link">
                        <i class="bi bi-stack"></i>
                        <span>Semua Aset Saya</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/dashboard/transactions" class="sidebar-link active">
                        <i class="bi bi-receipt"></i>
                        <span>Riwayat Transaksi</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/dashboard/profile" class="sidebar-link">
                        <i class="bi bi-person"></i>
                        <span>Profil Penjual</span>
                    </a>
                </li>
            </ul>
        </aside>

        <main class="dashboard-main-content">

            <div class="mb-4">
                <h2 class="dash-page-title mb-1">Riwayat Transaksi Penjualan</h2>
                <p class="text-muted small m-0">Daftar seluruh transaksi pembelian aset Anda oleh pembeli lokal.</p>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-12 col-md-4">
                    <div class="stat-card stat-card-stripe stripe-green p-3 h-100">
                        <span class="stat-card-title d-block mb-1">Total Pendapatan</span>
                        <div class="stat-card-value text-stat-green mb-1"><?= format_rupiah_amount((int) $income) ?></div>
                        <span class="stat-card-sub text-muted">Sudah termasuk potongan fee platform</span>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="stat-card stat-card-stripe stripe-blue p-3 h-100">
                        <span class="stat-card-title d-block mb-1">Aset Terjual</span>
                        <div class="stat-card-value text-stat-blue mb-1"><?= (int) $count ?> Transaksi</div>
                        <span class="stat-card-sub text-muted">Status: Midtrans Settlement</span>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="stat-card stat-card-stripe stripe-yellow p-3 h-100">
                        <span class="stat-card-title d-block mb-1">Rata-rata Pendapatan</span>
                        <div class="stat-card-value text-stat-yellow mb-1"><?= format_rupiah_amount((int) $average) ?></div>
                        <span class="stat-card-sub text-muted">Per Aset Berbayar</span>
                    </div>
                </div>
            </div>

            <form class="filter-bar-container mb-4" method="get" action="/dashboard/transactions">
                <div class="row g-2 align-items-center">
                    <div class="col-12 col-md-5">
                        <div class="search-input-box">
                            <i class="bi bi-search"></i>
                            <input type="text" name="q" class="form-control filter-input" placeholder="Cari No.Invoice atau Judul Aset....."
                                value="<?= htmlspecialchars($keyword, ENT_QUOTES, 'UTF-8') ?>">
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <input type="date" name="date" class="form-control filter-input" value="<?= htmlspecialchars($date, ENT_QUOTES, 'UTF-8') ?>">
                    </div>
                    <div class="col-10 col-sm-5 col-md-3">
                        <select class="form-select filter-select" name="payment_type">
                            <option value="">Semua Metode Pembayaran</option>
                            <option value="qris" <?= $paymentType === 'qris' ? 'selected' : '' ?>>QRIS / GoPay / E-wallet</option>
                            <option value="credit_card" <?= $paymentType === 'credit_card' ? 'selected' : '' ?>>Kartu Kredit / Debit</option>
                            <option value="bank_transfer" <?= $paymentType === 'bank_transfer' ? 'selected' : '' ?>>Virtual Account / Bank</option>
                        </select>
                    </div>
                    <div class="col-2 col-sm-1 col-md-1 text-end">
                        <button class="btn btn-filter-submit w-100" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
            </form>

            <?php
            // Filter diterapkan di PHP karena daftar transaksi satu penjual selalu kecil.
            $rows = array_values(array_filter($transactions, static function (array $row) use ($keyword, $paymentType, $date): bool {
                if ($keyword !== ''
                    && stripos((string) $row['invoice_number'], $keyword) === false
                    && stripos((string) $row['asset_title'], $keyword) === false) {
                    return false;
                }
                if ($paymentType !== '' && (string) $row['payment_type'] !== $paymentType) {
                    return false;
                }
                if ($date !== '' && !str_starts_with((string) ($row['paid_at'] ?? $row['created_at']), $date)) {
                    return false;
                }

                return true;
            }));
            ?>

            <div class="table-card-wrapper">
                <div class="table-responsive">
                    <table class="table table-custom align-middle mb-0">
                        <thead>
                            <tr>
                                <th scope="col" style="width: 18%;">No.Invoice</th>
                                <th scope="col" style="width: 27%;">Aset Terjual</th>
                                <th scope="col" style="width: 18%;">Metode Pembayaran</th>
                                <th scope="col" style="width: 18%;">Tanggal Transaksi</th>
                                <th scope="col" style="width: 9%;">Status</th>
                                <th scope="col">Total Pendapatan</th>
                                <th scope="col" class="text-center" style="width: 6%;">Cetak</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($rows === []): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="bi bi-receipt fs-2 d-block mb-2 text-secondary"></i>
                                        <h6 class="fw-bold mb-1">Belum Ada Transaksi Penjualan</h6>
                                        <p class="small text-muted mb-0">Transaksi pembelian aset berbayar Anda oleh pengguna lain akan otomatis muncul di sini.</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($rows as $row): ?>
                                    <tr>
                                        <td><span class="action-link fw-semibold"><?= htmlspecialchars($row['invoice_number'], ENT_QUOTES, 'UTF-8') ?></span></td>
                                        <td class="fw-bold text-dark item-asset-title"><?= htmlspecialchars($row['asset_title'], ENT_QUOTES, 'UTF-8') ?></td>
                                        <td class="text-secondary small"><?= htmlspecialchars((string) ($row['payment_type'] ?: 'QRIS/GoPay'), ENT_QUOTES, 'UTF-8') ?></td>
                                        <td class="text-muted small"><?= date('d M Y', strtotime((string) ($row['paid_at'] ?? $row['created_at']))) ?></td>
                                        <td><span class="badge-status-approved"><?= htmlspecialchars(ucfirst((string) $row['status']), ENT_QUOTES, 'UTF-8') ?></span></td>
                                        <td class="fw-bold text-success-income"><?= format_rupiah_amount((int) $row['amount']) ?></td>
                                        <td class="text-center">
                                            <a href="javascript:window.print()" class="text-dark print-btn-icon" title="Cetak Bukti Transaksi">
                                                <i class="bi bi-printer fs-5"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>

    <footer class="footer-main" id="tentang">
        <div class="container-figma">
            <div class="footer-center-content">
                <h5 class="footer-brand-title-center">IndoFX</h5>
                <p class="footer-brand-desc-center">
                    Portal distribusi dan marketplace aset CG &amp; VFX berkualitas tinggi buatan lokal, dioptimalkan sepenuhnya untuk industri game Indonesia.
                </p>
            </div>
            <div class="footer-bottom-bar">
                <span>&copy; 2026 IndoFX. Hak Cipta Dilindungi Undang-Undang.</span>
                <span>Dibuat dengan bangga untuk Game Developer Indonesia</span>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>