-- =====================================================================
-- Tugas 6 - Database MySQL untuk E-Commerce
-- Tabel: products, users, orders  +  query CRUD untuk produk
-- Jalankan file ini di phpMyAdmin (tab Import) atau mysql client.
-- =====================================================================

-- 1. Buat database dan gunakan
CREATE DATABASE IF NOT EXISTS ecommerce
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE ecommerce;

-- =====================================================================
-- 2. TABEL PRODUCTS
--    id, nama_produk, harga, deskripsi, stok
-- =====================================================================
CREATE TABLE IF NOT EXISTS products (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  nama_produk VARCHAR(150)  NOT NULL,
  harga       INT           NOT NULL,              -- Catatan: untuk uang nyata, DECIMAL(12,2) lebih tepat
  deskripsi   TEXT,
  stok        INT           NOT NULL DEFAULT 0,
  created_at  TIMESTAMP     DEFAULT CURRENT_TIMESTAMP
);

-- =====================================================================
-- 3. TABEL USERS
--    id, nama, email, password
-- =====================================================================
CREATE TABLE IF NOT EXISTS users (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  nama       VARCHAR(100)  NOT NULL,
  email      VARCHAR(150)  NOT NULL UNIQUE,
  password   VARCHAR(255)  NOT NULL,               -- simpan HASH (bcrypt), jangan password polos
  created_at TIMESTAMP     DEFAULT CURRENT_TIMESTAMP
);

-- =====================================================================
-- 4. TABEL ORDERS
--    order_id, user_id, product_id, quantity, total
-- =====================================================================
CREATE TABLE IF NOT EXISTS orders (
  order_id   INT AUTO_INCREMENT PRIMARY KEY,
  user_id    INT NOT NULL,
  product_id INT NOT NULL,
  quantity   INT NOT NULL DEFAULT 1,
  total      INT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_orders_user
    FOREIGN KEY (user_id)    REFERENCES users(id)    ON DELETE CASCADE,
  CONSTRAINT fk_orders_product
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- =====================================================================
-- 5. DATA CONTOH (opsional, untuk testing)
-- =====================================================================
INSERT INTO products (nama_produk, harga, deskripsi, stok) VALUES
  ('Keyboard Mechanical', 450000, 'Keyboard RGB switch biru',      25),
  ('Mouse Wireless',      180000, 'Mouse 2.4GHz hemat baterai',    40),
  ('Monitor 24 inch',    1500000, 'Monitor IPS Full HD 75Hz',      10);

INSERT INTO users (nama, email, password) VALUES
  ('Wira', 'wira@example.com', 'hash_password_disini'),
  ('Budi', 'budi@example.com', 'hash_password_disini');

INSERT INTO orders (user_id, product_id, quantity, total) VALUES
  (1, 1, 2, 900000),
  (2, 3, 1, 1500000);


-- =====================================================================
-- 6. QUERY CRUD UNTUK PRODUK
-- =====================================================================

-- ---- CREATE (menambah produk) ----
INSERT INTO products (nama_produk, harga, deskripsi, stok)
VALUES ('Headset Gaming', 320000, 'Headset surround 7.1', 15);

-- ---- READ (membaca produk) ----
-- semua produk
SELECT * FROM products;

-- satu produk berdasarkan id
SELECT * FROM products WHERE id = 1;

-- cari produk berdasarkan nama
SELECT * FROM products WHERE nama_produk LIKE '%mouse%';

-- ---- UPDATE (mengubah produk) ----
UPDATE products
SET harga = 400000, stok = 30
WHERE id = 1;

-- ---- DELETE (menghapus produk) ----
DELETE FROM products WHERE id = 4;
