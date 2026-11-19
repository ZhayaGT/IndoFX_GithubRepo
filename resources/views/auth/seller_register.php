<?php
$isLoggedIn = isset($_SESSION['user_id']);
$userName   = $isLoggedIn ? (string) ($_SESSION['name'] ?? '') : '';
$userEmail  = $isLoggedIn ? (string) ($_SESSION['email'] ?? '') : '';
$error      = $error ?? null;
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

        <?php if ($error): ?>
            <div class="alert alert-danger py-2 px-3 small"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
        <?php endif; ?>

        <?php if (!$isLoggedIn): ?>
            <div class="alert alert-warning py-2 px-3 small">
                Masuk dulu untuk mendaftar sebagai penjual.
                <a href="/login" class="fw-bold text-decoration-none">Login</a>
            </div>
        <?php else: ?>
            <form id="formRegisterSeller" method="post" action="/seller/register">
                <div class="mb-3">
                    <label class="form-label-custom" for="regName">Nama Toko / Studio</label>
                    <input type="text" id="regName" name="store_name" class="form-control form-control-custom"
                        value="<?= htmlspecialchars($userName, ENT_QUOTES, 'UTF-8') ?>"
                        placeholder="Contoh: Arga Studio" maxlength="150" required>
                </div>

                <div class="mb-3">
                    <label class="form-label-custom" for="regEmail">Alamat Email</label>
                    <input type="email" id="regEmail" class="form-control form-control-custom"
                        value="<?= htmlspecialchars($userEmail, ENT_QUOTES, 'UTF-8') ?>"
                        placeholder="email@domain.com" readonly style="background-color: #faf5f0;">
                </div>

                <div class="mb-3">
                    <label class="form-label-custom" for="regAddress">Alamat Domisili</label>
                    <textarea id="regAddress" name="address" class="form-control form-control-custom" rows="2"
                        placeholder="Jalan, RT/RW, Kelurahan, Kecamatan, Kota, Provinsi, Kode Pos."></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label-custom" for="regReason">Alasan</label>
                    <textarea id="regReason" name="reason" class="form-control form-control-custom" rows="2"
                        placeholder="Alasan kamu menjadi penjual..."></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label-custom" for="regPortfolio">URL Portofolio (Opsional)</label>
                    <input type="url" id="regPortfolio" name="portfolio_url" class="form-control form-control-custom"
                        placeholder="http://www.domainanda.com/">
                </div>

                <div class="mb-3">
                    <small class="text-muted">Sudah punya akun? <a href="/login" class="text-primary text-decoration-none fw-bold">Login</a></small>
                </div>

                <button type="submit" class="btn-register-submit">Daftar</button>
            </form>
        <?php endif; ?>
    </div>

</body>

</html>