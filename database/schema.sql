-- ============================================================================
-- SUPABASE SQL MIGRATION SCRIPT: MARKETPLACE GAME ASSETS
-- ============================================================================

-- Pastikan ekstensi pgcrypto aktif untuk fungsi UUID (biasanya sudah aktif di Supabase)
CREATE EXTENSION IF NOT EXISTS "pgcrypto";

-- ============================================================================
-- 1. PEMBUATAN TABEL (DDL) - SCHEMA PUBLIC
-- ============================================================================

-- Tabel 1: categories
CREATE TABLE IF NOT EXISTS public.categories (
    id BIGSERIAL PRIMARY KEY,
    public_id UUID DEFAULT gen_random_uuid() NOT NULL UNIQUE,
    name VARCHAR(50) NOT NULL,
    slug VARCHAR(50) NOT NULL UNIQUE,
    created_at TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP
);

-- Tabel 2: users
-- Catatan: Tabel ini adalah tabel users aplikasi kustom Anda (public.users),
-- terpisah dari tabel internal auth.users milik Supabase Auth.
CREATE TABLE IF NOT EXISTS public.users (
    id BIGSERIAL PRIMARY KEY,
    public_id UUID DEFAULT gen_random_uuid() NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) NOT NULL,
    remember_token VARCHAR(100) DEFAULT NULL,
    created_at TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP
);

-- Tabel 3: assets
CREATE TABLE IF NOT EXISTS public.assets (
    id BIGSERIAL PRIMARY KEY,
    public_id UUID DEFAULT gen_random_uuid() NOT NULL UNIQUE,
    seller_id BIGINT NOT NULL,
    category_id BIGINT NOT NULL,
    title VARCHAR(150) NOT NULL,
    slug VARCHAR(150) NOT NULL UNIQUE,
    description VARCHAR(2000) NOT NULL,
    how_to_use VARCHAR(2000) DEFAULT NULL,
    price INTEGER NOT NULL,
    render_pipeline VARCHAR(50) NOT NULL,
    unity_version VARCHAR(20) NOT NULL,
    status VARCHAR(20) NOT NULL,
    preview_video_url VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
    deleted_at TIMESTAMPTZ DEFAULT NULL,
    CONSTRAINT fk_assets_seller FOREIGN KEY (seller_id) REFERENCES public.users(id) ON DELETE CASCADE,
    CONSTRAINT fk_assets_category FOREIGN KEY (category_id) REFERENCES public.categories(id) ON DELETE RESTRICT
);

CREATE INDEX IF NOT EXISTS idx_assets_seller_id ON public.assets(seller_id);
CREATE INDEX IF NOT EXISTS idx_assets_category_id ON public.assets(category_id);

-- Tabel 4: asset_files
CREATE TABLE IF NOT EXISTS public.asset_files (
    id BIGSERIAL PRIMARY KEY,
    public_id UUID DEFAULT gen_random_uuid() NOT NULL UNIQUE,
    asset_id BIGINT NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    original_name VARCHAR(255) NOT NULL,
    mime_type VARCHAR(100) NOT NULL,
    extension VARCHAR(20) NOT NULL,
    file_size INTEGER NOT NULL,
    version VARCHAR(20) NOT NULL,
    created_at TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_asset_files_asset FOREIGN KEY (asset_id) REFERENCES public.assets(id) ON DELETE CASCADE
);

CREATE INDEX IF NOT EXISTS idx_asset_files_asset_id ON public.asset_files(asset_id);

-- Tabel 5: asset_images
CREATE TABLE IF NOT EXISTS public.asset_images (
    id BIGSERIAL PRIMARY KEY,
    public_id UUID DEFAULT gen_random_uuid() NOT NULL UNIQUE,
    asset_id BIGINT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    sort_order INTEGER NOT NULL DEFAULT 1,
    created_at TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_asset_images_asset FOREIGN KEY (asset_id) REFERENCES public.assets(id) ON DELETE CASCADE
);

CREATE INDEX IF NOT EXISTS idx_asset_images_asset_id ON public.asset_images(asset_id);

