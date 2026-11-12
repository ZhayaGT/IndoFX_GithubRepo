<?php
if (!isset($_SESSION['user_id'])) {
    header('Location: /login');
    exit;
}

$userKey    = (string) ($_SESSION['user_id'] ?? $_SESSION['name'] ?? 'default_user');
$userName   = (string) ($_SESSION['username'] ?? $_SESSION['name'] ?? 'naufalhaidarazmi');
$sellerInitials = mb_strtoupper(mb_substr($userName, 0, 1));
?>
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

    <!-- Stylesheets Asli Dashboard Figma (Tanpa admin.css agar tidak merusak layout) -->
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/dashboard.css">
</head>

<body class="dashboard-body">

    <!-- 1. Header Navbar Oranye Dashboard -->
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

            <!-- Profil Penjual Kanan -->
            <a href="/dashboard/assets" class="d-flex align-items-center gap-2 text-white text-decoration-none" title="Semua Aset Saya">
                <span class="avatar-circle" id="navDashAvatar"><?= htmlspecialchars($sellerInitials) ?></span>
                <span class="fw-bold fs-7 tracking-wide text-uppercase" id="navDashStoreName"><?= htmlspecialchars($userName) ?></span>
            </a>
        </div>
    </nav>

    <!-- 2. Konten Form Utama Bersih Sesuai Desain Figma -->
    <main class="py-4 form-page-wrapper">
        <div class="container-form">

            <!-- Judul Halaman & Tombol Kembali -->
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <h2 class="dash-page-title mb-0">Tambah Aset Baru</h2>
                <a href="/dashboard/assets" class="btn btn-outline-secondary btn-sm px-3 fw-bold">Kembali ke Semua Aset Saya</a>
            </div>

            <form id="formAddAsset">

                <!-- CARD 1: INFORMASI ASET -->
                <div class="form-section-card p-4 mb-4">
                    <h5 class="form-section-title mb-3">Informasi Aset</h5>

                    <div class="mb-3">
                        <label for="asset_title" class="form-label form-field-label">Judul Aset *</label>
                        <input type="text" class="form-control form-field-input" id="asset_title" placeholder="Judul Aset Anda" required>
                    </div>

                    <div class="mb-3">
                        <label for="category_id" class="form-label form-field-label">Kategori Aset *</label>
                        <select class="form-select form-field-input" id="category_id" required>
                            <option selected disabled value="">Pilih Minimal 1 Kategori</option>
                            <option value="VFX Partikel">VFX Partikel</option>
                            <option value="Shader">Shader</option>
                            <option value="Model 3D">Model 3D</option>
                            <option value="Tekstur & Material">Tekstur &amp; Material</option>
                            <option value="Script / Tool">Script / Tool</option>
                        </select>
                    </div>

                    <div class="mb-0">
                        <label for="description" class="form-label form-field-label">Deskripsi Aset *</label>
                        <textarea class="form-control form-field-input" id="description" rows="4" placeholder="Tulis deskripsi lengkap aset Anda..." required></textarea>
                    </div>
                </div>

                <!-- CARD 2: KOMPATIBILITAS -->
                <div class="form-section-card p-4 mb-4">
                    <h5 class="form-section-title mb-3">Kompatibilitas</h5>

                    <div class="mb-3">
                        <label for="unity_version" class="form-label form-field-label">Versi Unity Minimum *</label>
                        <select class="form-select form-field-input" id="unity_version" required>
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
                                <input class="form-check-input form-checkbox-custom" type="checkbox" name="render_pipeline" value="Built-in" id="pipe_builtin" checked>
                                <label class="form-check-label form-check-text" for="pipe_builtin">Built-in</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input form-checkbox-custom" type="checkbox" name="render_pipeline" value="URP" id="pipe_urp" checked>
                                <label class="form-check-label form-check-text" for="pipe_urp">Universal Render Pipeline (URP)</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input form-checkbox-custom" type="checkbox" name="render_pipeline" value="HDRP" id="pipe_hdrp">
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
                            <input type="number" class="form-control form-field-input" id="price" value="150000" placeholder="150000" min="0" step="5000">
                        </div>
                    </div>
                </div>

                <!-- CARD 4: PRATINJAU MEDIA -->
                <div class="form-section-card p-4 mb-4">
                    <h5 class="form-section-title mb-3">Pratinjau Media</h5>

                    <div class="mb-3">
                        <label class="form-label form-field-label">Gambar Pratinjau Utama (16:9) *</label>
                        <div class="dropzone-box text-center p-4" onclick="document.getElementById('imageFileInput').click()" style="cursor: pointer;">
                            <input type="file" id="imageFileInput" class="d-none" accept="image/png, image/jpeg">
                            <div class="dropzone-icon mb-2">
                                <i class="bi bi-card-image fs-3 text-secondary"></i>
                            </div>
                            <div class="dropzone-title fw-bold mb-1" id="imageNameLabel">Tarik &amp; lepas gambar pratinjau di sini</div>
                            <div class="dropzone-hint text-muted small">Format PNG, JPG maks 5MB. Direkomendasikan resolusi 1920×1080.</div>
                        </div>
                    </div>

                    <div class="mb-0">
                        <label for="preview_video_url" class="form-label form-field-label">Video Pratinjau (Opsional)</label>
                        <input type="url" class="form-control form-field-input" id="preview_video_url" placeholder="https://www.youtube.com/watch?v=...">
                    </div>
                </div>

                <!-- CARD 5: PANDUAN PENGGUNAAN & BERKAS ASET -->
                <div class="form-section-card p-4 mb-4">
                    <h5 class="form-section-title mb-3">Panduan Penggunaan &amp; Berkas Aset</h5>

                    <div class="mb-3">
                        <label for="how_to_use" class="form-label form-field-label">Panduan Penggunaan Aset *</label>
                        <textarea class="form-control form-field-input" id="how_to_use" rows="4" placeholder="Tulis panduan lengkap cara penggunaan aset Anda langkah demi langkah..." required></textarea>
                    </div>

                    <div class="mb-0">
                        <label class="form-label form-field-label">Berkas Aset (.unitypackage / .zip) *</label>
                        <div class="dropzone-box text-center p-4" onclick="document.getElementById('packageFileInput').click()" style="cursor: pointer;">
                            <input type="file" id="packageFileInput" class="d-none" accept=".unitypackage, .zip, .rar">
                            <div class="dropzone-icon mb-2">
                                <i class="bi bi-box-seam fs-3 text-secondary"></i>
                            </div>
                            <div class="dropzone-title fw-bold mb-1" id="packageNameLabel">Pilih berkas package untuk diunggah</div>
                            <div class="dropzone-hint text-muted small">Maksimal ukuran berkas 500MB.</div>
                        </div>
                    </div>
                </div>

                <!-- TOMBOL SUBMIT -->
                <div class="d-flex justify-content-end mb-5">
                    <button type="submit" class="btn btn-submit-upload" id="btnSubmitAsset">
                        <i class="bi bi-upload me-1"></i> Ajukan Pengunggahan
                    </button>
                </div>

            </form>

        </div>
    </main>

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

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Helper penyimpanan lokal (kunci berbasis user_id) -->
    <script src="/assets/js/indofx-storage.js"></script>

    <!-- Script Form Pengajuan & Sinkronisasi Antrean Admin -->
    <script>
        const sellerUserId = '<?= $userKey ?>';
        const sellerUsername = '<?= $userName ?>';
        let uploadedImageBase64 = '';
        let uploadedPackageName = '';

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

        document.addEventListener('DOMContentLoaded', function() {
            // Ambil Nama Studio jika ada (kunci baru user_id, fallback kunci lama)
            const profile = IndoFXStorage.readSellerProfile(sellerUserId, [sellerUsername]);
            let storeName = profile.store_name || sellerUsername;
            document.getElementById('navDashStoreName').innerText = storeName.toUpperCase();
            const words = storeName.trim().split(/\s+/);
            let inits = '';
            words.slice(0, 2).forEach(w => inits += w.charAt(0).toUpperCase());
            document.getElementById('navDashAvatar').innerText = inits || 'NH';

            // Nama File Indikator Gambar
            const imgInput = document.getElementById('imageFileInput');
            imgInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const file = this.files[0];
                    document.getElementById('imageNameLabel').innerText = file.name;
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        uploadedImageBase64 = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Nama File Indikator Package
            const pkgInput = document.getElementById('packageFileInput');
            pkgInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    uploadedPackageName = this.files[0].name;
                    document.getElementById('packageNameLabel').innerText = uploadedPackageName;
                }
            });

            // SUBMIT: MENYIMPAN KE ANTREAN MODERASI SECARA OTOMATIS
            document.getElementById('formAddAsset').addEventListener('submit', function(e) {
                e.preventDefault();

                const isFree = document.getElementById('price_free').checked;
                const rawPrice = isFree ? 0 : (parseFloat(document.getElementById('price').value) || 0);
                const priceFormatted = isFree ? 'Gratis' : ('Rp' + rawPrice.toLocaleString('id-ID'));

                const selectedPipes = [];
                document.querySelectorAll('input[name="render_pipeline"]:checked').forEach(cb => {
                    selectedPipes.push(cb.value);
                });
                if (selectedPipes.length === 0) selectedPipes.push('URP');

                const title = document.getElementById('asset_title').value.trim();
                const category = document.getElementById('category_id').value;
                const description = document.getElementById('description').value.trim();
                const howToUse = document.getElementById('how_to_use').value.trim();
                const unityVer = document.getElementById('unity_version').value;
                const videoUrl = document.getElementById('preview_video_url').value.trim();

                const now = new Date();
                const dateStr = now.toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'short',
                    year: 'numeric'
                });
                const assetId = 'asset-' + Date.now();
                const fileName = uploadedPackageName || (title.toLowerCase().replace(/[^a-z0-9]/g, '_') + '.unitypackage');
                const sellerStorageKey = IndoFXStorage.sellerAssetsKey(sellerUserId);

                // 1. Simpan ke ruang aset toko penjual
                const sellerAssetObj = {
                    id: assetId,
                    title: title,
                    category: category,
                    description: description,
                    how_to_use: howToUse,
                    price: rawPrice,
                    price_text: priceFormatted,
                    pipelines: selectedPipes,
                    unity_version: unityVer,
                    status: 'Menunggu',
                    sales: '0x Terjual',
                    date: dateStr,
                    created_at: dateStr,
                    file_info: 'File: ' + fileName + ' (18 MB)',
                    image: uploadedImageBase64 || 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?auto=format&fit=crop&w=800&q=80',
                    video_url: videoUrl,
                    seller: storeName,
                    creator: storeName,
                    seller_user_id: sellerUserId,
                    seller_key: sellerStorageKey
                };

                let sellerAssets = IndoFXStorage.readSellerAssets(sellerUserId, [sellerUsername, storeName]);
                sellerAssets.unshift(sellerAssetObj);
                localStorage.setItem(sellerStorageKey, JSON.stringify(sellerAssets));

                // 2. Suntikkan ke antrean moderasi admin (indofx_moderation_queue)
                let adminQueue = [];
                try {
                    adminQueue = JSON.parse(localStorage.getItem('indofx_moderation_queue')) || [];
                } catch (err) {
                    adminQueue = [];
                }

                adminQueue.unshift({
                    id: assetId,
                    title: title,
                    pipeline: selectedPipes[0] || 'URP',
                    pipelines: selectedPipes,
                    category: category,
                    file_info: 'File: ' + fileName + ' (18 MB)',
                    studio: storeName,
                    creator: storeName,
                    seller: storeName,
                    unity_ver: 'Unity ' + unityVer + ' LTS',
                    price: priceFormatted,
                    price_num: rawPrice,
                    date: dateStr,
                    created_at: dateStr,
                    time: 'Baru saja',
                    status: 'Pending Review',
                    seller_storage_key: sellerStorageKey,
                    seller_key: sellerStorageKey,
                    seller_user_id: sellerUserId,
                    description: description,
                    how_to_use: howToUse,
                    video_url: videoUrl,
                    image: uploadedImageBase64 || 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?auto=format&fit=crop&w=800&q=80'
                });
                localStorage.setItem('indofx_moderation_queue', JSON.stringify(adminQueue));

                alert('Aset berhasil diajukan! Status saat ini: "Menunggu" verifikasi Administrator.');
                window.location.href = '/dashboard/assets';
            });
        });
    </script>
</body>

</html>