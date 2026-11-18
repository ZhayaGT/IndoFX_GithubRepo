-- Profil toko penjual. user_id jadi primary key karena satu penjual satu toko.

CREATE TABLE IF NOT EXISTS seller_profiles (
    user_id             BIGINT PRIMARY KEY REFERENCES users (id) ON DELETE CASCADE,
    store_name          VARCHAR(150) NOT NULL,
    address             VARCHAR(500),
    reason              VARCHAR(1000),
    portfolio_url       VARCHAR(255),
    website_url         VARCHAR(255),
    payout_number       VARCHAR(50),
    payout_account_name VARCHAR(150),
    created_at          TIMESTAMPTZ NOT NULL DEFAULT NOW(),
    updated_at          TIMESTAMPTZ NOT NULL DEFAULT NOW()
);