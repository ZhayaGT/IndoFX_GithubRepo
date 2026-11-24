<?php
if (!isset($_SESSION['user_id'])) {
    header('Location: /login');
    exit;
}

$userKey  = (string) ($_SESSION['user_id'] ?? $_SESSION['name'] ?? 'default_user');
$userName = (string) ($_SESSION['username'] ?? $_SESSION['name'] ?? 'naufalhaidarazmi');
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Aset Baru - IndoFX</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS & Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/dashboard.css">

    <style>
        /* Badge jumlah gambar dipilih */
        .media-count-badge {
            background-color: #FCEEE6;
            color: #9A4A1C;
            font-size: 0.78rem;
            font-weight: 700;
            padding: 4px 12px;
            border-radius: 999px;
        }

        /* Wadah utama multi-upload gambar pratinjau */
        .multi-media-dropzone {
            background-color: #F4F4F4;
            border: 1.5px solid #9E9E9E;
            border-radius: 10px;
            padding: 16px;
            transition: border-color 0.15s ease, background-color 0.15s ease;
        }

        .multi-media-dropzone.drag-over {
            border-color: #ED6B1D;
            background-color: #FFF7F2;
        }

        .media-dropzone-hint {
            font-size: 0.82rem;
            color: #5C5552;
            margin-bottom: 12px;
        }

        /* Kartu kapasitas terpakai (0 MB / 10 MB terpakai) */
        .capacity-status-card {
            background-color: #FFFFFF;
            border: 1px solid #D6D6D6;
            border-radius: 8px;
            padding: 10px 14px;
            margin-bottom: 16px;
        }

        .capacity-progress-track {
            width: 100%;
            height: 6px;
            background-color: #E5E5E5;
            border-radius: 999px;
            overflow: hidden;
            margin-top: 8px;
        }

        .capacity-progress-fill {
            height: 100%;
            width: 0%;
            background-color: #ED6B1D;
            border-radius: 999px;
            transition: width 0.25s ease, background-color 0.2s ease;
        }

        /* Grid thumbnail gambar + tombol tambah gambar */
        .media-preview-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: stretch;
        }

        .btn-add-more-image {
            width: 155px;
            height: 115px;
            background-color: #FFFFFF;
            border: 1.5px dashed #B8B8B8;
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 6px;
            color: #4A4A4A;
            font-size: 0.82rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s ease;
            flex-shrink: 0;
        }

        .btn-add-more-image:hover {
            border-color: #ED6B1D;
            color: #ED6B1D;
            background-color: #FFF9F5;
        }

        .media-thumb-card {
            position: relative;
            width: 155px;
            height: 115px;
            border-radius: 10px;
            overflow: hidden;
            border: 1.5px solid #D0C7C0;
            background-color: #1E1E1E;
            flex-shrink: 0;
        }

        .media-thumb-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .media-thumb-badge {
            position: absolute;
            left: 6px;
            bottom: 6px;
            background-color: rgba(26, 26, 26, 0.82);
            color: #FFFFFF;
            font-size: 0.65rem;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 4px;
        }

        .media-thumb-badge.primary {
            background-color: #ED6B1D;
        }

        .btn-remove-media-thumb {
            position: absolute;
            top: 6px;
            right: 6px;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            border: none;
            background-color: rgba(220, 38, 38, 0.92);
            color: #FFFFFF;
            font-size: 0.78rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: transform 0.12s ease;
        }

        .btn-remove-media-thumb:hover {
            transform: scale(1.08);
            background-color: #B91C1C;
        }

        .btn-cancel-upload {
            background-color: #FFFFFF;
            color: #1A1A1A;
            border: 1.5px solid #D0C7C0;
            border-radius: 6px;
            font-weight: 700;
            font-size: 0.9rem;
            padding: 10px 24px;
            text-decoration: none;
            transition: background-color 0.15s ease;
        }

        .btn-cancel-upload:hover {
            background-color: #F7F1EC;
            color: #1A1A1A;
        }
    </style>
