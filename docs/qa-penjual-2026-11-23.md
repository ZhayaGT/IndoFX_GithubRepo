# Perbaikan QA penjual, 23 November 2026

Source dasar: `develop` pada `c6aeb90`. Branch frontend
`origin/feat/fe-buyer-flow-and-library` memiliki commit tambahan `e0c0e82`.
Perbaikan berikut mempertahankan alur database PHP dan mengambil kebutuhan UI
yang relevan; kode status akun berbasis localStorage tidak dimasukkan.

| QA | Penyebab dan hasil |
| --- | --- |
| 1. Pembayaran | Kedua key Midtrans di hosting kosong. Dibiarkan sesuai instruksi pengguna. |
| 2. Pending upload | Tabel memanggil `esc()` tanpa memuat `indofx-utils.js`. Helper dimuat pada daftar aset, dashboard, dan transaksi. Upload menampilkan pesan sukses dan baris Pending / Menunggu. |
| 3. Unduhan | Semua 19 aset terbit tidak memiliki baris `asset_files`. Ditambahkan paket dummy, tanpa menimpa satu paket asli pada aset pending. |
| 4. Filter Explore mobile | Filter tampil di atas kartu pada mobile, tetap di kanan pada desktop. |
| 5. Email kontak | Input sebelumnya readonly; controller mengabaikan email; database belum memiliki kolom terpisah. Ditambahkan `seller_profiles.contact_email`, validasi server, dan penyimpanan atomik bersama profil. Email login tetap terpisah. |
| 6–7. Filter dan pencarian | Query pencarian/filter yang sudah bekerja dipertahankan. |
| 8. Keranjang mobile | Kartu ditumpuk pada layar sempit; gambar mengikuti rasio 16:9 dan teks membungkus. Label DIBELI diperbaiki menjadi BELUM DIBAYAR. |
| 9–10. Menu profil | Keranjang dan checkout memakai partial yang sama dengan katalog. Skrip menu dimuat juga pada detail aset, profil publik, dan ubah profil. Tautan dashboard mobile disamakan. |

## Supabase dan paket dummy

DDL diterapkan melalui MCP dengan nama `seller_contact_email_and_package_rls`.
SQL tersimpan pada `database/migrations/2026-11-23_seller_contact_email.sql`.
RLS diaktifkan pada `seller_profiles` dan `asset_files`. Aplikasi memakai koneksi
server `postgres`, dengan otorisasi melalui sesi PHP; kedua tabel tidak diberi
kebijakan akses langsung untuk klien anon/authenticated Supabase.

Seed MCP menghasilkan 19 metadata paket dummy. Semua 19 aset terbit kini memiliki
paket. Berkas ZIP berukuran 275 byte disimpan privat di
`/data/indofx/shared/storage/uploads/assets/DUMMY-QA-ASSET.zip`, mengikuti alur
unggahan hosting yang sudah ada. Berkas ini tidak diunggah ke Supabase Storage.
SQL seed dan ZIP sumber tersedia pada `database/seeders/qa_dummy_packages.sql`
dan `database/seeders/fixtures/DUMMY-QA-ASSET.zip`.

Nama unduhan, README di dalam ZIP, changelog, detail aset, dan detail library
menyatakan bahwa paket ini dummy QA, bukan aset Unity asli. Penjual perlu
mengunggah paket asli sebelum penjualan nyata. Paket baru diprioritaskan melalui
urutan `created_at DESC, id DESC`.

