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




SELECT 
    p.id, 
    p.name, 
    p.slug, 
    b.name AS brand_name,
    s.price, 
    s.old_price,
    s.sold_qty,
    s.stock_qty,
    ROUND(((s.old_price - s.price) / s.old_price) * 100) AS discount_percent,
    s.image_url,
    p.rating_avg
FROM products p
JOIN brands b ON p.brand_id = b.id
JOIN product_skus s ON p.id = s.product_id
WHERE p.status = 'published' AND s.is_default = 1
ORDER BY p.created_at DESC
LIMIT 12;


SELECT name, slug, base_price 
FROM products 
WHERE MATCH(name) AGAINST('iPhone 15' IN NATURAL LANGUAGE MODE)
AND status = 'published';


-- Lấy thông tin chung
SELECT p.*, b.name as brand_name, c.name as cat_name
FROM products p
LEFT JOIN brands b ON p.brand_id = b.id
LEFT JOIN categories c ON p.category_id = c.id
WHERE p.slug = 'iphone-15-pro-max-titanium';

-- Lấy các phiên bản màu sắc/dung lượng hiện có
SELECT id, sku_code, price, old_price, stock_qty, image_url 
FROM product_skus 
WHERE product_id = (SELECT id FROM products WHERE slug = 'iphone-15-pro-max-titanium');