<?php
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: /login');
    exit;
}

$adminName = 'Super Admin IndoFX';
$adminInitials = 'AP';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring Transaksi &amp; Midtrans Gateway - IndoFX</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Stylesheet Admin -->
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/admin.css">
</head>

<body class="admin-body">

    <!-- 1. Header Navbar Oranye Admin Sesuai Figma -->
    <nav class="navbar navbar-admin sticky-top">
        <div class="navbar-admin-container">
            <!-- Brand Logo Kiri -->
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

            <!-- Profil Admin Kanan & Dropdown Menu Figma (Hanya Logout) -->
            <div class="admin-dropdown-wrap">
                <button type="button" class="btn-admin-profile" id="adminDropdownBtn">
                    <span class="avatar-admin-badge"><?= htmlspecialchars($adminInitials) ?></span>
                    <span><?= htmlspecialchars($adminName) ?></span>
                </button>

                <div class="admin-dropdown-menu" id="adminDropdownMenu">
                    <a href="/logout" class="admin-logout-btn text-danger">
                        Keluar
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- 2. Kontainer Layout Utama -->
    <div class="admin-layout-container">

        <!-- Sidebar Kiri Sesuai Figma image_1ecc7d -->
        <aside class="admin-sidebar">
            <nav class="nav flex-column">
                <a href="/admin/dashboard" class="admin-sidebar-link">
                    <span><i class="bi bi-speedometer2 me-2"></i>Ringkasan Sistem</span>
                </a>
                <a href="/admin/assets" class="admin-sidebar-link">
                    <span><i class="bi bi-card-checklist me-2"></i>Moderasi Aset</span>
                    <span class="admin-sidebar-badge">2</span>
                </a>
                <a href="/admin/categories" class="admin-sidebar-link">
                    <span><i class="bi bi-tags me-2"></i>Kelola Kategori</span>
                </a>
                <a href="/admin/users" class="admin-sidebar-link">
                    <span><i class="bi bi-people-fill me-2"></i>Kelola Pengguna</span>
                </a>
                <a href="/admin/seller-requests" class="admin-sidebar-link">
                    <span><i class="bi bi-person-badge me-2"></i>Pengajuan Penjual</span>
                    <span class="admin-sidebar-badge">2</span>
                </a>
                <a href="/admin/transactions" class="admin-sidebar-link active">
                    <span><i class="bi bi-currency-dollar me-2"></i>Monitor Transaksi</span>
                </a>
            </nav>
        </aside>

        <!-- Konten Utama Kanan -->
        <main class="admin-main-content">
            <!-- Header Judul & Tombol Ekspor CSV -->
            <div class="d-flex justify-content-between align-items-center mb-1 flex-wrap gap-2">
                <h1 class="admin-page-title mb-0">Monitoring Transaksi &amp; Midtrans Gateway</h1>
                <button type="button" class="btn-export-csv" onclick="exportTransactionsCSV()">
                    <i class="bi bi-file-earmark-spreadsheet"></i> Ekspor Laporan (.CSV)
                </button>
            </div>
            <p class="admin-page-sub">Pantau seluruh aliran transaksi pembelian aset, status pembayaran Midtrans, dan bagi hasil platform.</p>

            <!-- 4 Kartu Ringkasan Sesuai Figma image_1ecc7d -->
            <div class="row g-3 mb-4">
                <!-- Card 1: Total Omset Gross -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="admin-stat-card">
                        <div class="admin-stat-label">Total Omset Gross</div>
                        <div class="admin-stat-val">Rp48.250.000</div>
                        <div class="admin-stat-sub">320 Transaksi Berhasil</div>
                    </div>
                </div>

                <!-- Card 2: Bagi Hasil Platform (10%) -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="admin-stat-card">
                        <div class="admin-stat-label">Bagi Hasil Platform (10%)</div>
                        <div class="admin-stat-val val-green">Rp4.825.000</div>
                        <div class="admin-stat-sub">Nett Fee IndoFX</div>
                    </div>
                </div>

                <!-- Card 3: Menunggu Pembayaran -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="admin-stat-card">
                        <div class="admin-stat-label">Menunggu Pembayaran</div>
                        <div class="admin-stat-val val-orange">3 Transaksi</div>
                        <div class="admin-stat-sub">Status Midtrans Pending</div>
                    </div>
                </div>

                <!-- Card 4: Gagal / Kadaluarsa -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="admin-stat-card">
                        <div class="admin-stat-label">Gagal / Kadaluarsa</div>
                        <div class="admin-stat-val val-red">12 Transaksi</div>
                        <div class="admin-stat-sub">Expired / Cancelled</div>
                    </div>
                </div>
            </div>

            <!-- Bar Filter Sesuai Desain Figma image_1ecc7d -->
            <div class="row g-2 align-items-center admin-filter-bar mb-3">
                <div class="col-12 col-md-6">
                    <div class="admin-search-wrap">
                        <i class="bi bi-search admin-search-icon"></i>
                        <input type="text" id="trxSearchInput" class="form-control admin-search-input" placeholder="Cari Order ID, Pembeli, Penjual, atau Aset...">
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <!-- Dropdown Status Midtrans Sesuai Petunjuk Figma -->
                    <select class="form-select admin-select" id="filterStatusMidtrans">
                        <option value="all" selected>Semua Status Midtrans</option>
                        <option value="Settlement">Settlement</option>
                        <option value="Pending">Pending</option>
                        <option value="Expire">Expire</option>
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <!-- Dropdown Metode Sesuai Petunjuk Figma -->
                    <select class="form-select admin-select" id="filterPaymentMethod">
                        <option value="all" selected>Semua Metode</option>
                        <option value="QRIS / GoPay">E-Wallet</option>
                        <option value="Kartu Kredit">Kartu Kredit</option>
                        <option value="BCA Virtual Account">Transfer Bank</option>
                    </select>
                </div>
            </div>

            <!-- Tabel Monitoring Transaksi Sesuai Figma image_1ecc7d -->
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
                            <!-- Diisi dinamis oleh JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>

    <!-- 3. MODAL DETAIL LOG TRANSAKSI & RESPON GATEWAY (Sesuai Figma image_1ecc7d) -->
    <div class="modal fade" id="modalTrxDetail" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 680px;">
            <div class="modal-content modal-category-card">
                <div class="modal-category-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="modal-category-title d-flex align-items-center gap-2">
                            <i class="bi bi-file-text"></i> Rincian Transaksi &amp; Respon Gateway
                        </h5>
                        <span class="text-muted small" id="modalOrderIdTitle">Order ID: TRX-20261024-001</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-category-body">
                    <!-- Baris 1: Dua Kolom Info Pembeli & Midtrans -->
                    <div class="row g-3 mb-3">
                        <div class="col-12 col-md-6">
                            <div class="trx-modal-box">
                                <div class="trx-modal-box-title">
                                    <i class="bi bi-person"></i> INFORMASI PEMBELI &amp; ASET
                                </div>
                                <div class="trx-modal-row">
                                    <span class="trx-modal-row-label">Aset:</span>
                                    <span class="trx-modal-row-val" id="modalAssetTitle">Sci-Fi Holo UI Shader Package</span>
                                </div>
                                <div class="trx-modal-row">
                                    <span class="trx-modal-row-label">Penjual / Studio:</span>
                                    <span class="trx-modal-row-val" id="modalStudioName">CyberNet Studio</span>
                                </div>
                                <div class="trx-modal-row">
                                    <span class="trx-modal-row-label">Pembeli:</span>
                                    <span class="trx-modal-row-val" id="modalBuyerName">Rian_Devs Studio</span>
                                </div>
                                <div class="trx-modal-row">
                                    <span class="trx-modal-row-label">E-Mail Pembeli:</span>
                                    <span class="trx-modal-row-val text-muted" id="modalBuyerEmail">rian_prasetya@gmail.com</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="trx-modal-box">
                                <div class="trx-modal-box-title">
                                    <i class="bi bi-credit-card-2-front"></i> INFORMASI PEMBELI &amp; ASET
                                </div>
                                <div class="trx-modal-row">
                                    <span class="trx-modal-row-label">Waktu Transaksi:</span>
                                    <span class="trx-modal-row-val" id="modalTrxTime">24 Okt 2026, 09:14 WIB</span>
                                </div>
                                <div class="trx-modal-row">
                                    <span class="trx-modal-row-label">Metode Pembayaran:</span>
                                    <span class="trx-modal-row-val" id="modalTrxMethod">QRIS / GoPay</span>
                                </div>
                                <div class="trx-modal-row">
                                    <span class="trx-modal-row-label">Midtrans Ref ID:</span>
                                    <span class="trx-modal-row-val text-muted" id="modalMidtransRef">gopay-3288420942</span>
                                </div>
                                <div class="trx-modal-row">
                                    <span class="trx-modal-row-label">Status Midtrans:</span>
                                    <span class="trx-modal-row-val" id="modalStatusBadge"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Baris 2: Rincian Pembagian Hasil (Revenue Split) -->
                    <div class="trx-split-box mb-3">
                        <div class="trx-modal-box-title mb-2">
                            <i class="bi bi-receipt"></i> RINCIAN PEMBAGIAN HASIL (REVENUE SPLIT)
                        </div>
                        <div class="trx-split-row">
                            <span class="text-muted">Nilai Gross Transaksi (Harga Aset)</span>
                            <span class="fw-bold text-dark" id="modalGrossVal">Rp 150.000</span>
                        </div>
                        <div class="trx-split-row">
                            <span class="text-muted">Potongan Fee Platform IndoFX (10%)</span>
                            <span class="fw-bold text-danger" id="modalFeeVal">Rp 15.000</span>
                        </div>
                        <div class="trx-split-row total-row">
                            <span>Nett Penghasilan Bersih Penjual (90%)</span>
                            <span class="fs-6" id="modalNettVal">Rp 135.000</span>
                        </div>
                    </div>

                    <!-- Baris 3: Accordion Payload Raw JSON Midtrans Sandbox -->
                    <div>
                        <button type="button" class="trx-raw-toggle" onclick="toggleJsonViewer()">
                            <i class="bi bi-code-slash"></i>
                            <span id="jsonToggleText">Lihat Payload Raw JSON Response Midtrans Sandbox &or;</span>
                        </button>
                        <pre class="trx-json-viewer" id="trxJsonViewer"><code>{
  "status_code": "200",
  "status_message": "Midtrans payment notification",
  "transaction_id": "93da7e43-85f2-4bc3-96b0-ec067bd611e0",
  "order_id": "TRX-20261024-001",
  "gross_amount": "150000.00",
  "currency": "IDR",
  "payment_type": "gopay",
  "transaction_time": "2026-10-24 09:14:22",
  "transaction_status": "settlement",
  "fraud_status": "accept"
}</code></pre>
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

    <!-- 4. Footer Sesuai Desain Figma -->
    <footer class="footer-admin">
        <div class="footer-admin-content">
            <h5 class="footer-admin-title">IndoFX</h5>
            <p class="footer-admin-desc">
                Portal distribusi dan marketplace aset CG &amp; VFX berkualitas tinggi buatan lokal, dioptimalkan sepenuhnya untuk industri game Indonesia.
            </p>
        </div>
        <div class="footer-admin-bottom">
            <span>&copy; 2026 IndoFX. Hak Cipta Dilindungi Undang-Undang.</span>
            <span>Dibuat dengan bangga untuk Game Developer Indonesia</span>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Data Transaksi Sesuai Figma image_1ecc7d
        const transactionRecords = [{
                order_id: 'TRX-20261024-001',
                time: '24 Okt 2026, 09:14 WIB',
                asset_title: 'Sci-Fi Holo UI Shader Package',
                seller: 'CyberNet Studio',
                buyer: 'Rian_Devs Studio',
                buyer_email: 'rian_prasetya@gmail.com',
                method: 'QRIS / GoPay',
                method_group: 'E-Wallet',
                total_bayar: 150000,
                fee: 15000,
                status: 'Settlement',
                ref_id: 'gopay-3288420942'
            },
            {
                order_id: 'TRX-20261024-002',
                time: '24 Okt 2026, 10:02 WIB',
                asset_title: 'Procedural Stylized Grass System',
                seller: 'Nusantara Devs',
                buyer: 'Budi GameDev',
                buyer_email: 'budi.gamedev@yahoo.com',
                method: 'BCA Virtual Account',
                method_group: 'Transfer Bank',
                total_bayar: 200000,
                fee: 20000,
                status: 'Pending',
                ref_id: 'bca-va-8941094012'
            },
            {
                order_id: 'TRX-20261023-018',
                time: '23 Okt 2026, 16:45 WIB',
                asset_title: 'Volumetric Rain & Storm URP',
                seller: 'Arga Studio',
                buyer: 'Indie Game Lab',
                buyer_email: 'admin@indiegamelab.com',
                method: 'Kartu Kredit',
                method_group: 'Kartu Kredit',
                total_bayar: 150000,
                fee: 15000,
                status: 'Settlement',
                ref_id: 'cc-visa-4912093847'
            },
            {
                order_id: 'TRX-20261022-005',
                time: '22 Okt 2026, 14:10 WIB',
                asset_title: 'Sci-Fi Holo UI Shader Package',
                seller: 'CyberNet Studio',
                buyer: 'User Unregistered',
                buyer_email: 'test.user@mail.com',
                method: 'QRIS / GoPay',
                method_group: 'E-Wallet',
                total_bayar: 150000,
                fee: 15000,
                status: 'Expire',
                ref_id: 'qris-exp-9081239102'
            }
        ];

        let modalTrxInstance = null;
        let currentActiveTrx = null;

        function renderTransactionsTable() {
            const tbody = document.getElementById('trxTableBody');
            const keyword = document.getElementById('trxSearchInput').value.toLowerCase().trim();
            const statusFilter = document.getElementById('filterStatusMidtrans').value;
            const methodFilter = document.getElementById('filterPaymentMethod').value;

            const filtered = transactionRecords.filter(t => {
                const matchKeyword = !keyword ||
                    t.order_id.toLowerCase().includes(keyword) ||
                    t.asset_title.toLowerCase().includes(keyword) ||
                    t.seller.toLowerCase().includes(keyword) ||
                    t.buyer.toLowerCase().includes(keyword) ||
                    t.buyer_email.toLowerCase().includes(keyword);

                const matchStatus = statusFilter === 'all' || t.status === statusFilter;
                const matchMethod = methodFilter === 'all' || t.method === methodFilter;

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
                if (item.status === 'Settlement') {
                    statusBadge = `<span class="badge-midtrans-settlement"><i class="bi bi-check-circle"></i> Settlement</span>`;
                } else if (item.status === 'Pending') {
                    statusBadge = `<span class="badge-midtrans-pending"><i class="bi bi-clock"></i> Pending</span>`;
                } else {
                    statusBadge = `<span class="badge-midtrans-expire"><i class="bi bi-x-circle"></i> Expire</span>`;
                }

                return `
                    <tr>
                        <td>
                            <div class="fw-bold text-dark cell-nowrap">${item.order_id}</div>
                            <div class="text-muted small" style="font-size: 0.73rem;">${item.time}</div>
                        </td>
                        <td>
                            <div class="fw-bold text-dark">${item.asset_title}</div>
                            <div class="text-muted small" style="font-size: 0.73rem;">Oleh: ${item.seller}</div>
                        </td>
                        <td>
                            <div class="fw-bold text-dark cell-nowrap">${item.buyer}</div>
                            <div class="text-muted small" style="font-size: 0.73rem;">${item.buyer_email}</div>
                        </td>
                        <td class="fw-bold text-dark cell-nowrap">${item.method}</td>
                        <td class="cell-nowrap">
                            <div class="fw-bold text-dark">Rp${item.total_bayar.toLocaleString('id-ID')}</div>
                            <div class="text-muted small" style="font-size: 0.72rem;">Fee (10%): Rp${item.fee.toLocaleString('id-ID')}</div>
                        </td>
                        <td>${statusBadge}</td>
                        <td class="text-end pe-4 cell-nowrap">
                            <button type="button" class="btn-detail-log" onclick="openTrxDetailModal('${item.order_id}')">
                                <i class="bi bi-file-earmark-text"></i> Detail Log
                            </button>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        // AKSI 1: Buka Modal Detail Log
        function openTrxDetailModal(orderId) {
            const item = transactionRecords.find(t => t.order_id === orderId);
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

            // Status Badge
            let badgeHtml = '';
            if (item.status === 'Settlement') {
                badgeHtml = `<span class="badge-midtrans-settlement"><i class="bi bi-check-circle"></i> Settlement</span>`;
            } else if (item.status === 'Pending') {
                badgeHtml = `<span class="badge-midtrans-pending"><i class="bi bi-clock"></i> Pending</span>`;
            } else {
                badgeHtml = `<span class="badge-midtrans-expire"><i class="bi bi-x-circle"></i> Expire</span>`;
            }
            document.getElementById('modalStatusBadge').innerHTML = badgeHtml;

            // Revenue Split
            const gross = item.total_bayar;
            const fee = item.fee;
            const nett = gross - fee;

            document.getElementById('modalGrossVal').innerText = 'Rp ' + gross.toLocaleString('id-ID');
            document.getElementById('modalFeeVal').innerText = 'Rp ' + fee.toLocaleString('id-ID');
            document.getElementById('modalNettVal').innerText = 'Rp ' + nett.toLocaleString('id-ID');

            // Reset JSON payload viewer
            const jsonBox = document.getElementById('trxJsonViewer');
            jsonBox.classList.remove('show');
            document.getElementById('jsonToggleText').innerText = 'Lihat Payload Raw JSON Response Midtrans Sandbox \u2228';

            jsonBox.innerHTML = `<code>${JSON.stringify({
                status_code: item.status === 'Settlement' ? "200" : (item.status === 'Pending' ? "201" : "407"),
                status_message: "Midtrans payment notification",
                transaction_id: "93da7e43-85f2-4bc3-96b0-" + item.order_id.replace(/[^0-9]/g, '').slice(-12),
                order_id: item.order_id,
                gross_amount: gross + ".00",
                currency: "IDR",
                payment_type: item.method.toLowerCase().includes('qris') ? "gopay" : (item.method.toLowerCase().includes('card') ? "credit_card" : "bank_transfer"),
                transaction_time: "2026-10-24 09:14:22",
                transaction_status: item.status.toLowerCase(),
                fraud_status: "accept"
            }, null, 2)}</code>`;

            modalTrxInstance.show();
        }

        // AKSI 2: Toggle Accordion JSON Sandbox
        function toggleJsonViewer() {
            const viewer = document.getElementById('trxJsonViewer');
            const txt = document.getElementById('jsonToggleText');
            if (viewer.classList.contains('show')) {
                viewer.classList.remove('show');
                txt.innerText = 'Lihat Payload Raw JSON Response Midtrans Sandbox \u2228';
            } else {
                viewer.classList.add('show');
                txt.innerText = 'Sembunyikan Payload Raw JSON Response Midtrans Sandbox \u2227';
            }
        }

        // AKSI 3: Cetak Invoice Simulasi
        function printSimulatedInvoice() {
            if (!currentActiveTrx) return;
            const printWindow = window.open('', '', 'width=750,height=600');
            printWindow.document.write(`
                <html>
                <head>
                    <title>Invoice - ${currentActiveTrx.order_id}</title>
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
                        <div>No. Order: <strong>${currentActiveTrx.order_id}</strong></div>
                        <div>Tanggal: ${currentActiveTrx.time}</div>
                    </div>
                    <p><strong>Pembeli:</strong> ${currentActiveTrx.buyer} (${currentActiveTrx.buyer_email})</p>
                    <p><strong>Penjual:</strong> ${currentActiveTrx.seller}</p>
                    <p><strong>Metode Pembayaran:</strong> ${currentActiveTrx.method} (Ref: ${currentActiveTrx.ref_id})</p>
                    <p><strong>Status:</strong> ${currentActiveTrx.status}</p>
                    <table>
                        <thead>
                            <tr>
                                <th>Item Aset</th>
                                <th style="text-align: right;">Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>${currentActiveTrx.asset_title}</td>
                                <td style="text-align: right;">Rp${currentActiveTrx.total_bayar.toLocaleString('id-ID')}</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="total">Total Bayar: Rp${currentActiveTrx.total_bayar.toLocaleString('id-ID')}</div>
                </body>
                </html>
            `);
            printWindow.document.close();
            printWindow.focus();
            printWindow.print();
        }

        // AKSI 4: Ekspor Laporan (.CSV)
        function exportTransactionsCSV() {
            let csvContent = "data:text/csv;charset=utf-8,";
            csvContent += "Order ID,Waktu,Aset,Penjual,Pembeli,Email Pembeli,Metode,Total Bayar,Fee Platform (10%),Status Midtrans,Ref ID\r\n";

            transactionRecords.forEach(t => {
                const row = [
                    `"${t.order_id}"`,
                    `"${t.time}"`,
                    `"${t.asset_title}"`,
                    `"${t.seller}"`,
                    `"${t.buyer}"`,
                    `"${t.buyer_email}"`,
                    `"${t.method}"`,
                    `"${t.total_bayar}"`,
                    `"${t.fee}"`,
                    `"${t.status}"`,
                    `"${t.ref_id}"`
                ].join(",");
                csvContent += row + "\r\n";
            });

            const encodedUri = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", `Laporan_Transaksi_IndoFX_${new Date().toISOString().slice(0, 10)}.csv`);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        document.addEventListener('DOMContentLoaded', function() {
            modalTrxInstance = new bootstrap.Modal(document.getElementById('modalTrxDetail'));
            renderTransactionsTable();

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