Advisor Supabase masih melaporkan 11 tabel lain yang sejak awal belum memakai
RLS. Perubahan ini tidak mengubah akses tabel di luar profil penjual dan file
aset. Panduan: [RLS disabled in public](https://supabase.com/docs/guides/database/database-linter?lint=0013_rls_disabled_in_public).
Pemberitahuan kedua tabel tanpa policy sesuai akses hanya dari backend di atas.

## Bukti pengujian

Pengujian browser tersedia pada `tests/qa/seller.cjs` dan memakai akun QA
sementara. Kredensial tidak disimpan dalam repository. Pengujian membutuhkan
Playwright yang sudah tersedia, URL aplikasi, file kredensial QA, dan ID aset
gratis yang berpaket dummy:

```sh
QA_BASE_URL=http://127.0.0.1:8011 \
QA_CREDENTIALS_FILE=/path/to/qa-credentials.json \
QA_FREE_ASSET_ID=20 \
PLAYWRIGHT_MODULE=/path/to/playwright-core \
node tests/qa/seller.cjs
```

File kredensial berisi `email` dan `password` akun QA penjual. Pengujian
mengubah profil dan membuat aset pada akun tersebut; jangan memakai akun nyata.

Hasil lokal dan URL hosting Instabox: PASS untuk seluruh butir berikut.

- Login penjual membuka beranda.
- Email kontak dapat diubah, tersimpan setelah reload, dan tampil di profil publik.
- Email tidak valid ditolak tanpa menimpa profil yang tersimpan.
- Submit form dengan gambar dan ZIP menghasilkan pesan sukses dan baris pending.
- Filter pending tetap menampilkan aset yang baru diunggah.
- Dashboard menampilkan data aset tanpa exception JavaScript.
- Menu profil katalog, detail, keranjang, checkout, profil publik, dan ubah profil
  memiliki tautan yang sama; klik membuka menu dan Escape menutupnya.
- Batas kartu keranjang tetap berada di viewport pada lebar 320, 375, 768, dan
  1280 piksel. Filter Explore berada di atas kartu pada mobile dan di kanan pada desktop.
- Tombol hapus membuka modal; Batal menutup modal.
- Klaim aset gratis memberi akses unduhan ZIP yang valid (signature `PK`) dan
  nama file DUMMY-QA. Halaman library menampilkan penjelasan dummy.
- Tamu yang mencoba mengunduh diarahkan ke login.
- Tidak ada exception JavaScript pada alur tersebut.

Cek sintaks PHP untuk seluruh file PHP yang berubah dan `node --check` untuk
skrip QA lulus. Suite PHPUnit lama yang memerlukan database PostgreSQL lokal
tidak dijalankan; verifikasi fitur memakai browser dan Supabase yang digunakan
aplikasi.

## Antislop delivery gate, lingkup perubahan QA

Arah visual mengikuti prototype Figma di `design/prototype` dan CSS IndoFX yang
sudah ada: oranye, krem, teks cokelat gelap, font Inter. ENERGY 2, RHYTHM 2,
MOTION 1. Kartu menampilkan informasi aset, tabel membandingkan status moderasi,
dan filter diprioritaskan pada mobile agar tidak perlu menggulir melewati hasil.

- Hard gate PASS: kontrol yang diperbaiki diuji melalui browser; paket dummy
  berlabel; batas layout diuji pada empat lebar; input email memiliki label dan
  validasi; menu memakai tombol native dan mendukung Escape.
- Purpose gate PASS: perubahan hanya menata posisi filter/kartu dan memakai
  kembali menu serta helper yang sudah ada; tidak menambah dekorasi atau aset visual.
- Liveliness PASS: identitas warna, tipografi, kartu aset, dan tabel Figma
  dipertahankan; perbaikan tidak mengganti gaya produk.
- Craftsmanship PASS: pesan sukses/gagal dan keadaan kosong tersedia pada alur
  yang diperbaiki; email invalid tidak merusak data; unduhan memeriksa sesi dan
  entitlement; semua klaim pengujian di atas berasal dari hasil eksekusi.

## Hosting

Release aktif: `/data/indofx/releases/qa-seller-20261123-c6aeb90`.
Release sebelumnya dipertahankan melalui `/data/indofx/previous`.
Source release berasal dari `c6aeb90` ditambah perubahan lokal tugas ini;
perubahan belum di-commit atau di-push ke GitHub.

Nginx dan PHP-FPM lulus pemeriksaan konfigurasi; beranda release baru merespons HTTP 200.
Screenshot mobile keranjang dan Explore ditinjau setelah uji hosting.
Akun QA, aset uji sementara, entitlement, dan log unduhan QA dibersihkan;
hitungan unduhan uji dikembalikan. Sebanyak 19 paket dummy tetap tersedia.
