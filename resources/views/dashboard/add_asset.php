<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Aset Baru - IndoFX</title>

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

    <!-- 1. Header Navbar Oranye Dashboard (Sama dengan Semua Halaman Dashboard) -->
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

            <!-- Profil Kreator Kanan -->
            <div class="d-flex align-items-center gap-2 text-white">
                <span class="avatar-circle">AS</span>
                <span class="fw-bold fs-7 tracking-wide">ARGA STUDIO</span>
            </div>

        </div>
    </nav>

    <!-- 2. Konten Form Utama (Krem Hangat & Terpusat Rapi) -->
    <main class="py-4 form-page-wrapper">
        <div class="container-form">

            <!-- Judul Halaman -->
            <h2 class="dash-page-title mb-4">Tambah Aset Baru</h2>

            <form action="/dashboard/assets" method="POST" enctype="multipart/form-data">

                <!-- CARD 1: INFORMASI ASET -->
                <div class="form-section-card p-4 mb-4">
                    <h5 class="form-section-title mb-3">Informasi Aset</h5>

                    <div class="mb-3">
                        <label for="asset_title" class="form-label form-field-label">Judul Aset</label>
                        <input type="text" class="form-control form-field-input" id="asset_title" name="title" placeholder="Judul Aset Anda" required>
                    </div>

                    <div class="mb-3">
                        <label for="category_id" class="form-label form-field-label">Kategori Aset</label>
                        <select class="form-select form-field-input" id="category_id" name="category_id" required>
                            <option selected disabled value="">Pilih Minimal 1 Kategori</option>
                            <option value="1">VFX Partikel</option>
                            <option value="2">Shader</option>
                            <option value="3">Model 3D</option>
                            <option value="4">Tekstur &amp; Material</option>
                        </select>
                    </div>

                    <div class="mb-0">
                        <label for="description" class="form-label form-field-label">Deskripsi Aset</label>
                        <textarea class="form-control form-field-input" id="description" name="description" rows="4" placeholder="Tulis deskripsi lengkap aset Anda..."></textarea>
                    </div>
                </div>

                <!-- CARD 2: KOMPATIBILITAS -->
                <div class="form-section-card p-4 mb-4">
                    <h5 class="form-section-title mb-3">Kompatibilitas</h5>

                    <div class="mb-3">
                        <label for="unity_version" class="form-label form-field-label">Versi Unity Minimum</label>
                        <select class="form-select form-field-input" id="unity_version" name="unity_version" required>
                            <option selected disabled value="">Pilih versi minimum yang kompatibel</option>
                            <option value="2021.3">Unity 2021.3 LTS</option>
                            <option value="2022.3">Unity 2022.3 LTS</option>
                            <option value="2023.2">Unity 2023.2</option>
                            <option value="6.0">Unity 6</option>
                        </select>
                    </div>

                    <div>
                        <label class="form-label form-field-label d-block mb-2">Render Pipeline Didukung (Pilih semua yang sesuai) *</label>
                        <div class="d-flex align-items-center gap-4 flex-wrap">
                            <div class="form-check">
                                <input class="form-check-input form-checkbox-custom" type="checkbox" name="render_pipeline[]" value="builtin" id="pipe_builtin" checked>
                                <label class="form-check-label form-check-text" for="pipe_builtin">Built-in</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input form-checkbox-custom" type="checkbox" name="render_pipeline[]" value="urp" id="pipe_urp" checked>
                                <label class="form-check-label form-check-text" for="pipe_urp">Universal Render Pipeline (URP)</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input form-checkbox-custom" type="checkbox" name="render_pipeline[]" value="hdrp" id="pipe_hdrp">
                                <label class="form-check-label form-check-text text-muted" for="pipe_hdrp">High Definition Render Pipeline (HDRP)</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CARD 3: HARGA -->
                <div class="form-section-card p-4 mb-4">
                    <h5 class="form-section-title mb-3">Harga</h5>

                    <div class="d-flex align-items-center gap-4 mb-3">
                        <div class="form-check">
                            <input class="form-check-input form-radio-custom" type="radio" name="pricing_type" id="price_paid" value="paid" checked onchange="togglePriceInput(true)">
                            <label class="form-check-label form-check-text fw-semibold" for="price_paid">Berbayar</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input form-radio-custom" type="radio" name="pricing_type" id="price_free" value="free" onchange="togglePriceInput(false)">
                            <label class="form-check-label form-check-text text-muted" for="price_free">Gratis</label>
                        </div>
                    </div>

                    <div id="priceInputFieldWrapper">
                        <label for="price" class="form-label form-field-label">Harga Jual (Rupiah) *</label>
                        <div class="input-group">
                            <span class="input-group-text form-addon-rp">Rp</span>
                            <input type="number" class="form-control form-field-input" id="price" name="price" value="150000" placeholder="150.000">
                        </div>
                    </div>
                </div>

                <!-- CARD 4: PRATINJAU MEDIA -->
                <div class="form-section-card p-4 mb-4">
                    <h5 class="form-section-title mb-3">Pratinjau Media</h5>

                    <div class="mb-3">
                        <label class="form-label form-field-label">Gambar Pratinjau Utama (16:9) *</label>
                        <div class="dropzone-box text-center p-4" onclick="document.getElementById('imageFileInput').click()">
                            <input type="file" id="imageFileInput" name="preview_images[]" class="d-none" accept="image/png, image/jpeg" multiple>
                            <div class="dropzone-icon mb-2">
                                <i class="bi bi-card-image fs-3"></i>
                            </div>
                            <div class="dropzone-title fw-bold mb-1">Tarik &amp; lepas gambar pratinjau di sini</div>
                            <div class="dropzone-hint text-muted small">Format PNG, JPG maks 5MB. Direkomendasikan resolusi 1920×1080.</div>
                        </div>
                    </div>

                    <div class="mb-0">
                        <label for="preview_video_url" class="form-label form-field-label">Video Pratinjau</label>
                        <input type="url" class="form-control form-field-input" id="preview_video_url" name="preview_video_url" placeholder="URL Youtube">
                    </div>
                </div>

                <!-- CARD 5: PANDUAN PENGGUNAAN & BERKAS ASET -->
                <div class="form-section-card p-4 mb-4">
                    <h5 class="form-section-title mb-3">Panduan Penggunaan &amp; Berkas Aset</h5>

                    <div class="mb-3">
                        <label for="how_to_use" class="form-label form-field-label">Panduan Penggunaan Aset</label>
                        <textarea class="form-control form-field-input" id="how_to_use" name="how_to_use" rows="3" placeholder="Tulis panduan lengkap cara penggunaan aset..."></textarea>
                    </div>

                    <div class="mb-0">
                        <label class="form-label form-field-label">Berkas Aset (.unitypackage / .zip) *</label>
                        <div class="dropzone-box text-center p-4" onclick="document.getElementById('packageFileInput').click()">
                            <input type="file" id="packageFileInput" name="asset_file" class="d-none" accept=".unitypackage, .zip">
                            <div class="dropzone-icon mb-2">
                                <i class="bi bi-box-seam fs-3"></i>
                            </div>
                            <div class="dropzone-title fw-bold mb-1">Pilih berkas package untuk diunggah</div>
                            <div class="dropzone-hint text-muted small">Maksimal ukuran berkas 500MB.</div>
                        </div>
                    </div>
                </div>

                <!-- TOMBOL SUBMIT KANAN -->
                <div class="d-flex justify-content-end mb-5">
                    <button type="submit" class="btn btn-submit-upload">
                        Ajukan Pengunggahan
                    </button>
                </div>

            </form>

        </div>
    </main>

    <!-- 3. Footer Putih Konsisten -->
    <footer class="footer-main" id="tentang">
        <div class="container-figma">
            <div class="row g-4 justify-content-between mb-4">
                <div class="col-12 col-md-5 col-lg-4">
                    <h5 class="footer-brand-title mb-2">IndoFX</h5>
                    <p class="footer-brand-desc m-0">
                        Portal distribusi dan marketplace aset CG & VFX berkualitas tinggi buatan lokal, dioptimalkan sepenuhnya untuk industri game Indonesia.
                    </p>
                </div>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="row">
                        <div class="col-6">
                            <h6 class="footer-col-title mb-2">Katalog</h6>
                            <a href="/#katalog" class="footer-link">Shader</a>
                            <a href="/#katalog" class="footer-link">VFX Partikel</a>
                            <a href="/#katalog" class="footer-link">Model 3D</a>
                        </div>
                        <div class="col-6">
                            <h6 class="footer-col-title mb-2">Dukungan</h6>
                            <a href="#" class="footer-link">Dokumentasi</a>
                            <a href="#" class="footer-link">Hubungi Kami</a>
                            <a href="#" class="footer-link">Syarat & Ketentuan</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
                <span>&copy; 2026 IndoFX. Hak Cipta Dilindungi Undang-Undang.</span>
                <span>Dibuat dengan bangga untuk Game Developer Indonesia</span>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS & Script Toggle Gratis/Berbayar -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePriceInput(isPaid) {
            const priceInput = document.getElementById('price');
            const wrapper = document.getElementById('priceInputFieldWrapper');
            if (isPaid) {
                wrapper.classList.remove('opacity-50');
                priceInput.removeAttribute('disabled');
                if (priceInput.value === '0') priceInput.value = '150000';
            } else {
                wrapper.classList.add('opacity-50');
                priceInput.value = '0';
                priceInput.setAttribute('disabled', 'true');
            }
        }
    </script>
</body>

</html>