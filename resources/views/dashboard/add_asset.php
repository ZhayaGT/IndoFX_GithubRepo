<?php
if (!isset($_SESSION['user_id'])) {
    header('Location: /login');
    exit;
}

$isEdit       = !empty($asset);
$storeName    = (string) ($storeName ?? $_SESSION['store_name'] ?? $_SESSION['name'] ?? 'Penjual');
$categories   = $categories ?? [];
$assetImage   = $assetImage ?? null;
$assetFile    = $assetFile ?? null;

$words = preg_split('/\s+/', trim($storeName)) ?: [];
$sellerInitials = '';
foreach (array_slice($words, 0, 2) as $w) {
    $sellerInitials .= mb_strtoupper(mb_substr($w, 0, 1));
}
if ($sellerInitials === '') $sellerInitials = 'S';

// Siapkan nilai default untuk mode Edit vs Tambah
$valTitle       = $asset['title'] ?? '';
$valCategoryId  = (int) ($asset['category_id'] ?? 0);
$valDesc        = $asset['description'] ?? '';
$valHowToUse    = $asset['how_to_use'] ?? '';
$valUnityVer    = $asset['unity_version'] ?? '';
$valLisensi     = $asset['lisensi'] ?? 'Standard Unity Asset Store EULA';
$valPrice       = isset($asset['price']) ? (int) $asset['price'] : 150000;
$valIsFree      = $isEdit && $valPrice <= 0;
$valVideoUrl    = $asset['preview_video_url'] ?? '';
$valPipelines   = $isEdit ? strtolower((string) ($asset['render_pipeline'] ?? '')) : 'built-in, urp';

