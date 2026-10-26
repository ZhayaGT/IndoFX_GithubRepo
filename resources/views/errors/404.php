<?php

/**
 * Halaman 404.
 * Dipakai oleh Router dan oleh rute yang view-nya belum tersedia.
 */
http_response_code(404);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Tidak Ditemukan - IndoFX</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css">
</head>

<body class="d-flex align-items-center justify-content-center" style="min-height: 100vh; background-color: var(--ifx-cream-bg);">
    <main class="text-center px-3">
        <h1 class="fw-bold mb-2" style="color: var(--ifx-brown-dark);">404</h1>
        <p class="mb-4" style="color: var(--ifx-brown-muted);">
            Halaman ini belum tersedia atau alamatnya salah.
        </p>
        <a href="/" class="btn btn-hero-orange">Kembali ke Beranda</a>
    </main>
</body>

</html>
