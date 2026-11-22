CREATE TABLE users (id BIGSERIAL PRIMARY KEY, email TEXT UNIQUE NOT NULL,
    role TEXT NOT NULL, is_active BOOLEAN NOT NULL DEFAULT TRUE, remember_token TEXT,
    created_at TIMESTAMPTZ DEFAULT NOW(), updated_at TIMESTAMPTZ DEFAULT NOW());
CREATE TABLE user_profiles (user_id BIGINT PRIMARY KEY REFERENCES users(id), name TEXT);
CREATE TABLE seller_profiles (user_id BIGINT PRIMARY KEY REFERENCES users(id), store_name TEXT,
    status TEXT DEFAULT 'pending', reject_reason TEXT, reviewed_by BIGINT REFERENCES users(id),
    reviewed_at TIMESTAMPTZ, created_at TIMESTAMPTZ DEFAULT NOW(), updated_at TIMESTAMPTZ DEFAULT NOW());
CREATE TABLE categories (id BIGSERIAL PRIMARY KEY, name TEXT NOT NULL, slug TEXT UNIQUE NOT NULL,
    description TEXT DEFAULT '', icon TEXT DEFAULT 'bi-tags', updated_at TIMESTAMPTZ DEFAULT NOW());
CREATE TABLE assets (id BIGSERIAL PRIMARY KEY, seller_id BIGINT REFERENCES users(id),
    category_id BIGINT REFERENCES categories(id), status TEXT DEFAULT 'pending', reject_reason TEXT,
    reviewed_by BIGINT REFERENCES users(id), reviewed_at TIMESTAMPTZ,
    updated_at TIMESTAMPTZ DEFAULT NOW(), deleted_at TIMESTAMPTZ);
