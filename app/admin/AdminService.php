<?php
namespace App\admin;

use App\Core\Services;
use App\Core\Controller;
use App\Core\Database;
use PDO;
use Exception;

class AdminDashboardService {
    public $db;

    public function __construct($db) {
        $this->db = $db;
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Đồng bộ lấy danh sách sản phẩm liên kết với SKU mặc định để đổ ra bảng quản trị
     */
    public function getDashboardProducts($filters = []) {
        $sql = "SELECT p.*, c.name as category_name, b.name as brand_name,
                       s.sku_code, s.price as sku_price, s.old_price as sku_old_price, s.stock_qty as sku_stock_qty,
                    (SELECT image_url FROM product_images WHERE product_id = p.id ORDER BY sort_order ASC LIMIT 1) as main_image
                FROM products p
                JOIN categories c ON p.category_id = c.id
                JOIN brands b ON p.brand_id = b.id
                LEFT JOIN product_skus s ON s.product_id = p.id AND s.is_default = 1";

        $conditions = [];
        $params = [];

        if (!empty($filters['search'])) {
            $conditions[] = "(p.name LIKE :search OR s.sku_code LIKE :search)";
            $params[':search'] = "%" . $filters['search'] . "%";
        }

        if (!empty($filters['category_id'])) {
            $conditions[] = "p.category_id = :category_id";
            $params[':category_id'] = (int)$filters['category_id'];
        }

        if (!empty($filters['brand_id'])) {
            $conditions[] = "p.brand_id = :brand_id";
            $params[':brand_id'] = (int)$filters['brand_id'];
        }

        if (!empty($filters['status'])) {
            $conditions[] = "p.status = :status";
            $params[':status'] = $filters['status'];
        }

        if (count($conditions) > 0) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        $sql .= " ORDER BY p.created_at DESC";

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCategories() {
        $sql = "SELECT id, name FROM categories WHERE status = 1 ORDER BY name ASC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBrands() {
        $sql = "SELECT id, name FROM brands ORDER BY name ASC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Tạo mới sản phẩm và ràng buộc SKU thủ công được cấu hình từ giao diện
     */
    public function createProduct($data, $skuData, $imageUrl) {
        try {
            $this->db->beginTransaction();

            // 1. Thêm bản ghi sản phẩm
            $sqlProduct = "INSERT INTO products (category_id, brand_id, name, slug, short_description, long_description, base_price, status) 
                           VALUES (:category_id, :brand_id, :name, :slug, :short_description, :long_description, :base_price, :status)";
            
            $stmtProduct = $this->db->prepare($sqlProduct);
            $stmtProduct->execute([
                ':category_id'       => (int)$data['category_id'],
                ':brand_id'          => (int)$data['brand_id'],
                ':name'              => $data['name'],
                ':slug'              => $data['slug'],
                ':short_description' => $data['short_description'],
                ':long_description'  => $data['long_description'],
                ':base_price'        => (float)$data['base_price'],
                ':status'            => $data['status']
            ]);

            $productId = $this->db->lastInsertId();

            // 2. Thêm ảnh
            if ($imageUrl) {
                $sqlImg = "INSERT INTO product_images (product_id, image_url, sort_order) VALUES (:product_id, :image_url, 0)";
                $stmtImg = $this->db->prepare($sqlImg);
                $stmtImg->execute([
                    ':product_id' => $productId,
                    ':image_url'  => $imageUrl
                ]);
            }

            // 3. Khởi tạo mã SKU và chèn vào bảng product_skus
            // Nếu người dùng không nhập tay SKU thì tự sinh mã SKU chuyên nghiệp dựa theo thương hiệu và tên viết tắt
            $skuCode = trim($skuData['sku_code'] ?? '');
            if (empty($skuCode)) {
                $cleanSlug = strtoupper(preg_replace('/[^a-zA-Z0-9]/', '', $data['slug']));
                $skuCode = 'SKU-' . substr($cleanSlug, 0, 10) . '-' . rand(1000, 9999);
            }

            $sqlSku = "INSERT INTO product_skus (product_id, sku_code, price, old_price, stock_qty, sold_qty, image_url, is_default) 
                       VALUES (:product_id, :sku_code, :price, :old_price, :stock_qty, 0, :image_url, 1)";
            
            $stmtSku = $this->db->prepare($sqlSku);
            $stmtSku->execute([
                ':product_id' => $productId,
                ':sku_code'   => $skuCode,
                ':price'      => (float)$skuData['price'],
                ':old_price'  => $skuData['old_price'] ? (float)$skuData['old_price'] : null,
                ':stock_qty'  => (int)$skuData['stock_qty'],
                ':image_url'  => $imageUrl
            ]);

            $this->db->commit();
            return $productId;

        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Failed to create product and SKU: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Cập nhật thông tin chi tiết sản phẩm và đồng bộ SKU tương ứng
     */
    public function updateProduct($id, $data, $skuData, $imageUrl = null) {
        try {
            $this->db->beginTransaction();

            // 1. Cập nhật bảng products
            $sqlProduct = "UPDATE products 
                           SET category_id = :category_id, 
                               brand_id = :brand_id, 
                               name = :name, 
                               slug = :slug, 
                               short_description = :short_description, 
                               long_description = :long_description, 
                               base_price = :base_price, 
                               status = :status 
                           WHERE id = :id";
            
            $stmtProduct = $this->db->prepare($sqlProduct);
            $stmtProduct->execute([
                ':category_id'       => (int)$data['category_id'],
                ':brand_id'          => (int)$data['brand_id'],
                ':name'              => $data['name'],
                ':slug'              => $data['slug'],
                ':short_description' => $data['short_description'],
                ':long_description'  => $data['long_description'],
                ':base_price'        => (float)$data['base_price'],
                ':status'            => $data['status'],
                ':id'                => (int)$id
            ]);

            // 2. Thêm ảnh nếu tải lên ảnh mới
            if ($imageUrl) {
                $sqlDelImg = "DELETE FROM product_images WHERE product_id = :product_id";
                $stmtDel = $this->db->prepare($sqlDelImg);
                $stmtDel->execute([':product_id' => (int)$id]);

                $sqlImg = "INSERT INTO product_images (product_id, image_url, sort_order) VALUES (:product_id, :image_url, 0)";
                $stmtImg = $this->db->prepare($sqlImg);
                $stmtImg->execute([
                    ':product_id' => (int)$id,
                    ':image_url'  => $imageUrl
                ]);
            }

            // 3. Cập nhật SKU mặc định hiện tại
            $sqlCheck = "SELECT id FROM product_skus WHERE product_id = :product_id AND is_default = 1 LIMIT 1";
            $stmtCheck = $this->db->prepare($sqlCheck);
            $stmtCheck->execute([':product_id' => (int)$id]);
            $skuExists = $stmtCheck->fetch(PDO::FETCH_ASSOC);

            if ($skuExists) {
                // Đã có SKU thì tiến hành cập nhật kho hàng và giá bán mới
                $sqlUpdSku = "UPDATE product_skus 
                              SET price = :price, 
                                  old_price = :old_price, 
                                  stock_qty = :stock_qty" . ($imageUrl ? ", image_url = :image_url" : "") . " 
                              WHERE product_id = :product_id AND is_default = 1";
                
                $stmtUpdSku = $this->db->prepare($sqlUpdSku);
                $paramsSku = [
                    ':price'      => (float)$skuData['price'],
                    ':old_price'  => $skuData['old_price'] ? (float)$skuData['old_price'] : null,
                    ':stock_qty'  => (int)$skuData['stock_qty'],
                    ':product_id' => (int)$id
                ];
                if ($imageUrl) {
                    $paramsSku[':image_url'] = $imageUrl;
                }
                $stmtUpdSku->execute($paramsSku);
            } else {
                // Khởi sinh SKU mặc định mới nếu hệ thống chưa có
                $cleanSlug = strtoupper(preg_replace('/[^a-zA-Z0-9]/', '', $data['slug']));
                $skuCode = 'SKU-' . substr($cleanSlug, 0, 10) . '-' . rand(1000, 9999);

                $sqlInsSku = "INSERT INTO product_skus (product_id, sku_code, price, old_price, stock_qty, is_default, image_url) 
                              VALUES (:product_id, :sku_code, :price, :old_price, :stock_qty, 1, :image_url)";
                $stmtInsSku = $this->db->prepare($sqlInsSku);
                $stmtInsSku->execute([
                    ':product_id' => (int)$id,
                    ':sku_code'   => $skuCode,
                    ':price'      => (float)$skuData['price'],
                    ':old_price'  => $skuData['old_price'] ? (float)$skuData['old_price'] : null,
                    ':stock_qty'  => (int)$skuData['stock_qty'],
                    ':image_url'  => $imageUrl
                ]);
            }

            $this->db->commit();
            return true;

        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Failed to update product and SKU: " . $e->getMessage());
            throw $e;
        }
    }

    public function deleteProduct($id) {
        $sql = "DELETE FROM products WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => (int)$id]);
    }

    public function getDashboardOrders($filters = []) {
        $sql = "SELECT 
                    o.id AS order_id,
                    o.final_amount,
                    o.status,
                    o.payment_status,
                    o.payment_method,
                    o.created_at,
                    u.id AS user_id,
                    u.name AS user_name,
                    u.email,
                    u.phone,
                    oi.quantity,
                    oi.price,
                    p.name AS product_name,
                    p.slug,
                    s.sku_code
                FROM orders o
                JOIN users u ON o.user_id = u.id
                JOIN order_items oi ON oi.order_id = o.id
                JOIN products p ON oi.product_id = p.id
                JOIN product_skus s ON oi.sku_id = s.id";

        $conditions = [];
        $params = [];

        if (!empty($filters['status'])) {
            $conditions[] = "o.status = :status";
            $params[':status'] = $filters['status'];
        }

        if (!empty($filters['customer_name'])) {
            $conditions[] = "u.name LIKE :customer_name";
            $params[':customer_name'] = "%" . $filters['customer_name'] . "%";
        }

        if (!empty($filters['start_date'])) {
            $conditions[] = "DATE(o.created_at) >= :start_date";
            $params[':start_date'] = $filters['start_date'];
        }

        if (!empty($filters['end_date'])) {
            $conditions[] = "DATE(o.created_at) <= :end_date";
            $params[':end_date'] = $filters['end_date'];
        }

        if (count($conditions) > 0) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        $sql .= " ORDER BY o.created_at DESC";

        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $this->groupOrders($rows);
    }

    public function groupOrders($rows) {
        $orders = [];
        foreach ($rows as $row) {
            $orderId = $row['order_id'];
            if (!isset($orders[$orderId])) {
                $orders[$orderId] = [
                    'order_id' => $orderId,
                    'final_amount' => $row['final_amount'],
                    'status' => $row['status'],
                    'payment_status' => $row['payment_status'],
                    'payment_method' => $row['payment_method'],
                    'created_at' => $row['created_at'],
                    'user' => [
                        'id' => $row['user_id'],
                        'name' => $row['user_name'],
                        'email' => $row['email'],
                        'phone' => $row['phone'],
                    ],
                    'items' => []
                ];
            }
            $orders[$orderId]['items'][] = [
                'product_name' => $row['product_name'],
                'slug' => $row['slug'],
                'sku_code' => $row['sku_code'],
                'price' => $row['price'],
                'quantity' => $row['quantity']
            ];
        }

        return array_values($orders);
    }

    public function updateOrderStatus($orderId, $status, $paymentStatus) {
        $sql = "UPDATE orders 
                SET status = :status, 
                    payment_status = :payment_status,
                    updated_at = CURRENT_TIMESTAMP
                WHERE id = :order_id";
                
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':status' => $status,
            ':payment_status' => $paymentStatus,
            ':order_id' => $orderId
        ]);
    }
      public function getFullDashboard() {
        return [
            'stats' => $this->getDashboardStats(),
            'recent_orders' => $this->getRecentOrders(),
            'top_products' => $this->getTopSellingProducts(),
            'low_stock' => $this->getLowStockProducts(),
            'revenue_chart' => $this->getRevenueByDate(),
            'order_status' => $this->getOrderStatusStats(),
            'payment_stats' => $this->getPaymentMethodStats(),
            'top_customers' => $this->getTopCustomers(),
        ];
    }
 
    public function getDashboardStats() {
        $sql = "SELECT 
                    COUNT(*) as total_orders,
                    SUM(final_amount) as total_revenue,
                    SUM(CASE WHEN status = 'delivered' THEN final_amount ELSE 0 END) as completed_revenue,
                    SUM(CASE WHEN payment_status = 'paid' THEN final_amount ELSE 0 END) as paid_revenue,
                    COUNT(DISTINCT user_id) as total_customers
                FROM orders";

        return $this->db->query($sql)->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Thống kê sản phẩm bán chạy nhất
     */
    public function getTopSellingProducts($limit = 10) {
        $sql = "SELECT 
                    p.id,
                    p.name,
                    p.slug,
                    SUM(oi.quantity) as total_sold,
                    SUM(oi.quantity * oi.price) as revenue
                FROM order_items oi
                JOIN products p ON oi.product_id = p.id
                JOIN orders o ON oi.order_id = o.id
                WHERE o.status = 'delivered'
                GROUP BY p.id, p.name, p.slug
                ORDER BY total_sold DESC
                LIMIT :limit";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Thống kê doanh thu theo thời gian
     */
    public function getRevenueByDate($type = 'day') {
        $format = $type === 'month' ? '%Y-%m' : '%Y-%m-%d';

        $sql = "SELECT 
                    DATE_FORMAT(created_at, :format) as period,
                    SUM(final_amount) as revenue
                FROM orders
                WHERE status = 'delivered'
                GROUP BY period
                ORDER BY period ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':format', $format, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Thống kê số lượng đơn hàng theo từng trạng thái
     */
    public function getOrderStatusStats() {
        $sql = "SELECT 
                    status,
                    COUNT(*) as total
                FROM orders
                GROUP BY status";

        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy danh sách các đơn hàng mới cập nhật gần đây
     */
    public function getRecentOrders($limit = 10) {
        $sql = "SELECT 
                    o.id,
                    u.name,
                    o.final_amount,
                    o.status,
                    o.created_at
                FROM orders o
                JOIN users u ON o.user_id = u.id
                ORDER BY o.created_at DESC
                LIMIT :limit";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Lấy danh sách sản phẩm có lượng tồn kho dưới ngưỡng tối thiểu
     */
    public function getLowStockProducts($threshold = 10) {
        $sql = "SELECT 
                    p.name,
                    s.sku_code,
                    s.stock_qty
                FROM product_skus s
                JOIN products p ON s.product_id = p.id
                WHERE s.stock_qty <= :threshold
                ORDER BY s.stock_qty ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':threshold', $threshold, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Thống kê phương thức thanh toán được sử dụng
     */
    public function getPaymentMethodStats() {
        $sql = "SELECT 
                    payment_method,
                    COUNT(*) as total,
                    SUM(final_amount) as revenue
                FROM orders
                GROUP BY payment_method";

        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Thống kê những khách hàng tiềm năng chi tiêu nhiều nhất
     */
    public function getTopCustomers($limit = 5) {
        $sql = "SELECT 
                    u.name,
                    u.email,
                    SUM(o.final_amount) as total_spent
                FROM users u
                JOIN orders o ON u.id = o.user_id
                WHERE o.status = 'delivered'
                GROUP BY u.id, u.name, u.email
                ORDER BY total_spent DESC
                LIMIT :limit";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}