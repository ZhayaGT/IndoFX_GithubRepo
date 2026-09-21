-- Skema basis data IndoFX (PostgreSQL)
-- Milik: Back End. Perubahan wajib diumumkan ke tim sebelum digabungkan.

CREATE EXTENSION IF NOT EXISTS "pgcrypto";

-- ---------------------------------------------------------------------------
-- users
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
    id            UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    name          VARCHAR(255) NOT NULL,
    email         VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role          VARCHAR(50)  NOT NULL DEFAULT 'buyer'
                  CHECK (role IN ('buyer', 'seller', 'admin')),
    avatar_url    VARCHAR(255),
    is_active     BOOLEAN NOT NULL DEFAULT TRUE,
    created_at    TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at    TIMESTAMP NOT NULL DEFAULT NOW()
);

CREATE INDEX IF NOT EXISTS idx_users_role ON users (role);

-- ---------------------------------------------------------------------------
-- assets
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS assets (
    id               UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    seller_id        UUID NOT NULL REFERENCES users (id) ON DELETE CASCADE,
    slug             VARCHAR(255) NOT NULL UNIQUE,
    title            VARCHAR(255) NOT NULL,
    short_description VARCHAR(500),
    description_md   TEXT,
    how_to_use_md    TEXT,
    price            INTEGER NOT NULL DEFAULT 0 CHECK (price >= 0),
    render_pipeline  VARCHAR(50) CHECK (render_pipeline IN ('Built-in', 'URP', 'HDRP')),
    unity_version    VARCHAR(50),
    gdrive_file_id   VARCHAR(255),
    file_name        VARCHAR(255),
    file_size        BIGINT,
    preview_video_url VARCHAR(255),
    images           JSONB NOT NULL DEFAULT '[]'::JSONB,
    status           VARCHAR(50) NOT NULL DEFAULT 'draft'
                     CHECK (status IN ('draft', 'published', 'archived')),
    download_count   INTEGER NOT NULL DEFAULT 0,
    created_at       TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at       TIMESTAMP NOT NULL DEFAULT NOW()
);

CREATE INDEX IF NOT EXISTS idx_assets_seller   ON assets (seller_id);
CREATE INDEX IF NOT EXISTS idx_assets_status   ON assets (status);
CREATE INDEX IF NOT EXISTS idx_assets_pipeline ON assets (render_pipeline);
CREATE INDEX IF NOT EXISTS idx_assets_price    ON assets (price);

-- ---------------------------------------------------------------------------
-- transactions
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS transactions (
    id                UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    invoice_id        VARCHAR(100) NOT NULL UNIQUE,
    buyer_id          UUID NOT NULL REFERENCES users (id) ON DELETE RESTRICT,
    asset_id          UUID NOT NULL REFERENCES assets (id) ON DELETE RESTRICT,
    amount            INTEGER NOT NULL CHECK (amount >= 0),
    payment_type      VARCHAR(50),
    midtrans_order_id VARCHAR(100) UNIQUE,
    midtrans_token    VARCHAR(255),
    status            VARCHAR(50) NOT NULL DEFAULT 'pending'
                      CHECK (status IN ('pending', 'settlement', 'capture', 'expire', 'failed', 'refund')),
    paid_at           TIMESTAMP,
    created_at        TIMESTAMP NOT NULL DEFAULT NOW(),
    updated_at        TIMESTAMP NOT NULL DEFAULT NOW()
);

CREATE INDEX IF NOT EXISTS idx_transactions_buyer  ON transactions (buyer_id);
CREATE INDEX IF NOT EXISTS idx_transactions_asset  ON transactions (asset_id);
CREATE INDEX IF NOT EXISTS idx_transactions_status ON transactions (status);

-- ---------------------------------------------------------------------------
-- entitlements (hak unduh setelah pembayaran terverifikasi)
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS entitlements (
    id             UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    user_id        UUID NOT NULL REFERENCES users (id) ON DELETE CASCADE,
    asset_id       UUID NOT NULL REFERENCES assets (id) ON DELETE CASCADE,
    transaction_id UUID REFERENCES transactions (id) ON DELETE SET NULL,
    granted_at     TIMESTAMP NOT NULL DEFAULT NOW(),
    UNIQUE (user_id, asset_id)
);

-- ---------------------------------------------------------------------------
-- reviews
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS reviews (
    id         UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    asset_id   UUID NOT NULL REFERENCES assets (id) ON DELETE CASCADE,
    user_id    UUID NOT NULL REFERENCES users (id) ON DELETE CASCADE,
    rating     SMALLINT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    comment    TEXT,
    created_at TIMESTAMP NOT NULL DEFAULT NOW(),
    UNIQUE (asset_id, user_id)
);

CREATE INDEX IF NOT EXISTS idx_reviews_asset ON reviews (asset_id);

-- ---------------------------------------------------------------------------
-- payment_notifications (log webhook Midtrans, untuk idempotensi)
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS payment_notifications (
    id             UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    order_id       VARCHAR(100) NOT NULL,
    transaction_status VARCHAR(50),
    signature_key  VARCHAR(255),
    payload        JSONB NOT NULL,
    processed_at   TIMESTAMP NOT NULL DEFAULT NOW(),
    UNIQUE (order_id, transaction_status)
);
