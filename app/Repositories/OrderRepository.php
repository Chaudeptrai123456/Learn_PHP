<?php
namespace App\Repositories;
use App\Core\Database;
use PDO;


class OrderRepository {
    public $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
public function getOrdersByUserId(int $userId): array {
    $sql = "
        SELECT 
            o.id AS order_id,
            o.total_amount,
            o.discount_amount,
            o.final_amount,
            o.status,
            o.payment_status,
            o.payment_method,
            o.created_at,

            v.code AS voucher_code,
            v.discount_type,
            v.discount_value,

            oi.id AS order_item_id,
            oi.product_name,
            oi.sku_code,
            oi.price,
            oi.quantity,
            oi.image_url,

            p.slug,
            p.name AS product_real_name,

            s.price AS sku_price

        FROM orders o

        LEFT JOIN vouchers v ON o.voucher_id = v.id

        LEFT JOIN order_items oi ON o.id = oi.order_id
        LEFT JOIN products p ON oi.product_id = p.id
        LEFT JOIN product_skus s ON oi.sku_id = s.id

        WHERE o.user_id = :user_id

        ORDER BY o.id DESC, oi.id ASC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['user_id' => $userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOrderDetail(int $orderId): array {
        $stmt = $this->db->prepare("
            SELECT o.*, oi.*
            FROM orders o
            JOIN order_items oi ON oi.order_id = o.id
            WHERE o.id = :id
        ");

        $stmt->execute(['id' => $orderId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createOrder(array $data): int {
        $stmt = $this->db->prepare("
            INSERT INTO orders 
            (user_id, total_amount, discount_amount, final_amount, payment_method, note)
            VALUES (:user_id, :total, :discount, :final, :payment, :note)
        ");

        $stmt->execute([
            'user_id' => $data['user_id'],
            'total'   => $data['total_amount'],
            'discount'=> $data['discount_amount'],
            'final'   => $data['final_amount'],
            'payment' => $data['payment_method'],
            'note'    => $data['note'] ?? null
        ]);

        return (int)$this->db->lastInsertId();
    }

    public function insertOrderItems(int $orderId, array $items): void {
        $stmt = $this->db->prepare("
            INSERT INTO order_items 
            (order_id, product_id, sku_id, product_name, sku_code, price, quantity, image_url)
            VALUES (:order_id, :product_id, :sku_id, :name, :sku_code, :price, :qty, :img)
        ");

        foreach ($items as $item) {
            $stmt->execute([
                'order_id'   => $orderId,
                'product_id' => $item->product_id,
                'sku_id'     => $item->sku_id,
                'name'       => $item->product_name,
                'sku_code'   => $item->sku_code,
                'price'      => $item->price,
                'qty'        => $item->quantity,
                'img'        => $item->image_url
            ]);
        }
    }
}