-- Tabel 6: transactions
CREATE TABLE IF NOT EXISTS public.transactions (
    id BIGSERIAL PRIMARY KEY,
    public_id UUID DEFAULT gen_random_uuid() NOT NULL UNIQUE,
    buyer_id BIGINT NOT NULL,
    asset_id BIGINT NOT NULL,
    invoice_number VARCHAR(50) NOT NULL UNIQUE,
    amount INTEGER NOT NULL,
    payment_type VARCHAR(50) DEFAULT NULL,
    status VARCHAR(20) NOT NULL,
    midtrans_transaction_id VARCHAR(100) DEFAULT NULL,
    paid_at TIMESTAMPTZ DEFAULT NULL,
    created_at TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_transactions_buyer FOREIGN KEY (buyer_id) REFERENCES public.users(id) ON DELETE CASCADE,
    CONSTRAINT fk_transactions_asset FOREIGN KEY (asset_id) REFERENCES public.assets(id) ON DELETE CASCADE
);

CREATE INDEX IF NOT EXISTS idx_transactions_buyer_id ON public.transactions(buyer_id);
CREATE INDEX IF NOT EXISTS idx_transactions_asset_id ON public.transactions(asset_id);

-- Tabel 7: entitlements
CREATE TABLE IF NOT EXISTS public.entitlements (
    id BIGSERIAL PRIMARY KEY,
    public_id UUID DEFAULT gen_random_uuid() NOT NULL UNIQUE,
    user_id BIGINT NOT NULL,
    asset_id BIGINT NOT NULL,
    transaction_id BIGINT NOT NULL,
    source VARCHAR(20) NOT NULL,
    granted_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMPTZ DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_entitlements_user FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE,
    CONSTRAINT fk_entitlements_asset FOREIGN KEY (asset_id) REFERENCES public.assets(id) ON DELETE CASCADE,
    CONSTRAINT fk_entitlements_transaction FOREIGN KEY (transaction_id) REFERENCES public.transactions(id) ON DELETE CASCADE
);

CREATE INDEX IF NOT EXISTS idx_entitlements_user_id ON public.entitlements(user_id);
CREATE INDEX IF NOT EXISTS idx_entitlements_asset_id ON public.entitlements(asset_id);
CREATE INDEX IF NOT EXISTS idx_entitlements_transaction_id ON public.entitlements(transaction_id);

-- Tabel 8: download_logs
CREATE TABLE IF NOT EXISTS public.download_logs (
    id BIGSERIAL PRIMARY KEY,
    public_id UUID DEFAULT gen_random_uuid() NOT NULL UNIQUE,
    entitlement_id BIGINT NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    downloaded_at TIMESTAMPTZ NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_download_logs_entitlement FOREIGN KEY (entitlement_id) REFERENCES public.entitlements(id) ON DELETE CASCADE
);

CREATE INDEX IF NOT EXISTS idx_download_logs_entitlement_id ON public.download_logs(entitlement_id);

-- ============================================================================
-- 2. HAK AKSES ROLE SUPABASE (Pengganti GRANT TO diaz)
-- ============================================================================
-- Di Supabase, role bawaan adalah: postgres, anon, authenticated, dan service_role.
GRANT USAGE ON SCHEMA public TO anon, authenticated, service_role;
GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA public TO postgres, anon, authenticated, service_role;
GRANT ALL PRIVILEGES ON ALL SEQUENCES IN SCHEMA public TO postgres, anon, authenticated, service_role;
ALTER DEFAULT PRIVILEGES IN SCHEMA public GRANT ALL ON TABLES TO postgres, anon, authenticated, service_role;
ALTER DEFAULT PRIVILEGES IN SCHEMA public GRANT ALL ON SEQUENCES TO postgres, anon, authenticated, service_role;

-- ============================================================================
-- 3. PENGISIAN DATA DUMMY (DML)
-- ============================================================================

