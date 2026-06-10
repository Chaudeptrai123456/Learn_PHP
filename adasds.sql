CREATE DATABASE ecommerce;
USE ecommerce;
UPDATE users
SET role = 'admin'
WHERE email = 'phamchaugiatu123@gmail.com';


SELECT * FROM orders;

UPDATE users
SET 
    phone = CASE 
        WHEN phone IS NULL OR phone = '' 
        THEN CONCAT('09', FLOOR(100000000 + RAND() * 900000000))
        ELSE phone
    END,

    address = CASE 
        WHEN address IS NULL OR address = '' 
        THEN ELT(
            FLOOR(1 + RAND() * 5),
            'Ho Chi Minh City',
            'Ha Noi',
            'Da Nang',
            'Can Tho',
            'Hai Phong'
        )
        ELSE address
    END;



 ALTER TABLE users 
 
ADD COLUMN address VARCHAR(500) NULL AFTER phone;
SELECT * FROM vouchers;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    phone VARCHAR(20) UNIQUE,
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
    sku_code VARCHAR(100) NOT NULL,
    price DECIMAL(15, 2) NOT NULL,
    old_price DECIMAL(15, 2),
    stock_qty INT DEFAULT 0,
    sold_qty INT DEFAULT 0,
    image_url VARCHAR(500),
    is_default TINYINT(1) DEFAULT 0, 
    UNIQUE KEY unique_product_sku (product_id, sku_code),
    
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
) ENGINE=INNODB;
























USE ecommerce;

-- Xóa dữ liệu cũ nếu có để tránh trùng lặp khi test lại
SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE order_items;
TRUNCATE TABLE orders;
TRUNCATE TABLE product_images;
TRUNCATE TABLE product_skus;
TRUNCATE TABLE products;
TRUNCATE TABLE brands;
TRUNCATE TABLE categories;
TRUNCATE TABLE vouchers;
TRUNCATE TABLE users;
SET FOREIGN_KEY_CHECKS = 1;
USE ksadjfklsjdfl;
-- =========================================================================
-- 1. INSERT DANH MỤC (Chỉ gồm Điện thoại & Laptop)
-- =========================================================================
INSERT INTO categories (id, parent_id, name, slug, status) VALUES
(1, NULL, 'Thiết bị công nghệ', 'thiet-bi-cong-nghe', 1),
(2, 1, 'Điện thoại thông minh', 'dien-thoai-thong-minh', 1),
(3, 1, 'Máy tính xách tay (Laptop)', 'laptop', 1);

-- =========================================================================
-- 2. INSERT THƯƠNG HIỆU CÔNG NGHỆ
-- =========================================================================
INSERT INTO brands (id, name, slug, logo_url) VALUES
(1, 'Apple', 'apple', 'https://example.com/logos/apple.png'),
(2, 'Samsung', 'samsung', 'https://example.com/logos/samsung.png'),
(3, 'Dell', 'dell', 'https://example.com/logos/dell.png'),
(4, 'Asus', 'asus', 'https://example.com/logos/asus.png'),
(5, 'HP', 'hp', 'https://example.com/logos/hp.png');

