# IndoFX

Platform marketplace & komunitas aset digital Unity CG dan VFX untuk pengembang game, 3D/VFX artist, dan mahasiswa di Indonesia.

**Tech stack:** PHP native (PDO) · Bootstrap 5 · PostgreSQL · JavaScript · Midtrans Snap

---

## Struktur Folder

```
IndoFX/
├── .github/                     # Konfigurasi kolaborasi GitHub
│   ├── workflows/ci.yml         # CI: lint PHP + pengujian
│   ├── ISSUE_TEMPLATE/          # Template bug, fitur, tugas
│   ├── PULL_REQUEST_TEMPLATE.md # Checklist wajib saat membuka PR
│   └── CODEOWNERS               # Pemilik folder per peran
│
├── app/                         # [BACK END] Logika aplikasi
│   ├── Config/                  # app.php, database.php, midtrans.php, mail.php
│   ├── Core/                    # Router, Request, Database, Session, Auth, Csrf, Validator, View
│   ├── Controllers/             # Auth, Asset, Checkout, Payment, Invoice, Download, Admin
│   ├── Models/                  # User, Asset, Transaction, Entitlement, Review
│   ├── Services/                # MidtransService, MailService, InvoiceService, StorageService
│   ├── Middleware/              # Auth, Role, Csrf
│   └── Helpers/                 # format.php, markdown.php
│
├── routes/
│   └── web.php                  # [BACK END] Peta URL aplikasi
│
├── resources/                   # Sumber tampilan
│   ├── views/                   # [FRONT END] Template PHP
│   │   ├── layouts/             # Kerangka halaman + navbar/footer Bootstrap
│   │   ├── partials/            # Komponen berulang: kartu aset, badge pipeline, pagination
│   │   ├── auth/                # Masuk, daftar, lupa sandi
│   │   ├── catalog/             # Beranda, katalog, pencarian, filter
│   │   ├── asset/               # Detail aset, preview media, ulasan
│   │   ├── checkout/            # Keranjang, checkout, tombol Midtrans Snap
│   │   ├── invoice/             # Invoice ramah cetak (@media print)
│   │   ├── dashboard/           # Dashboard pembeli & penjual
│   │   ├── admin/               # Panel admin
│   │   ├── profile/             # Profil & pengaturan akun
│   │   └── errors/              # 404, 403, 500
│   ├── css/                     # [UI/UX] SCSS/CSS sumber sebelum dikompilasi
│   └── js/                      # [FRONT END] JS sumber (modular)
│
├── public/                      # Satu-satunya folder yang diakses web
│   ├── index.php                # Front controller
│   ├── .htaccess                # Rewrite + header keamanan
│   └── assets/                  # [FRONT END] Aset siap saji
│       ├── css/                 # Hasil kompilasi
│       ├── js/                  # Hasil kompilasi
│       ├── img/
│       └── vendor/              # Bootstrap, ikon (via Composer/npm)
│
├── database/                    # [BACK END]
│   ├── schema.sql               # Skema lengkap PostgreSQL
│   ├── migrations/              # Perubahan skema bernomor
│   └── seeders/                 # Data awal & data uji
│
├── storage/                     # Runtime, tidak di-commit
│   ├── logs/
│   ├── cache/
│   ├── invoices/                # Arsip invoice
│   └── uploads/tmp/             # Unggahan sementara sebelum ke Google Drive
│
├── tests/
│   ├── Feature/                 # Uji alur: checkout, webhook, unduhan
│   └── Unit/                    # Uji fungsi: validasi, format harga
│
├── design/                      # [UI/UX]
│   ├── wireframe/               # Wireframe tiap halaman
│   ├── prototype/               # Prototipe interaktif
│   └── design-system/           # Palet warna, tipografi, komponen
│
├── docs/                        # [PM] Dokumentasi proyek
│   ├── arsitektur.md
│   ├── erd.md
│   ├── api.md
│   ├── setup.md
│   └── panduan-kontribusi.md
│
├── .env.example                 # Contoh variabel lingkungan
├── .gitignore
├── composer.json
├── CONTRIBUTING.md              # Aturan kolaborasi GitHub
└── README.md
```

---

## Pembagian Peran

