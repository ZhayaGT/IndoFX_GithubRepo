<?php
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: /login');
    exit;
}

$adminName = (string) ($_SESSION['name'] ?? 'Admin IndoFX');
$adminInitials = 'AP';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="admin-csrf" content="<?= htmlspecialchars($_SESSION['admin_csrf'], ENT_QUOTES, 'UTF-8') ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring Transaksi &amp; Midtrans Gateway - IndoFX</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/admin.css">
</head>

<body class="admin-body">

    <!-- 1. Header Navbar Oranye Admin -->
    <nav class="navbar navbar-admin sticky-top">
        <div class="navbar-admin-container">
            <a class="navbar-brand m-0 d-flex align-items-center gap-2 text-decoration-none" href="/admin/dashboard">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path d="M12 2L20.66 7V17L12 22L3.34 17V7L12 2Z" fill="#ED6B1D" stroke="#1A1A1A" stroke-width="1.8" stroke-linejoin="round" />
                    <path d="M12 2L20.66 7L12 12L3.34 7L12 2Z" fill="#FDBA74" stroke="#1A1A1A" stroke-width="1.5" stroke-linejoin="round" />
                    <path d="M12 12V22" stroke="#1A1A1A" stroke-width="1.5" />
                    <path d="M12 12L20.66 7" stroke="#1A1A1A" stroke-width="1.5" />
                    <path d="M12 12L3.34 7" stroke="#1A1A1A" stroke-width="1.5" />
                    <path d="M12 4.5L16.5 7L12 9.5L7.5 7L12 4.5Z" fill="#FED7AA" stroke="#1A1A1A" stroke-width="1" stroke-linejoin="round" />
                </svg>
                <span class="brand-text text-white fw-bold fs-6">IndoFX</span>
            </a>

            <div class="admin-dropdown-wrap">
                <button type="button" class="btn-admin-profile" id="adminDropdownBtn">
                    <span class="avatar-admin-badge"><?= htmlspecialchars($adminInitials) ?></span>
                    <span><?= htmlspecialchars($adminName) ?></span>
                </button>

                <div class="admin-dropdown-menu" id="adminDropdownMenu">
                    <a href="/logout" class="admin-logout-btn text-danger">Keluar</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- 2. Kontainer Utama -->
    <div class="admin-layout-container">
        <!-- Sidebar Kiri -->
        <aside class="admin-sidebar">
            <nav class="nav flex-column">
                <a href="/admin/dashboard" class="admin-sidebar-link">
                    <span><i class="bi bi-speedometer2 me-2"></i>Ringkasan Sistem</span>
                </a>
                <a href="/admin/assets" class="admin-sidebar-link">
                    <span><i class="bi bi-card-checklist me-2"></i>Moderasi Aset</span>
                    <span class="admin-sidebar-badge" id="sidebarModerationBadge">0</span>
                </a>
                <a href="/admin/categories" class="admin-sidebar-link">
                    <span><i class="bi bi-tags me-2"></i>Kelola Kategori</span>
                </a>
                <a href="/admin/users" class="admin-sidebar-link">
                    <span><i class="bi bi-people-fill me-2"></i>Kelola Pengguna</span>
                </a>
                <a href="/admin/seller-requests" class="admin-sidebar-link">
                    <span><i class="bi bi-person-badge me-2"></i>Pengajuan Penjual</span>
                    <span class="admin-sidebar-badge" id="sidebarSellerBadge">0</span>
                </a>
                <a href="/admin/transactions" class="admin-sidebar-link active">
                    <span><i class="bi bi-currency-dollar me-2"></i>Monitor Transaksi</span>
                </a>
            </nav>
        </aside>

        <!-- Konten Utama Kanan -->
        <main class="admin-main-content">
            <div class="d-flex justify-content-between align-items-center mb-1 flex-wrap gap-2">
                <h1 class="admin-page-title mb-0">Monitoring Transaksi &amp; Midtrans Gateway</h1>
                <button type="button" class="btn-export-csv" onclick="exportTransactionsCSV()">
                    <i class="bi bi-file-earmark-spreadsheet"></i> Ekspor Laporan (.CSV)
                </button>
            </div>
            <p class="admin-page-sub">Pantau seluruh aliran transaksi pembelian aset, status pembayaran Midtrans, dan bagi hasil platform.</p>

            <!-- 4 Kartu Metrik Keuangan 100% Dinamis dari Transaksi Riil -->
            <div class="row g-3 mb-4">
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="admin-stat-card">
                        <div class="admin-stat-label">Total Omset Gross</div>
                        <div class="admin-stat-val" id="statGrossOmset">Rp 0</div>
                        <div class="admin-stat-sub" id="statTrxSuccessCount">0 Transaksi Berhasil</div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="admin-stat-card">
                        <div class="admin-stat-label">Biaya Layanan Tercatat</div>
                        <div class="admin-stat-val val-green" id="statPlatformFee">Rp 0</div>
                        <div class="admin-stat-sub">Selisih total bayar dan harga aset</div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="admin-stat-card">
                        <div class="admin-stat-label">Menunggu Pembayaran</div>
                        <div class="admin-stat-val val-orange" id="statPendingCount">0 Transaksi</div>
                        <div class="admin-stat-sub">Status Midtrans Pending</div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="admin-stat-card">
                        <div class="admin-stat-label">Status Lainnya</div>
                        <div class="admin-stat-val val-red" id="statFailedCount">0 Transaksi</div>
                        <div class="admin-stat-sub">Termasuk kedaluwarsa, pembatalan, dan refund</div>
                    </div>
                </div>
            </div>

            <!-- Bar Filter -->
            <div class="row g-2 align-items-center admin-filter-bar mb-3">
                <div class="col-12 col-md-6">
                    <div class="admin-search-wrap">
                        <i class="bi bi-search admin-search-icon"></i>
                        <input type="text" id="trxSearchInput" class="form-control admin-search-input" placeholder="Cari Order ID, Pembeli, Penjual, atau Aset...">
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <select class="form-select admin-select" id="filterStatusMidtrans">
                        <option value="all" selected>Semua Status Midtrans</option>
                        <option value="Settlement">Settlement</option>
                        <option value="Pending">Pending</option>
                        <option value="Expire">Expire</option>
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <select class="form-select admin-select" id="filterPaymentMethod">
                        <option value="all" selected>Semua Metode</option>
                        <option value="QRIS">E-Wallet (QRIS / GoPay)</option>
                        <option value="Kartu Kredit">Kartu Kredit</option>
                        <option value="Virtual Account">Transfer Bank</option>
                    </select>
                </div>
            </div>

            <!-- Tabel Monitoring Transaksi -->
            <div class="admin-table-card">
                <div class="table-responsive">
                    <table class="table table-admin align-middle">
                        <thead>
                            <tr>
                                <th scope="col" style="width: 17%;">Order ID &amp; Waktu</th>
                                <th scope="col" style="width: 22%;">Aset &amp; Penjual</th>
                                <th scope="col" style="width: 17%;">Pembeli</th>
                                <th scope="col" style="width: 14%;">Metode</th>
                                <th scope="col" style="width: 12%;">Total Bayar</th>
                                <th scope="col" style="width: 10%;">Status Midtrans</th>
                                <th scope="col" class="text-end pe-4" style="width: 8%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="trxTableBody">
                            <!-- Diisi dinamis -->
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- 3. Modal Detail Log Transaksi & Gateway -->
    <div class="modal fade" id="modalTrxDetail" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 680px;">
            <div class="modal-content modal-category-card">
                <div class="modal-category-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="modal-category-title d-flex align-items-center gap-2">
                            <i class="bi bi-file-text"></i> Rincian Transaksi &amp; Respon Gateway
                        </h5>
                        <span class="text-muted small" id="modalOrderIdTitle">Order ID: -</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-category-body">
                    <div class="row g-3 mb-3">
                        <div class="col-12 col-md-6">
                            <div class="trx-modal-box">
                                <div class="trx-modal-box-title">
                                    <i class="bi bi-person"></i> INFORMASI PEMBELI &amp; ASET
                                </div>
                                <div class="trx-modal-row">
                                    <span class="trx-modal-row-label">Aset:</span>
                                    <span class="trx-modal-row-val" id="modalAssetTitle">-</span>
                                </div>
                                <div class="trx-modal-row">
                                    <span class="trx-modal-row-label">Penjual / Studio:</span>
                                    <span class="trx-modal-row-val" id="modalStudioName">-</span>
                                </div>
                                <div class="trx-modal-row">
                                    <span class="trx-modal-row-label">Pembeli:</span>
                                    <span class="trx-modal-row-val" id="modalBuyerName">-</span>
                                </div>
                                <div class="trx-modal-row">
                                    <span class="trx-modal-row-label">E-Mail Pembeli:</span>
                                    <span class="trx-modal-row-val text-muted" id="modalBuyerEmail">-</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="trx-modal-box">
                                <div class="trx-modal-box-title">
                                    <i class="bi bi-credit-card-2-front"></i> DETAIL PEMBAYARAN MIDTRANS
                                </div>
                                <div class="trx-modal-row">
                                    <span class="trx-modal-row-label">Waktu Transaksi:</span>
                                    <span class="trx-modal-row-val" id="modalTrxTime">-</span>
                                </div>
                                <div class="trx-modal-row">
                                    <span class="trx-modal-row-label">Metode Pembayaran:</span>
                                    <span class="trx-modal-row-val" id="modalTrxMethod">-</span>
                                </div>
                                <div class="trx-modal-row">
                                    <span class="trx-modal-row-label">Midtrans Ref ID:</span>
                                    <span class="trx-modal-row-val text-muted" id="modalMidtransRef">-</span>
                                </div>
                                <div class="trx-modal-row">
                                    <span class="trx-modal-row-label">Status Midtrans:</span>
                                    <span class="trx-modal-row-val" id="modalStatusBadge"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Revenue Split -->
                    <div class="trx-split-box mb-3">
                        <div class="trx-modal-box-title mb-2">
                            <i class="bi bi-receipt"></i> RINCIAN PEMBAGIAN HASIL (REVENUE SPLIT)
                        </div>
                        <div class="trx-split-row">
                            <span class="text-muted">Nilai Gross Transaksi (Harga Aset)</span>
                            <span class="fw-bold text-dark" id="modalGrossVal">Rp 0</span>
                        </div>
                        <div class="trx-split-row">
                            <span class="text-muted">Biaya Layanan Tercatat</span>
                            <span class="fw-bold text-danger" id="modalFeeVal">Rp 0</span>
                        </div>
                        <div class="trx-split-row total-row">
                            <span>Nett Penghasilan Bersih Penjual (90%)</span>
                            <span class="fs-6" id="modalNettVal">Rp 0</span>
                        </div>
                    </div>

                    <!-- Accordion Payload Raw JSON Sandbox -->
                    <div>
                        <button type="button" class="trx-raw-toggle" onclick="toggleJsonViewer()">
                            <i class="bi bi-code-slash"></i>
                            <span id="jsonToggleText">Lihat Data Transaksi Tersimpan &or;</span>
                        </button>
                        <pre class="trx-json-viewer" id="trxJsonViewer"><code></code></pre>
                    </div>
                </div>

                <div class="modal-category-footer">
                    <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn-modal-submit" onclick="printSimulatedInvoice()">
                        <i class="bi bi-printer"></i> Cetak Invoice Simulasi
                    </button>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer-main">
        <div class="container-figma">
            <div class="footer-center-content">
                <h5 class="footer-brand-title-center">IndoFX</h5>
                <p class="footer-brand-desc-center">
                    Portal distribusi dan marketplace aset CG &amp; VFX berkualitas tinggi buatan lokal, dioptimalkan sepenuhnya untuk industri game Indonesia.
                </p>
                <!-- Baris Kontak dan Email sesuai desain baru -->
                <div style="font-size: 0.85rem; color: #1a1a1a; margin-top: 10px;">
                    <strong style="font-weight: 800;">Kontak dan Email:</strong>
                    <span style="margin: 0 16px;">08123456xxxx</span>
                    <span>helpdesk@indofx.com</span>
                </div>
            </div>
            <div class="footer-bottom-bar">
                <span>&copy; 2026 IndoFX. Hak Cipta Dilindungi Undang-Undang.</span>
                <span>Dibuat dengan bangga untuk Game Developer Indonesia</span>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Helper escape output HTML -->
    <script src="/assets/js/indofx-utils.js"></script>
    <script src="/assets/js/admin-api.js"></script>

    <!-- ENGINE MONITORING TRANSAKSI DINAMIS 100% -->
    <script>
        let transactions = [];
        let modalTrxInstance = null;
        let currentActiveTrx = null;

        // 1. MUAT SELURUH TRANSAKSI RIIL WEBSITE
        async function loadTransactionsData() {
            try {
                transactions = (await AdminApi.data()).transactions;
                const paid = transactions.filter(t => t.status === 'Settlement');
                document.getElementById('statGrossOmset').textContent = 'Rp ' + paid.reduce((s,t) => s+t.total_bayar,0).toLocaleString('id-ID');
                document.getElementById('statPlatformFee').textContent = 'Rp ' + paid.reduce((s,t) => s+t.fee,0).toLocaleString('id-ID');
                document.getElementById('statTrxSuccessCount').textContent = paid.length + ' Transaksi Berhasil';
                document.getElementById('statPendingCount').textContent = transactions.filter(t => t.status === 'Pending').length + ' Transaksi';
                document.getElementById('statFailedCount').textContent = transactions.filter(t => !['Pending','Settlement'].includes(t.status)).length + ' Transaksi';
                const select = document.getElementById('filterStatusMidtrans');
                select.innerHTML = '<option value="all">Semua Status</option>';
                [...new Set(transactions.map(t => t.status))].forEach(status => select.add(new Option(status, status)));
                renderTransactionsTable();
            } catch (error) { AdminApi.showError(error); }
        }

        // 2. RENDER TABEL TRANSAKSI
        function renderTransactionsTable() {
            const tbody = document.getElementById('trxTableBody');
            const keyword = document.getElementById('trxSearchInput').value.toLowerCase().trim();
            const statusFilter = document.getElementById('filterStatusMidtrans').value;
            const methodFilter = document.getElementById('filterPaymentMethod').value;

            const filtered = transactions.filter(t => {
                const matchKeyword = !keyword ||
                    t.order_id.toLowerCase().includes(keyword) ||
                    t.asset_title.toLowerCase().includes(keyword) ||
                    t.seller.toLowerCase().includes(keyword) ||
                    t.buyer.toLowerCase().includes(keyword) ||
                    t.buyer_email.toLowerCase().includes(keyword);

                const matchStatus = statusFilter === 'all' || t.status === statusFilter;

                let matchMethod = true;
                if (methodFilter !== 'all') {
                    matchMethod = t.method.toLowerCase().includes(methodFilter.toLowerCase());
                }

                return matchKeyword && matchStatus && matchMethod;
            });

            if (filtered.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted small">
                            Tidak ada data transaksi yang cocok dengan kriteria pencarian.
                        </td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = filtered.map(item => {
                let statusBadge = '';
                if (item.status === 'Settlement' || item.status === 'Berhasil') {
                    statusBadge = `<span class="badge-midtrans-settlement"><i class="bi bi-check-circle"></i> Settlement</span>`;
                } else if (item.status === 'Pending') {
                    statusBadge = `<span class="badge-midtrans-pending"><i class="bi bi-clock"></i> Pending</span>`;
                } else {
                    statusBadge = `<span class="badge-midtrans-expire"><i class="bi bi-x-circle"></i> ${esc(item.database_status)}</span>`;
                }

                return `
                    <tr>
                        <td>
                            <div class="fw-bold text-dark cell-nowrap">${esc(item.order_id)}</div>
                            <div class="text-muted small" style="font-size: 0.73rem;">${esc(item.time)}</div>
                        </td>
                        <td>
                            <div class="fw-bold text-dark">${esc(item.asset_title)}</div>
                            <div class="text-muted small" style="font-size: 0.73rem;">Oleh: ${esc(item.seller)}</div>
                        </td>
                        <td>
                            <div class="fw-bold text-dark cell-nowrap">${esc(item.buyer)}</div>
                            <div class="text-muted small" style="font-size: 0.73rem;">${esc(item.buyer_email)}</div>
                        </td>
                        <td class="fw-bold text-dark cell-nowrap">${esc(item.method)}</td>
                        <td class="cell-nowrap">
                            <div class="fw-bold text-dark">Rp ${(item.total_bayar || 0).toLocaleString('id-ID')}</div>
                            <div class="text-muted small" style="font-size: 0.72rem;">Biaya Layanan: ${item.fee === null ? 'Belum diketahui' : 'Rp ' + item.fee.toLocaleString('id-ID')}</div>
                        </td>
                        <td>${statusBadge}</td>
                        <td class="text-end pe-4 cell-nowrap">
                            <button type="button" class="btn-detail-log" data-trx-order-id="${esc(item.order_id)}">
                                <i class="bi bi-file-earmark-text"></i> Detail Log
                            </button>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        // 3. BUKA MODAL DETAIL LOG TRANSAKSI
        function openTrxDetailModal(orderId) {
            const item = transactions.find(t => t.order_id === orderId);
            if (!item) return;

            currentActiveTrx = item;

            document.getElementById('modalOrderIdTitle').innerText = 'Order ID: ' + item.order_id;
            document.getElementById('modalAssetTitle').innerText = item.asset_title;
            document.getElementById('modalStudioName').innerText = item.seller;
            document.getElementById('modalBuyerName').innerText = item.buyer;
            document.getElementById('modalBuyerEmail').innerText = item.buyer_email;
            document.getElementById('modalTrxTime').innerText = item.time;
            document.getElementById('modalTrxMethod').innerText = item.method;
            document.getElementById('modalMidtransRef').innerText = item.ref_id;

            let badgeHtml = '';
            if (item.status === 'Settlement' || item.status === 'Berhasil') {
                badgeHtml = `<span class="badge-midtrans-settlement"><i class="bi bi-check-circle"></i> Settlement</span>`;
            } else if (item.status === 'Pending') {
                badgeHtml = `<span class="badge-midtrans-pending"><i class="bi bi-clock"></i> Pending</span>`;
            } else {
                badgeHtml = `<span class="badge-midtrans-expire"><i class="bi bi-x-circle"></i> ${esc(item.database_status)}</span>`;
            }
            document.getElementById('modalStatusBadge').innerHTML = badgeHtml;

            const gross = item.total_bayar || 0;
            const fee = item.fee ?? 0;
            const nett = gross - fee;

            document.getElementById('modalGrossVal').innerText = 'Rp ' + gross.toLocaleString('id-ID');
            document.getElementById('modalFeeVal').innerText = item.fee === null ? 'Belum diketahui' : 'Rp ' + fee.toLocaleString('id-ID');
            document.getElementById('modalNettVal').innerText = item.fee === null ? 'Belum diketahui' : 'Rp ' + nett.toLocaleString('id-ID');

            const jsonBox = document.getElementById('trxJsonViewer');
            jsonBox.classList.remove('show');
            document.getElementById('jsonToggleText').innerText = 'Lihat Data Transaksi Tersimpan \u2228';

            jsonBox.textContent = JSON.stringify(item.record, null, 2);

            modalTrxInstance.show();
        }

        // 4. TOGGLE RAW JSON
        function toggleJsonViewer() {
            const viewer = document.getElementById('trxJsonViewer');
            const txt = document.getElementById('jsonToggleText');
            if (viewer.classList.contains('show')) {
                viewer.classList.remove('show');
                txt.innerText = 'Lihat Data Transaksi Tersimpan \u2228';
            } else {
                viewer.classList.add('show');
                txt.innerText = 'Sembunyikan Data Transaksi Tersimpan \u2227';
            }
        }

        // 5. CETAK INVOICE SIMULASI
        function printSimulatedInvoice() {
            if (!currentActiveTrx) return;
            const printWindow = window.open('', '', 'width=750,height=600');
            printWindow.document.write(`
                <html>
                <head>
                    <title>Invoice - ${esc(currentActiveTrx.order_id)}</title>
                    <style>
                        body { font-family: sans-serif; padding: 30px; color: #2B2623; }
                        .header { border-bottom: 2px solid #ED6B1D; padding-bottom: 12px; margin-bottom: 20px; }
                        .title { font-size: 20px; font-weight: bold; color: #ED6B1D; }
                        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
                        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
                        th { background: #fdf3ec; }
                        .total { font-size: 16px; font-weight: bold; text-align: right; margin-top: 15px; }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <div class="title">INDOFX MARKETPLACE - INVOICE</div>
                        <div>No. Order: <strong>${esc(currentActiveTrx.order_id)}</strong></div>
                        <div>Tanggal: ${esc(currentActiveTrx.time)}</div>
                    </div>
                    <p><strong>Pembeli:</strong> ${esc(currentActiveTrx.buyer)} (${esc(currentActiveTrx.buyer_email)})</p>
                    <p><strong>Penjual:</strong> ${esc(currentActiveTrx.seller)}</p>
                    <p><strong>Metode Pembayaran:</strong> ${esc(currentActiveTrx.method)} (Ref: ${esc(currentActiveTrx.ref_id)})</p>
                    <p><strong>Status:</strong> ${esc(currentActiveTrx.status)}</p>
                    <table>
                        <thead>
                            <tr>
                                <th>Item Aset</th>
                                <th style="text-align: right;">Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>${esc(currentActiveTrx.asset_title)}</td>
                                <td style="text-align: right;">Rp ${(currentActiveTrx.total_bayar || 0).toLocaleString('id-ID')}</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="total">Total Bayar: Rp ${(currentActiveTrx.total_bayar || 0).toLocaleString('id-ID')}</div>
                </body>
                </html>
            `);
            printWindow.document.close();
            printWindow.focus();
            printWindow.print();
        }

        // 6. EKSPOR LAPORAN CSV
        function exportTransactionsCSV() {
            const cell = value => {
                let text = String(value ?? '');
                if (/^[=+@\-\t\r]/.test(text)) text = "'" + text;
                return '"' + text.replace(/"/g, '""') + '"';
            };
            const rows = [['Order ID','Waktu','Aset','Penjual','Pembeli','Email','Metode','Total Bayar','Biaya Layanan','Status','Ref ID']];
            transactions.forEach(t => rows.push([t.order_id,t.time,t.asset_title,t.seller,t.buyer,t.buyer_email,t.method,t.total_bayar,t.fee,t.database_status,t.ref_id]));
            const url = URL.createObjectURL(new Blob(['\uFEFF' + rows.map(r => r.map(cell).join(',')).join('\r\n')], {type:'text/csv;charset=utf-8'}));
            const link = document.createElement('a');
            link.href = url;
            link.download = 'Laporan_Transaksi_IndoFX.csv';
            link.click();
            URL.revokeObjectURL(url);
        }

        document.addEventListener('DOMContentLoaded', function() {
            modalTrxInstance = new bootstrap.Modal(document.getElementById('modalTrxDetail'));
            loadTransactionsData();

            // Delegasi tombol Detail Log transaksi
            document.addEventListener('click', function(event) {
                const logBtn = event.target.closest('[data-trx-order-id]');
                if (!logBtn) return;
                openTrxDetailModal(logBtn.getAttribute('data-trx-order-id'));
            });

            document.getElementById('trxSearchInput').addEventListener('input', renderTransactionsTable);
            document.getElementById('filterStatusMidtrans').addEventListener('change', renderTransactionsTable);
            document.getElementById('filterPaymentMethod').addEventListener('change', renderTransactionsTable);

            // Dropdown Profil Admin
            const btn = document.getElementById('adminDropdownBtn');
            const menu = document.getElementById('adminDropdownMenu');
            if (btn && menu) {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    menu.classList.toggle('show');
                });
                document.addEventListener('click', function(e) {
                    if (!menu.contains(e.target) && !btn.contains(e.target)) {
                        menu.classList.remove('show');
                    }
                });
            }
        });
    </script>
</body>

</html>