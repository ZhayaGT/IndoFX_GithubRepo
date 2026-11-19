<?php
$isLoggedIn = isset($_SESSION['user_id']);
$userKey    = (string) ($_SESSION['user_id'] ?? $_SESSION['name'] ?? 'default_user');
$userName   = (string) ($_SESSION['name'] ?? $_SESSION['user_name'] ?? 'Pengguna');
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Transaksi Penjualan - IndoFX</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS & Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/dashboard.css">
</head>

<body class="dashboard-body">

    <!-- 1. Header Navbar Oranye Dashboard -->
    <nav class="navbar navbar-orange sticky-top">
        <div class="container-fluid px-4 d-flex justify-content-between align-items-center">

            <!-- Logo IndoFX Kiri -->
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

            <!-- Profil Kreator Kanan Dinamis -->
            <a href="/" class="d-flex align-items-center gap-2 text-white text-decoration-none" title="Kembali ke Beranda">
                <span class="avatar-circle" id="navDashAvatar">AS</span>
                <span class="fw-bold fs-7 tracking-wide" id="navDashStoreName">ARGA STUDIO</span>
            </a>

        </div>
    </nav>

    <!-- 2. Kontainer Utama -->
    <div class="dashboard-layout-container">

        <!-- SIDEBAR KIRI (PUTIH) -->
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

        <!-- KONTEN KANAN -->
        <main class="dashboard-main-content">

            <!-- Header Judul -->
            <div class="mb-4">
                <h2 class="dash-page-title mb-1">Riwayat Transaksi Penjualan</h2>
                <p class="text-muted small m-0">Daftar seluruh transaksi pembelian aset Anda oleh pembeli lokal.</p>
            </div>

            <!-- 3 Stat Cards Dinamis dengan Garis Aksen Kiri -->
            <div class="row g-3 mb-4">
                <!-- Card 1: Total Pendapatan (Aksen Hijau) -->
                <div class="col-12 col-md-4">
                    <div class="stat-card stat-card-stripe stripe-green p-3 h-100">
                        <span class="stat-card-title d-block mb-1">Total Pendapatan</span>
                        <div class="stat-card-value text-stat-green mb-1" id="statTotalIncome">Rp0</div>
                        <span class="stat-card-sub text-muted">Sudah termasuk potongan fee platform</span>
                    </div>
                </div>

                <!-- Card 2: Aset Terjual (Aksen Biru) -->
                <div class="col-12 col-md-4">
                    <div class="stat-card stat-card-stripe stripe-blue p-3 h-100">
                        <span class="stat-card-title d-block mb-1">Aset Terjual</span>
                        <div class="stat-card-value text-stat-blue mb-1" id="statTotalOrders">0 Transaksi</div>
                        <span class="stat-card-sub text-muted">Status: Midtrans Settlement</span>
                    </div>
                </div>

                <!-- Card 3: Rata-rata Pendapatan (Aksen Kuning) -->
                <div class="col-12 col-md-4">
                    <div class="stat-card stat-card-stripe stripe-yellow p-3 h-100">
                        <span class="stat-card-title d-block mb-1">Rata-rata Pendapatan</span>
                        <div class="stat-card-value text-stat-yellow mb-1" id="statAvgIncome">Rp0</div>
                        <span class="stat-card-sub text-muted">Per Aset Berbayar</span>
                    </div>
                </div>
            </div>

            <!-- Bar Filter Transaksi Responsif -->
            <div class="filter-bar-container mb-4">
                <div class="row g-2 align-items-center">
                    <!-- Search Input -->
                    <div class="col-12 col-md-5">
                        <div class="search-input-box">
                            <i class="bi bi-search"></i>
                            <input type="text" id="filterKeyword" class="form-control filter-input" placeholder="Cari No.Invoice atau Judul Aset.....">
                        </div>
                    </div>
                    <!-- Date Picker -->
                    <div class="col-12 col-sm-6 col-md-3">
                        <input type="date" id="filterDate" class="form-control filter-input">
                    </div>
                    <!-- Dropdown Metode Pembayaran -->
                    <div class="col-10 col-sm-5 col-md-3">
                        <select class="form-select filter-select" id="filterPayment">
                            <option value="all" selected>Semua Metode Pembayaran</option>
                            <option value="qris">QRIS / GoPay / E-wallet</option>
                            <option value="card">Kartu Kredit / Debit</option>
                            <option value="bank">Virtual Account / Bank</option>
                        </select>
                    </div>
                    <!-- Tombol Cari -->
                    <div class="col-2 col-sm-1 col-md-1 text-end">
                        <button class="btn btn-filter-submit w-100" type="button" id="btnApplyFilter">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tabel Riwayat Transaksi -->
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
                        <tbody id="transactionsTableBody">
                            <!-- Diisi dinamis dari LocalStorage -->
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>

    <!-- 3. Footer Minimal -->
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

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const userKey = '<?= $userKey ?>';
        let storeName = 'Arga Studio';
        let allTransactions = [];

        document.addEventListener('DOMContentLoaded', function() {
            // 1. Identifikasi Profil Penjual
            const profileRaw = localStorage.getItem('indofx_seller_profile_' + userKey);
            if (profileRaw) {
                try {
                    const prof = JSON.parse(profileRaw);
                    if (prof.store_name) storeName = prof.store_name;
                } catch (e) {}
            }

            document.getElementById('navDashStoreName').innerText = storeName.toUpperCase();
            const words = storeName.trim().split(/\s+/);
            let inits = '';
            words.slice(0, 2).forEach(w => inits += w.charAt(0).toUpperCase());
            document.getElementById('navDashAvatar').innerText = inits || 'AS';

            // 2. Ambil Transaksi Penjual
            loadTransactions();

            // 3. Listener Filter
            document.getElementById('filterKeyword').addEventListener('input', renderTransactions);
            document.getElementById('filterDate').addEventListener('change', renderTransactions);
            document.getElementById('filterPayment').addEventListener('change', renderTransactions);
            document.getElementById('btnApplyFilter').addEventListener('click', renderTransactions);
        });

        function loadTransactions() {
            // Kunci spesifik penjual: indofx_seller_sales_[userKey] atau nama toko yang dinormalisasi
            const cleanKey = storeName.toLowerCase().replace(/[^a-z0-9]/g, '');
            const rawSales = localStorage.getItem('indofx_seller_sales_' + userKey) ||
                localStorage.getItem('indofx_seller_sales_' + cleanKey) ||
                localStorage.getItem('indofx_seller_sales_' + storeName);

            if (rawSales) {
                try {
                    allTransactions = JSON.parse(rawSales);
                } catch (e) {
                    allTransactions = [];
                }
            } else {
                // Jika penjual belum memiliki transaksi penjualan dari pembeli
                allTransactions = [];
            }

            updateSummaryCards(allTransactions);
            renderTransactions();
        }

        function updateSummaryCards(data) {
            const totalOrders = data.length;
            let totalIncome = 0;

            data.forEach(item => {
                const price = parseFloat(item.price) || 0;
                totalIncome += price;
            });

            const avgIncome = totalOrders > 0 ? Math.round(totalIncome / totalOrders) : 0;

            document.getElementById('statTotalIncome').innerText = 'Rp ' + totalIncome.toLocaleString('id-ID');
            document.getElementById('statTotalOrders').innerText = totalOrders + ' Transaksi';
            document.getElementById('statAvgIncome').innerText = 'Rp ' + avgIncome.toLocaleString('id-ID');
        }

        function renderTransactions() {
            const tbody = document.getElementById('transactionsTableBody');
            const keyword = document.getElementById('filterKeyword').value.toLowerCase().trim();
            const selectedDate = document.getElementById('filterDate').value; // Format YYYY-MM-DD
            const paymentFilter = document.getElementById('filterPayment').value;

            const filtered = allTransactions.filter(item => {
                const matchKeyword = !keyword ||
                    (item.invoice_no && item.invoice_no.toLowerCase().includes(keyword)) ||
                    (item.asset_title && item.asset_title.toLowerCase().includes(keyword));

                let matchDate = true;
                if (selectedDate && item.date_raw) {
                    matchDate = item.date_raw.startsWith(selectedDate);
                }

                let matchPayment = true;
                if (paymentFilter !== 'all') {
                    const payStr = (item.payment_method || '').toLowerCase();
                    if (paymentFilter === 'qris') matchPayment = payStr.includes('qris') || payStr.includes('gopay') || payStr.includes('shopee') || payStr.includes('ewallet');
                    if (paymentFilter === 'card') matchPayment = payStr.includes('kartu') || payStr.includes('card') || payStr.includes('visa');
                    if (paymentFilter === 'bank') matchPayment = payStr.includes('bank') || payStr.includes('virtual') || payStr.includes('bill') || payStr.includes('bca') || payStr.includes('mandiri');
                }

                return matchKeyword && matchDate && matchPayment;
            });

            if (filtered.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-receipt fs-2 d-block mb-2 text-secondary"></i>
                            <h6 class="fw-bold mb-1">Belum Ada Transaksi Penjualan</h6>
                            <p class="small text-muted mb-0">Transaksi pembelian aset berbayar Anda oleh pengguna lain akan otomatis muncul di sini.</p>
                        </td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = filtered.map(item => {
                const price = parseFloat(item.price) || 0;
                return `
                    <tr>
                        <td>
                            <a href="javascript:void(0)" class="action-link fw-semibold">${item.invoice_no}</a>
                        </td>
                        <td class="fw-bold text-dark item-asset-title">${item.asset_title}</td>
                        <td class="text-secondary small">${item.payment_method || 'QRIS/GoPay'}</td>
                        <td class="text-muted small">${item.date || 'Hari ini'}</td>
                        <td>
                            <span class="badge-status-approved">Settlement</span>
                        </td>
                        <td class="fw-bold text-success-income">Rp${price.toLocaleString('id-ID')}</td>
                        <td class="text-center">
                            <a href="javascript:window.print()" class="text-dark print-btn-icon" title="Cetak Bukti Transaksi">
                                <i class="bi bi-printer fs-5"></i>
                            </a>
                        </td>
                    </tr>
                `;
            }).join('');
        }
    </script>
</body>

</html>