-- =========================================================================
-- 3. INSERT SẢN PHẨM (5 Điện thoại & 5 Laptop)
-- =========================================================================
INSERT INTO products (id, category_id, brand_id, name, slug, short_description, long_description, base_price, status, rating_avg, view_count) VALUES
-- --- PHÂN KHÚC ĐIỆN THOẠI (category_id = 2) ---
(1, 2, 1, 'iPhone 15 Pro Max', 'iphone-15-pro-max', 'Flagship cao cấp nhất của Apple năm 2023.', 'Mô tả chi tiết iPhone 15 Pro Max với khung viền Titanium siêu nhẹ, nút Action mới và chip xử lý A17 Pro tối tân hỗ trợ chơi game mượt mà.', 29990000.00, 'published', 4.9, 1250),
(2, 2, 2, 'Samsung Galaxy S24 Ultra', 'samsung-galaxy-s24-ultra', 'Điện thoại tích hợp trí tuệ nhân tạo Galaxy AI.', 'Mô tả chi tiết Samsung Galaxy S24 Ultra sở hữu màn hình phẳng siêu sáng, bút S-Pen đa năng, camera chính lên tới 200MP và các tính năng AI hữu dụng.', 26990000.00, 'published', 4.8, 1100),
(3, 2, 1, 'iPhone 13', 'iphone-13', 'Điện thoại quốc dân, hiệu năng ổn định giá tốt.', 'Mô tả chi tiết iPhone 13 màn hình Super Retina XDR sắc nét, cụm camera chéo độc đáo, hiệu năng từ chip Apple A15 Bionic vẫn cực kỳ mạnh mẽ.', 13990000.00, 'published', 4.7, 850),
(4, 2, 2, 'Samsung Galaxy Z Fold 5', 'samsung-galaxy-z-fold-5', 'Điện thoại màn hình gập cao cấp, đa nhiệm tối đa.', 'Mô tả chi tiết Galaxy Z Fold 5 sở hữu cơ chế bản lề Flex mới gập không kẽ hở, màn hình lớn nâng cao hiệu suất làm việc và giải trí.', 32990000.00, 'published', 4.6, 620),
(5, 2, 4, 'Asus ROG Phone 8', 'asus-rog-phone-8', 'Điện thoại chuyên game đỉnh cao cho game thủ.', 'Mô tả chi tiết Asus ROG Phone 8 với cấu hình mạnh mẽ hàng đầu, hệ thống tản nhiệt tối ưu AeroActive Cooler cùng màn hình tần số quét cao.', 24990000.00, 'published', 4.8, 510),

-- --- PHÂN KHÚC LAPTOP (category_id = 3) ---
(6, 3, 1, 'MacBook Pro 14 inch M3', 'macbook-pro-14-inch-m3', 'Laptop tối ưu cho công việc sáng tạo chuyên nghiệp.', 'Mô tả chi tiết MacBook Pro M3 với hiệu năng vượt trội, thời lượng pin sử dụng thực tế lên đến 22 tiếng liên tục và màn hình Liquid Retina XDR cực đẹp.', 39990000.00, 'published', 4.9, 740),
(7, 3, 3, 'Dell XPS 15 9530', 'dell-xps-15-9530', 'Laptop Windows cao cấp, mỏng nhẹ hiệu năng cao.', 'Mô tả chi tiết Dell XPS 15 với màn hình vô cực OLED, vỏ nhôm nguyên khối sang trọng kết hợp cùng card đồ họa rời NVIDIA hỗ trợ xử lý đồ họa mượt mà.', 45990000.00, 'published', 4.7, 430),
(8, 3, 4, 'Asus ROG Zephyrus G14', 'asus-rog-zephyrus-g14', 'Laptop gaming nhỏ gọn, cơ động mạnh mẽ.', 'Mô tả chi tiết Asus ROG Zephyrus G14 sở hữu màn hình chuẩn màu, thiết kế lịch lãm mỏng nhẹ nhưng mang trong mình cấu hình Ryzen và RTX mạnh mẽ.', 35990000.00, 'published', 4.6, 380),
(9, 3, 5, 'HP Spectre x360 14', 'hp-spectre-x360-14', 'Laptop xoay gập 360 độ kèm bút cảm ứng.', 'Mô tả chi tiết HP Spectre x360 phân khúc cao cấp, màn hình OLED cảm ứng sắc nét, bản lề linh hoạt giúp chuyển đổi sử dụng như máy tính bảng dễ dàng.', 37990000.00, 'published', 4.5, 290),
(10, 3, 1, 'MacBook Air 13 inch M2', 'macbook-air-13-inch-m2', 'Chiếc laptop mỏng nhẹ, pin trâu phù hợp mọi nhu cầu.', 'Mô tả chi tiết MacBook Air M2 thiết kế vuông vắn mỏng nhẹ, không quạt tản nhiệt hoạt động hoàn toàn yên tĩnh, hiệu năng xử lý văn phòng cực tốt.', 23490000.00, 'published', 4.8, 920);

