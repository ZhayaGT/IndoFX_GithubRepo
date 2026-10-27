CREATE TABLE IF NOT EXISTS categories (
    id BIGSERIAL PRIMARY KEY,
    public_id UUID DEFAULT gen_random_uuid(),
    name VARCHAR(50) NOT NULL,
    slug VARCHAR(50) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS users (
    id BIGSERIAL PRIMARY KEY,
    public_id UUID DEFAULT gen_random_uuid(),
    name VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(70) NOT NULL,
    role VARCHAR(10) NOT NULL,
    remember_token VARCHAR(50) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

select * from users;

GRANT USAGE ON SCHEMA public TO diaz;
GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA public TO diaz;
ALTER DEFAULT PRIVILEGES IN SCHEMA public GRANT SELECT ON TABLES TO diaz;
GRANT ALL PRIVILEGES ON ALL SEQUENCES IN SCHEMA public TO diaz;

CREATE TABLE IF NOT EXISTS assets (
    id BIGSERIAL PRIMARY KEY,
    public_id UUID DEFAULT gen_random_uuid(),
    seller_id BIGINT NOT NULL,
    category_id BIGINT NOT NULL,
    title VARCHAR(150) NOT NULL,
    slug VARCHAR(150) NOT NULL UNIQUE,
    description VARCHAR(2000) NOT NULL,
    how_to_use VARCHAR(2000) DEFAULT NULL,
    price INTEGER NOT NULL,
    render_pipeline VARCHAR(50) NOT NULL,
    unity_version VARCHAR(15) NOT NULL,
    status VARCHAR(20) NOT NULL,
    preview_video_url VARCHAR(150) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP DEFAULT NULL,
    CONSTRAINT fk_assets_seller FOREIGN KEY (seller_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_assets_category FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE RESTRICT
);

CREATE INDEX IF NOT EXISTS idx_assets_seller_id ON assets(seller_id);
CREATE INDEX IF NOT EXISTS idx_assets_category_id ON assets(category_id);

CREATE TABLE IF NOT EXISTS asset_files (
    id BIGSERIAL PRIMARY KEY,
    public_id UUID DEFAULT gen_random_uuid(),
    asset_id BIGINT NOT NULL,
    file_path VARCHAR(150) NOT NULL,
    original_name VARCHAR(150) NOT NULL,
    mime_type VARCHAR(100) NOT NULL,
    extension VARCHAR(20) NOT NULL,
    file_size INTEGER NOT NULL,
    version VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_asset_files_asset FOREIGN KEY (asset_id) REFERENCES assets(id) ON DELETE CASCADE
);

CREATE INDEX IF NOT EXISTS idx_asset_files_asset_id ON asset_files(asset_id);

CREATE TABLE IF NOT EXISTS asset_images (
    id BIGSERIAL PRIMARY KEY,
    public_id UUID DEFAULT gen_random_uuid(),
    asset_id BIGINT NOT NULL,
    image_path VARCHAR(150) NOT NULL,
    sort_order INTEGER NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_asset_images_asset FOREIGN KEY (asset_id) REFERENCES assets(id) ON DELETE CASCADE
);

CREATE INDEX IF NOT EXISTS idx_asset_images_asset_id ON asset_images(asset_id);

CREATE TABLE IF NOT EXISTS transactions (
    id BIGSERIAL PRIMARY KEY,
    public_id UUID DEFAULT gen_random_uuid(),
    buyer_id BIGINT NOT NULL,
    asset_id BIGINT NOT NULL,
    invoice_number VARCHAR(50) NOT NULL UNIQUE,
    amount INTEGER NOT NULL,
    payment_type VARCHAR(20) DEFAULT NULL,
    status VARCHAR(20) NOT NULL,
    midtrans_transaction_id VARCHAR(50) DEFAULT NULL,
    paid_at TIMESTAMP DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_transactions_buyer FOREIGN KEY (buyer_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_transactions_asset FOREIGN KEY (asset_id) REFERENCES assets(id) ON DELETE CASCADE
);

CREATE INDEX IF NOT EXISTS idx_transactions_buyer_id ON transactions(buyer_id);
CREATE INDEX IF NOT EXISTS idx_transactions_asset_id ON transactions(asset_id);

CREATE TABLE IF NOT EXISTS entitlements (
    id BIGSERIAL PRIMARY KEY,
    public_id UUID DEFAULT gen_random_uuid(),
    user_id BIGINT NOT NULL,
    asset_id BIGINT NOT NULL,
    transaction_id BIGINT NOT NULL,
    source VARCHAR(20) NOT NULL,
    granted_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_entitlements_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_entitlements_asset FOREIGN KEY (asset_id) REFERENCES assets(id) ON DELETE CASCADE,
    CONSTRAINT fk_entitlements_transaction FOREIGN KEY (transaction_id) REFERENCES transactions(id) ON DELETE CASCADE
);

CREATE INDEX IF NOT EXISTS idx_entitlements_user_id ON entitlements(user_id);
CREATE INDEX IF NOT EXISTS idx_entitlements_asset_id ON entitlements(asset_id);
CREATE INDEX IF NOT EXISTS idx_entitlements_transaction_id ON entitlements(transaction_id);

CREATE TABLE IF NOT EXISTS download_logs (
    id BIGSERIAL PRIMARY KEY,
    public_id UUID DEFAULT gen_random_uuid(),
    entitlement_id BIGINT NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    downloaded_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_download_logs_entitlement FOREIGN KEY (entitlement_id) REFERENCES entitlements(id) ON DELETE CASCADE
);

CREATE INDEX IF NOT EXISTS idx_download_logs_entitlement_id ON download_logs(entitlement_id);

INSERT INTO categories (id, name, slug, created_at, updated_at) VALUES
(1, '3D Characters', '3d-characters', '2026-01-10 10:00:00', '2026-01-10 10:00:00'),
(2, 'Environments & Landscapes', 'environments-landscapes', '2026-01-10 10:05:00', '2026-01-10 10:05:00'),
(3, 'VFX & Shaders', 'vfx-and-shaders', '2026-01-10 10:10:00', '2026-01-10 10:10:00'),
(4, 'UI & GUI Kits', 'ui-gui-kits', '2026-01-10 10:15:00', '2026-01-10 10:15:00'),
(5, 'Animations & Mocap', 'animations-mocap', '2026-01-10 10:20:00', '2026-01-10 10:20:00')
ON CONFLICT (id) DO NOTHING;

INSERT INTO users (id, name, email, password, role, remember_token, created_at, updated_at) VALUES
(1, 'Super Admin', 'admin@assetforge.io', '$2y$12$e8J3j3T0K0Zz9198adminhash', 'admin', NULL, '2026-01-01 08:00:00', '2026-01-01 08:00:00'),
(2, 'Budi Santoso (Studio 3D)', 'budi.studio@gmail.com', '$2y$12$K1x89LmP0883sellerhash1', 'seller', 'token_seller_budi_123', '2026-01-05 09:30:00', '2026-01-05 09:30:00'),
(3, 'Chandra Pixel Artisan', 'chandra.art@pixelartisan.id', '$2y$12$Z8mQ10Lw7744sellerhash2', 'seller', NULL, '2026-01-08 14:15:00', '2026-01-08 14:15:00'),
(4, 'Dimas Game Developer', 'dimas.dev@indiegame.com', '$2y$12$W9xT55Pl2211buyerhash1', 'buyer', 'token_buyer_dimas_789', '2026-01-12 11:20:00', '2026-01-12 11:20:00'),
(5, 'Eka Putri Unity Dev', 'ekaputri@gamedev.org', '$2y$12$Y2vB44Rt5533buyerhash2', 'buyer', NULL, '2026-01-15 16:45:00', '2026-01-15 16:45:00')
ON CONFLICT (id) DO NOTHING;

INSERT INTO assets (id, seller_id, category_id, title, slug, description, how_to_use, price, render_pipeline, unity_version, status, preview_video_url, created_at, updated_at, deleted_at) VALUES
(1, 2, 1, 'Stylized Fantasy Warrior Hero', 'stylized-fantasy-warrior-hero', 'Karakter prajurit fantasi lengkap dengan 25 animasi combat, modular armor, dan tekstur 4K hand-painted.', 'Import paket unitypackage ke project Anda, drag prefab Warrior_Hero ke scene, dan pasang Animator Controller bawaan.', 250000, 'Universal Render Pipeline (URP)', '2022.3 LTS', 'published', 'https://youtu.be/dummy_warrior_preview', '2026-01-15 10:00:00', '2026-01-15 10:00:00', NULL),
(2, 2, 2, 'Low Poly Sci-Fi Cyber City Modular Pack', 'low-poly-sci-fi-cyber-city-modular-pack', 'Modular kit kota masa depan gaya low-poly mencakup lebih dari 120 prefab gedung, jalanan, kendaraan terbang, dan lampu neon dinamis.', 'Gunakan snapping grid 1 unit pada Unity Editor untuk menyusun jalan dan modul gedung secara presisi.', 350000, 'Built-in / URP', '2021.3 LTS', 'published', 'https://youtu.be/dummy_cybercity_preview', '2026-01-18 13:30:00', '2026-01-18 13:30:00', NULL),
(3, 3, 3, 'Stylized Elemental Magic Spells VFX', 'stylized-elemental-magic-spells-vfx', 'Koleksi 40 efek visual sihir partikel (Api, Es, Petir, Kegelapan) dibuat menggunakan Visual Effect Graph (VFX Graph).', 'Pastikan project Anda mengaktifkan paket VFX Graph via Unity Package Manager.', 180000, 'Universal Render Pipeline (URP)', '2022.3 LTS', 'published', 'https://youtu.be/dummy_vfx_preview', '2026-01-20 09:15:00', '2026-01-20 09:15:00', NULL),
(4, 3, 4, 'Modern Mobile Game Clean UI Kit', 'modern-mobile-game-clean-ui-kit', 'Asset user interface lengkap untuk game mobile: HUD, toko virtual, level selector, dialog popup, dan ikon SVG/PNG siap pakai.', 'Tarik prefab Canvas_Main_Menu ke Hierarchy, sesuaikan event listener tombol dengan script game manager Anda.', 120000, 'Any (UI Canvas)', '2020.3 LTS', 'published', NULL, '2026-01-22 15:00:00', '2026-01-22 15:00:00', NULL),
(5, 2, 5, 'Ultimate Melee Sword Combat Animations', 'ultimate-melee-sword-combat-animations', '35 motion capture animasi pertarungan pedang satu tangan (combo serangan, dodge, parry, dan death animation).', 'Mendukung Humanoid Avatar standar Unity. Retargeting otomatis dengan model karakter 3D apa pun yang bertipe Humanoid.', 200000, 'Any', '2022.3 LTS', 'published', 'https://youtu.be/dummy_anim_preview', '2026-01-25 11:45:00', '2026-01-25 11:45:00', NULL)
ON CONFLICT (id) DO NOTHING;

INSERT INTO asset_files (id, asset_id, file_path, original_name, mime_type, extension, file_size, version, created_at, updated_at) VALUES
(1, 1, 'assets/files/2026/01/warrior_hero_v1.0.0.unitypackage', 'Warrior_Hero_Modular_v1.unitypackage', 'application/octet-stream', 'unitypackage', 154820000, '1.0.0', '2026-01-15 10:05:00', '2026-01-15 10:05:00'),
(2, 1, 'assets/files/2026/02/warrior_hero_v1.1.0_patch.unitypackage', 'Warrior_Hero_Modular_v1.1.unitypackage', 'application/octet-stream', 'unitypackage', 158200000, '1.1.0', '2026-02-01 08:30:00', '2026-02-01 08:30:00'),
(3, 2, 'assets/files/2026/01/cyber_city_modular_pack.unitypackage', 'SciFi_CyberCity_Modular.unitypackage', 'application/octet-stream', 'unitypackage', 324100000, '1.0.0', '2026-01-18 13:35:00', '2026-01-18 13:35:00'),
(4, 3, 'assets/files/2026/01/elemental_magic_vfx.unitypackage', 'Elemental_Magic_VFX_Pack.unitypackage', 'application/octet-stream', 'unitypackage', 84500000, '1.0.0', '2026-01-20 09:20:00', '2026-01-20 09:20:00'),
(5, 4, 'assets/files/2026/01/clean_mobile_ui.zip', 'Mobile_Clean_UI_Assets.zip', 'application/zip', 'zip', 45200000, '1.0.0', '2026-01-22 15:05:00', '2026-01-22 15:05:00'),
(6, 5, 'assets/files/2026/01/melee_combat_anim.unitypackage', 'Melee_Sword_Combat_Anim.unitypackage', 'application/octet-stream', 'unitypackage', 62300000, '1.0.0', '2026-01-25 11:50:00', '2026-01-25 11:50:00')
ON CONFLICT (id) DO NOTHING;

INSERT INTO asset_images (id, asset_id, image_path, sort_order, created_at, updated_at) VALUES
(1, 1, 'assets/images/warrior_hero_main.webp', 1, '2026-01-15 10:02:00', '2026-01-15 10:02:00'),
(2, 1, 'assets/images/warrior_hero_wireframe.webp', 2, '2026-01-15 10:02:00', '2026-01-15 10:02:00'),
(3, 1, 'assets/images/warrior_hero_animations.webp', 3, '2026-01-15 10:02:00', '2026-01-15 10:02:00'),
(4, 2, 'assets/images/cyber_city_overview.webp', 1, '2026-01-18 13:32:00', '2026-01-18 13:32:00'),
(5, 2, 'assets/images/cyber_city_street_view.webp', 2, '2026-01-18 13:32:00', '2026-01-18 13:32:00'),
(6, 3, 'assets/images/vfx_fire_ice_spells.webp', 1, '2026-01-20 09:18:00', '2026-01-20 09:18:00'),
(7, 4, 'assets/images/mobile_ui_mockup.webp', 1, '2026-01-22 15:02:00', '2026-01-22 15:02:00'),
(8, 5, 'assets/images/combat_animations_sheet.webp', 1, '2026-01-25 11:48:00', '2026-01-25 11:48:00')
ON CONFLICT (id) DO NOTHING;

INSERT INTO transactions (id, buyer_id, asset_id, invoice_number, amount, payment_type, status, midtrans_transaction_id, paid_at, created_at, updated_at) VALUES
(1, 4, 1, 'INV-202602-0001', 250000, 'qris', 'settlement', 'midtrans-trx-8849201-9921', '2026-02-05 14:22:10', '2026-02-05 14:20:00', '2026-02-05 14:22:10'),
(2, 4, 3, 'INV-202602-0002', 180000, 'gopay', 'settlement', 'midtrans-trx-8849202-7712', '2026-02-10 19:05:45', '2026-02-10 19:02:00', '2026-02-10 19:05:45'),
(3, 5, 2, 'INV-202602-0003', 350000, 'bank_transfer', 'settlement', 'midtrans-trx-8849203-1120', '2026-02-14 09:12:00', '2026-02-14 09:00:00', '2026-02-14 09:12:00'),
(4, 5, 4, 'INV-202602-0004', 120000, 'qris', 'pending', 'midtrans-trx-8849204-5534', NULL, '2026-02-16 11:30:00', '2026-02-16 11:30:00'),
(5, 4, 5, 'INV-202602-0005', 200000, 'credit_card', 'settlement', 'midtrans-trx-8849205-4490', '2026-02-18 16:40:15', '2026-02-18 16:38:00', '2026-02-18 16:40:15')
ON CONFLICT (id) DO NOTHING;

INSERT INTO entitlements (id, user_id, asset_id, transaction_id, source, granted_at, created_at, updated_at) VALUES
(1, 4, 1, 1, 'purchase', '2026-02-05 14:22:10', '2026-02-05 14:22:10', '2026-02-05 14:22:10'),
(2, 4, 3, 2, 'purchase', '2026-02-10 19:05:45', '2026-02-10 19:05:45', '2026-02-10 19:05:45'),
(3, 5, 2, 3, 'purchase', '2026-02-14 09:12:00', '2026-02-14 09:12:00', '2026-02-14 09:12:00'),
(4, 4, 5, 5, 'purchase', '2026-02-18 16:40:15', '2026-02-18 16:40:15', '2026-02-18 16:40:15')
ON CONFLICT (id) DO NOTHING;

INSERT INTO download_logs (id, entitlement_id, ip_address, downloaded_at) VALUES
(1, 1, '182.253.120.45', '2026-02-05 14:25:30'),
(2, 1, '182.253.120.45', '2026-02-06 09:10:12'),
(3, 2, '182.253.120.45', '2026-02-10 19:10:00'),
(4, 3, '36.85.14.90', '2026-02-14 09:18:22'),
(5, 3, '36.85.14.90', '2026-02-15 13:40:05'),
(6, 4, '182.253.120.45', '2026-02-18 16:45:50')
ON CONFLICT (id) DO NOTHING;

SELECT setval(pg_get_serial_sequence('categories', 'id'), COALESCE(MAX(id), 1)) FROM categories;
SELECT setval(pg_get_serial_sequence('users', 'id'), COALESCE(MAX(id), 1)) FROM users;
SELECT setval(pg_get_serial_sequence('assets', 'id'), COALESCE(MAX(id), 1)) FROM assets;
SELECT setval(pg_get_serial_sequence('asset_files', 'id'), COALESCE(MAX(id), 1)) FROM asset_files;
SELECT setval(pg_get_serial_sequence('asset_images', 'id'), COALESCE(MAX(id), 1)) FROM asset_images;
SELECT setval(pg_get_serial_sequence('transactions', 'id'), COALESCE(MAX(id), 1)) FROM transactions;
SELECT setval(pg_get_serial_sequence('entitlements', 'id'), COALESCE(MAX(id), 1)) FROM entitlements;
SELECT setval(pg_get_serial_sequence('download_logs', 'id'), COALESCE(MAX(id), 1)) FROM download_logs;

-- menghitung pembagian dividen bersih seller(potongan biaya platform 10%)
CREATE OR REPLACE FUNCTION hitung_royalti_seller(p_amount NUMERIC, p_fee_percent NUMERIC DEFAULT 10)
RETURNS NUMERIC AS $$
BEGIN
    RETURN p_amount - (p_amount * (p_fee_percent / 100));
END;
$$ LANGUAGE plpgsql;

SELECT hitung_royalti_seller(200000); 

-- view untuk menampilkan data asset siap jual bagi buyer
CREATE OR REPLACE VIEW vw_katalog_publik AS
SELECT a.id, a.title, a.slug, c.name AS category_name, 
	   u.name AS seller_name, a.price, a.render_pipeline, a.unity_version
FROM assets a
JOIN categories c ON a.category_id = c.id
JOIN users u ON a.seller_id = u.id
WHERE a.status = 'published' AND a.deleted_at IS NULL;

SELECT * FROM vw_katalog_publik;

-- laporan rekapitulasi omset bulanan bagi seller
CREATE MATERIALIZED VIEW IF NOT EXISTS mv_rekap_penjualan_bulanan AS
SELECT 
    DATE_TRUNC('month', paid_at) AS periode_bulan,
    COUNT(id) AS total_transaksi,
    SUM(amount) AS total_omset
FROM transactions
WHERE status = 'settlement'
GROUP BY DATE_TRUNC('month', paid_at);

SELECT * FROM mv_rekap_penjualan_bulanan;

INSERT INTO transactions (buyer_id, asset_id, invoice_number, amount, payment_type, status, paid_at, created_at)
VALUES (4, 2, 'INV-TEST-MV-01', 500000, 'qris', 'settlement', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);

REFRESH MATERIALIZED VIEW mv_rekap_penjualan_bulanan;

-- triger untuk membuat entitlement saat status transaksi berubah menjadi settlement 
CREATE OR REPLACE FUNCTION trg_grant_entitlement_on_settlement()
RETURNS TRIGGER AS $$
BEGIN
    IF NEW.status = 'settlement' AND (OLD.status IS NULL OR OLD.status != 'settlement') THEN
        INSERT INTO entitlements (user_id, asset_id, transaction_id, source, granted_at)
        VALUES (NEW.buyer_id, NEW.asset_id, NEW.id, 'purchase', CURRENT_TIMESTAMP)
        ON CONFLICT (id) DO NOTHING;
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trigger_auto_entitlement
AFTER UPDATE ON transactions
FOR EACH ROW
EXECUTE FUNCTION trg_grant_entitlement_on_settlement();

SELECT 
    trigger_name,
    event_object_table AS tabel,
    action_timing AS waktu,
    event_manipulation AS aksi
FROM information_schema.triggers
WHERE trigger_name = 'trigger_auto_entitlement';

-- cek transaksi ID 4 masih berstatus pending
SELECT id, buyer_id, asset_id, invoice_number, status 
FROM transactions 
WHERE id = 4;

-- cek hak akses untuk transaksi ID 4 saat ini masih kosong
SELECT * FROM entitlements WHERE transaction_id = 4;

UPDATE transactions 
SET status = 'settlement', paid_at = CURRENT_TIMESTAMP 
WHERE id = 4;

SELECT 
    e.id AS entitlement_id,
    u.name AS nama_pembeli,
    a.title AS judul_aset,
    e.source,
    e.granted_at
FROM entitlements e
JOIN users u ON e.user_id = u.id
JOIN assets a ON e.asset_id = a.id
WHERE e.transaction_id = 4;

select * from entitlements;
