CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    parent_id INT DEFAULT NULL,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    status TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES categories(id),
    INDEX idx_category_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,

    email VARCHAR(255) NOT NULL UNIQUE,
    name VARCHAR(255),
    avatar_url VARCHAR(500),

    password VARCHAR(255), -- NULL nếu login Google

    provider ENUM('local', 'google') DEFAULT 'google',
    provider_id VARCHAR(255), -- Google sub ID

    role ENUM('user', 'admin', 'staff') DEFAULT 'user',

    status TINYINT(1) DEFAULT 1, -- 1 active, 0 banned

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_user_email (email),
    INDEX idx_provider (provider, provider_id)
) ENGINE=InnoDB;
CREATE TABLE brands (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    logo_url VARCHAR(500),
    INDEX idx_brand_slug (slug)
) ENGINE=InnoDB;

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    brand_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    short_description TEXT,
    long_description LONGTEXT,
    base_price DECIMAL(15, 2) NOT NULL, -- Giá tham khảo thấp nhất
    status ENUM('draft', 'published', 'out_of_stock') DEFAULT 'published',
    rating_avg DECIMAL(3, 2) DEFAULT 0,
    view_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id),
    FOREIGN KEY (brand_id) REFERENCES brands(id),
    FULLTEXT INDEX idx_fulltext_name (name), -- Hỗ trợ Search nhanh
    INDEX idx_product_status (status)
) ENGINE=InnoDB;

CREATE TABLE product_skus (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    sku_code VARCHAR(100) UNIQUE NOT NULL, -- Ví dụ: IP15PM-TI-256
    price DECIMAL(15, 2) NOT NULL,
    old_price DECIMAL(15, 2),
    stock_qty INT DEFAULT 0,
    sold_qty INT DEFAULT 0,
    image_url VARCHAR(500),
    is_default TINYINT(1) DEFAULT 0, 
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    INDEX idx_sku_price (price)
) ENGINE=InnoDB;

CREATE TABLE vouchers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) UNIQUE NOT NULL,
    discount_type ENUM('fixed', 'percent') NOT NULL,
    discount_value DECIMAL(15, 2) NOT NULL,
    min_order_value DECIMAL(15, 2) DEFAULT 0,
    max_discount_value DECIMAL(15, 2) DEFAULT NULL, 
    start_date DATETIME,
    end_date DATETIME,
    usage_limit INT DEFAULT 100,
    status TINYINT(1) DEFAULT 1,
    INDEX idx_voucher_code (code)
) ENGINE=InnoDB;

CREATE TABLE product_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    image_url VARCHAR(500) NOT NULL,
    sort_order INT DEFAULT 0,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    voucher_id INT DEFAULT NULL,

    total_amount DECIMAL(15,2) NOT NULL, -- tổng tiền trước giảm
    discount_amount DECIMAL(15,2) DEFAULT 0,
    final_amount DECIMAL(15,2) NOT NULL, -- sau giảm

    status ENUM('pending', 'confirmed', 'shipping', 'delivered', 'cancelled') DEFAULT 'pending',
    payment_status ENUM('unpaid', 'paid', 'failed') DEFAULT 'unpaid',
    payment_method ENUM('cod', 'vnpay', 'momo') DEFAULT 'cod',

    note TEXT,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (voucher_id) REFERENCES vouchers(id),

    INDEX idx_order_user (user_id),
    INDEX idx_order_status (status)
) ENGINE=InnoDB;

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
    FOREIGN KEY (sku_id) REFERENCES product_skus(id),

    INDEX idx_order_items_order (order_id)
) ENGINE=InnoDB;
ALTER TABLE product_skus
ADD COLUMN is_default_flag INT AS (
    CASE WHEN is_default = 1 THEN product_id ELSE NULL END
) STORED;

ALTER TABLE product_skus
ADD UNIQUE KEY unique_default_per_product (is_default_flag);




DELIMITER $$

CREATE PROCEDURE create_product_full (
    IN p_category_id INT,
    IN p_brand_id INT,
    IN p_name VARCHAR(255),
    IN p_slug VARCHAR(255),
    IN p_short_desc TEXT,
    IN p_long_desc LONGTEXT,
    IN p_base_price DECIMAL(15,2)
)
BEGIN
    DECLARE v_product_id INT;

    -- ERROR HANDLER (rollback nếu lỗi)
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
    END;

    START TRANSACTION;

    -- 1. Validate slug unique
    IF EXISTS (SELECT 1 FROM products WHERE slug = p_slug) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Slug already exists';
    END IF;

    -- 2. Insert product
    INSERT INTO products (
        category_id, brand_id, name, slug,
        short_description, long_description, base_price
    )
    VALUES (
        p_category_id, p_brand_id, p_name, p_slug,
        p_short_desc, p_long_desc, p_base_price
    );

    SET v_product_id = LAST_INSERT_ID();

    -- 3. Insert default SKU (bắt buộc có ít nhất 1)
    INSERT INTO product_skus (
        product_id, sku_code, price, stock_qty, is_default
    )
    VALUES (
        v_product_id,
        CONCAT('SKU-', v_product_id),
        p_base_price,
        0,
        1
    );

    COMMIT;