// URL gambar pratinjau lama (jika mode edit)
$supabaseUrl = $_ENV['SUPABASE_URL'] ?? $_SERVER['SUPABASE_URL'] ?? getenv('SUPABASE_URL') ?? '';
$storageBasePath = rtrim($supabaseUrl, '/') . '/storage/v1/object/public/asset-previews/catalog/';
$currentImgUrl = '';
if (!empty($assetImage['image_path'])) {
    $p = $assetImage['image_path'];
    if (strpos($p, 'http') === 0 || strpos($p, '/') === 0) {
        $currentImgUrl = $p;
    } else {
        $currentImgUrl = $storageBasePath . $p;
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $isEdit ? 'Edit & Perbarui Aset' : 'Tambah Aset Baru' ?> - IndoFX</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

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

            <a href="/dashboard/assets" class="d-flex align-items-center gap-2 text-white text-decoration-none" title="Semua Aset Saya">
                <span class="avatar-circle"><?= htmlspecialchars($sellerInitials, ENT_QUOTES, 'UTF-8') ?></span>
                <span class="fw-bold fs-7 tracking-wide text-uppercase"><?= htmlspecialchars($storeName, ENT_QUOTES, 'UTF-8') ?></span>
            </a>
        </div>
    </nav>

    <!-- 2. Konten Form Utama -->
    <main class="py-4 form-page-wrapper">
        <div class="container-form">

            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                <h2 class="dash-page-title mb-0"><?= $isEdit ? 'Edit &amp; Perbarui Aset' : 'Tambah Aset Baru' ?></h2>
                <a href="/dashboard/assets" class="btn btn-outline-secondary btn-sm px-3 fw-bold">Kembali ke Semua Aset Saya</a>
            </div>

            <?php if (!empty($_SESSION['error_msg'])): ?>
                <div class="alert alert-danger py-2 px-3 mb-3 small">
                    <?= htmlspecialchars($_SESSION['error_msg'], ENT_QUOTES, 'UTF-8') ?>
                </div>
                <?php unset($_SESSION['error_msg']); ?>
            <?php endif; ?>

            <?php if ($isEdit): ?>
                <!-- Banner Pemberitahuan Re-moderasi sesuai Figma -->
                <div class="alert d-flex align-items-start gap-2 mb-4 small" style="background-color: #FADBD8; border: 1px solid #F5B7B1; color: #5B2C27; border-radius: 8px;">
                    <i class="bi bi-exclamation-triangle-fill mt-1"></i>
                    <div>
                        <strong>Pemberitahuan Re-moderasi:</strong> Pengubahan berkas paket utama atau penetapan harga baru akan mengubah status aset menjadi <strong>pending</strong> untuk pemeriksaan ulang oleh Admin.
                    </div>
                </div>
            <?php endif; ?>

            <form action="<?= $isEdit ? '/dashboard/edit-asset' : '/dashboard/add-asset' ?>" method="POST" enctype="multipart/form-data">
                <?php if ($isEdit): ?>
                    <input type="hidden" name="asset_id" value="<?= (int) $asset['id'] ?>">
                <?php endif; ?>

                <!-- CARD 1: INFORMASI ASET -->
                <div class="form-section-card p-4 mb-4">
                    <h5 class="form-section-title mb-3">Informasi Aset</h5>

                    <div class="mb-3">
                        <label for="asset_title" class="form-label form-field-label">Judul Aset *</label>
                        <input type="text" class="form-control form-field-input" id="asset_title" name="title"
                            value="<?= htmlspecialchars($valTitle, ENT_QUOTES, 'UTF-8') ?>" placeholder="Judul Aset Anda" required>
                    </div>

                    <div class="mb-3">
                        <label for="category_id" class="form-label form-field-label">Kategori Aset *</label>
                        <select class="form-select form-field-input" id="category_id" name="category_id" required>
                            <option value="" disabled <?= $valCategoryId === 0 ? 'selected' : '' ?>>Pilih Minimal 1 Kategori</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= (int) $cat['id'] ?>" <?= $valCategoryId === (int) $cat['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat['name'], ENT_QUOTES, 'UTF-8') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-0">
                        <label for="description" class="form-label form-field-label">Deskripsi Aset *</label>
                        <textarea class="form-control form-field-input" id="description" name="description" rows="4"
                            placeholder="Tulis deskripsi lengkap aset Anda..." required><?= htmlspecialchars($valDesc, ENT_QUOTES, 'UTF-8') ?></textarea>
                    </div>
                </div>

                <!-- CARD 2: KOMPATIBILITAS & LISENSI -->
                <div class="form-section-card p-4 mb-4">
                    <h5 class="form-section-title mb-3">Kompatibilitas &amp; Lisensi</h5>

                    <div class="mb-3">
                        <label for="unity_version" class="form-label form-field-label">Versi Unity Minimum *</label>
                        <select class="form-select form-field-input" id="unity_version" name="unity_version" required>
                            <option value="" disabled <?= $valUnityVer === '' ? 'selected' : '' ?>>Pilih versi minimum yang kompatibel</option>
                            <option value="2021.3 LTS" <?= stripos($valUnityVer, '2021') !== false ? 'selected' : '' ?>>Unity 2021.3 LTS</option>
                            <option value="2022.3 LTS" <?= stripos($valUnityVer, '2022') !== false ? 'selected' : '' ?>>Unity 2022.3 LTS</option>
                            <option value="2023.2" <?= stripos($valUnityVer, '2023') !== false ? 'selected' : '' ?>>Unity 2023.2</option>
                            <option value="Unity 6" <?= stripos($valUnityVer, '6') !== false ? 'selected' : '' ?>>Unity 6</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label form-field-label d-block mb-2">Render Pipeline Didukung (Pilih semua yang sesuai) *</label>
                        <div class="d-flex align-items-center gap-4 flex-wrap">
                            <div class="form-check">
                                <input class="form-check-input form-checkbox-custom" type="checkbox" name="render_pipeline[]" value="Built-in" id="pipe_builtin"
                                    <?= stripos($valPipelines, 'built') !== false ? 'checked' : '' ?>>
                                <label class="form-check-label form-check-text" for="pipe_builtin">Built-in</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input form-checkbox-custom" type="checkbox" name="render_pipeline[]" value="URP" id="pipe_urp"
                                    <?= stripos($valPipelines, 'urp') !== false ? 'checked' : '' ?>>
                                <label class="form-check-label form-check-text" for="pipe_urp">Universal Render Pipeline (URP)</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input form-checkbox-custom" type="checkbox" name="render_pipeline[]" value="HDRP" id="pipe_hdrp"
                                    <?= stripos($valPipelines, 'hdrp') !== false ? 'checked' : '' ?>>
                                <label class="form-check-label form-check-text" for="pipe_hdrp">High Definition Render Pipeline (HDRP)</label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-0">
                        <label for="lisensi" class="form-label form-field-label">Lisensi *</label>
                        <select class="form-select form-field-input" id="lisensi" name="lisensi" required>
                            <option value="Standard Unity Asset Store EULA" <?= $valLisensi === 'Standard Unity Asset Store EULA' ? 'selected' : '' ?>>Standard Unity Asset Store EULA</option>
                            <option value="MIT License" <?= $valLisensi === 'MIT License' ? 'selected' : '' ?>>MIT License (Open Source)</option>
                            <option value="CC BY 4.0" <?= $valLisensi === 'CC BY 4.0' ? 'selected' : '' ?>>Creative Commons (CC BY 4.0)</option>
                            <option value="Commercial Single-Seat" <?= $valLisensi === 'Commercial Single-Seat' ? 'selected' : '' ?>>Commercial Single-Seat</option>
                        </select>
                    </div>
                </div>

                <!-- CARD 3: HARGA -->
                <div class="form-section-card p-4 mb-4">
                    <h5 class="form-section-title mb-3">Harga</h5>

                    <div class="d-flex align-items-center gap-4 mb-3">
                        <div class="form-check">
                            <input class="form-check-input form-radio-custom" type="radio" name="pricing_type" id="price_paid" value="paid"
                                <?= !$valIsFree ? 'checked' : '' ?> onchange="togglePriceInput(true)">
                            <label class="form-check-label form-check-text fw-semibold" for="price_paid">Berbayar</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input form-radio-custom" type="radio" name="pricing_type" id="price_free" value="free"
                                <?= $valIsFree ? 'checked' : '' ?> onchange="togglePriceInput(false)">
                            <label class="form-check-label form-check-text text-muted" for="price_free">Gratis</label>
                        </div>
                    </div>

                    <div id="priceInputFieldWrapper" class="<?= $valIsFree ? 'opacity-50' : '' ?>">
                        <label for="price" class="form-label form-field-label">Harga Jual (Rupiah) *</label>
                        <div class="input-group">
                            <span class="input-group-text form-addon-rp">Rp</span>
                            <input type="number" class="form-control form-field-input" id="price" name="price"
                                value="<?= $valPrice ?>" placeholder="150000" min="5000" step="1000"
                                <?= $valIsFree ? 'disabled' : 'required' ?>>
                        </div>

                        <!-- Alert Peringatan Harga -->
                        <div id="priceAlert" class="d-none mt-2 px-3 py-2 rounded-2 small fw-semibold align-items-center gap-2"
                            style="background-color: #fef2f2; border: 1px solid #fecaca; color: #991b1b;">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            <span id="priceAlertText">Peringatan: Harga jual aset berbayar tidak boleh kurang dari Rp5.000.</span>
                        </div>
                    </div>
                </div>

                <!-- CARD 4: PRATINJAU MEDIA -->
                <div class="form-section-card p-4 mb-4">
                    <h5 class="form-section-title mb-3">Pratinjau Media</h5>

                    <div class="mb-3">
                        <label class="form-label form-field-label">Gambar Pratinjau Utama (16:9) <?= $isEdit ? '(Opsional)' : '*' ?></label>

                        <?php if ($isEdit && $currentImgUrl !== ''): ?>
                            <div class="d-flex align-items-center gap-3 mb-3 p-2 border rounded bg-light">
                                <img src="<?= htmlspecialchars($currentImgUrl, ENT_QUOTES, 'UTF-8') ?>" alt="Preview" style="width: 140px; height: 80px; object-fit: cover; border-radius: 6px;">
                                <div>
                                    <div class="fw-bold small text-dark"><?= htmlspecialchars(basename($currentImgUrl), ENT_QUOTES, 'UTF-8') ?></div>
                                    <button type="button" class="btn btn-outline-danger btn-sm mt-1 py-0 px-2" onclick="document.getElementById('imageFileInput').click()">Ganti Gambar</button>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="dropzone-box text-center p-4" onclick="document.getElementById('imageFileInput').click()" style="cursor: pointer;">
                            <input type="file" id="imageFileInput" name="preview_image" class="d-none" accept="image/png, image/jpeg" <?= $isEdit ? '' : 'required' ?>>
                            <div class="dropzone-icon mb-2">
                                <i class="bi bi-card-image fs-3 text-secondary"></i>
                            </div>
                            <div class="dropzone-title fw-bold mb-1" id="imageNameLabel">Tarik &amp; lepas gambar pratinjau di sini</div>
                            <div class="dropzone-hint text-muted small">Format PNG, JPG maks 5MB. Direkomendasikan resolusi 1920×1080.</div>
                        </div>
                    </div>

                    <div class="mb-0">
                        <label for="preview_video_url" class="form-label form-field-label">Video Pratinjau (Opsional)</label>
                        <input type="url" class="form-control form-field-input" id="preview_video_url" name="preview_video_url"
                            value="<?= htmlspecialchars($valVideoUrl, ENT_QUOTES, 'UTF-8') ?>" placeholder="https://www.youtube.com/watch?v=...">
                    </div>
                </div>

                <!-- CARD 5: PANDUAN PENGGUNAAN & BERKAS ASET -->
                <div class="form-section-card p-4 mb-4">
                    <h5 class="form-section-title mb-3">Panduan Penggunaan &amp; Berkas Aset</h5>

                    <div class="mb-3">
                        <label for="how_to_use" class="form-label form-field-label">Panduan Penggunaan Aset *</label>
                        <textarea class="form-control form-field-input" id="how_to_use" name="how_to_use" rows="4"
                            placeholder="Tulis panduan lengkap cara penggunaan aset Anda langkah demi langkah..." required><?= htmlspecialchars($valHowToUse, ENT_QUOTES, 'UTF-8') ?></textarea>
                    </div>

                    <?php if ($isEdit && !empty($assetFile)): ?>
                        <div class="mb-3">
                            <label class="form-label form-field-label">Berkas Aset Aktif</label>
                            <div class="d-flex justify-content-between align-items-center p-3 border rounded bg-light">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-file-earmark-zip-fill text-primary fs-4"></i>
                                    <div>
                                        <div class="fw-bold small"><?= htmlspecialchars($assetFile['original_name'] ?? basename($assetFile['file_path']), ENT_QUOTES, 'UTF-8') ?></div>
                                        <div class="text-muted" style="font-size: 0.75rem;">
                                            Versi <?= htmlspecialchars($assetFile['version'] ?? '1.0', ENT_QUOTES, 'UTF-8') ?>
                                        </div>
                                    </div>
                                </div>
                                <span class="badge bg-success">Versi Aktif</span>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="mb-0">
                        <label class="form-label form-field-label">
                            <?= $isEdit ? 'Unggah Versi Berkas Baru (Opsional)' : 'Berkas Aset (.unitypackage / .zip) *' ?>
                        </label>
                        <div class="dropzone-box text-center p-4" onclick="document.getElementById('packageFileInput').click()" style="cursor: pointer;">
                            <input type="file" id="packageFileInput" name="package_file" class="d-none" accept=".unitypackage, .zip, .rar" <?= $isEdit ? '' : 'required' ?>>
                            <div class="dropzone-icon mb-2">
                                <i class="bi bi-box-seam fs-3 text-secondary"></i>
                            </div>
                            <div class="dropzone-title fw-bold mb-1" id="packageNameLabel">
                                <?= $isEdit ? 'Biarkan kosong jika tidak ingin memperbarui berkas' : 'Pilih berkas package untuk diunggah' ?>
                            </div>
                            <div class="dropzone-hint text-muted small">Maksimal ukuran berkas 500MB.</div>
                        </div>
                    </div>
                </div>

                <!-- TOMBOL SUBMIT -->
                <div class="d-flex justify-content-end align-items-center gap-3 mb-5">
                    <a href="/dashboard/assets" class="btn btn-profile-cancel">Batal</a>
                    <button type="submit" class="btn btn-submit-upload">
                        <i class="bi <?= $isEdit ? 'bi-check-lg' : 'bi-upload' ?> me-1"></i>
                        <?= $isEdit ? 'Simpan Perubahan' : 'Ajukan Pengunggahan' ?>
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

    <script>
        const priceInput = document.getElementById('price');
        const priceAlert = document.getElementById('priceAlert');
        const priceAlertText = document.getElementById('priceAlertText');
        const assetForm = document.querySelector('form');

        function validatePrice() {
            const isPaid = document.getElementById('price_paid').checked;
            if (!isPaid) {
                priceAlert.classList.add('d-none');
                priceAlert.classList.remove('d-flex');
                return true;
            }

            const val = parseFloat(priceInput.value);

            if (isNaN(val) || val < 0) {
                priceAlertText.innerText = 'Peringatan: Harga jual tidak boleh bernilai minus atau kosong.';
                priceAlert.classList.remove('d-none');
                priceAlert.classList.add('d-flex');
                return false;
            }

            if (val < 5000) {
                priceAlertText.innerText = 'Peringatan: Harga jual aset berbayar tidak boleh kurang dari Rp5.000.';
                priceAlert.classList.remove('d-none');
                priceAlert.classList.add('d-flex');
                return false;
            }

            priceAlert.classList.add('d-none');
            priceAlert.classList.remove('d-flex');
            return true;
        }

        function togglePriceInput(isPaid) {
            const wrapper = document.getElementById('priceInputFieldWrapper');
            if (isPaid) {
                wrapper.classList.remove('opacity-50');
                priceInput.removeAttribute('disabled');
                priceInput.setAttribute('required', 'true');
                if (!priceInput.value || Number(priceInput.value) < 5000) {
                    priceInput.value = '150000';
                }
                validatePrice();
            } else {
                wrapper.classList.add('opacity-50');
                priceInput.value = '0';
                priceInput.setAttribute('disabled', 'true');
                priceInput.removeAttribute('required');
                priceAlert.classList.add('d-none');
                priceAlert.classList.remove('d-flex');
            }
        }

        // Cegah mengetik tanda minus (-), plus (+), atau huruf e
        priceInput.addEventListener('keydown', function(e) {
            if (['-', '+', 'e', 'E'].includes(e.key)) {
                e.preventDefault();
            }
        });

        // Validasi langsung setiap kali angka diketik / diubah
        priceInput.addEventListener('input', validatePrice);

        // Tahan submit form jika harga belum valid
        assetForm.addEventListener('submit', function(e) {
            if (!validatePrice()) {
                e.preventDefault();
                priceInput.focus();
                priceInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });

        document.getElementById('imageFileInput').addEventListener('change', function() {
            if (this.files && this.files[0]) {
                document.getElementById('imageNameLabel').innerText = this.files[0].name;
            }
        });

        document.getElementById('packageFileInput').addEventListener('change', function() {
            if (this.files && this.files[0]) {
                document.getElementById('packageNameLabel').innerText = this.files[0].name;
            }
        });
    </script>
</body>

</html>