INSERT INTO public.categories (id, name, slug, created_at, updated_at) VALUES
(1, '3D Characters', '3d-characters', '2026-01-10 10:00:00', '2026-01-10 10:00:00'),
(2, 'Environments & Landscapes', 'environments-landscapes', '2026-01-10 10:05:00', '2026-01-10 10:05:00'),
(3, 'VFX & Shaders', 'vfx-and-shaders', '2026-01-10 10:10:00', '2026-01-10 10:10:00'),
(4, 'UI & GUI Kits', 'ui-gui-kits', '2026-01-10 10:15:00', '2026-01-10 10:15:00'),
(5, 'Animations & Mocap', 'animations-mocap', '2026-01-10 10:20:00', '2026-01-10 10:20:00')
ON CONFLICT (id) DO NOTHING;

INSERT INTO public.users (id, name, email, password, role, remember_token, created_at, updated_at) VALUES
(1, 'Super Admin', 'admin@assetforge.io', '$2y$12$e8J3j3T0K0Zz9198adminhash', 'admin', NULL, '2026-01-01 08:00:00', '2026-01-01 08:00:00'),
(2, 'Budi Santoso (Studio 3D)', 'budi.studio@gmail.com', '$2y$12$K1x89LmP0883sellerhash1', 'seller', 'token_seller_budi_123', '2026-01-05 09:30:00', '2026-01-05 09:30:00'),
(3, 'Chandra Pixel Artisan', 'chandra.art@pixelartisan.id', '$2y$12$Z8mQ10Lw7744sellerhash2', 'seller', NULL, '2026-01-08 14:15:00', '2026-01-08 14:15:00'),
(4, 'Dimas Game Developer', 'dimas.dev@indiegame.com', '$2y$12$W9xT55Pl2211buyerhash1', 'buyer', 'token_buyer_dimas_789', '2026-01-12 11:20:00', '2026-01-12 11:20:00'),
(5, 'Eka Putri Unity Dev', 'ekaputri@gamedev.org', '$2y$12$Y2vB44Rt5533buyerhash2', 'buyer', NULL, '2026-01-15 16:45:00', '2026-01-15 16:45:00')
ON CONFLICT (id) DO NOTHING;

INSERT INTO public.assets (id, seller_id, category_id, title, slug, description, how_to_use, price, render_pipeline, unity_version, status, preview_video_url, created_at, updated_at, deleted_at) VALUES
(1, 2, 1, 'Stylized Fantasy Warrior Hero', 'stylized-fantasy-warrior-hero', 'Karakter prajurit fantasi lengkap dengan 25 animasi combat, modular armor, dan tekstur 4K hand-painted.', 'Import paket unitypackage ke project Anda, drag prefab Warrior_Hero ke scene, dan pasang Animator Controller bawaan.', 250000, 'Universal Render Pipeline (URP)', '2022.3 LTS', 'published', 'https://youtu.be/dummy_warrior_preview', '2026-01-15 10:00:00', '2026-01-15 10:00:00', NULL),
(2, 2, 2, 'Low Poly Sci-Fi Cyber City Modular Pack', 'low-poly-sci-fi-cyber-city-modular-pack', 'Modular kit kota masa depan gaya low-poly mencakup lebih dari 120 prefab gedung, jalanan, kendaraan terbang, dan lampu neon dinamis.', 'Gunakan snapping grid 1 unit pada Unity Editor untuk menyusun jalan dan modul gedung secara presisi.', 350000, 'Built-in / URP', '2021.3 LTS', 'published', 'https://youtu.be/dummy_cybercity_preview', '2026-01-18 13:30:00', '2026-01-18 13:30:00', NULL),
(3, 3, 3, 'Stylized Elemental Magic Spells VFX', 'stylized-elemental-magic-spells-vfx', 'Koleksi 40 efek visual sihir partikel (Api, Es, Petir, Kegelapan) dibuat menggunakan Visual Effect Graph (VFX Graph).', 'Pastikan project Anda mengaktifkan paket VFX Graph via Unity Package Manager.', 180000, 'Universal Render Pipeline (URP)', '2022.3 LTS', 'published', 'https://youtu.be/dummy_vfx_preview', '2026-01-20 09:15:00', '2026-01-20 09:15:00', NULL),
(4, 3, 4, 'Modern Mobile Game Clean UI Kit', 'modern-mobile-game-clean-ui-kit', 'Asset user interface lengkap untuk game mobile: HUD, toko virtual, level selector, dialog popup, dan ikon SVG/PNG siap pakai.', 'Tarik prefab Canvas_Main_Menu ke Hierarchy, sesuaikan event listener tombol dengan script game manager Anda.', 120000, 'Any (UI Canvas)', '2020.3 LTS', 'published', NULL, '2026-01-22 15:00:00', '2026-01-22 15:00:00', NULL),
(5, 2, 5, 'Ultimate Melee Sword Combat Animations', 'ultimate-melee-sword-combat-animations', '35 motion capture animasi pertarungan pedang satu tangan (combo serangan, dodge, parry, dan death animation).', 'Mendukung Humanoid Avatar standar Unity. Retargeting otomatis dengan model karakter 3D apa pun yang bertipe Humanoid.', 200000, 'Any', '2022.3 LTS', 'published', 'https://youtu.be/dummy_anim_preview', '2026-01-25 11:45:00', '2026-01-25 11:45:00', NULL)
ON CONFLICT (id) DO NOTHING;

