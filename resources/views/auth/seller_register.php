<?php
$isLoggedIn = isset($_SESSION['user_id']);
$userKey    = $isLoggedIn ? (string)($_SESSION['user_id'] ?? $_SESSION['name'] ?? 'default_user') : '';
$userName   = $isLoggedIn ? (string)($_SESSION['name'] ?? $_SESSION['user_name'] ?? '') : '';
$userEmail  = $isLoggedIn ? (string)($_SESSION['email'] ?? '') : '';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Jadi Penjual - IndoFX</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS & Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">

    <style>
        .navbar-orange {
            background-color: #ED6B1D !important;
            padding: 12px 0 !important;
        }
        .navbar-orange .container-figma {
            display: flex !important;
            justify-content: flex-start !important;
            align-items: center !important;
            width: 100% !important;
        }
        .navbar-orange .navbar-brand {
            margin: 0 !important;
            display: flex !important;
            align-items: center !important;
        }

        body {
            background-color: var(--indofx-cream-bg, #FAF0E6);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            font-family: 'Inter', sans-serif;
        }

        .seller-register-main {
            flex: 1 0 auto;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 15px;
        }

        .seller-register-card {
            background-color: #ffffff;
            border: 1.5px solid #4A3B32;
            border-radius: 16px;
            width: 100%;
            max-width: 580px;
            padding: 36px 34px;
            box-shadow: 0 10px 30px rgba(60, 42, 33, 0.08);
        }

        .seller-register-title {
            font-size: 1.65rem;
            font-weight: 800;
            color: #1A1A1A;
            margin-bottom: 4px;
        }

        .seller-register-sub {
            font-size: 0.88rem;
            color: #736B66;
            margin-bottom: 24px;
        }

        .form-label-custom {
            font-size: 0.85rem;
            font-weight: 700;
            color: #1A1A1A;
            margin-bottom: 6px;
        }

        .form-control-custom {
            border: 1.5px solid #4A3B32;
            border-radius: 6px;
            padding: 9px 12px;
            font-size: 0.88rem;
            color: #1A1A1A;
            background-color: #ffffff;
            transition: border-color 0.15s ease;
        }

        .form-control-custom:focus {
            border-color: #ED6B1D;
            box-shadow: none;
            outline: none;
        }

        .form-control-custom::placeholder {
            color: #9C8F87;
            font-size: 0.85rem;
        }

        /* Checkbox Syarat & Ketentuan */
        .form-check-terms {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.84rem;
            color: #2B211B;
        }

        .form-check-terms .form-check-input {
            border: 1.5px solid #4A3B32;
            border-radius: 4px;
            width: 16px;
            height: 16px;
            cursor: pointer;
            flex-shrink: 0;
            margin-top: 0;
        }

        .form-check-terms .form-check-input:checked {
            background-color: #ED6B1D;
            border-color: #ED6B1D;
        }

        .link-terms {
            color: #0d6efd;
            text-decoration: underline;
            font-weight: 600;
            cursor: pointer;
        }

        .link-terms:hover {
            color: #0a58ca;
        }

        /* Tombol Daftar Oranye */
        .btn-register-submit {
            background-color: #ED6B1D;
            color: #ffffff !important;
            font-weight: 700;
            font-size: 0.95rem;
            border: none;
            border-radius: 6px;
            padding: 12px;
            width: 100%;
            cursor: pointer;
            transition: background-color 0.15s ease;
            box-shadow: 0 4px 12px rgba(237, 107, 29, 0.2);
        }

        .btn-register-submit:hover {
            background-color: #D85A0E;
        }

        /* Modal Syarat & Ketentuan Sesuai Gambar */
        .modal-terms-content {
            border: 1.5px solid #4A3B32;
            border-radius: 14px;
            padding: 24px;
            background-color: #ffffff;
        }

        .terms-icon-badge {
            width: 34px;
            height: 34px;
            background-color: #FDF2E9;
            border-radius: 8px;
            color: #ED6B1D;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
        }

        .btn-close-modal-terms {
            background: #ffffff;
            border: 1.5px solid #D8C8BD;
            border-radius: 8px;
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #4A3B32;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .btn-close-modal-terms:hover {
            border-color: #4A3B32;
            background-color: #FAF0E6;
        }

        .terms-text-box {
            border: 1px solid #D8C8BD;
            border-radius: 8px;
            padding: 16px;
            font-size: 0.82rem;
            line-height: 1.6;
            color: #554A43;
            max-height: 420px;
            overflow-y: auto;
            background-color: #ffffff;
        }

        @media (max-width: 576px) {
            .seller-register-main {
                padding: 20px 12px;
                align-items: flex-start;
            }

            .seller-register-card {
                padding: 22px 18px;
                border-radius: 12px;
            }

            .seller-register-title {
                font-size: 1.35rem;
            }

            .seller-register-sub {
                font-size: 0.8rem;
                margin-bottom: 18px;
            }
        }

        /* Wrapper agar tombol kembali sejajar dengan tepi card */
        .seller-register-shell {
            width: 100%;
            max-width: 580px;
        }

        /* Tombol kembali di atas form */
        .seller-back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            min-height: 38px;
            margin-bottom: 14px;
            padding: 7px 14px;
            font-size: 0.84rem;
            font-weight: 700;
            color: #4A3B32;
            background-color: #ffffff;
            border: 1.5px solid #4A3B32;
            border-radius: 8px;
            text-decoration: none;
            transition:
                color 0.15s ease,
                border-color 0.15s ease,
                background-color 0.15s ease;
        }

        .seller-back-link:hover {
            color: #ED6B1D;
            border-color: #ED6B1D;
            background-color: #FDF2E9;
        }

        .seller-back-link:focus-visible {
            outline: 3px solid rgba(237, 107, 29, 0.35);
            outline-offset: 2px;
        }

        @media (max-width: 576px) {
            .seller-back-link {
                margin-bottom: 12px;
            }
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-orange sticky-top">
        <div class="container-figma">
            <a class="navbar-brand gap-2 text-decoration-none" href="/">
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
        </div>
    </nav>

    <main class="seller-register-main">
        <div class="seller-register-shell">
            <a href="/" class="seller-back-link">
                <i class="bi bi-arrow-left" aria-hidden="true"></i> Kembali ke Beranda
            </a>

            <div class="seller-register-card">
                <h2 class="seller-register-title">Daftar Jadi Penjual</h2>
                <p class="seller-register-sub">Upload hasil karyamu agar dikenal semua orang.</p>

                <form id="formRegisterSeller" action="/seller/register" method="post" enctype="multipart/form-data">
                    <!-- 1. Nama Toko -->
                    <div class="mb-3">
                        <label class="form-label-custom" for="regName">Nama Toko</label>
                        <input type="text" id="regName" name="store_name" class="form-control form-control-custom"
                            value="<?= htmlspecialchars($userName, ENT_QUOTES, 'UTF-8') ?>"
                            placeholder="Arga Studio" required>
                    </div>

                    <!-- 2. Alamat Domisili -->
                    <div class="mb-3">
                        <label class="form-label-custom" for="regAddress">Alamat Domisili</label>
                        <textarea id="regAddress" name="address" class="form-control form-control-custom" rows="3"
                            placeholder="Jalan, RT/RW, Kelurahan, Kecamatan, Kota, Provinsi, Kode Pos." required></textarea>
                    </div>

                    <!-- 3. Alasan -->
                    <div class="mb-3">
                        <label class="form-label-custom" for="regReason">Alasan</label>
                        <textarea id="regReason" name="reason" class="form-control form-control-custom" rows="2"
                            placeholder="Alasan kamu menjadi penjual..." required></textarea>
                    </div>

                    <!-- Informasi tambahan visual; upgrade hanya memakai empat field backend di atas. -->
                    <div class="mb-3">
                        <label class="form-label-custom" for="regAssetFile">Contoh Aset</label>
                        <input type="file" id="regAssetFile" class="form-control form-control-custom" accept=".unitypackage,.zip,.rar,.fbx">
                    </div>

                    <div class="mb-3">
                        <label class="form-label-custom" for="regAssetDesc">Deskripsi Aset</label>
                        <textarea id="regAssetDesc" class="form-control form-control-custom" rows="3"
                            placeholder="Deskripsi Aset..."></textarea>
                    </div>

                    <!-- 4. URL Portofolio (Opsional) -->
                    <div class="mb-3">
                        <label class="form-label-custom" for="regPortfolio">URL Portofolio (Opsional)</label>
                        <input type="url" id="regPortfolio" name="portfolio_url" class="form-control form-control-custom"
                            placeholder="https://www.artstation.com/nama_anda">
                    </div>

                    <div class="mb-4 form-check-terms">
                        <input class="form-check-input" type="checkbox" id="checkTerms" required>
                        <label class="form-check-label" for="checkTerms">
                            Saya telah menyetujui <span class="link-terms" onclick="openTermsModal()">Syarat dan Ketentuan</span>
                        </label>
                    </div>

                    <button type="submit" class="btn-register-submit mb-3">Daftar</button>

                    <div>
                        <small class="text-muted">Sudah punya akun? <a href="/login" class="text-primary text-decoration-none fw-bold">Login</a></small>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <!-- MODAL POPUP: SYARAT DAN KETENTUAN (SESUAI GAMBAR) -->
    <div class="modal fade" id="modalTerms" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 520px;">
            <div class="modal-content modal-terms-content shadow">
                <!-- Header Modal -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <span class="terms-icon-badge">
                            <i class="bi bi-file-earmark-text"></i>
                        </span>
                        <span class="fw-bold fs-6" style="color: #2B211B;">Syarat dan Ketentuan</span>
                    </div>
                    <button type="button" class="btn-close-modal-terms" data-bs-dismiss="modal" aria-label="Close">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                <div class="modal-body p-0">
                    <h5 class="fw-bold mb-3" style="color: #2B211B; font-size: 1.15rem; line-height: 1.4;">
                        Mohon perhatikan Syarat dan Ketentuan berikut sebelum memutuskan menjadi Penjual di IndoFX.
                    </h5>

                    <!-- Box Teks Ketentuan Scrollable -->
                    <div class="terms-text-box">
                        <p class="mb-3">
                            Lorem ipsum dolor sit amet consectetur. Tempus pretium quisque tellus rhoncus. Elementum rutrum augue gravida arcu. Porttitor convallis gravida feugiat proin. Vitae a non pulvinar sagittis proin. Feugiat turpis metus sed amet tellus accumsan. Diam tellus id phasellus volutpat augue sit et adipiscing sagittis. Dictum tellus vel eget faucibus mi. Vestibulum ultrices nunc phasellus pellentesque nisl eget. Mauris tincidunt habitant vulputate at donec id adipiscing volutpat diam. Tempor ut blandit nunc elementum nunc in quam congue. Orci mattis massa sed ultrices et. Aliquam adipiscing risus egestas adipiscing urna ac. Quam augue dictum dictumst elit elementum. A sed interdum duis sed ornare arcu tincidunt maecenas.
                        </p>
                        <p class="mb-3">
                            Non eleifend quis tortor turpis fermentum. Suspendisse maecenas habitant at gravida pharetra gravida felis orci. Vel est posuere ultricies ipsum mauris. Aliquet vulputate odio pretium mauris. Sem lacus accumsan dui sem porttitor aliquam id tellus.
                        </p>
                        <p class="mb-3">
                            Interdum consequat ornare ornare tortor ultricies. In sagittis ullamcorper amet feugiat morbi libero turpis in ut. Cursus viverra platea faucibus sed at proin auctor. Tortor tortor a viverra suspendisse quis. Netus ultrices elementum urna eros a eget semper. Nunc lacus malesuada mattis urna molestie diam consequat. Nibh feugiat pellentesque laoreet ac fermentum. Ac mi commodo ac ipsum tempor adipiscing vitae dolor est. Sit sit sagittis mi eu dictum et rutrum magna elit. Nunc mauris imperdiet quis orci nec praesent nunc aenean dolor.
                        </p>
                        <p class="mb-3">
                            Dui pellentesque enim ac justo mattis tristique a dictum consectetur. Dignissim blandit elementum volutpat pulvinar velit sit vitae. Enim purus amet eget mi elit aenean convallis. Neque mi vel ipsum nunc ultricies. Arcu elit eget scelerisque morbi. Blandit venenatis amet nisl quis proin velit. Aenean morbi egestas sagittis porttitor tincidunt. In nulla vel nunc commodo ultrices tellus vitae enim id. Massa quam a porttitor arcu. Augue orci mi est viverra morbi venenatis in. Placerat sed odio urna blandit ut dignissim vulputate vitae. Tellus amet eget urna dignissim morbi risus. Feugiat eu sit in at vel euismod massa.
                        </p>
                        <p class="mb-0">
                            Et dolor enim tempor massa at mattis in. Magna pellentesque cras rhoncus eget faucibus. Molestie tempor lacus adipiscing quis consectetur in lorem molestie vulputate. Semper accumsan mauris arcu tincidunt enim diam amet tellus in. Tempus in eu sit a viverra auctor vel in. Pharetra ultrices volutpat proin ante eu gravida massa nibh mauris. Nulla erat porta fusce purus vitae cras eget amet nulla. Amet cras maecenas tincidunt enim morbi quis in at. Ipsum lacus sed amet eu nibh. Ut convallis pulvinar elit in arcu egestas nunc. Augue ornare nam magna mattis orci pretium nibh nullam. Ornare urna enim arcu amet. Luctus enim porttitor hac eros dui. In sed morbi amet adipiscing sed est feugiat egestas facilisis. Gravida in et quam mauris cras.
                        </p>
                    </div>
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

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        let modalTermsInstance = null;

        function openTermsModal() {
            if (!modalTermsInstance) {
                modalTermsInstance = new bootstrap.Modal(document.getElementById('modalTerms'));
            }
            modalTermsInstance.show();
        }

        document.addEventListener('DOMContentLoaded', function() {
            modalTermsInstance = new bootstrap.Modal(document.getElementById('modalTerms'));
        });
    </script>
</body>

</html>