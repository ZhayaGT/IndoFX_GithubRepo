<?php
$storeName = $storeName ?? '';
$initials  = '';
foreach (array_slice(preg_split('/\s+/', trim($storeName)) ?: [], 0, 2) as $word) {
    $initials .= mb_strtoupper(mb_substr($word, 0, 1));
}
$initials = $initials !== '' ? $initials : 'AS';

$isEdit      = $mode === 'edit';
$selected    = $asset ?? [];
$pipes       = array_map('trim', explode(',', (string) ($selected['render_pipeline'] ?? '')));
$pid         = htmlspecialchars((string) ($selected['public_id'] ?? ''), ENT_QUOTES, 'UTF-8');
$selectedCat = (int) ($selected['category_id'] ?? 0);
$unity       = (string) ($selected['unity_version'] ?? '');
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $isEdit ? 'Edit Aset' : 'Tambah Aset Baru' ?> - IndoFX</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/dashboard.css">
</head>

<body class="dashboard-body">

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

            <a href="/" class="d-flex align-items-center gap-2 text-white text-decoration-none" title="Kembali ke Beranda">
                <span class="avatar-circle"><?= htmlspecialchars($initials, ENT_QUOTES, 'UTF-8') ?></span>
                <span class="fw-bold fs-7 tracking-wide"><?= htmlspecialchars(mb_strtoupper($storeName), ENT_QUOTES, 'UTF-8') ?></span>
            </a>
        </div>
    </nav>

    <div class="dashboard-layout-container">

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
                    <a href="/dashboard/profile" class="sidebar-link">
                        <i class="bi bi-person"></i>
                        <span>Profil Penjual</span>
                    </a>
                </li>
            </ul>
        </aside>

        <main class="py-4 form-page-wrapper flex-grow-1">
            <div class="container-form">

                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                    <h2 class="dash-page-title mb-0"><?= $isEdit ? 'Edit Aset' : 'Tambah Aset Baru' ?></h2>
                    <a href="/dashboard/assets" class="btn btn-outline-secondary btn-sm px-3 fw-bold">Kembali ke Daftar Aset</a>
                </div>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger py-2 px-3 small"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
                <?php endif; ?>

                <form method="post" action="/dashboard/assets/save" enctype="multipart/form-data">
                    <?php if ($isEdit): ?>
                        <input type="hidden" name="id" value="<?= $pid ?>">
                    <?php endif; ?>

                    <div class="form-section-card p-4 mb-4">
                        <h5 class="form-section-title mb-3">Informasi Aset</h5>

                        <div class="mb-3">
                            <label for="asset_title" class="form-label form-field-label">Judul Aset *</label>
                            <input type="text" class="form-control form-field-input" id="asset_title" name="title"
                                value="<?= htmlspecialchars((string) ($selected['title'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                                placeholder="Judul Aset Anda" maxlength="150" required>
                        </div>

                        <div class="mb-3">
                            <label for="category_id" class="form-label form-field-label">Kategori Aset *</label>
                            <select class="form-select form-field-input" id="category_id" name="category_id" required>
                                <option value="" disabled <?= $selectedCat === 0 ? 'selected' : '' ?>>Pilih Minimal 1 Kategori</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= (int) $category['id'] ?>" <?= $selectedCat === (int) $category['id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($category['name'], ENT_QUOTES, 'UTF-8') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-0">
                            <label for="description" class="form-label form-field-label">Deskripsi Aset</label>
                            <textarea class="form-control form-field-input" id="description" name="description" rows="4"
                                placeholder="Tulis deskripsi lengkap aset Anda..."><?= htmlspecialchars((string) ($selected['description'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
                        </div>
                    </div>

                    <div class="form-section-card p-4 mb-4">
                        <h5 class="form-section-title mb-3">Kompatibilitas</h5>

                        <div class="mb-3">
                            <label for="unity_version" class="form-label form-field-label">Versi Unity Minimum *</label>
                            <select class="form-select form-field-input" id="unity_version" name="unity_version" required>
                                <option value="" disabled <?= $unity === '' ? 'selected' : '' ?>>Pilih versi minimum yang kompatibel</option>
                                <?php foreach (['2021.3' => 'Unity 2021.3 LTS', '2022.3' => 'Unity 2022.3 LTS', '2023.2' => 'Unity 2023.2', '6.0' => 'Unity 6'] as $value => $label): ?>
                                    <option value="<?= $value ?>" <?= $unity === $value ? 'selected' : '' ?>><?= $label ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div>
                            <label class="form-label form-field-label d-block mb-2">Render Pipeline Didukung (Pilih semua yang sesuai) *</label>
                            <div class="d-flex align-items-center gap-4 flex-wrap">
                                <?php
                                $pipeOptions = [
                                    'Built-in' => ['builtin', 'Built-in'],
                                    'URP'      => ['urp', 'Universal Render Pipeline (URP)'],
                                    'HDRP'     => ['hdrp', 'High Definition Render Pipeline (HDRP)'],
                                ];
                                foreach ($pipeOptions as $value => [$id, $label]):
                                    $checked = $isEdit ? in_array($value, $pipes, true) : $value !== 'HDRP';
                                ?>
                                    <div class="form-check">
                                        <input class="form-check-input form-checkbox-custom" type="checkbox" name="render_pipeline[]"
                                            value="<?= $value ?>" id="pipe_<?= $id ?>" <?= $checked ? 'checked' : '' ?>>
                                        <label class="form-check-label form-check-text" for="pipe_<?= $id ?>"><?= $label ?></label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-section-card p-4 mb-4">
                        <h5 class="form-section-title mb-3">Harga</h5>

                        <div class="d-flex align-items-center gap-4 mb-3">
                            <div class="form-check">
                                <input class="form-check-input form-radio-custom" type="radio" name="pricing_type" id="price_paid"
                                    value="paid" <?= (int) ($selected['price'] ?? 150000) > 0 ? 'checked' : '' ?> onchange="togglePriceInput(true)">
                                <label class="form-check-label form-check-text fw-semibold" for="price_paid">Berbayar</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input form-radio-custom" type="radio" name="pricing_type" id="price_free"
                                    value="free" <?= (int) ($selected['price'] ?? 150000) === 0 ? 'checked' : '' ?> onchange="togglePriceInput(false)">
                                <label class="form-check-label form-check-text text-muted" for="price_free">Gratis</label>
                            </div>
                        </div>

                        <div id="priceInputFieldWrapper">
                            <label for="price" class="form-label form-field-label">Harga Jual (Rupiah) *</label>
                            <div class="input-group">
                                <span class="input-group-text form-addon-rp">Rp</span>
                                <input type="number" class="form-control form-field-input" id="price" name="price" min="0"
                                    value="<?= (int) ($selected['price'] ?? 150000) ?>" placeholder="150000">
                            </div>
                        </div>
                    </div>

                    <div class="form-section-card p-4 mb-4">
                        <h5 class="form-section-title mb-3">Pratinjau Media</h5>

                        <div class="mb-3">
                            <label class="form-label form-field-label">Gambar Pratinjau Utama (16:9) <?= $isEdit ? '' : '*' ?></label>
                            <div class="dropzone-box text-center p-4" onclick="document.getElementById('image').click()">
                                <input type="file" id="image" name="image" class="d-none" accept="image/png, image/jpeg" <?= $isEdit ? '' : 'required' ?>>
                                <div class="dropzone-icon mb-2">
                                    <i class="bi bi-card-image fs-3"></i>
                                </div>
                                <div class="dropzone-title fw-bold mb-1" id="imageNameLabel">
                                    <?= $gallery !== [] ? 'Ganti gambar pratinjau' : 'Tarik & lepas gambar pratinjau di sini' ?>
                                </div>
                                <div class="dropzone-hint text-muted small">
                                    Format PNG, JPG maks 5MB.
                                    <?php if ($gallery !== []): ?>Gambar saat ini: <?= htmlspecialchars(basename($gallery[0]), ENT_QUOTES, 'UTF-8') ?>.<?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="mb-0">
                            <label for="preview_video_url" class="form-label form-field-label">Video Pratinjau (Opsional)</label>
                            <input type="url" class="form-control form-field-input" id="preview_video_url" name="preview_video_url"
                                value="<?= htmlspecialchars((string) ($selected['preview_video_url'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                                placeholder="https://www.youtube.com/watch?v=...">
                        </div>
                    </div>

                    <div class="form-section-card p-4 mb-4">
                        <h5 class="form-section-title mb-3">Panduan Penggunaan &amp; Berkas Aset</h5>

                        <div class="mb-3">
                            <label for="how_to_use" class="form-label form-field-label">Panduan Penggunaan Aset</label>
                            <textarea class="form-control form-field-input" id="how_to_use" name="how_to_use" rows="3"
                                placeholder="Tulis panduan lengkap cara penggunaan aset..."><?= htmlspecialchars((string) ($selected['how_to_use'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
                        </div>

                        <div class="mb-0">
                            <label class="form-label form-field-label">Berkas Aset (.unitypackage / .zip) <?= $isEdit ? '' : '*' ?></label>
                            <div class="dropzone-box text-center p-4" onclick="document.getElementById('package').click()">
                                <input type="file" id="package" name="package" class="d-none"
                                    accept=".unitypackage, .zip, .rar, .fbx, .blend" <?= $isEdit ? '' : 'required' ?>>
                                <div class="dropzone-icon mb-2">
                                    <i class="bi bi-box-seam fs-3"></i>
                                </div>
                                <div class="dropzone-title fw-bold mb-1" id="packageNameLabel">
                                    <?= $isEdit ? 'Ganti berkas paket (opsional)' : 'Pilih berkas package untuk diunggah' ?>
                                </div>
                                <div class="dropzone-hint text-muted small">
                                    Maksimal ukuran berkas 200 MB.
                                    <?php if ($file !== null): ?>
                                        Berkas saat ini: <?= htmlspecialchars($file['original_name'], ENT_QUOTES, 'UTF-8') ?>.
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mb-5">
                        <button type="submit" class="btn btn-submit-upload">
                            <?= $isEdit ? 'Simpan Perubahan' : 'Ajukan Pengunggahan' ?>
                        </button>
                    </div>
                </form>

            </div>
        </main>
    </div>

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

        // Input harga yang disabled tidak terkirim, jadi kembalikan nilainya sebelum submit.
        document.querySelector('form[action="/dashboard/assets/save"]').addEventListener('submit', function() {
            document.getElementById('price').removeAttribute('disabled');
        });

        document.getElementById('image').addEventListener('change', function() {
            if (this.files.length > 0) document.getElementById('imageNameLabel').innerText = this.files[0].name;
        });

        document.getElementById('package').addEventListener('change', function() {
            if (this.files.length > 0) document.getElementById('packageNameLabel').innerText = this.files[0].name;
        });
    </script>
</body>

</html>