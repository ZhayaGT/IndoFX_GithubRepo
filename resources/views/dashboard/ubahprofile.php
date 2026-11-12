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
    <title>Kelola Profil - IndoFX</title>

    <!-- Google Fonts & Bootstrap 5 -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">

    <style>
        body {
            background-color: var(--ifx-cream-bg, #fdece2);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            font-family: 'Inter', sans-serif;
        }

        .profile-edit-card {
            background-color: #ffffff;
            border: 1.5px solid var(--ifx-border-dark, #3b2314);
            border-radius: 8px;
            padding: 32px 36px;
            box-shadow: 0 4px 14px rgba(59, 35, 20, 0.04);
            margin-bottom: 24px;
        }

        .profile-page-title {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--ifx-brown-dark, #3b2314);
            letter-spacing: -0.5px;
        }

        .profile-form-label {
            font-size: 0.95rem;
            font-weight: 800;
            color: var(--ifx-brown-dark, #3b2314);
            margin-bottom: 8px;
            display: block;
        }

        .profile-form-input {
            border: 1.5px solid #6e5a4f;
            border-radius: 4px;
            padding: 10px 14px;
            font-size: 0.88rem;
            color: var(--ifx-brown-dark, #3b2314);
            width: 100%;
        }

        .profile-form-input:focus {
            outline: none;
            border-color: var(--ifx-orange, #ed6b1d);
            box-shadow: 0 0 0 3px rgba(237, 107, 29, 0.15);
        }

        .btn-profile-save {
            background-color: var(--ifx-orange, #ed6b1d);
            color: #ffffff;
            border: none;
            border-radius: 6px;
            font-weight: 700;
            font-size: 0.9rem;
            padding: 10px 38px;
            transition: all 0.2s ease;
        }

        .btn-profile-save:hover {
            background-color: var(--ifx-orange-hover, #d95d12);
            color: #ffffff;
            transform: translateY(-1px);
        }
    </style>
</head>

<body>

    <!-- 1. Header Navbar Oranye -->
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
                <span class="brand-text">IndoFX</span>
            </a>

            <div class="d-flex align-items-center gap-2">
                <?php
                $authPartial = BASE_PATH . '/resources/views/partials/navbar-auth.php';
                if (file_exists($authPartial)) {
                    require $authPartial;
                }
                ?>
            </div>
        </div>
    </nav>

    <!-- 2. Konten Formulir Dinamis -->
    <main class="flex-grow-1 py-5">
        <div class="container-figma" style="max-width: 900px;">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <h1 class="profile-page-title mb-0" id="pageTitleText">Kelola Profil</h1>
                <a href="/creator/profile?me=1" class="btn btn-outline-secondary btn-sm fw-bold px-3">
                    <i class="bi bi-eye me-1"></i>Lihat Profil Publik
                </a>
            </div>

            <!-- Notifikasi Berhasil -->
            <div id="saveSuccessAlert" class="alert alert-success py-2 px-3 mb-4 rounded-3 small d-none">
                <i class="bi bi-check-circle-fill me-1"></i> Profil berhasil diperbarui dan tersimpan permanen!
            </div>

            <!-- FORMULIR KHUSUS PENJUAL (Tampil Otomatis jika Penjual) -->
            <div id="sellerFormSection" class="d-none">
                <form id="formSeller" onsubmit="handleSaveSeller(event)">
                    <div class="profile-edit-card">
                        <h5 class="fw-bold text-dark mb-3">Informasi Toko Penjual</h5>
                        <div class="row g-3 mb-3">
                            <div class="col-12 col-md-6">
                                <label class="profile-form-label" for="seller_name">Nama Toko / Kreator*</label>
                                <input type="text" class="profile-form-input" id="seller_name" required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="profile-form-label" for="contact_email">Email Kontak*</label>
                                <input type="email" class="profile-form-input" id="contact_email" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="profile-form-label" for="seller_bio">Bio &amp; Deskripsi Kreator</label>
                            <textarea class="profile-form-input" id="seller_bio" rows="4"></textarea>
                        </div>

                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label class="profile-form-label" for="portfolio_url">Tautan ArtStation / Portofolio</label>
                                <input type="url" class="profile-form-input" id="portfolio_url" placeholder="https://www.artstation.com/nama_anda">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="profile-form-label" for="website_url">Tautan GitHub / Website</label>
                                <input type="text" class="profile-form-input" id="website_url" placeholder="https://github.com/nama_anda">
                            </div>
                        </div>
                    </div>

                    <div class="profile-edit-card">
                        <h5 class="fw-bold text-dark mb-3">Pencairan Dana (E-Wallet)</h5>
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label class="profile-form-label" for="dana_number">Nomor DANA / Rekening</label>
                                <input type="text" class="profile-form-input" id="dana_number">
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="profile-form-label" for="dana_account_name">Nama Pemilik Akun</label>
                                <input type="text" class="profile-form-input" id="dana_account_name">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mb-4">
                        <button type="submit" id="btnSubmitSeller" class="btn btn-profile-save">Simpan Perubahan Penjual</button>
                    </div>
                </form>
            </div>

            <!-- FORMULIR KHUSUS PEMBELI BIASA -->
            <div id="buyerFormSection" class="d-none">
                <form action="/dashboard/ubah-profil" method="POST">
                    <div class="profile-edit-card mb-4">
                        <div class="mb-4">
                            <label class="profile-form-label" for="buyer_name">Nama Pengguna</label>
                            <input type="text" class="profile-form-input" id="buyer_name" name="name"
                                value="<?= htmlspecialchars($_SESSION['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
                        </div>

                        <div>
                            <label class="profile-form-label" for="buyer_bio">Bio</label>
                            <textarea class="profile-form-input" id="buyer_bio" name="bio" rows="6"><?= htmlspecialchars($_SESSION['bio'] ?? 'Hanya game developer pemula :v', ENT_QUOTES, 'UTF-8') ?></textarea>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-profile-save">Simpan</button>
                    </div>
                </form>
            </div>

        </div>
    </main>

    <!-- Footer -->
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

    <script>
        const userKey = '<?= $userKey ?>';
        const defaultName = <?= json_encode($userName, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
        const defaultEmail = <?= json_encode($userEmail, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;

        document.addEventListener('DOMContentLoaded', function() {
            // Cek apakah akun adalah penjual terdaftar
            let sellerProfile = null;
            if (userKey) {
                const raw = localStorage.getItem('indofx_seller_profile_' + userKey);
                if (raw) {
                    try {
                        sellerProfile = JSON.parse(raw);
                    } catch (e) {}
                }
            }

            const isSeller = Boolean(sellerProfile && (sellerProfile.is_seller || sellerProfile.store_name));

            if (isSeller) {
                // Tampilkan Formulir Penjual
                document.getElementById('pageTitleText').innerText = 'Kelola Profil Penjual';
                document.getElementById('sellerFormSection').classList.remove('d-none');

                document.getElementById('seller_name').value = sellerProfile.store_name || defaultName;
                document.getElementById('contact_email').value = sellerProfile.contact_email || defaultEmail;
                document.getElementById('seller_bio').value = sellerProfile.bio || 'Pengembang independen khusus aset shader, efek visual partikel (VFX) dan material prosedural berkualitas tinggi untuk proyek game Unity lokal.';
                document.getElementById('portfolio_url').value = sellerProfile.portfolio_url || 'https://www.artstation.com/' + (sellerProfile.store_name || defaultName).toLowerCase().replace(/[^a-z0-9]/g, '');
                document.getElementById('website_url').value = sellerProfile.website_url || 'https://github.com/' + (sellerProfile.store_name || defaultName).toLowerCase().replace(/[^a-z0-9]/g, '');
                document.getElementById('dana_number').value = sellerProfile.dana_number || '08123456789';
                document.getElementById('dana_account_name').value = sellerProfile.dana_account_name || sellerProfile.store_name || defaultName;
            } else {
                // Tampilkan Formulir Pembeli Biasa
                document.getElementById('pageTitleText').innerText = 'Kelola Profil Pembeli';
                document.getElementById('buyerFormSection').classList.remove('d-none');
            }
        });

        function handleSaveSeller(e) {
            e.preventDefault();
            const btn = document.getElementById('btnSubmitSeller');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...';

            const payload = {
                is_seller: true,
                store_name: document.getElementById('seller_name').value.trim() || defaultName,
                owner_name: defaultName,
                contact_email: document.getElementById('contact_email').value.trim() || defaultEmail,
                bio: document.getElementById('seller_bio').value.trim(),
                portfolio_url: document.getElementById('portfolio_url').value.trim(),
                website_url: document.getElementById('website_url').value.trim(),
                dana_number: document.getElementById('dana_number').value.trim(),
                dana_account_name: document.getElementById('dana_account_name').value.trim(),
                status: 'approved',
                updated_at: new Date().toISOString()
            };

            // Simpan ke LocalStorage profil penjual
            localStorage.setItem('indofx_seller_profile_' + userKey, JSON.stringify(payload));

            setTimeout(() => {
                btn.disabled = false;
                btn.innerHTML = 'Simpan Perubahan Penjual';
                const alertEl = document.getElementById('saveSuccessAlert');
                if (alertEl) {
                    alertEl.classList.remove('d-none');
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                }
            }, 300);
        }
    </script>
</body>

</html>