Setiap anggota memiliki satu peran utama dan **folder yang menjadi tanggung jawabnya**. Ini mencegah dua orang mengedit berkas yang sama.

| Peran | Penanggung jawab | Folder utama |
|---|---|---|
| Project Manager | Jonathan Emmanual Kristanto | `docs/`, `.github/`, `README.md` |
| UI/UX Designer | Hikmal Fadhillah Rasyid Andro Mulyawan | `design/`, `resources/css/` |
| UI/UX Designer | Dhafir Tsabit | `design/`, `resources/css/` |
| Front End Developer | Naufal Haidar Azmi | `resources/views/`, `resources/js/`, `public/assets/` |
| Back End Developer | Diaz Prahardyan | `app/`, `routes/`, `database/`, `tests/` |

Area milik peran lain boleh dibaca, tetapi perubahan harus melalui pull request dan persetujuan pemiliknya.

### Kontributor

| Nama | Akun GitHub | Kontribusi |
|---|---|---|
| Jonathan Emmanual Kristanto | [@ZhayaGT](https://github.com/ZhayaGT) | Project manager; navbar auth, katalog beranda dan eksplorasi, detail aset, pencarian, integrasi branch |
| Diaz Prahardyan | [@Dzy-hue](https://github.com/Dzy-hue) | Back end: PDO wrapper, router, autentikasi login/register/logout, skema `database/Script marketplace_game_assets.sql` (tabel, seed, view `vw_katalog_publik`, trigger entitlement, fungsi royalti), `HomeController` |
| Naufal Haidar Azmi | [@naufal2323](https://github.com/naufal2323) | Front end: halaman login dan register, eksplorasi katalog, styling |
| Hikmal Fadhillah Rasyid Andro Mulyawan | - | UI/UX design |
| Dhafir Tsabit | - | UI/UX design |

Riwayat kontribusi per orang bisa dilihat dengan `git log --author="<nama>"`.

---

## Aturan Kolaborasi GitHub (Ringkasan)

Aturan lengkap ada di **[CONTRIBUTING.md](CONTRIBUTING.md)**.

### Branch

| Branch | Fungsi |
|---|---|
| `main` | Stabil, siap demo. Dilarang push langsung. |
| `develop` | Gabungan fitur sebelum rilis. |
| `feat/*`, `fix/*`, `docs/*`, `chore/*`, `design/*` | Branch kerja sementara. |

Pola: `<tipe>/<peran>-<topik>` → contoh `feat/be-filter-pipeline`, `design/ux-wireframe-checkout`.

### Commit

Conventional Commits: `<tipe>(<lingkup>): <deskripsi>`

```
feat(be): tambah endpoint filter render pipeline
feat(fe): integrasi Midtrans Snap pada checkout
fix(be): cegah entitlement ganda saat webhook berulang
docs(pm): tambah langkah konfigurasi Midtrans
design(ux): tambah wireframe halaman invoice
```

### Alur Pull Request

```
develop ──► feat/be-filter-pipeline ──► PR ke develop ──► review 1 orang ──► merge
```

1. Buat branch dari `develop` terbaru.
2. Kerjakan dan uji lokal.
3. Buka PR ke `develop`, isi template lengkap, tautkan issue (`Closes #12`).
4. Minta review pemilik area (lihat `CODEOWNERS`).
5. Merge setelah **1 persetujuan** dan CI hijau.
6. Hapus branch.

### Aturan Wajib

1. Jangan pernah menulis kredensial di kode. Gunakan `.env`.
2. Jangan mengubah `main` secara langsung.
3. Perubahan skema database harus diumumkan sebelum digabungkan.
4. Query wajib memakai **prepared statement PDO**; output di-escape dengan `htmlspecialchars`.
5. Satu PR = satu pekerjaan. PR besar dipecah.
6. Semua teks antarmuka berbahasa Indonesia.

---

## Menyiapkan Lingkungan Lokal

```bash
# 1. Clone
git clone https://github.com/OWNER/IndoFX.git && cd IndoFX

# 2. Dependensi PHP
composer install

# 3. Variabel lingkungan
cp .env.example .env
# isi DB_PASSWORD (password database Supabase), STORAGE_BASE_URL, MIDTRANS_*, MAIL_*, dll.

# 4. Database sudah ada di Supabase; tidak perlu membuat tabel lagi.
#    Skema dan data contoh: lihat docs/panduan-supabase.md

# 5. Jalankan server pengembangan
composer serve          # http://localhost:8000

# 6. Perintah lain
composer lint           # cek gaya kode PSR-12
composer lint:fix       # perbaiki otomatis
composer test           # jalankan PHPUnit (butuh role PostgreSQL dengan hak CREATEDB)
```

Database proyek ada di Supabase (project `fjjcuvbhwjtyvmlddevj`). `.env` harus berisi
`DB_HOST`, `DB_PORT=6543`, `DB_NAME=postgres`, `DB_USER=postgres.<project-ref>`,
`DB_PASSWORD`, dan `DB_SSLMODE=require`. Rincian ada di
**[docs/panduan-supabase.md](docs/panduan-supabase.md)**.

Isi tabel saat ini: 20 aset, 5 pengguna, 2 kategori (`Shader`, `Particle`), 21 baris
`asset_images`. Akun penjual yang bisa dipakai: `budi.studio@gmail.com` /
`seller12345`, `chandra.art@pixelartisan.id` / `seller12345`.

`composer test` membuat database sementara pada `DB_HOST` di `.env`, lalu menghapusnya.
Jalankan dengan `.env` yang menunjuk PostgreSQL lokal agar tidak membuat database di
project Supabase.

### Gambar dan penyimpanan berkas

Gambar aset disimpan di Supabase Storage, bucket publik `asset-previews`, folder `catalog/`.
Kolom `asset_images.image_path` berisi URL penuh (mis.
`https://<ref>.supabase.co/storage/v1/object/public/asset-previews/catalog/01.jpg`), sehingga
`IndoFX\Helpers\Storage` meneruskannya apa adanya.

Bila path di database masih relatif (`assets/img/catalog/01.jpg`), isi `STORAGE_BASE_URL`
di `.env` dengan URL bucket publik; helper membuang awalan `assets/img/` sehingga path
menjadi `<bucket>/catalog/01.jpg`. Bila `STORAGE_BASE_URL` kosong, gambar diambil dari
`public/assets/img/`.

Panduan lengkap konfigurasi Supabase: **[docs/panduan-supabase.md](docs/panduan-supabase.md)**.

---

## Backend admin

Halaman `/admin` dan `/admin/dashboard` hanya dapat dibuka oleh akun aktif dengan
`users.role = 'admin'`. Login admin langsung menuju `/admin/dashboard`.

Data halaman admin dibaca melalui `GET /api/admin/data`. Perubahan dikirim ke
`POST /api/admin/save` dengan token CSRF dari sesi admin. Pengajuan penjual yang
disetujui mendapat status `approved` dan role pengguna berubah menjadi `seller`.
Aset yang disetujui mendapat status `published`; penolakan wajib disertai alasan.
Akun admin tidak dapat diblokir atau diubah rolenya dari panel pengguna.

Kolom pendukung ada di `database/migrations/2026-11-22_admin_backend.sql`.
Migrasi ini ditujukan untuk skema Supabase yang menggunakan ID pengguna BIGINT,
`users.password`, `user_profiles`, dan `seller_profiles`. Jangan menjalankan
`database/schema.sql` yang masih memakai struktur UUID lama untuk mengganti
skema Supabase tersebut. Migrasi admin sudah diterapkan ke proyek Supabase pada
22 November 2026 dan aman dijalankan ulang.

Untuk mencoba di lokal, jalankan dari worktree `develop`:

```bash
php -S localhost:8000 -t public
```

Login admin, lalu coba tambah/edit/hapus kategori, tinjau pengajuan penjual,
setujui atau tolak aset, dan blokir/buka blokir akun pembeli. Muat ulang halaman
untuk memastikan hasilnya tersimpan. Kategori yang masih digunakan aset tidak
bisa dihapus. Catatan transaksi mengikuti data database; biaya layanan dihitung
dari selisih total bayar dan jumlah harga aset, bukan persentase contoh.
Berkas unggahan untuk review harus tersedia pada server yang menjalankan PHP.

Pengujian model admin memakai database PostgreSQL lokal dan membuat schema uji
sementara yang dihapus setelah pengujian. Contoh untuk database lokal yang sudah
tersedia (sesuaikan nama database dan pengguna):

```bash
ADMIN_TEST_DSN='pgsql:host=127.0.0.1;port=5432;dbname=indofx_admin_test' \
ADMIN_TEST_USER=postgres vendor/bin/phpunit tests/Feature/AdminBackendTest.php
```

Pengujian tersebut tidak menjalankan `tests/Feature/AuthFlowTest.php`, yang masih
bergantung pada skema UUID lama.

---

## Deployment Azure App Service Linux

Konfigurasi hosting ada di `deploy/azure/`. Gunakan runtime PHP 8.3 atau versi
kompatibel yang tersedia pada subscription. Server memakai Nginx dan PHP-FPM,
dengan document root `/home/site/wwwroot/public`.

Set Startup Command pada App Service ke:

```sh
sh /home/site/wwwroot/deploy/azure/startup.sh
```

Set environment variables pada App Service melalui Azure Portal, bukan dengan
mengunggah `.env` ke GitHub:

| Nama | Nilai |
|---|---|
| `APP_ENV` | `production` |
| `APP_DEBUG` | `false` |
| `APP_URL` | URL HTTPS Default domain dari App Service |
| `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASSWORD` | Koneksi Supabase yang sekarang |
| `DB_SSLMODE` | `require` |
| `SUPABASE_URL` | URL proyek Supabase |
| `STORAGE_BASE_URL` | URL bucket pratinjau bila gambar menggunakan path relatif |
| `MIDTRANS_SERVER_KEY`, `MIDTRANS_CLIENT_KEY` | Key Sandbox, untuk demo |
| `MIDTRANS_IS_PRODUCTION` | `false` untuk demo |
| `PHP_INI_SCAN_DIR` | `:/home/site/wwwroot/deploy/azure/ini` |
| `SCM_DO_BUILD_DURING_DEPLOYMENT` | `false` (vendor dibangun sebelum ZIP dibuat) |

Aktifkan **HTTPS Only**. Jangan aktifkan `WEBSITE_RUN_FROM_PACKAGE`: startup
membuat tautan direktori upload pada document root yang harus dapat ditulis.
Upload dan sesi disimpan di `/home/indofx`, di luar folder source deployment.
Saat migrasi dari lokal, pindahkan juga berkas upload lama yang masih digunakan
oleh record database; berkas ini tidak dikirim lewat Git.

Buat paket dari commit yang akan dirilis:

```sh
sh deploy/azure/package.sh /tmp/indofx-azure.zip
```

Paket hanya memuat source aplikasi dan dependency produksi. `.env`, `.mcp.json`,
riwayat Git, data unggahan, dan test tidak termasuk.

Workflow **Deploy IndoFX to Azure** dijalankan manual dari branch `develop`.
Gunakan environment GitHub `azure-production`, konfigurasi OIDC Azure, dan
repository/environment variables berikut:

- `AZURE_CLIENT_ID`
- `AZURE_TENANT_ID`
- `AZURE_SUBSCRIPTION_ID`
- `AZURE_RESOURCE_GROUP`
- `AZURE_WEBAPP_NAME`

Federated credential Azure untuk workflow ini menggunakan subject
`repo:ZhayaGT/IndoFX_GithubRepo:environment:azure-production`. Batasi role
identitas deployment pada App Service yang dituju. Workflow tidak membuat atau
mengubah paket biaya App Service.

Setelah deploy, periksa beranda, login admin, katalog, upload, dan unduhan dari
library. URL notifikasi pembayaran Midtrans adalah
`https://<default-domain-aplikasi>/webhook/midtrans`. Pastikan key Midtrans dan
mode pada dashboard Midtrans sesuai environment yang diuji.

Panduan Azure: [konfigurasi PHP](https://learn.microsoft.com/en-us/azure/app-service/configure-language-php)
dan [GitHub Actions dengan OIDC](https://learn.microsoft.com/en-us/azure/app-service/deploy-github-actions?tabs=openid).

---

## Alur Kerja Harian

```bash
git checkout develop && git pull
git checkout -b feat/be-nama-fitur
# ...kerjakan...
git add .
git commit -m "feat(be): deskripsi singkat"
git push -u origin feat/be-nama-fitur
# buka Pull Request ke develop
```