</head>

<body class="dashboard-body">

    <!-- 1. Header Navbar Oranye (Hanya Logo & Nama IndoFX) -->
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
        </div>
    </nav>

    <!-- 2. Konten Form Utama -->
    <main class="py-4 form-page-wrapper">
        <div class="container-form">

            <!-- Judul Halaman -->
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <h2 class="dash-page-title mb-0" id="formPageHeading">Tambah Aset Baru</h2>
                <a href="/dashboard/assets" class="btn btn-outline-secondary btn-sm px-3 fw-bold">Kembali ke Semua Aset Saya</a>
            </div>

            <form id="formAddAsset" novalidate>

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

                <!-- CARD 2: KOMPATIBILITAS & LISENSI -->
                <div class="form-section-card p-4 mb-4">
                    <h5 class="form-section-title mb-3">Kompatibilitas &amp; Lisensi</h5>

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

                    <div class="mb-3">
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

                    <div class="mb-0">
                        <label for="asset_license" class="form-label form-field-label">Lisensi *</label>
                        <select class="form-select form-field-input" id="asset_license" required>
                            <option selected disabled value="">Lisensi aset Anda</option>
                            <option value="Public Domain">Public Domain</option>
                            <option value="Creative Commons">Creative Commons</option>
                            <option value="Royalti">Royalti</option>
                            <option value="Open source">Open source</option>
                            <option value="Komersial">Komersial</option>
                            <option value="Personal">Personal</option>
                            <option value="Free">Free</option>
                        </select>
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
                            <input type="number" class="form-control form-field-input" id="price" value="150000" placeholder="150000" min="5000" step="1000">
                        </div>
                        <small class="text-muted mt-1 d-block" id="priceMinHint">Minimal harga jual untuk aset berbayar adalah Rp5.000.</small>
                        <div id="priceWarningAlert" class="d-none mt-2 px-3 py-2 rounded-2 small fw-semibold" style="background-color: #fef2f2; border: 1px solid #fecaca; color: #b91c1c;">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i> Peringatan: Harga jual aset berbayar tidak boleh kurang dari Rp5.000.
                        </div>
                    </div>
                </div>

                <!-- CARD 4: PRATINJAU MEDIA (MULTI-GAMBAR MAKS TOTAL 10 MB) -->
                <div class="form-section-card p-4 mb-4">
                    <h5 class="form-section-title mb-3">Pratinjau Media</h5>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label form-field-label mb-0">Gambar Pratinjau (16:9) *</label>
                            <span class="media-count-badge" id="selectedImageCountBadge">0 gambar dipilih</span>
                        </div>

                        <div class="multi-media-dropzone" id="multiImageDropzone">
                            <input type="file" id="imageFileInput" class="d-none" accept="image/png, image/jpeg, image/webp" multiple>

                            <div class="media-dropzone-hint">
                                JPG, PNG, WEBP &bull; Seret file ke area ini &bull; Maks. total <strong>10 MB</strong>
                            </div>

                            <!-- Bar Kapasitas Terpakai -->
                            <div class="capacity-status-card">
                                <div class="d-flex justify-content-between align-items-center small">
                                    <span class="fw-semibold text-dark">Kapasitas Terpakai</span>
                                    <span class="fw-semibold text-muted" id="capacityTextLabel">0 MB / 10 MB terpakai</span>
                                </div>
                                <div class="capacity-progress-track">
                                    <div class="capacity-progress-fill" id="capacityProgressBar"></div>
                                </div>
                            </div>

                            <!-- Grid Preview Gambar + Tombol Tambah Gambar -->
                            <div class="media-preview-grid" id="mediaPreviewGrid">
                                <button type="button" class="btn-add-more-image" id="btnAddMoreImage" onclick="document.getElementById('imageFileInput').click()">
                                    <i class="bi bi-plus-lg fs-4"></i>
                                    <span>Tambah Gambar</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="mb-0">
                        <label for="preview_video_url" class="form-label form-field-label">Video Pratinjau (Opsional)</label>
                        <input type="url" class="form-control form-field-input" id="preview_video_url" placeholder="URL Youtube">
                    </div>
                </div>

                <!-- CARD 5: PANDUAN PENGGUNAAN & BERKAS ASET -->
                <div class="form-section-card p-4 mb-4">
                    <h5 class="form-section-title mb-3">Panduan Penggunaan &amp; Berkas Aset</h5>

                    <div class="mb-3">
                        <label for="how_to_use" class="form-label form-field-label">Panduan Penggunaan Aset *</label>
                        <textarea class="form-control form-field-input" id="how_to_use" rows="4" placeholder="Tulis panduan lengkap cara penggunaan aset Anda..." required></textarea>
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

                <!-- TOMBOL BATAL & SUBMIT KANAN -->
                <div class="d-flex justify-content-end align-items-center gap-2 mb-5">
                    <a href="/dashboard/assets" class="btn-cancel-upload">Batal</a>
                    <button type="submit" class="btn btn-submit-upload" id="btnSubmitAsset" style="background-color: #18181B; border-color: #18181B;">
                        Ajukan Pengunggahan
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

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script Form Pengajuan, Multi-Gambar 10MB, Lisensi & Sinkronisasi Admin -->
    <script>
        const userKey = <?= json_encode($userKey, JSON_UNESCAPED_UNICODE) ?>;
        const sellerUsername = <?= json_encode($userName, JSON_UNESCAPED_UNICODE) ?>;
        const MAX_TOTAL_IMAGE_BYTES = 10 * 1024 * 1024; // 10 MB

        // Daftar gambar terpilih: [{ id, name, sizeBytes, dataUrl }]
        let uploadedImagesList = [];
        let uploadedPackageName = '';
        let uploadedPackageSize = '18 MB';
        let pendingImageLoads = 0;
        let editingAssetId = null;

        function formatBytesToMb(bytes) {
            if (!bytes || bytes <= 0) return '0 MB';
            const mb = bytes / (1024 * 1024);
            return (mb < 0.1 ? mb.toFixed(2) : mb.toFixed(1).replace(/\.0$/, '')) + ' MB';
        }

        function getTotalImageBytes() {
            return uploadedImagesList.reduce((sum, img) => sum + (Number(img.sizeBytes) || 0), 0);
        }

        function renderMediaGalleryUI() {
            const countBadge = document.getElementById('selectedImageCountBadge');
            const capText = document.getElementById('capacityTextLabel');
            const capBar = document.getElementById('capacityProgressBar');
            const grid = document.getElementById('mediaPreviewGrid');

            const count = uploadedImagesList.length;
            const totalBytes = getTotalImageBytes();
            const pct = Math.min(100, Math.round((totalBytes / MAX_TOTAL_IMAGE_BYTES) * 100));

            if (countBadge) {
                countBadge.textContent = count + ' gambar dipilih';
            }
            if (capText) {
                capText.textContent = `${formatBytesToMb(totalBytes)} / 10 MB terpakai`;
            }
            if (capBar) {
                capBar.style.width = pct + '%';
                capBar.style.backgroundColor = pct >= 90 ? '#DC2626' : '#ED6B1D';
            }

            if (!grid) return;

            const thumbsHtml = uploadedImagesList.map((item, idx) => {
                const badgeLabel = idx === 0 ? 'Utama' : `#${idx + 1} (${formatBytesToMb(item.sizeBytes)})`;
                const badgeCls = idx === 0 ? 'media-thumb-badge primary' : 'media-thumb-badge';
                return `
                    <div class="media-thumb-card" title="${String(item.name || '').replace(/"/g, '&quot;')}">
                        <img src="${item.dataUrl}" alt="Pratinjau ${idx + 1}">
                        <span class="${badgeCls}">${badgeLabel}</span>
                        <button type="button" class="btn-remove-media-thumb" onclick="removeUploadedImage('${item.id}')" title="Hapus gambar">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                `;
            }).join('');

            grid.innerHTML = thumbsHtml + `
                <button type="button" class="btn-add-more-image" id="btnAddMoreImage" onclick="document.getElementById('imageFileInput').click()">
                    <i class="bi bi-plus-lg fs-4"></i>
                    <span>Tambah Gambar</span>
                </button>
            `;
        }

        function removeUploadedImage(imgId) {
            uploadedImagesList = uploadedImagesList.filter(img => String(img.id) !== String(imgId));
            renderMediaGalleryUI();
        }

        // Kompresi gambar ringan agar banyak foto tetap aman di batas 5MB localStorage
        function compressImage(file, callback) {
            const reader = new FileReader();
            reader.onload = function(event) {
                const img = new Image();
                img.onload = function() {
                    const canvas = document.createElement('canvas');
                    const maxW = 640;
                    const scale = img.width > maxW ? (maxW / img.width) : 1;
                    canvas.width = Math.round(img.width * scale);
                    canvas.height = Math.round(img.height * scale);
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                    callback(canvas.toDataURL('image/jpeg', 0.68));
                };
                img.onerror = function() {
                    callback('');
                };
                img.src = event.target.result;
            };
            reader.onerror = function() {
                callback('');
            };
            reader.readAsDataURL(file);
        }

        function handleSelectedImageFiles(fileList) {
            if (!fileList || fileList.length === 0) return;

            const allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
            let runningTotal = getTotalImageBytes();

            Array.from(fileList).forEach(file => {
                if (!allowedTypes.includes(file.type)) {
                    alert(`Format berkas "${file.name}" tidak didukung. Gunakan JPG, PNG, atau WEBP.`);
                    return;
                }

                if (runningTotal + file.size > MAX_TOTAL_IMAGE_BYTES) {
                    alert(`Total ukuran gambar melebihi batas maksimal 10 MB! Berkas "${file.name}" tidak dapat ditambahkan.`);
                    return;
                }

                runningTotal += file.size;
                pendingImageLoads++;

                compressImage(file, function(dataUrl) {
                    pendingImageLoads = Math.max(0, pendingImageLoads - 1);
                    if (dataUrl) {
                        uploadedImagesList.push({
                            id: 'img-' + Date.now() + '-' + Math.random().toString(36).slice(2, 7),
                            name: file.name,
                            sizeBytes: file.size,
                            dataUrl: dataUrl
                        });
                        renderMediaGalleryUI();
                    }
                });
            });
        }

        function validatePriceWarning() {
            const isFree = document.getElementById('price_free').checked;
            const priceInput = document.getElementById('price');
            const warningEl = document.getElementById('priceWarningAlert');
            const hintEl = document.getElementById('priceMinHint');

            if (isFree) {
                warningEl.classList.add('d-none');
                hintEl.classList.remove('d-none');
                priceInput.style.borderColor = '';
                return true;
            }

            const val = Number(priceInput.value);
            if (priceInput.value.trim() === '' || isNaN(val) || val < 5000) {
                warningEl.classList.remove('d-none');
                hintEl.classList.add('d-none');
                priceInput.style.borderColor = '#dc2626';
                return false;
            } else {
                warningEl.classList.add('d-none');
                hintEl.classList.remove('d-none');
                priceInput.style.borderColor = '';
                return true;
            }
        }

        function togglePriceInput(isPaid) {
            const priceInput = document.getElementById('price');
            const wrapper = document.getElementById('priceInputFieldWrapper');
            if (isPaid) {
                wrapper.classList.remove('opacity-50');
                priceInput.removeAttribute('disabled');
                if ((parseFloat(priceInput.value) || 0) < 5000) {
                    priceInput.value = '150000';
                }
            } else {
                wrapper.classList.add('opacity-50');
                priceInput.value = '0';
                priceInput.setAttribute('disabled', 'true');
            }
            validatePriceWarning();
        }

        function syncAdminCategoriesToDropdown() {
            const selectEl = document.getElementById('category_id');
            if (!selectEl) return;

            try {
                const storedCats = JSON.parse(localStorage.getItem('indofx_admin_categories'));
                if (Array.isArray(storedCats) && storedCats.length > 0) {
                    selectEl.innerHTML = '<option selected disabled value="">Pilih Minimal 1 Kategori</option>' +
                        storedCats.map(c =>
                            `<option value="${String(c.name).replace(/"/g, '&quot;')}">${c.name}</option>`
                        ).join('');
                }
            } catch (e) {}
        }

        // Helper simpan aman agar QuotaExceededError tidak pernah menghentikan tombol Submit
        function safeSetLocalStorage(key, valueObj) {
            try {
                localStorage.setItem(key, JSON.stringify(valueObj));
                return true;
            } catch (e) {
                if (Array.isArray(valueObj) && valueObj.length > 0) {
                    const compacted = valueObj.slice(0, 6).map((item, idx) => {
                        if (idx > 0 && Array.isArray(item.images) && item.images.length > 1) {
                            return Object.assign({}, item, {
                                images: [item.images[0]]
                            });
                        }
                        return item;
                    });
                    try {
                        localStorage.setItem(key, JSON.stringify(compacted));
                        return true;
                    } catch (e2) {}
                }
                return false;
            }
        }

        function loadEditAssetIfRequested() {
            const params = new URLSearchParams(window.location.search);
            const editId = params.get('edit_id');
            if (!editId) return;

            let found = null;
            try {
                const sAssets = JSON.parse(localStorage.getItem('indofx_seller_assets_' + userKey)) || [];
                found = sAssets.find(a => String(a.id) === String(editId));
            } catch (e) {}

            if (!found) {
                try {
                    const qAssets = JSON.parse(localStorage.getItem('indofx_moderation_queue')) || [];
                    found = qAssets.find(a => String(a.id) === String(editId));
                } catch (e) {}
            }

            if (!found) return;

            editingAssetId = found.id;
            const headingEl = document.getElementById('formPageHeading');
            if (headingEl) headingEl.innerText = 'Edit / Revisi Aset';

            document.getElementById('asset_title').value = found.title || '';
            if (found.category) document.getElementById('category_id').value = found.category;
            document.getElementById('description').value = found.description || '';
            if (found.unity_version) {
                const cleanVer = String(found.unity_version).replace(/[^0-9.]/g, '');
                if (cleanVer) document.getElementById('unity_version').value = cleanVer;
            }
            if (found.license) {
                document.getElementById('asset_license').value = found.license;
            }

            const pipes = Array.isArray(found.pipelines) ? found.pipelines : [found.pipeline || 'URP'];
            document.querySelectorAll('input[name="render_pipeline"]').forEach(cb => {
                cb.checked = pipes.some(p => String(p).toLowerCase() === cb.value.toLowerCase());
            });

            const numPrice = typeof found.price_num === 'number' ?
                found.price_num :
                (parseFloat(String(found.price || '0').replace(/[^0-9]/g, '')) || 0);

            if (numPrice <= 0) {
                document.getElementById('price_free').checked = true;
                togglePriceInput(false);
            } else {
                document.getElementById('price_paid').checked = true;
                togglePriceInput(true);
                document.getElementById('price').value = numPrice;
            }

            const existingImgs = Array.isArray(found.images) && found.images.length > 0 ?
                found.images :
                (found.image ? [found.image] : []);

            uploadedImagesList = existingImgs.map((url, i) => ({
                id: 'img-existing-' + i,
                name: 'gambar_' + (i + 1) + '.jpg',
                sizeBytes: Math.round((url.length * 3) / 4),
                dataUrl: url
            }));
            renderMediaGalleryUI();

            if (found.video_url) document.getElementById('preview_video_url').value = found.video_url;
            if (found.how_to_use) document.getElementById('how_to_use').value = found.how_to_use;
            if (found.file_name) {
                uploadedPackageName = found.file_name;
                document.getElementById('packageNameLabel').innerText = found.file_name;
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            syncAdminCategoriesToDropdown();
            loadEditAssetIfRequested();

            // Ambil Nama Studio dari profil penjual
            const profileRaw = localStorage.getItem('indofx_seller_profile_' + userKey) ||
                localStorage.getItem('indofx_seller_profile');
            let storeName = sellerUsername || 'Studio Kreator';
            if (profileRaw) {
                try {
                    const prof = JSON.parse(profileRaw);
                    if (prof && prof.store_name) storeName = prof.store_name;
                } catch (e) {}
            }

            // Peringatan real-time jika harga diinput di bawah Rp5.000
            const priceInput = document.getElementById('price');
            priceInput.addEventListener('input', validatePriceWarning);
            priceInput.addEventListener('change', validatePriceWarning);
            priceInput.addEventListener('blur', validatePriceWarning);

            // Input Multi-Gambar Pratinjau
            const imgInput = document.getElementById('imageFileInput');
            imgInput.addEventListener('change', function() {
                handleSelectedImageFiles(this.files);
                this.value = '';
            });

            // Drag & Drop pada area Pratinjau Media
            const dropzone = document.getElementById('multiImageDropzone');
            if (dropzone) {
                ['dragenter', 'dragover'].forEach(evtName => {
                    dropzone.addEventListener(evtName, function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        dropzone.classList.add('drag-over');
                    });
                });

                ['dragleave', 'drop'].forEach(evtName => {
                    dropzone.addEventListener(evtName, function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        dropzone.classList.remove('drag-over');
                    });
                });

                dropzone.addEventListener('drop', function(e) {
                    if (e.dataTransfer && e.dataTransfer.files) {
                        handleSelectedImageFiles(e.dataTransfer.files);
                    }
                });
            }

            // Indikator Berkas Package
            const pkgInput = document.getElementById('packageFileInput');
            pkgInput.addEventListener('change', function() {
                if (this.files && this.files.length > 0) {
                    const file = this.files[0];
                    uploadedPackageName = file.name;
                    const mb = (file.size / (1024 * 1024)).toFixed(1);
                    uploadedPackageSize = (mb > 0 ? mb : '0.5') + ' MB';
                    document.getElementById('packageNameLabel').innerText = uploadedPackageName;
                }
            });

            // Form Submit Logic
            document.getElementById('formAddAsset').addEventListener('submit', function(e) {
                e.preventDefault();

                const title = document.getElementById('asset_title').value.trim();
                const category = document.getElementById('category_id').value;
                const description = document.getElementById('description').value.trim();
                const unityVer = document.getElementById('unity_version').value;
                const license = document.getElementById('asset_license').value;
                const howToUse = document.getElementById('how_to_use').value.trim();
                const videoUrl = document.getElementById('preview_video_url').value.trim();

                if (!title) {
                    alert('Judul Aset wajib diisi.');
                    document.getElementById('asset_title').focus();
                    return;
                }
                if (!category) {
                    alert('Silakan pilih Kategori Aset.');
                    document.getElementById('category_id').focus();
                    return;
                }
                if (!description) {
                    alert('Deskripsi Aset wajib diisi.');
                    document.getElementById('description').focus();
                    return;
                }
                if (!unityVer) {
                    alert('Silakan pilih Versi Unity Minimum.');
                    document.getElementById('unity_version').focus();
                    return;
                }

                const selectedPipes = [];
                document.querySelectorAll('input[name="render_pipeline"]:checked').forEach(cb => {
                    selectedPipes.push(cb.value);
                });
                if (selectedPipes.length === 0) {
                    alert('Pilih minimal satu Render Pipeline yang didukung.');
                    return;
                }

                if (!license) {
                    alert('Silakan pilih Lisensi aset Anda.');
                    document.getElementById('asset_license').focus();
                    return;
                }

                const isFree = document.getElementById('price_free').checked;
                const rawPrice = isFree ? 0 : (Number(document.getElementById('price').value) || 0);
                if (!validatePriceWarning()) {
                    document.getElementById('price').scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                    document.getElementById('price').focus();
                    alert('Peringatan: Harga jual aset berbayar minimal Rp5.000.');
                    return;
                }

                if (pendingImageLoads > 0) {
                    alert('Gambar sedang diproses, silakan klik Ajukan Pengunggahan sekali lagi.');
                    return;
                }

                if (uploadedImagesList.length === 0) {
                    alert('Silakan tambahkan minimal 1 Gambar Pratinjau (16:9).');
                    return;
                }

                if (!howToUse) {
                    alert('Panduan Penggunaan Aset wajib diisi.');
                    document.getElementById('how_to_use').focus();
                    return;
                }

                const priceFormatted = isFree ? 'Gratis' : ('Rp' + rawPrice.toLocaleString('id-ID'));
                const allImageUrls = uploadedImagesList.map(img => img.dataUrl);
                const primaryImageUrl = allImageUrls[0];

                const assetId = editingAssetId || ('asset-' + Date.now());
                const dateStr = new Date().toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'short',
                    year: 'numeric'
                });
                const fileName = uploadedPackageName || (title.toLowerCase().replace(/[^a-z0-9]/g, '_') + '.unitypackage');

                // 1. Simpan ke daftar aset toko pengguna
                const newAsset = {
                    id: assetId,
                    title: title,
                    category: category,
                    description: description,
                    how_to_use: howToUse,
                    license: license,
                    price: priceFormatted,
                    price_num: rawPrice,
                    pipelines: selectedPipes,
                    unity_version: unityVer,
                    status: 'Pending',
                    downloads: '0x Unduhan',
                    sales: '0x Terjual',
                    created_at: dateStr,
                    date: dateStr,
                    file_name: fileName,
                    file_info: 'File: ' + fileName + ' (' + uploadedPackageSize + ')',
                    image: primaryImageUrl,
                    preview_image: primaryImageUrl,
                    images: allImageUrls,
                    video_url: videoUrl,
                    seller: storeName,
                    creator: storeName
                };

                let assets = [];
                try {
                    assets = JSON.parse(localStorage.getItem('indofx_seller_assets_' + userKey)) || [];
                } catch (err) {
                    assets = [];
                }
                assets = assets.filter(a => String(a.id) !== String(assetId));
                assets.unshift(newAsset);
                safeSetLocalStorage('indofx_seller_assets_' + userKey, assets);

                // 2. Sinkronkan ke antrean moderasi admin
                let adminQueue = [];
                try {
                    adminQueue = JSON.parse(localStorage.getItem('indofx_moderation_queue')) || [];
                } catch (err) {
                    adminQueue = [];
                }

                adminQueue = adminQueue.filter(q => String(q.id) !== String(assetId));
                adminQueue.unshift({
                    id: assetId,
                    title: title,
                    pipeline: selectedPipes[0],
                    pipelines: selectedPipes,
                    category: category,
                    license: license,
                    file_name: fileName,
                    file_info: 'File: ' + fileName + ' (' + uploadedPackageSize + ')',
                    studio: storeName,
                    creator: storeName,
                    seller: storeName,
                    unity_ver: 'Unity ' + unityVer + ' LTS',
                    price: priceFormatted,
                    price_num: rawPrice,
                    date: dateStr,
                    created_at: dateStr,
                    time: 'Hari ini',
                    status: 'Pending',
                    is_read: false,
                    seller_storage_key: 'indofx_seller_assets_' + userKey,
                    seller_key: 'indofx_seller_assets_' + userKey,
                    description: description,
                    how_to_use: howToUse,
                    video_url: videoUrl,
                    image: primaryImageUrl,
                    preview_image: primaryImageUrl,
                    images: allImageUrls
                });
                safeSetLocalStorage('indofx_moderation_queue', adminQueue);

                alert('Aset berhasil diajukan! Status saat ini: "Menunggu" verifikasi Administrator.');
                window.location.href = '/dashboard/assets';
            });
        });
    </script>
</body>

</html>