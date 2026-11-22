-- Migrasi 001: menambah kolom bio pada tabel users.
--
-- Kolom ini dipakai halaman /dashboard/ubahprofile (routes/web.php) untuk
-- menyimpan bio pengguna. Tanpa kolom ini query UPDATE akan gagal.
--
-- Jalankan manual pada database yang sudah ada:
--   psql -h 127.0.0.1 -p 5433 -U postgres -d indofx -f database/migrations/001_add_bio_to_users.sql
--
-- Idempoten: aman dijalankan berulang kali.

ALTER TABLE users ADD COLUMN IF NOT EXISTS bio TEXT;