INSERT INTO public.asset_files (id, asset_id, file_path, original_name, mime_type, extension, file_size, version, created_at, updated_at) VALUES
(1, 1, 'assets/files/2026/01/warrior_hero_v1.0.0.unitypackage', 'Warrior_Hero_Modular_v1.unitypackage', 'application/octet-stream', 'unitypackage', 154820000, '1.0.0', '2026-01-15 10:05:00', '2026-01-15 10:05:00'),
(2, 1, 'assets/files/2026/02/warrior_hero_v1.1.0_patch.unitypackage', 'Warrior_Hero_Modular_v1.1.unitypackage', 'application/octet-stream', 'unitypackage', 158200000, '1.1.0', '2026-02-01 08:30:00', '2026-02-01 08:30:00'),
(3, 2, 'assets/files/2026/01/cyber_city_modular_pack.unitypackage', 'SciFi_CyberCity_Modular.unitypackage', 'application/octet-stream', 'unitypackage', 324100000, '1.0.0', '2026-01-18 13:35:00', '2026-01-18 13:35:00'),
(4, 3, 'assets/files/2026/01/elemental_magic_vfx.unitypackage', 'Elemental_Magic_VFX_Pack.unitypackage', 'application/octet-stream', 'unitypackage', 84500000, '1.0.0', '2026-01-20 09:20:00', '2026-01-20 09:20:00'),
(5, 4, 'assets/files/2026/01/clean_mobile_ui.zip', 'Mobile_Clean_UI_Assets.zip', 'application/zip', 'zip', 45200000, '1.0.0', '2026-01-22 15:05:00', '2026-01-22 15:05:00'),
(6, 5, 'assets/files/2026/01/melee_combat_anim.unitypackage', 'Melee_Sword_Combat_Anim.unitypackage', 'application/octet-stream', 'unitypackage', 62300000, '1.0.0', '2026-01-25 11:50:00', '2026-01-25 11:50:00')
ON CONFLICT (id) DO NOTHING;

INSERT INTO public.asset_images (id, asset_id, image_path, sort_order, created_at, updated_at) VALUES
(1, 1, 'assets/images/warrior_hero_main.webp', 1, '2026-01-15 10:02:00', '2026-01-15 10:02:00'),
(2, 1, 'assets/images/warrior_hero_wireframe.webp', 2, '2026-01-15 10:02:00', '2026-01-15 10:02:00'),
(3, 1, 'assets/images/warrior_hero_animations.webp', 3, '2026-01-15 10:02:00', '2026-01-15 10:02:00'),
(4, 2, 'assets/images/cyber_city_overview.webp', 1, '2026-01-18 13:32:00', '2026-01-18 13:32:00'),
(5, 2, 'assets/images/cyber_city_street_view.webp', 2, '2026-01-18 13:32:00', '2026-01-18 13:32:00'),
(6, 3, 'assets/images/vfx_fire_ice_spells.webp', 1, '2026-01-20 09:18:00', '2026-01-20 09:18:00'),
(7, 4, 'assets/images/mobile_ui_mockup.webp', 1, '2026-01-22 15:02:00', '2026-01-22 15:02:00'),
(8, 5, 'assets/images/combat_animations_sheet.webp', 1, '2026-01-25 11:48:00', '2026-01-25 11:48:00')
ON CONFLICT (id) DO NOTHING;

