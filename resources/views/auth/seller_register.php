<?php
$isLoggedIn = isset($_SESSION['user_id']);
$userKey    = $isLoggedIn ? (string)($_SESSION['user_id'] ?? $_SESSION['name'] ?? 'default_user') : '';
$userName   = $isLoggedIn ? (string)($_SESSION['name'] ?? $_SESSION['user_name'] ?? '') : '';
$userEmail  = $isLoggedIn ? (string)($_SESSION['email'] ?? 'naufal@example.com') : '';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Jadi Penjual - IndoFX</title>

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
            align-items: center;
            justify-content: center;
            padding: 40px 15px;
            font-family: 'Inter', sans-serif;
        }

        .seller-register-card {
            background-color: #ffffff;
            border: 1.5px solid #4A3B32;
            border-radius: 16px;
            width: 100%;
            max-width: 560px;
            padding: 36px 32px;
            box-shadow: 0 10px 30px rgba(60, 42, 33, 0.08);
        }

        .seller-register-title {
            font-size: 1.6rem;
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
            font-size: 0.86rem;
            font-weight: 700;
            color: #1A1A1A;
            margin-bottom: 6px;
        }

        .form-control-custom {
            border: 1.5px solid #D8C8BD;
            border-radius: 6px;
            padding: 9px 14px;
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
        }

        .btn-register-submit:hover {
            background-color: #D85A0E;
        }
    </style>
</head>

<body>

    <div class="seller-register-card">
        <h2 class="seller-register-title">Daftar Jadi Penjual</h2>
        <p class="seller-register-sub">Upload hasil karyamu agar dikenal semua orang.</p>

        <form id="formRegisterSeller">
            <!-- 1. Nama Lengkap / Studio -->
            <div class="mb-3">
                <label class="form-label-custom">Nama Lengkap / Studio</label>
                <input type="text" id="regName" class="form-control form-control-custom"
                    value="<?= htmlspecialchars($userName, ENT_QUOTES, 'UTF-8') ?>"
                    placeholder="Contoh: Arga Studio" required>
            </div>

            <!-- 2. Alamat Email -->
            <div class="mb-3">
                <label class="form-label-custom">Alamat Email</label>
                <input type="email" id="regEmail" class="form-control form-control-custom"
                    value="<?= htmlspecialchars($userEmail, ENT_QUOTES, 'UTF-8') ?>"
                    placeholder="email@domain.com" required <?= $isLoggedIn ? 'readonly style="background-color: #faf5f0;"' : '' ?>>
            </div>

            <!-- 3. Alamat Domisili -->
            <div class="mb-3">
                <label class="form-label-custom">Alamat Domisili</label>
                <textarea id="regAddress" class="form-control form-control-custom" rows="2"
                    placeholder="Jalan, RT/RW, Kelurahan, Kecamatan, Kota, Provinsi, Kode Pos." required></textarea>
            </div>

            <!-- 4. Alasan Menjadi Penjual -->
            <div class="mb-3">
                <label class="form-label-custom">Alasan</label>
                <textarea id="regReason" class="form-control form-control-custom" rows="2"
                    placeholder="Alasan kamu menjadi penjual..." required></textarea>
            </div>

            <!-- 5. Contoh Aset (File Upload) -->
            <div class="mb-3">
                <label class="form-label-custom">Contoh Aset</label>
                <input type="file" id="regAssetFile" class="form-control form-control-custom" accept=".unitypackage,.zip,.rar,.fbx" required>
            </div>

            <!-- 6. Deskripsi Aset -->
            <div class="mb-3">
                <label class="form-label-custom">Deskripsi Aset</label>
                <textarea id="regAssetDesc" class="form-control form-control-custom" rows="2"
                    placeholder="Deskripsi Aset..." required></textarea>
            </div>

            <!-- 7. URL Portofolio (Opsional) -->
            <div class="mb-3">
                <label class="form-label-custom">URL Portofolio (Opsional)</label>
                <input type="url" id="regPortfolio" class="form-control form-control-custom"
                    placeholder="http://www.domainanda.com/">
            </div>

            <!-- 8. Password: Hanya tampil jika pengunjung belum login (Guest) -->
            <?php if (!$isLoggedIn): ?>
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label-custom">Buat Kata Sandi</label>
                        <input type="password" id="regPassword" class="form-control form-control-custom" placeholder="••••••••" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label-custom">Ulangi Kata Sandi</label>
                        <input type="password" id="regPasswordConfirm" class="form-control form-control-custom" placeholder="••••••••" required>
                    </div>
                </div>
            <?php endif; ?>

            <div class="mb-3">
                <small class="text-muted">Sudah punya akun? <a href="/login" class="text-primary text-decoration-none fw-bold">Login</a></small>
            </div>

            <button type="submit" class="btn-register-submit">Daftar</button>
        </form>
    </div>

    <script>
        const isLoggedIn = <?= $isLoggedIn ? 'true' : 'false' ?>;
        const currentUserKey = '<?= $userKey ?>';

        document.getElementById('formRegisterSeller').addEventListener('submit', function(e) {
            e.preventDefault();

            const sellerName = document.getElementById('regName').value.trim() || 'Arga Studio';
            const sellerEmail = document.getElementById('regEmail').value.trim();
            const sellerAddress = document.getElementById('regAddress').value.trim();
            const sellerReason = document.getElementById('regReason').value.trim();
            const portfolioUrl = document.getElementById('regPortfolio') ? document.getElementById('regPortfolio').value.trim() : '';
            const fileInput = document.getElementById('regAssetFile');
            const assetDesc = document.getElementById('regAssetDesc').value.trim();

            const targetKey = currentUserKey || ('user_' + Date.now());

            // 1. Simpan Profil Toko / Penjual
            const sellerProfile = {
                user_id: targetKey,
                store_name: sellerName,
                is_seller: true,
                email: sellerEmail,
                address: sellerAddress,
                reason: sellerReason,
                portfolio: portfolioUrl || '-',
                is_active: true
            };

            // Simpan ganda (dengan targetKey dan global) agar selalu terbaca oleh navbar
            localStorage.setItem('indofx_seller_profile_' + targetKey, JSON.stringify(sellerProfile));
            localStorage.setItem('indofx_is_seller_' + targetKey, 'true');
            localStorage.setItem('indofx_seller_profile', JSON.stringify(sellerProfile));
            localStorage.setItem('indofx_is_seller', 'true');

            // 2. Ambil File Sampel dan Masukkan sebagai Aset Pertama (Status: Pending)
            const sampleFileName = (fileInput && fileInput.files.length > 0) ?
                fileInput.files[0].name.replace(/\.[^/.]+$/, "") :
                'Sample Asset Project';

            const newPendingAsset = {
                id: 'asset-' + Date.now(),
                title: sampleFileName,
                category: 'VFX Partikel',
                price: 'Rp150.000',
                status: 'Pending',
                created_at: new Date().toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'short',
                    year: 'numeric'
                }),
                description: assetDesc
            };

            let sellerAssets = [];
            try {
                sellerAssets = JSON.parse(localStorage.getItem('indofx_seller_assets_' + targetKey)) || [];
            } catch (err) {
                sellerAssets = [];
            }

            sellerAssets.unshift(newPendingAsset);
            localStorage.setItem('indofx_seller_assets_' + targetKey, JSON.stringify(sellerAssets));

            // 3. Arahkan kembali ke halaman Katalog Utama
            window.location.href = '/';
        });
    </script>
</body>

</html>