-- =========================================================================
-- 4. INSERT SKU SẢN PHẨM (Mỗi sản phẩm có SKU mặc định is_default = 1)
-- =========================================================================
INSERT INTO product_skus (id, product_id, sku_code, price, old_price, stock_qty, sold_qty, image_url, is_default) VALUES
-- iPhone 15 Pro Max (ID: 1)
(1, 1, 'IP15PM-256GB-TITAN', 29990000.00, 34990000.00, 45, 12, 'https://example.com/products/ip15-titan.jpg', 1), -- Mặc định
(2, 1, 'IP15PM-512GB-BLACK', 35990000.00, 40990000.00, 20, 4, 'https://example.com/products/ip15-black.jpg', 0),

-- Samsung Galaxy S24 Ultra (ID: 2)
(3, 2, 'S24U-256GB-GRAY', 26990000.00, 31990000.00, 40, 10, 'https://example.com/products/s24u-gray.jpg', 1), -- Mặc định
(4, 2, 'S24U-512GB-BLACK', 30990000.00, 35990000.00, 15, 3, 'https://example.com/products/s24u-black.jpg', 0),

-- iPhone 13 (ID: 3)
(5, 3, 'IP13-128GB-BLUE', 13990000.00, 16990000.00, 60, 25, 'https://example.com/products/ip13-blue.jpg', 1), -- Mặc định
(6, 3, 'IP13-256GB-PINK', 16490000.00, 19490000.00, 25, 8, 'https://example.com/products/ip13-pink.jpg', 0),

-- Samsung Galaxy Z Fold 5 (ID: 4)
(7, 4, 'ZFOLD5-256GB-BLUE', 32990000.00, 40990000.00, 15, 5, 'https://example.com/products/zfold5-blue.jpg', 1), -- Mặc định
(8, 4, 'ZFOLD5-512GB-BLACK', 36990000.00, 44990000.00, 10, 2, 'https://example.com/products/zfold5-black.jpg', 0),

-- Asus ROG Phone 8 (ID: 5)
(9, 5, 'ROG8-16GB-512GB', 24990000.00, 27990000.00, 30, 7, 'https://example.com/products/rog8.jpg', 1), -- Mặc định

-- MacBook Pro 14 inch M3 (ID: 6)
(10, 6, 'MBP14-M3-8G-512G', 39990000.00, 43990000.00, 20, 6, 'https://example.com/products/mbp14-gray.jpg', 1), -- Mặc định
(11, 6, 'MBP14-M3-16G-512G', 45490000.00, 49990000.00, 12, 3, 'https://example.com/products/mbp14-silver.jpg', 0),

-- Dell XPS 15 9530 (ID: 7)
(12, 7, 'DELL-XPS15-I7-16G', 45990000.00, 49990000.00, 10, 2, 'https://example.com/products/xps15.jpg', 1), -- Mặc định
(13, 7, 'DELL-XPS15-I9-32G', 55990000.00, 59990000.00, 5, 1, 'https://example.com/products/xps15-high.jpg', 0),

-- Asus ROG Zephyrus G14 (ID: 8)
(14, 8, 'ROG-G14-R7-16G', 35990000.00, 39990000.00, 15, 4, 'https://example.com/products/g14.jpg', 1), -- Mặc định

-- HP Spectre x360 14 (ID: 9)
(15, 9, 'HP-SPECTRE-I7-16G', 37990000.00, 41990000.00, 8, 2, 'https://example.com/products/spectre.jpg', 1), -- Mặc định