INSERT INTO public.transactions (id, buyer_id, asset_id, invoice_number, amount, payment_type, status, midtrans_transaction_id, paid_at, created_at, updated_at) VALUES
(1, 4, 1, 'INV-202602-0001', 250000, 'qris', 'settlement', 'midtrans-trx-8849201-9921', '2026-02-05 14:22:10', '2026-02-05 14:20:00', '2026-02-05 14:22:10'),
(2, 4, 3, 'INV-202602-0002', 180000, 'gopay', 'settlement', 'midtrans-trx-8849202-7712', '2026-02-10 19:05:45', '2026-02-10 19:02:00', '2026-02-10 19:05:45'),
(3, 5, 2, 'INV-202602-0003', 350000, 'bank_transfer', 'settlement', 'midtrans-trx-8849203-1120', '2026-02-14 09:12:00', '2026-02-14 09:00:00', '2026-02-14 09:12:00'),
(4, 5, 4, 'INV-202602-0004', 120000, 'qris', 'pending', 'midtrans-trx-8849204-5534', NULL, '2026-02-16 11:30:00', '2026-02-16 11:30:00'),
(5, 4, 5, 'INV-202602-0005', 200000, 'credit_card', 'settlement', 'midtrans-trx-8849205-4490', '2026-02-18 16:40:15', '2026-02-18 16:38:00', '2026-02-18 16:40:15')
ON CONFLICT (id) DO NOTHING;

INSERT INTO public.entitlements (id, user_id, asset_id, transaction_id, source, granted_at, created_at, updated_at) VALUES
(1, 4, 1, 1, 'purchase', '2026-02-05 14:22:10', '2026-02-05 14:22:10', '2026-02-05 14:22:10'),
(2, 4, 3, 2, 'purchase', '2026-02-10 19:05:45', '2026-02-10 19:05:45', '2026-02-10 19:05:45'),
(3, 5, 2, 3, 'purchase', '2026-02-14 09:12:00', '2026-02-14 09:12:00', '2026-02-14 09:12:00'),
(4, 4, 5, 5, 'purchase', '2026-02-18 16:40:15', '2026-02-18 16:40:15', '2026-02-18 16:40:15')
ON CONFLICT (id) DO NOTHING;

INSERT INTO public.download_logs (id, entitlement_id, ip_address, downloaded_at) VALUES
(1, 1, '182.253.120.45', '2026-02-05 14:25:30'),
(2, 1, '182.253.120.45', '2026-02-06 09:10:12'),
(3, 2, '182.253.120.45', '2026-02-10 19:10:00'),
(4, 3, '36.85.14.90', '2026-02-14 09:18:22'),
(5, 3, '36.85.14.90', '2026-02-15 13:40:05'),
(6, 4, '182.253.120.45', '2026-02-18 16:45:50')
ON CONFLICT (id) DO NOTHING;

-- ============================================================================
-- 4. SINKRONISASI SEQUENCE AUTO-INCREMENT
-- ============================================================================
SELECT setval(pg_get_serial_sequence('public.categories', 'id'), COALESCE(MAX(id), 1)) FROM public.categories;
SELECT setval(pg_get_serial_sequence('public.users', 'id'), COALESCE(MAX(id), 1)) FROM public.users;
SELECT setval(pg_get_serial_sequence('public.assets', 'id'), COALESCE(MAX(id), 1)) FROM public.assets;
SELECT setval(pg_get_serial_sequence('public.asset_files', 'id'), COALESCE(MAX(id), 1)) FROM public.asset_files;
SELECT setval(pg_get_serial_sequence('public.asset_images', 'id'), COALESCE(MAX(id), 1)) FROM public.asset_images;
SELECT setval(pg_get_serial_sequence('public.transactions', 'id'), COALESCE(MAX(id), 1)) FROM public.transactions;
SELECT setval(pg_get_serial_sequence('public.entitlements', 'id'), COALESCE(MAX(id), 1)) FROM public.entitlements;
SELECT setval(pg_get_serial_sequence('public.download_logs', 'id'), COALESCE(MAX(id), 1)) FROM public.download_logs;

