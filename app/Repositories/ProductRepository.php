<?php
namespace App\Repositories;

use App\Core\Database;
use App\Models\Product;
use App\Models\ProductSku;
use App\DTOs\ProductCreateDTO;
use PDO;

class ProductRepository {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findBySlug($slug) {
        // Query SQL
        $stmt = $this->db->prepare("SELECT * FROM products WHERE slug = :slug LIMIT 1");
        $stmt->execute(['slug' => $slug]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) return null;

        // DATA MAPPER: Biến array thô thành Entity Object
        $product = new Product($row);

        // Lấy kèm các SKU
        $stmt = $this->db->prepare("SELECT * FROM product_skus WHERE product_id = ?");
        $stmt->execute([$product->id]);
        while($skuRow = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $product->addSku(new ProductSku($skuRow));
        }

        return $product;
    }

    public function getAllPublished() {
        $stmt = $this->db->query("SELECT p.*, s.price FROM products p JOIN product_skus s ON p.id = s.product_id WHERE s.is_default = 1");
        $products = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $products[] = new Product($row);
        }
        return $products;
    }
     public function create(ProductCreateDTO $dto): bool {
        try {
            $this->db->beginTransaction();

            $sql = "INSERT INTO products (category_id, brand_id, name, slug, short_description, long_description, base_price) 
                    VALUES (:cat, :brand, :name, :slug, :short, :long, :price)";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'cat'    => $dto->category_id,
                'brand'  => $dto->brand_id,
                'name'   => $dto->name,
                'slug'   => $dto->slug,
                'short'  => $dto->short_desc,
                'long'   => $dto->long_desc,
                'price'  => $dto->base_price
            ]);

            $productId = $this->db->lastInsertId();

            // Lưu SKU thông qua mảng DTO
            $skuSql = "INSERT INTO product_skus (product_id, sku_code, price, old_price, stock_qty, is_default) VALUES (?, ?, ?, ?, ?, ?)";
            $skuStmt = $this->db->prepare($skuSql);

            foreach ($dto->skus as $skuDto) {
                $skuStmt->execute([
                    $productId,
                    $skuDto->sku_code,
                    $skuDto->price,
                    $skuDto->old_price,
                    $skuDto->stock_qty,
                    $skuDto->is_default
                ]);
            }

            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}