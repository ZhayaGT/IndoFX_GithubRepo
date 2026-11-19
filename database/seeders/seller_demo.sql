-- Data contoh untuk pemeriksaan tampilan dashboard penjual. Aman dijalankan dua kali.
-- Urutan mengikuti foreign key: profil toko, transaksi, entitlements, download_logs.

INSERT INTO seller_profiles (user_id, store_name, address, reason, portfolio_url)
SELECT 4, 'Arga Studio', 'Malang, Jawa Timur', 'Studio aset VFX dan shader untuk game Unity.', 'https://www.artstation.com/argastudio'
WHERE EXISTS (SELECT 1 FROM users WHERE id = 4)
  AND NOT EXISTS (SELECT 1 FROM seller_profiles WHERE user_id = 4);

INSERT INTO seller_profiles (user_id, store_name, address, reason, portfolio_url)
SELECT 5, 'Nusantara Digital', 'Bandung, Jawa Barat', 'Kreator aset 3D dan environment.', 'https://www.artstation.com/nusantaradigital'
WHERE EXISTS (SELECT 1 FROM users WHERE id = 5)
  AND NOT EXISTS (SELECT 1 FROM seller_profiles WHERE user_id = 5);

INSERT INTO transactions (buyer_id, asset_id, invoice_number, amount, payment_type, status, paid_at)
SELECT (SELECT id FROM users WHERE role = 'buyer' ORDER BY id LIMIT 1), a.id,
       'DEMO-0001', a.price, 'qris', 'settlement', NOW() - INTERVAL '3 days'
FROM assets a WHERE a.seller_id = 4 AND a.price > 0 ORDER BY a.id LIMIT 1 OFFSET 0
ON CONFLICT (invoice_number) DO NOTHING;

INSERT INTO transactions (buyer_id, asset_id, invoice_number, amount, payment_type, status, paid_at)
SELECT (SELECT id FROM users WHERE role = 'buyer' ORDER BY id LIMIT 1), a.id,
       'DEMO-0002', a.price, 'bank_transfer', 'settlement', NOW() - INTERVAL '7 days'
FROM assets a WHERE a.seller_id = 4 AND a.price > 0 ORDER BY a.id LIMIT 1 OFFSET 1
ON CONFLICT (invoice_number) DO NOTHING;

INSERT INTO transactions (buyer_id, asset_id, invoice_number, amount, payment_type, status, paid_at)
SELECT (SELECT id FROM users WHERE role = 'buyer' ORDER BY id LIMIT 1), a.id,
       'DEMO-0003', a.price, 'credit_card', 'settlement', NOW() - INTERVAL '12 days'
FROM assets a WHERE a.seller_id = 4 AND a.price > 0 ORDER BY a.id LIMIT 1 OFFSET 2
ON CONFLICT (invoice_number) DO NOTHING;

INSERT INTO transactions (buyer_id, asset_id, invoice_number, amount, payment_type, status, paid_at)
SELECT (SELECT id FROM users WHERE role = 'buyer' ORDER BY id LIMIT 1), a.id,
       'DEMO-0004', a.price, 'qris', 'settlement', NOW() - INTERVAL '19 days'
FROM assets a WHERE a.seller_id = 4 AND a.price > 0 ORDER BY a.id LIMIT 1 OFFSET 3
ON CONFLICT (invoice_number) DO NOTHING;

INSERT INTO transactions (buyer_id, asset_id, invoice_number, amount, payment_type, status, paid_at)
SELECT (SELECT id FROM users WHERE role = 'buyer' ORDER BY id LIMIT 1), a.id,
       'DEMO-0005', a.price, 'bank_transfer', 'settlement', NOW() - INTERVAL '2 days'
FROM assets a WHERE a.seller_id = 5 AND a.price > 0 ORDER BY a.id LIMIT 1 OFFSET 0
ON CONFLICT (invoice_number) DO NOTHING;

INSERT INTO transactions (buyer_id, asset_id, invoice_number, amount, payment_type, status, paid_at)
SELECT (SELECT id FROM users WHERE role = 'buyer' ORDER BY id LIMIT 1), a.id,
       'DEMO-0006', a.price, 'qris', 'settlement', NOW() - INTERVAL '9 days'
FROM assets a WHERE a.seller_id = 5 AND a.price > 0 ORDER BY a.id LIMIT 1 OFFSET 1
ON CONFLICT (invoice_number) DO NOTHING;

INSERT INTO transactions (buyer_id, asset_id, invoice_number, amount, payment_type, status, paid_at)
SELECT (SELECT id FROM users WHERE role = 'buyer' ORDER BY id LIMIT 1), a.id,
       'DEMO-0007', a.price, 'credit_card', 'settlement', NOW() - INTERVAL '16 days'
FROM assets a WHERE a.seller_id = 5 AND a.price > 0 ORDER BY a.id LIMIT 1 OFFSET 2
ON CONFLICT (invoice_number) DO NOTHING;

INSERT INTO transactions (buyer_id, asset_id, invoice_number, amount, payment_type, status, paid_at)
SELECT (SELECT id FROM users WHERE role = 'buyer' ORDER BY id LIMIT 1), a.id,
       'DEMO-0008', a.price, 'qris', 'settlement', NOW() - INTERVAL '26 days'
FROM assets a WHERE a.seller_id = 5 AND a.price > 0 ORDER BY a.id LIMIT 1 OFFSET 3
ON CONFLICT (invoice_number) DO NOTHING;

INSERT INTO entitlements (user_id, asset_id, transaction_id, source)
SELECT t.buyer_id, t.asset_id, t.id, 'purchase'
FROM transactions t
WHERE t.invoice_number LIKE 'DEMO-%'
  AND NOT EXISTS (SELECT 1 FROM entitlements e WHERE e.transaction_id = t.id);

INSERT INTO download_logs (entitlement_id, ip_address)
SELECT e.id, '127.0.0.1'
FROM entitlements e
JOIN transactions t ON t.id = e.transaction_id
WHERE t.invoice_number IN ('DEMO-0001', 'DEMO-0003', 'DEMO-0005')
  AND NOT EXISTS (SELECT 1 FROM download_logs d WHERE d.entitlement_id = e.id);