<?php
if (!isset($_SESSION['user_id'])) {
    header('Location: /login');
    exit;
}

$userName = (string) ($_SESSION['name'] ?? $_SESSION['user_name'] ?? 'Pengguna');
$userKey  = (string) ($_SESSION['user_id'] ?? $_SESSION['name'] ?? 'default_user');

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
    <title>Riwayat Transaksi - IndoFX</title>

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

        .page-title {
            font-size: 2rem;
            font-weight: 800;
            color: #1A1A1A;
            margin-bottom: 24px;
        }

        .table-custom-container {
            background-color: #ffffff;
            border: 1.5px solid #2B2129;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
        }

        .table-custom {
            margin-bottom: 0;
            width: 100%;
            border-collapse: collapse;
        }

        .table-custom th {
            background-color: #ffffff;
            font-size: 0.85rem;
            font-weight: 700;
            color: #2b2129;
            padding: 16px;
            border-bottom: 1.5px solid #2B2129;
        }

        .table-custom td {
            padding: 16px;
            font-size: 0.88rem;
            vertical-align: middle;
            border-bottom: 1px solid #efe0d5;
        }

        .table-custom tr:last-child td {
            border-bottom: none;
        }

        .badge-status-berhasil {
            background-color: #2e7d32;
            color: #ffffff;
            font-weight: 700;
            font-size: 0.72rem;
            padding: 4px 12px;
            border-radius: 4px;
            display: inline-block;
        }

        .btn-view-invoice {
            color: #0d6efd;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            font-size: 0.86rem;
            line-height: 1.2;
            display: inline-block;
        }

        .btn-view-invoice:hover {
            text-decoration: underline;
        }

        /* Kartu Preview Invoice Kanan */
        .invoice-preview-card {
            background-color: #ffffff;
            border: 1.5px solid #2B2129;
            border-radius: 8px;
            padding: 24px;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.04);
            position: sticky;
            top: 90px;
        }

        .invoice-card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 8px;
        }

        .invoice-card-title {
            font-size: 1.35rem;
            font-weight: 800;
            color: #1a1a1a;
            margin-bottom: 2px;
        }

        .invoice-no-text {
            font-size: 1.05rem;
            font-weight: 800;
            color: #1a1a1a;
            margin-bottom: 2px;
        }

        .invoice-date-text {
            font-size: 0.8rem;
            color: #736b66;
            margin-bottom: 20px;
            padding-bottom: 16px;
            border-bottom: 1px solid #efe0d5;
        }

        .invoice-section-label {
            font-size: 0.72rem;
            font-weight: 800;
            color: #736b66;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-bottom: 3px;
        }

        .invoice-section-value {
            font-size: 0.95rem;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 2px;
        }

        .invoice-section-sub {
            font-size: 0.8rem;
            color: #736b66;
            margin-bottom: 16px;
        }

        .invoice-items-table {
            width: 100%;
            margin-top: 14px;
            margin-bottom: 14px;
        }

        .invoice-items-table th {
            font-size: 0.8rem;
            font-weight: 700;
            color: #736b66;
            padding-bottom: 8px;
        }

        .invoice-items-table td {
            font-size: 0.88rem;
            color: #1a1a1a;
            padding: 6px 0;
        }

        .invoice-total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid #efe0d5;
            padding-top: 16px;
            margin-top: 10px;
            margin-bottom: 24px;
        }

        .invoice-total-label {
            font-size: 0.95rem;
            font-weight: 700;
            color: #1a1a1a;
        }

        .invoice-total-amount {
            font-size: 1.25rem;
            font-weight: 800;
            color: #0d6efd;
        }

        .btn-print-invoice {
            background-color: #000000;
            color: #ffffff !important;
            font-weight: 700;
            font-size: 0.9rem;
            padding: 12px;
            border-radius: 6px;
            width: 100%;
            text-align: center;
            border: none;
            display: block;
            cursor: pointer;
            transition: background-color 0.15s ease;
        }

        .btn-print-invoice:hover {
            background-color: #222222;
        }

        .empty-history-box {
            padding: 60px 20px;
            text-align: center;
            color: #736b66;
        }

        /* ==========================================
           ATURAN CETAK (PRINT PREVIEW CLEANUP)
           ========================================== */
        @media print {

            nav,
            .navbar,
            .navbar-orange,
            .page-title,
            .col-lg-7,
            .table-custom-container,
            .btn-print-invoice,
            footer,
            .footer-main {
                display: none !important;
            }

            body {
                background-color: #ffffff !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            main {
                padding: 0 !important;
            }

            .container,
            .container-figma {
                max-width: 100% !important;
                width: 100% !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            .row {
                display: block !important;
                margin: 0 !important;
            }

            .col-lg-5 {
                width: 100% !important;
                max-width: 100% !important;
                padding: 0 !important;
            }

            .invoice-preview-card {
                border: 1.5px solid #2B2129 !important;
                box-shadow: none !important;
                position: static !important;
                width: 100% !important;
                max-width: 580px !important;
                margin: 20px auto !important;
                padding: 28px !important;
                border-radius: 8px !important;
                page-break-inside: avoid;
            }

            .badge-status-berhasil {
                background-color: #2e7d32 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            @page {
                size: portrait;
                margin: 12mm;
            }
        }
    </style>
</head>

<body>

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

    <!-- Konten Riwayat Transaksi -->
    <main class="flex-grow-1 py-5">
        <div class="container-figma">
            <h1 class="page-title">Riwayat Transaksi</h1>

            <div class="row g-4 align-items-start">
                <div class="col-12 col-lg-7">
                    <div class="table-custom-container">
                        <table class="table-custom">
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
                            <tbody id="transactionTableBody"></tbody>
                        </table>
                    </div>
                </div>

                <div class="col-12 col-lg-5">
                    <div class="invoice-preview-card" id="invoiceCard">
                        <div class="invoice-card-header">
                            <div>
                                <h4 class="invoice-card-title">INVOICE</h4>
                                <div class="invoice-no-text" id="invNoText">-</div>
                            </div>
                            <span class="badge-status-berhasil" id="invBadgeStatus">Berhasil</span>
                        </div>
                        <div class="invoice-date-text" id="invDateText">Tanggal: -</div>

                        <div class="invoice-section-label">PEMBELI</div>
                        <div class="invoice-section-value" id="invBuyerName"><?= htmlspecialchars($userName, ENT_QUOTES, 'UTF-8') ?></div>
                        <div class="invoice-section-sub" id="invBuyerEmail">-</div>

                        <div class="invoice-section-label">METODE PEMBAYARAN</div>
                        <div class="invoice-section-value mb-4" id="invPaymentMethod">-</div>

                        <table class="invoice-items-table">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th class="text-end">Harga</th>
                                </tr>
                            </thead>
                            <tbody id="invItemsList"></tbody>
                        </table>

                        <div class="invoice-total-row">
                            <span class="invoice-total-label">Total Pembayaran</span>
                            <span class="invoice-total-amount" id="invTotalText">Rp0</span>
                        </div>

                        <button type="button" class="btn-print-invoice" onclick="printInvoice()">
                            Cetak Invoice
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer Resmi IndoFX Membentang Penuh di Dasar Halaman -->
    <?php
    $footerPartial = BASE_PATH . '/resources/views/partials/footer.php';
    if (file_exists($footerPartial)) {
        require $footerPartial;
    } else {
    ?>
        <footer class="footer-main mt-auto">
            <div class="container-figma">
                <div class="footer-center-content">
                    <h5 class="footer-brand-title-center">IndoFX</h5>
                    <p class="footer-brand-desc-center">
                        Portal distribusi dan marketplace aset CG &amp; VFX berkualitas tinggi buatan lokal, dioptimalkan sepenuhnya untuk industri game Indonesia.
                    </p>
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
    <?php } ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Helper escape output HTML -->
    <script src="/assets/js/indofx-utils.js"></script>
    <script src="/assets/js/navbar-auth.js"></script>
    <script>
        const userKey = '<?= $userKey ?>';
        const txKey = 'indofx_transactions_' + userKey;
        let userTransactions = [];

        function getTransactions() {
            try {
                return JSON.parse(localStorage.getItem(txKey)) || [];
            } catch (e) {
                return [];
            }
        }

        function loadTransactions() {
            userTransactions = getTransactions();
            const tbody = document.getElementById('transactionTableBody');
            const card = document.getElementById('invoiceCard');

            if (userTransactions.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="empty-history-box">
                            <i class="bi bi-receipt text-secondary fs-1 d-block mb-2"></i>
                            <h6 class="fw-bold text-dark mb-1">Belum Ada Riwayat Transaksi</h6>
                            <p class="small text-muted mb-0">Transaksi pembelian aset berbayar Anda akan tercatat secara otomatis di sini.</p>
                        </td>
                    </tr>
                `;
                if (card) card.style.display = 'none';
                return;
            }

            if (card) card.style.display = 'block';

            tbody.innerHTML = userTransactions.map((tx, idx) => {
                const firstItemTitle = tx.items && tx.items.length > 0 ? tx.items[0].title : 'Aset Visual IndoFX';
                const moreItemsCount = tx.items && tx.items.length > 1 ? ` (+${tx.items.length - 1} aset lainnya)` : '';
                const displayTitle = firstItemTitle + moreItemsCount;
                const totalDisplay = tx.total ? `Rp${tx.total.toLocaleString('id-ID')}` : 'Gratis';

                return `
                    <tr>
                        <td><strong>${esc(tx.invoice_no)}</strong></td>
                        <td>${esc(displayTitle)}</td>
                        <td>${esc(tx.short_date || tx.date)}</td>
                        <td><strong>${totalDisplay}</strong></td>
                        <td><span class="badge-status-berhasil">${esc(tx.status || 'Berhasil')}</span></td>
                        <td>
                            <a href="javascript:void(0)" class="btn-view-invoice" onclick="selectInvoice(${idx})">
                                Lihat Invoice
                            </a>
                        </td>
                    </tr>
                `;
            }).join('');

            selectInvoice(0);
        }

        function selectInvoice(index) {
            const tx = userTransactions[index];
            if (!tx) return;

            document.getElementById('invNoText').innerText = tx.invoice_no;
            document.getElementById('invBadgeStatus').innerText = tx.status || 'Berhasil';
            document.getElementById('invDateText').innerText = 'Tanggal: ' + (tx.full_date || tx.date);
            document.getElementById('invBuyerName').innerText = tx.buyer_name || <?= json_encode($userName, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
            document.getElementById('invBuyerEmail').innerText = tx.buyer_email || 'user@email.com';
            document.getElementById('invPaymentMethod').innerText = tx.payment_method || 'QRIS (GoPay)';

            const itemsContainer = document.getElementById('invItemsList');
            if (tx.items && tx.items.length > 0) {
                itemsContainer.innerHTML = tx.items.map(it => `
                    <tr>
                        <td><strong>${esc(it.title)}</strong></td>
                        <td class="text-end"><strong>Rp${(parseFloat(it.price) || 0).toLocaleString('id-ID')}</strong></td>
                    </tr>
                `).join('');
            } else {
                itemsContainer.innerHTML = `
                    <tr>
                        <td><strong>${esc(tx.asset_title || 'Aset Visual IndoFX')}</strong></td>
                        <td class="text-end"><strong>Rp${(parseFloat(tx.total) || 0).toLocaleString('id-ID')}</strong></td>
                    </tr>
                `;
            }

            document.getElementById('invTotalText').innerText = 'Rp' + (parseFloat(tx.total) || 0).toLocaleString('id-ID');
        }

        function printInvoice() {
            window.print();
        }

        document.addEventListener('DOMContentLoaded', loadTransactions);
    </script>
</body>

</html>