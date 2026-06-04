-- TẠO DATABASE
CREATE DATABASE IF NOT EXISTS lab04 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE lab04;

-- ========================
-- TABLE: categories
-- ========================
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    parent_id INT DEFAULT NULL,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    status TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES categories(id),
    INDEX idx_category_slug (slug)
) ENGINE=InnoDB;

-- ========================
-- TABLE: users
-- ========================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    name VARCHAR(255),
    avatar_url VARCHAR(500),
    password VARCHAR(255),
    provider ENUM('local', 'google') DEFAULT 'google',
    provider_id VARCHAR(255),
    role ENUM('user', 'admin', 'staff') DEFAULT 'user',
    status TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ========================
-- TABLE: brands
-- ========================
CREATE TABLE brands (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    logo_url VARCHAR(500)
) ENGINE=InnoDB;

-- ========================
-- TABLE: products
-- ========================
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    brand_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    short_description TEXT,
    long_description LONGTEXT,
    base_price DECIMAL(15,2) NOT NULL,
    status ENUM('draft', 'published', 'out_of_stock') DEFAULT 'published',
    rating_avg DECIMAL(3,2) DEFAULT 0,
    view_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id),
    FOREIGN KEY (brand_id) REFERENCES brands(id)
) ENGINE=InnoDB;

-- ========================
-- TABLE: product_skus
-- ========================
CREATE TABLE product_skus (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    sku_code VARCHAR(100) UNIQUE NOT NULL,
    price DECIMAL(15,2) NOT NULL,
    old_price DECIMAL(15,2),
    stock_qty INT DEFAULT 0,
    sold_qty INT DEFAULT 0,
    image_url VARCHAR(500),
    is_default TINYINT(1) DEFAULT 0,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ========================
-- TABLE: vouchers
-- ========================
CREATE TABLE vouchers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) UNIQUE NOT NULL,
    discount_type ENUM('fixed', 'percent') NOT NULL,
    discount_value DECIMAL(15,2) NOT NULL,
    min_order_value DECIMAL(15,2) DEFAULT 0,
    max_discount_value DECIMAL(15,2),
    start_date DATETIME,
    end_date DATETIME,
    usage_limit INT DEFAULT 100,
    status TINYINT(1) DEFAULT 1
) ENGINE=InnoDB;

-- ========================
-- TABLE: product_images
-- ========================
CREATE TABLE product_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    image_url VARCHAR(500) NOT NULL,
    sort_order INT DEFAULT 0,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ========================
-- TABLE: orders
-- ========================
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    voucher_id INT DEFAULT NULL,
    total_amount DECIMAL(15,2) NOT NULL,
    discount_amount DECIMAL(15,2) DEFAULT 0,
    final_amount DECIMAL(15,2) NOT NULL,
    status ENUM('pending', 'confirmed', 'shipping', 'delivered', 'cancelled') DEFAULT 'pending',
    payment_status ENUM('unpaid', 'paid', 'failed') DEFAULT 'unpaid',
    payment_method ENUM('cod', 'vnpay', 'momo') DEFAULT 'cod',
    note TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (voucher_id) REFERENCES vouchers(id)
) ENGINE=InnoDB;

-- ========================
-- TABLE: order_items
-- ========================
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    sku_id INT NOT NULL,
    product_name VARCHAR(255) NOT NULL,
    sku_code VARCHAR(100),
    price DECIMAL(15,2) NOT NULL,
    quantity INT NOT NULL,
    image_url VARCHAR(500),
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id),
    FOREIGN KEY (sku_id) REFERENCES product_skus(id)
) ENGINE=InnoDB;

-- ========================
-- INSERT DATA
-- ========================

INSERT INTO categories (id, parent_id, name, slug) VALUES
(1, NULL, 'Điện thoại', 'dien-thoai'),
(2, NULL, 'Laptop', 'laptop'),
(3, 1, 'iPhone', 'iphone'),
(4, 1, 'Samsung', 'samsung');

INSERT INTO brands (id, name, slug) VALUES
(1, 'Apple', 'apple'),
(2, 'Samsung', 'samsung'),
(3, 'Dell', 'dell');

INSERT INTO users (email, name, password, provider, role) VALUES
('admin@gmail.com', 'Admin', '123456', 'local', 'admin'),
('user@gmail.com', 'User', '123456', 'local', 'user');

INSERT INTO products (id, category_id, brand_id, name, slug, base_price) VALUES
(1, 3, 1, 'iPhone 15 Pro Max', 'iphone-15-pro-max', 30000000),
(2, 4, 2, 'Samsung Galaxy S24', 'samsung-galaxy-s24', 25000000),
(3, 2, 3, 'Dell XPS 13', 'dell-xps-13', 28000000);

INSERT INTO product_skus (product_id, sku_code, price, stock_qty, is_default) VALUES
(1, 'IP15PM-256', 30000000, 10, 1),
(2, 'SS-S24-256', 25000000, 8, 1),
(3, 'DELL-XPS13', 28000000, 6, 1);

INSERT INTO product_images (product_id, image_url) VALUES
(1, 'iphone.png'),
(2, 'samsung.png'),
(3, 'dell.png');

INSERT INTO vouchers (code, discount_type, discount_value) VALUES
('SALE10', 'percent', 10),
('GIAM50K', 'fixed', 50000);

INSERT INTO orders (user_id, total_amount, final_amount) VALUES
(1, 30000000, 27000000);

INSERT INTO order_items (order_id, product_id, sku_id, product_name, price, quantity) VALUES
(1, 1, 1, 'iPhone 15 Pro Max', 30000000, 1);