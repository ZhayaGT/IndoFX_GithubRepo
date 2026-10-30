<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Jadi Penjual - IndoFX</title>

    <!-- Google Fonts & Bootstrap 5 -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css">

    <style>
        body {
            background-color: var(--ifx-cream-bg, #fdece2);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 16px;
        }

        .seller-form-card {
            background-color: #ffffff;
            border: 1.5px solid var(--ifx-border-dark, #3b2314);
            border-radius: 14px;
            width: 100%;
            max-width: 580px;
            padding: 38px 34px;
            box-shadow: 0 8px 24px rgba(59, 35, 20, 0.06);
        }

        .form-title {
            font-size: 1.85rem;
            font-weight: 800;
            color: var(--ifx-brown-dark, #3b2314);
            letter-spacing: -0.5px;
            margin-bottom: 4px;
        }

        .form-subtitle {
            font-size: 0.88rem;
            color: var(--ifx-brown-muted, #6e5a4f);
            margin-bottom: 24px;
        }

        .form-label-custom {
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--ifx-brown-dark, #3b2314);
            margin-bottom: 6px;
            display: block;
        }

        .form-control-custom {
            border: 1.5px solid #6e5a4f;
            border-radius: 6px;
            padding: 9px 12px;
            font-size: 0.84rem;
            color: var(--ifx-brown-dark, #3b2314);
            width: 100%;
            transition: border-color 0.15s ease;
        }

        .form-control-custom:focus {
            outline: none;
            border-color: var(--ifx-orange, #ed6b1d);
            box-shadow: 0 0 0 3px rgba(237, 107, 29, 0.18);
        }

        .form-control-custom::placeholder {
            color: #a89a91;
            font-size: 0.82rem;
        }

        .btn-submit-seller {
            background-color: var(--ifx-orange, #ed6b1d);
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.95rem;
            padding: 11px 0;
            width: 100%;
            box-shadow: 0 4px 10px rgba(237, 107, 29, 0.28);
            transition: all 0.2s ease;
        }

        .btn-submit-seller:hover {
            background-color: var(--ifx-orange-hover, #d95d12);
            transform: translateY(-1px);
        }

        .login-redirect-text {
            font-size: 0.78rem;
            color: var(--ifx-brown-dark, #3b2314);
            margin-bottom: 14px;
        }

        .login-redirect-link {
            color: #0d6efd;
            text-decoration: underline;
            text-underline-offset: 2px;
        }
    </style>
</head>

<body>

    <div class="seller-form-card">
        <h1 class="form-title">Daftar Jadi Penjual</h1>
        <p class="form-subtitle">Upload hasil karyamu agar dikenal semua orang.</p>

        <form action="/seller/register" method="POST" enctype="multipart/form-data">
            <!-- Nama Lengkap -->
            <div class="mb-3">
                <label class="form-label-custom" for="name">Nama Lengkap</label>
                <input type="text" class="form-control-custom" id="name" name="name"
                    value="<?= htmlspecialchars($_SESSION['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    placeholder="Nama Lengkap / Studio" required>
            </div>

            <!-- Alamat Email -->
            <div class="mb-3">
                <label class="form-label-custom" for="email">Alamat Email</label>
                <input type="email" class="form-control-custom" id="email" name="email"
                    placeholder="email@domain.com" required>
            </div>

            <!-- Alamat Domisili -->
            <div class="mb-3">
                <label class="form-label-custom" for="domicile">Alamat Domisili</label>
                <textarea class="form-control-custom" id="domicile" name="domicile" rows="3"
                    placeholder="Jalan, RT/RW, Kelurahan, Kecamatan, Kota, Provinsi, Kode Pos." required></textarea>
            </div>

            <!-- Alasan -->
            <div class="mb-3">
                <label class="form-label-custom" for="reason">Alasan</label>
                <textarea class="form-control-custom" id="reason" name="reason" rows="3"
                    placeholder="Alasan kamu menjadi penjual..." required></textarea>
            </div>

            <!-- Contoh Aset -->
            <div class="mb-3">
                <label class="form-label-custom" for="asset_sample">Contoh Aset</label>
                <input type="file" class="form-control form-control-custom" id="asset_sample" name="asset_sample" required>
            </div>

            <!-- Deskripsi Aset -->
            <div class="mb-3">
                <label class="form-label-custom" for="asset_description">Deskripsi Aset</label>
                <textarea class="form-control-custom" id="asset_description" name="asset_description" rows="3"
                    placeholder="Deskripsi Aset..." required></textarea>
            </div>

            <!-- URL Portofolio (Opsional) -->
            <div class="mb-3">
                <label class="form-label-custom" for="portfolio_url">URL Portofolio (Opsional)</label>
                <input type="url" class="form-control-custom" id="portfolio_url" name="portfolio_url"
                    placeholder="http://www.domainanda.com/">
            </div>

            <!-- Baris Kata Sandi -->
            <div class="row g-2 mb-3">
                <div class="col-6">
                    <label class="form-label-custom" for="password">Buat Kata Sandi</label>
                    <input type="password" class="form-control-custom" id="password" name="password"
                        placeholder="********" required>
                </div>
                <div class="col-6">
                    <label class="form-label-custom" for="password_confirmation">Ulangi Kata Sandi</label>
                    <input type="password" class="form-control-custom" id="password_confirmation" name="password_confirmation"
                        placeholder="********" required>
                </div>
            </div>

            <div class="login-redirect-text">
                Sudah punya akun? <a href="/login" class="login-redirect-link">Login</a>
            </div>

            <button type="submit" class="btn btn-submit-seller">Daftar</button>
        </form>
    </div>

</body>

</html>