-- MacBook Air 13 inch M2 (ID: 10)
(16, 10, 'MBA13-M2-8G-256G', 23490000.00, 26990000.00, 35, 18, 'https://example.com/products/mba13-midnight.jpg', 1), -- Mặc định
(17, 10, 'MBA13-M2-16G-512G', 28990000.00, 32990000.00, 15, 6, 'https://example.com/products/mba13-silver.jpg', 0);

-- =========================================================================
-- 5. INSERT HÌNH ẢNH CHI TIẾT SẢN PHẨM
-- =========================================================================
INSERT INTO product_images (product_id, image_url, sort_order) VALUES
(1, 'https://example.com/products/ip15-back.jpg', 1),
(1, 'https://example.com/products/ip15-angle.jpg', 2),
(2, 'https://example.com/products/s24u-front.jpg', 1),
(6, 'https://example.com/products/mbp14-keyboard.jpg', 1),
(10, 'https://example.com/products/mba13-thin.jpg', 1);

-- =========================================================================
-- 6. INSERT NGƯỜI DÙNG HỆ THỐNG
-- =========================================================================
INSERT INTO users (id, email, name, avatar_url, password, provider, provider_id, role, status) VALUES
(1, 'admin@ecommerce.com', 'Admin Đẹp Trai', 'https://example.com/avatars/admin.png', '$2a$12$R9h/cIPz0gi.UR1gbyWD3ORpT0D1C63k5kXpWnreXn4p8K0Iox6Ym', 'local', NULL, 'admin', 1),
(2, 'chau@gmail.com', 'Châu Tester', 'https://example.com/avatars/chau.png', '$2a$12$R9h/cIPz0gi.UR1gbyWD3ORpT0D1C63k5kXpWnreXn4p8K0Iox6Ym', 'local', NULL, 'user', 1);

-- =========================================================================
-- 7. INSERT MÃ GIẢM GIÁ (Voucher)
-- =========================================================================
INSERT INTO vouchers (id, code, discount_type, discount_value, min_order_value, max_discount_value, start_date, end_date, usage_limit, status) VALUES
(1, 'TECHNEW', 'fixed', 200000.00, 10000000.00, NULL, '2024-01-01 00:00:00', '2026-12-31 23:59:59', 100, 1),
(2, 'PROLAPTOP', 'percent', 5.00, 20000000.00, 1500000.00, '2024-01-01 00:00:00', '2026-12-31 23:59:59', 50, 1);

-- =========================================================================
-- 8. INSERT ĐƠN HÀNG CHẠY THỬ (Mẫu mua Laptop & Điện thoại)
-- =========================================================================
-- Đơn 1: Châu mua 1 iPhone 15 Pro Max bản mặc định, thanh toán Momo thành công
INSERT INTO orders (id, user_id, voucher_id, total_amount, discount_amount, final_amount, status, payment_status, payment_method, note) VALUES
(1, 2, 1, 29990000.00, 200000.00, 29790000.00, 'delivered', 'paid', 'momo', 'Giao sau 5h chiều giúp mình.');

INSERT INTO order_items (order_id, product_id, sku_id, product_name, sku_code, price, quantity, image_url) VALUES
(1, 1, 1, 'iPhone 15 Pro Max', 'IP15PM-256GB-TITAN', 29990000.00, 1, 'https://example.com/products/ip15-titan.jpg');

-- Đơn 2: Châu mua 1 MacBook Air M2 mặc định, thanh toán khi nhận hàng (COD)
INSERT INTO orders (id, user_id, voucher_id, total_amount, discount_amount, final_amount, status, payment_status, payment_method, note) VALUES
(2, 2, NULL, 23490000.00, 0.00, 23490000.00, 'pending', 'unpaid', 'cod', 'Liên hệ trước khi giao.');

INSERT INTO order_items (order_id, product_id, sku_id, product_name, sku_code, price, quantity, image_url) VALUES
(2, 10, 16, 'MacBook Air 13 inch M2', 'MBA13-M2-8G-256G', 23490000.00, 1, 'https://example.com/products/mba13-midnight.jpg');


 