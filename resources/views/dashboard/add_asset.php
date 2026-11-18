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
                <h2 class="dash-page-title mb-0" id="pageFormTitle">Tambah Aset Baru</h2>
                <a href="/dashboard/assets" class="btn btn-outline-secondary btn-sm px-3 fw-bold">Kembali ke Semua Aset Saya</a>
            </div>

            <div class="alert alert-warning py-2 px-3 small d-none" id="editApprovalNotice">
                Perubahan aset akan masuk antrean moderasi dan perlu disetujui ulang oleh admin sebelum kembali tayang di katalog.
            </div>

            <form id="formAddAsset" action="/dashboard/assets/save" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= htmlspecialchars((string) ($asset['public_id'] ?? ($_GET['edit_id'] ?? '')), ENT_QUOTES, 'UTF-8') ?>">

                <!-- CARD 1: INFORMASI ASET -->
                <div class="form-section-card p-4 mb-4">
                    <h5 class="form-section-title mb-3">Informasi Aset</h5>

                    <div class="mb-3">
                        <label for="asset_title" class="form-label form-field-label">Judul Aset *</label>
                        <input type="text" class="form-control form-field-input" id="asset_title" name="title" value="<?= htmlspecialchars((string) ($asset['title'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" placeholder="Judul Aset Anda" required>
                    </div>

                    <div class="mb-3">
                        <label for="category_id" class="form-label form-field-label">Kategori Aset *</label>
                        <select class="form-select form-field-input" id="category_id" name="category_id" required>
                            <option selected disabled value="">Pilih Minimal 1 Kategori</option>
                            <?php foreach (($categories ?? []) as $category): ?>
                                <option value="<?= (int) $category['id'] ?>" <?= (int) ($asset['category_id'] ?? 0) === (int) $category['id'] ? 'selected' : '' ?>><?= htmlspecialchars((string) $category['name'], ENT_QUOTES, 'UTF-8') ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-0">
                        <label for="description" class="form-label form-field-label">Deskripsi Aset *</label>
                        <textarea class="form-control form-field-input" id="description" name="description" rows="4" placeholder="Tulis deskripsi lengkap aset Anda..." required><?= htmlspecialchars((string) ($asset['description'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
                    </div>
                </div>

                <!-- CARD 2: KOMPATIBILITAS -->
                <div class="form-section-card p-4 mb-4">
                    <h5 class="form-section-title mb-3">Kompatibilitas</h5>

                    <div class="mb-3">
                        <label for="unity_version" class="form-label form-field-label">Versi Unity Minimum *</label>
                    <select class="form-select form-field-input" id="unity_version" name="unity_version" required>
                            <option selected disabled value="">Pilih versi minimum yang kompatibel</option>
                            <?php foreach (['2021.3' => 'Unity 2021.3 LTS', '2022.3' => 'Unity 2022.3 LTS', '2023.2' => 'Unity 2023.2', '6.0' => 'Unity 6'] as $version => $label): ?>
                                <option value="<?= $version ?>" <?= (string) ($asset['unity_version'] ?? '') === $version ? 'selected' : '' ?>><?= $label ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label class="form-label form-field-label d-block mb-2">Render Pipeline Didukung (Pilih semua yang sesuai) *</label>
                        <div class="d-flex align-items-center gap-4 flex-wrap">
                            <div class="form-check">
                                <input class="form-check-input form-checkbox-custom" type="checkbox" name="render_pipeline[]" value="Built-in" id="pipe_builtin" <?= str_contains((string) ($asset['render_pipeline'] ?? ''), 'Built-in') || !isset($asset) ? 'checked' : '' ?>>
                                <label class="form-check-label form-check-text" for="pipe_builtin">Built-in</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input form-checkbox-custom" type="checkbox" name="render_pipeline[]" value="URP" id="pipe_urp" <?= str_contains((string) ($asset['render_pipeline'] ?? ''), 'URP') || !isset($asset) ? 'checked' : '' ?>>
                                <label class="form-check-label form-check-text" for="pipe_urp">Universal Render Pipeline (URP)</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input form-checkbox-custom" type="checkbox" name="render_pipeline[]" value="HDRP" id="pipe_hdrp" <?= str_contains((string) ($asset['render_pipeline'] ?? ''), 'HDRP') ? 'checked' : '' ?>>
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
                            <input type="number" class="form-control form-field-input" id="price" name="price" value="<?= (int) ($asset['price'] ?? 150000) ?>" placeholder="150000" min="0" step="1">
                        </div>
                    </div>
                </div>

                <!-- CARD 4: PRATINJAU MEDIA -->
                <div class="form-section-card p-4 mb-4">
                    <h5 class="form-section-title mb-3">Pratinjau Media</h5>

                    <div class="mb-3">
                        <label class="form-label form-field-label">Gambar Pratinjau Utama (16:9) *</label>
                        <div class="dropzone-box text-center p-4" onclick="document.getElementById('imageFileInput').click()" style="cursor: pointer;">
                            <input type="file" id="imageFileInput" name="image" class="d-none" accept="image/png, image/jpeg" <?= empty($asset) ? 'required' : '' ?>>
                            <div id="imagePreviewWrap" class="d-none mb-2">
                                <img id="imagePreviewThumb" alt="Pratinjau gambar aset" style="max-height: 120px; max-width: 100%; border-radius: 6px; object-fit: cover;">
                            </div>
                            <div class="dropzone-icon mb-2">
                                <i class="bi bi-card-image fs-3 text-secondary"></i>
                            </div>
                            <div class="dropzone-title fw-bold mb-1" id="imageNameLabel">Tarik &amp; lepas gambar pratinjau di sini</div>
                            <div class="dropzone-hint text-muted small">Format PNG, JPG maks 5MB. Direkomendasikan resolusi 1920×1080.</div>
                        </div>
                    </div>

                    <div class="mb-0">
                        <label for="preview_video_url" class="form-label form-field-label">Video Pratinjau (Opsional)</label>
                        <input type="url" class="form-control form-field-input" id="preview_video_url" name="preview_video_url" value="<?= htmlspecialchars((string) ($asset['preview_video_url'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" placeholder="https://www.youtube.com/watch?v=...">
                    </div>
                </div>

                <!-- CARD 5: PANDUAN PENGGUNAAN & BERKAS ASET -->
                <div class="form-section-card p-4 mb-4">
                    <h5 class="form-section-title mb-3">Panduan Penggunaan &amp; Berkas Aset</h5>

                    <div class="mb-3">
                        <label for="how_to_use" class="form-label form-field-label">Panduan Penggunaan Aset *</label>
                        <textarea class="form-control form-field-input" id="how_to_use" name="how_to_use" rows="4" placeholder="Tulis panduan lengkap cara penggunaan aset Anda langkah demi langkah..." required><?= htmlspecialchars((string) ($asset['how_to_use'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
                    </div>

                    <div class="mb-0">
                        <label class="form-label form-field-label">Berkas Aset (.unitypackage / .zip) *</label>
                        <div class="dropzone-box text-center p-4" onclick="document.getElementById('packageFileInput').click()" style="cursor: pointer;">
                            <input type="file" id="packageFileInput" name="package" class="d-none" accept=".unitypackage, .zip, .rar" <?= empty($asset) ? 'required' : '' ?>>
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

    <!-- Helper escape output HTML -->
    <script src="/assets/js/indofx-utils.js"></script>

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

        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('formAddAsset');
            const editId = form.querySelector('input[name="id"]').value;
            const storeName = <?= json_encode((string) ($storeName ?? $userName), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
            document.getElementById('navDashStoreName').textContent = storeName.toUpperCase();
            document.getElementById('navDashAvatar').textContent = storeName.trim().split(/\s+/).slice(0, 2).map(word => word.charAt(0).toUpperCase()).join('') || 'AS';
            if (editId) {
                document.getElementById('pageFormTitle').textContent = 'Edit Aset';
                document.getElementById('editApprovalNotice').classList.remove('d-none');
            }

            document.getElementById('imageFileInput').addEventListener('change', function() {
                const file = this.files[0];
                if (!file) return;
                document.getElementById('imageNameLabel').textContent = file.name;
                const reader = new FileReader();
                reader.onload = event => {
                    document.getElementById('imagePreviewThumb').src = event.target.result;
                    document.getElementById('imagePreviewWrap').classList.remove('d-none');
                };
                reader.readAsDataURL(file);
            });
            document.getElementById('packageFileInput').addEventListener('change', function() {
                if (this.files[0]) document.getElementById('packageNameLabel').textContent = this.files[0].name;
            });

            form.addEventListener('submit', function(event) {
                const price = Number(document.getElementById('price').value || 0);
                if (document.getElementById('price_paid').checked && price < 5000) {
                    event.preventDefault();
                    alert('Harga aset berbayar minimal Rp5.000.');
                }
            });
        });
    </script>
</body>

</html>