-- ============================================================================
-- 5. FUNCTION, VIEW, MATERIALIZED VIEW, & TRIGGER
-- ============================================================================

-- Function PL/pgSQL: Menghitung pembagian royalti bersih seller (potongan biaya platform 10%)
CREATE OR REPLACE FUNCTION public.hitung_royalti_seller(p_amount NUMERIC, p_fee_percent NUMERIC DEFAULT 10)
RETURNS NUMERIC AS $$
BEGIN
    RETURN p_amount - (p_amount * (p_fee_percent / 100));
END;
$$ LANGUAGE plpgsql;

-- Regular View: Menampilkan data aset siap jual bagi buyer
CREATE OR REPLACE VIEW public.vw_katalog_publik AS
SELECT 
    a.id, 
    a.public_id,
    a.title, 
    a.slug, 
    c.name AS category_name, 
    u.name AS seller_name, 
    a.price, 
    a.render_pipeline, 
    a.unity_version
FROM public.assets a
JOIN public.categories c ON a.category_id = c.id
JOIN public.users u ON a.seller_id = u.id
WHERE a.status = 'published' AND a.deleted_at IS NULL;

-- Materialized View: Laporan rekapitulasi omset bulanan
CREATE MATERIALIZED VIEW IF NOT EXISTS public.mv_rekap_penjualan_bulanan AS
SELECT 
    DATE_TRUNC('month', paid_at) AS periode_bulan,
    COUNT(id) AS total_transaksi,
    SUM(amount) AS total_omset
FROM public.transactions
WHERE status = 'settlement'
GROUP BY DATE_TRUNC('month', paid_at);

-- Trigger Function: Otomatis membuat entitlement saat status transaksi berubah menjadi 'settlement'
CREATE OR REPLACE FUNCTION public.trg_grant_entitlement_on_settlement()
RETURNS TRIGGER AS $$
BEGIN
    IF NEW.status = 'settlement' AND (OLD.status IS NULL OR OLD.status != 'settlement') THEN
        INSERT INTO public.entitlements (user_id, asset_id, transaction_id, source, granted_at)
        VALUES (NEW.buyer_id, NEW.asset_id, NEW.id, 'purchase', CURRENT_TIMESTAMP)
        ON CONFLICT (id) DO NOTHING;
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Hapus trigger lama jika sudah ada agar tidak error saat dijalankan ulang
DROP TRIGGER IF EXISTS trigger_auto_entitlement ON public.transactions;

CREATE TRIGGER trigger_auto_entitlement
AFTER UPDATE ON public.transactions
FOR EACH ROW
EXECUTE FUNCTION public.trg_grant_entitlement_on_settlement();

-- ============================================================================
-- 6. PENGUJIAN TRIGGER & MATERIALIZED VIEW (AMAN DIJALANKAN ULANG)
-- ============================================================================

-- Simulasi tambah transaksi baru untuk tes Materialized View
INSERT INTO public.transactions (buyer_id, asset_id, invoice_number, amount, payment_type, status, paid_at, created_at)
VALUES (4, 2, 'INV-TEST-MV-01', 500000, 'qris', 'settlement', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)
ON CONFLICT (invoice_number) DO NOTHING;

REFRESH MATERIALIZED VIEW public.mv_rekap_penjualan_bulanan;

-- Simulasi ubah status transaksi ID 4 dari 'pending' menjadi 'settlement' untuk memicu Trigger
UPDATE public.transactions 
SET status = 'settlement', paid_at = CURRENT_TIMESTAMP 
WHERE id = 4 AND status != 'settlement';

-- Tampilkan hasil akhir entitlements
SELECT 
    e.id AS entitlement_id,
    u.name AS nama_pembeli,
    a.title AS judul_aset,
    e.source,
    e.granted_at
FROM public.entitlements e
JOIN public.users u ON e.user_id = u.id
JOIN public.assets a ON e.asset_id = a.id
ORDER BY e.id;