END$$

DELIMITER ;
















-- ======================
-- CATEGORIES
-- ======================
INSERT INTO categories (id, parent_id, name, slug) VALUES
(1, NULL, 'Điện thoại', 'dien-thoai'),
(2, NULL, 'Laptop', 'laptop'),
(3, NULL, 'Tablet', 'tablet'),
(4, NULL, 'Phụ kiện', 'phu-kien');

-- ======================
-- BRANDS
-- ======================
INSERT INTO brands (id, name, slug, logo_url) VALUES
(1, 'Apple', 'apple', 'https://logo.clearbit.com/apple.com'),
(2, 'Samsung', 'samsung', 'https://logo.clearbit.com/samsung.com'),
(3, 'Dell', 'dell', 'https://logo.clearbit.com/dell.com'),
(4, 'Sony', 'sony', 'https://logo.clearbit.com/sony.com'),
(5, 'Asus', 'asus', 'https://logo.clearbit.com/asus.com');

-- ======================
-- USERS
-- ======================
INSERT INTO users (id, email, name, password, provider, role) VALUES
(1, 'admin@gmail.com', 'Admin', '123456', 'local', 'admin'),
(2, 'user1@gmail.com', 'User One', NULL, 'google', 'user');

-- ======================
-- PRODUCTS
-- ======================
INSERT INTO products (id, category_id, brand_id, name, slug, short_description, base_price) VALUES
(1, 1, 1, 'iPhone 15 Pro Max', 'iphone-15-pro-max', 'Flagship Apple', 29990000),
(2, 1, 2, 'Samsung Galaxy S24 Ultra', 's24-ultra', 'Camera zoom khủng', 27990000),
(3, 2, 3, 'Dell XPS 15', 'dell-xps-15', 'Laptop dev xịn', 38990000),
(4, 3, 1, 'iPad Pro M2', 'ipad-pro-m2', 'Tablet mạnh', 24990000),
(5, 4, 4, 'Sony WH-1000XM5', 'sony-xm5', 'Tai nghe ANC', 7990000);

-- ======================
-- PRODUCT SKUS
-- ======================
INSERT INTO product_skus (id, product_id, sku_code, price, old_price, stock_qty, sold_qty, image_url, is_default) VALUES
(1, 1, 'IP15PM-256', 29990000, 34990000, 100, 50, 'https://images.unsplash.com/photo-1696446701796-da61225697cc', 1),
(2, 2, 'S24U-512', 27990000, 31990000, 80, 40, 'https://images.unsplash.com/photo-1705585174800-5c6a9d9b0b2d', 1),
(3, 3, 'XPS15-1TB', 38990000, 42990000, 30, 10, 'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed', 1),
(4, 4, 'IPAD-M2', 24990000, 27990000, 60, 20, 'https://images.unsplash.com/photo-1585790050230-5dd28404ccb9', 1),
(5, 5, 'SONY-XM5', 7990000, 8990000, 120, 70, 'https://images.unsplash.com/photo-1618366712010-f4ae9c647dcb', 1);

-- ======================
-- PRODUCT IMAGES
-- ======================
INSERT INTO product_images (product_id, image_url) VALUES
(1, 'https://images.unsplash.com/photo-1696446701796-da61225697cc'),
(1, 'https://images.unsplash.com/photo-1695048133142-1a20484d2569'),
(2, 'https://images.unsplash.com/photo-1705585174800-5c6a9d9b0b2d'),
(3, 'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed'),
(4, 'https://images.unsplash.com/photo-1585790050230-5dd28404ccb9'),
(5, 'https://images.unsplash.com/photo-1618366712010-f4ae9c647dcb');

-- ======================
-- VOUCHERS
-- ======================
INSERT INTO vouchers (code, discount_type, discount_value, min_order_value) VALUES
('SALE10', 'percent', 10, 1000000),
('FREESHIP', 'fixed', 50000, 500000);

-- ======================
-- ORDERS
-- ======================
INSERT INTO orders (id, user_id, total_amount, final_amount, status) VALUES
(1, 2, 29990000, 26990000, 'delivered');

-- ======================
-- ORDER ITEMS
-- ======================
INSERT INTO order_items (order_id, product_id, sku_id, product_name, price, quantity) VALUES
(1, 1, 1, 'iPhone 15 Pro Max', 29990000, 1);