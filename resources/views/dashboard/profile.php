<?php
$isLoggedIn = isset($_SESSION['user_id']);
$userKey    = (string) ($_SESSION['user_id'] ?? $_SESSION['name'] ?? 'default_user');
$userName   = (string) ($_SESSION['name'] ?? $_SESSION['user_name'] ?? 'Pengguna');
$userEmail  = (string) ($_SESSION['email'] ?? ($userName . '@gmail.com'));
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Penjual - IndoFX</title>

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

        <!-- SIDEBAR KIRI -->
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
                    <a href="/dashboard/transactions" class="sidebar-link">
                        <i class="bi bi-receipt"></i>
                        <span>Riwayat Transaksi</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/dashboard/profile" class="sidebar-link active">
                        <i class="bi bi-person"></i>
                        <span>Profil Penjual</span>
                    </a>
                </li>
            </ul>
        </aside>

        <!-- KONTEN KANAN -->
        <main class="dashboard-main-content">

            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <div>
                    <h2 class="dash-page-title mb-1">Profil Penjual</h2>
                    <p class="text-muted small m-0">Kelola identitas publik toko dan rekening pencairan hasil penjualan Anda.</p>
                </div>
                <a href="/creator/profile?me=1" class="btn btn-outline-secondary btn-sm fw-bold px-3">
                    <i class="bi bi-eye me-1"></i>Lihat Profil Publik Toko
                </a>
            </div>

            <!-- Alert Notifikasi Berhasil -->
            <div id="saveSuccessAlert" class="alert alert-success py-2 px-3 mb-4 rounded-3 small d-none">
                <i class="bi bi-check-circle-fill me-1"></i> Perubahan profil penjual berhasil disimpan dan diperbarui!
            </div>

            <form id="formSellerProfile" onsubmit="handleSaveSellerProfile(event)">

                <!-- CARD 1: INFORMASI PENJUAL -->
                <div class="form-section-card p-4 mb-4">
                    <h5 class="form-section-title mb-3">Informasi Penjual</h5>

                    <div class="row g-3 mb-3">
                        <div class="col-12 col-md-6">
                            <label for="seller_name" class="form-label form-field-label">Nama Toko / Kreator*</label>
                            <input type="text" class="form-control form-field-input" id="seller_name" name="seller_name" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="contact_email" class="form-label form-field-label">Email Kontak*</label>
                            <input type="email" class="form-control form-field-input" id="contact_email" name="contact_email" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="bio" class="form-label form-field-label">Bio &amp; Deskripsi Kreator</label>
                        <textarea class="form-control form-field-input" id="bio" name="bio" rows="3" placeholder="Ceritakan tentang spesialisasi aset yang Anda buat..."></textarea>
                    </div>

                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label for="portfolio_url" class="form-label form-field-label">Tautan Portofolio (ArtStation / Behance)</label>
                            <input type="url" class="form-control form-field-input" id="portfolio_url" name="portfolio_url" placeholder="https://www.artstation.com/nama_anda">
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="website_url" class="form-label form-field-label">Tautan GitHub / Website</label>
                            <input type="text" class="form-control form-field-input" id="website_url" name="website_url" placeholder="https://github.com/nama_anda">
                        </div>
                    </div>
                </div>

                <!-- CARD 2: PENCAIRAN DANA -->
                <div class="form-section-card p-4 mb-4">
                    <h5 class="form-section-title mb-3">Pencairan Dana (E-Wallet)</h5>

                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <label for="dana_number" class="form-label form-field-label">Nomor DANA / Rekening</label>
                            <input type="text" class="form-control form-field-input" id="dana_number" name="dana_number" placeholder="Contoh: 08123456789">
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="dana_account_name" class="form-label form-field-label">Nama Pemilik Akun</label>
                            <input type="text" class="form-control form-field-input" id="dana_account_name" name="dana_account_name" placeholder="Sesuai nama rekening">
                        </div>
                    </div>
                </div>

                <!-- TOMBOL AKSI -->
                <div class="d-flex justify-content-end align-items-center gap-3 mb-5">
                    <a href="/dashboard" class="btn btn-profile-cancel">Batal</a>
                    <button type="submit" id="btnSubmitSeller" class="btn btn-profile-save">Simpan Perubahan</button>
                </div>

            </form>

        </main>
    </div>

    <!-- 3. Footer -->
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

    <!-- Helper escape output HTML -->
    <script src="/assets/js/indofx-utils.js"></script>

    <!-- Helper penyimpanan lokal (kunci berbasis user_id) -->
    <script src="/assets/js/indofx-storage.js"></script>

    <script>
        const userKey = '<?= $userKey ?>';
        const defaultName = <?= json_encode($userName, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
        const defaultEmail = <?= json_encode($userEmail, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;

        document.addEventListener('DOMContentLoaded', function() {
            // 1. Ambil data profil toko tersimpan
            let storeData = {
                store_name: defaultName,
                contact_email: defaultEmail,
                bio: 'Pengembang independen aset visual Unity berbasis di Indonesia.',
                portfolio_url: '',
                website_url: '',
                dana_number: '',
                dana_account_name: defaultName
            };

            const saved = IndoFXStorage.readSellerProfile(userKey, [defaultName]);
            if (saved && Object.keys(saved).length > 0) {
                storeData = Object.assign(storeData, saved);
            }

            // 2. Isi formulir
            document.getElementById('seller_name').value = storeData.store_name || defaultName;
            document.getElementById('contact_email').value = storeData.contact_email || defaultEmail;
            document.getElementById('bio').value = storeData.bio || '';
            document.getElementById('portfolio_url').value = storeData.portfolio_url || '';
            document.getElementById('website_url').value = storeData.website_url || '';
            document.getElementById('dana_number').value = storeData.dana_number || '';
            document.getElementById('dana_account_name').value = storeData.dana_account_name || storeData.store_name || defaultName;

            // 3. Update header
            updateHeaderNav(storeData.store_name || defaultName);
        });

        function updateHeaderNav(name) {
            const navName = document.getElementById('navDashStoreName');
            const navAvatar = document.getElementById('navDashAvatar');
            if (navName) navName.innerText = name.toUpperCase();
            if (navAvatar) {
                const words = name.trim().split(/\s+/);
                let inits = '';
                words.slice(0, 2).forEach(w => inits += w.charAt(0).toUpperCase());
                navAvatar.innerText = inits || 'AS';
            }
        }

        function handleSaveSellerProfile(e) {
            e.preventDefault();

            const btn = document.getElementById('btnSubmitSeller');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';

            const sellerName = document.getElementById('seller_name').value.trim();
            const contactEmail = document.getElementById('contact_email').value.trim();
            const bioText = document.getElementById('bio').value.trim();
            const portfolio = document.getElementById('portfolio_url').value.trim();
            const website = document.getElementById('website_url').value.trim();
            const danaNo = document.getElementById('dana_number').value.trim();
            const danaName = document.getElementById('dana_account_name').value.trim();

            const profilePayload = {
                is_seller: true,
                store_name: sellerName || defaultName,
                owner_name: defaultName,
                contact_email: contactEmail || defaultEmail,
                bio: bioText || 'Pengembang independen aset visual game di IndoFX.',
                portfolio_url: portfolio,
                website_url: website,
                dana_number: danaNo,
                dana_account_name: danaName,
                status: 'approved',
                updated_at: new Date().toISOString()
            };

            // Simpan ke kunci profil toko penjual aktif (user_id)
            profilePayload.user_id = userKey;
            localStorage.setItem(IndoFXStorage.sellerProfileKey(userKey), JSON.stringify(profilePayload));
            localStorage.setItem('indofx_seller_profile', JSON.stringify(profilePayload));

            // Perbarui header
            updateHeaderNav(profilePayload.store_name);

            setTimeout(() => {
                btn.disabled = false;
                btn.innerHTML = 'Simpan Perubahan';

                const alertEl = document.getElementById('saveSuccessAlert');
                if (alertEl) {
                    alertEl.classList.remove('d-none');
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                }
            }, 400);
        }
    </script>
</body>

</html>