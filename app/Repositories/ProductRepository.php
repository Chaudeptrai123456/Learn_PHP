<?php
namespace App\Repositories;

use App\Core\Database;
use App\Models\Product;
use App\Models\ProductSku;
use App\DTOs\ProductCreateDTO;
use App\Models\Category;
use PDO;

class ProductRepository {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
public function searchByName(string $keyword, int $limit = 20): array
{
    $sql = "
        SELECT p.*, s.price, s.old_price, s.image_url
        FROM products p
        LEFT JOIN product_skus s ON s.product_id = p.id AND s.is_default = 1
        WHERE p.status = 'published'
          AND MATCH(p.name) AGAINST (:keyword IN NATURAL LANGUAGE MODE)
        ORDER BY p.id DESC LIMIT :limit";

    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':keyword', $keyword, PDO::PARAM_STR);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return array_map(fn($row) => new Product($row), $rows);
}

public function searchByNameLike(string $keyword, int $limit = 20): array
{
    $sql = "
        SELECT p.*, s.price, s.old_price, s.image_url
        FROM products p
        LEFT JOIN product_skus s ON s.product_id = p.id AND s.is_default = 1
        WHERE p.status = 'published'
          AND p.name LIKE :keyword
        ORDER BY p.id DESC LIMIT :limit";

    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':keyword', "%$keyword%", PDO::PARAM_STR);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return array_map(fn($row) => new Product($row), $rows);
}
public function searchAdvanced($params) {

    $sql = "SELECT SQL_CALC_FOUND_ROWS p.id, p.name, p.slug, p.category_id, p.brand_id, MIN(s.price) as price
            FROM products p
            JOIN product_skus s ON s.product_id = p.id
            JOIN categories c ON c.id = p.category_id
            JOIN brands b ON b.id = p.brand_id
            WHERE s.is_default = 0";

    $bindings = [];
    $hasFilter = false;
    if (!empty($params['keyword'])) {
        $hasFilter = true;
        $sql .= " AND p.name LIKE :keyword";
        $bindings['keyword'] = "%" . $params['keyword'] . "%";
    }
    if (!empty($params['category']) && $params['category'] !== 'all') {
        $hasFilter = true;
        $sql .= " AND c.slug = :category";
        $bindings['category'] = $params['category'];
    }

    if (!empty($params['brand']) && $params['brand'] !== 'all') {
        $hasFilter = true;
        $sql .= " AND b.slug = :brand";
        $bindings['brand'] = $params['brand'];
    }
    if (!empty($params['price']) && $params['price'] !== 'all') {
        $hasFilter = true;

        $price = str_replace('-', '_', $params['price']);

        switch ($price) {
            case 'under_10m':
                $sql .= " AND s.price < 10000000";
                break;
            case '10m_25m':
                $sql .= " AND s.price BETWEEN 10000000 AND 25000000";
                break;
            case '25m_40m':
                $sql .= " AND s.price BETWEEN 25000000 AND 40000000";
                break;
            case 'over_40m':
                $sql .= " AND s.price > 40000000";
                break;
        }
    }

    $sql .= " GROUP BY p.id";

    if (!empty($params['sort'])) {
        switch ($params['sort']) {
            case 'low':
                $sql .= " ORDER BY price ASC";
                break;
            case 'high':
                $sql .= " ORDER BY price DESC";
                break;
            default:
                $sql .= " ORDER BY p.id DESC";
        }
    } else {
        $sql .= " ORDER BY p.id DESC";
    }

    $sql .= " LIMIT :limit OFFSET :offset";
    $stmt = $this->db->prepare($sql);
    foreach ($bindings as $key => $val) {
        $stmt->bindValue(":$key", $val);
    }
    $stmt->bindValue(':limit', (int)$params['limit'], PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$params['offset'], PDO::PARAM_INT);
    $stmt->execute();
    $data = $stmt->fetchAll();

    $total = $this->db->query("SELECT FOUND_ROWS()")->fetchColumn();
    return [
        'data' => $data,
        'total' => $total,
        'hasFilter' => $hasFilter
    ];
}


public function findSkuWithProduct(int $skuId): ?array
{
    $sql = "
        SELECT 
            p.id as product_id,
            p.name as product_name,

            s.id as sku_id,
            s.sku_code,
            s.price,
            s.image_url

        FROM product_skus s
        JOIN products p ON p.id = s.product_id

        WHERE s.id = :sku_id
        LIMIT 1
    ";

    $stmt = $this->db->prepare($sql);
    $stmt->execute(['sku_id' => $skuId]);

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result ?: null;
}
public function getDetailProduct(string $slug): ?Product
{
    $sql = "
        SELECT 
            p.id AS product_id,
            p.name AS product_name,
            p.slug,
            p.short_description,
            p.long_description,
            p.base_price,
            p.view_count,
            p.status,
            p.rating_avg,

            b.id AS brand_id,
            b.name AS brand_name,
            b.slug AS brand_slug,
            b.logo_url,

            c.id AS category_id,
            c.name AS category_name,
            c.slug AS category_slug,

            s.id AS sku_id,
            s.product_id,
            s.sku_code,
            s.price,
            s.old_price,
            s.stock_qty,
            s.sold_qty,
            s.image_url,
            s.is_default,

            CASE 
                WHEN s.old_price IS NOT NULL AND s.old_price > s.price 
                THEN ROUND((s.old_price - s.price) / s.old_price * 100)
                ELSE 0
            END AS discount_percent,

            pi.image_url AS gallery_image

        FROM products p
        LEFT JOIN brands b ON p.brand_id = b.id
        LEFT JOIN categories c ON p.category_id = c.id
        LEFT JOIN product_skus s ON s.product_id = p.id
        LEFT JOIN product_images pi ON pi.product_id = p.id

        WHERE p.slug = :slug
    ";

    $stmt = $this->db->prepare($sql);
    $stmt->execute(['slug' => $slug]);

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$rows) {
        return null;
    }

    $product = null;
    $skuMap = [];
    $images = [];

    foreach ($rows as $row) {

        if (!$product) {
            $product = new Product([
                'id' => $row['product_id'],
                'category_id' => $row['category_id'],
                'brand_id' => $row['brand_id'],
                'name' => $row['product_name'],
                'slug' => $row['slug'],
                'short_description' => $row['short_description'],
                'long_description' => $row['long_description'],
                'base_price' => $row['base_price'],
                'view_count' => $row['view_count'],
                'status' => $row['status'],
                'rating_avg' => $row['rating_avg']
            ]);
        }
        if (!empty($row['sku_id']) && !isset($skuMap[$row['sku_id']])) {
            $sku = new ProductSku([
                'id' => $row['sku_id'],
                'product_id' => $row['product_id'],
                'sku_code' => $row['sku_code'],
                'price' => $row['price'],
                'old_price' => $row['old_price'],
                'stock_qty' => $row['stock_qty'],
                'sold_qty' => $row['sold_qty'],
                'image_url' => $row['image_url'],
                'is_default' => $row['is_default']
            ]);

            $product->addSku($sku);
            $skuMap[$row['sku_id']] = true;
        }

        if (!empty($row['gallery_image'])) {
            $images[] = $row['gallery_image'];
        }
    }

    $product->images = array_values(array_unique($images));

    return $product;
} 
public function getProductsDefautl(
    ?int $categoryId = null,
    ?int $brandId = null,
    int $limit = 10,
    int $offset = 0
): array {

    $sql = "
    SELECT 
        p.id,
        p.name,
        p.slug,
        p.base_price,
        p.category_id,
        p.brand_id,
        p.rating_avg,

        c.name AS category_name,
        b.name AS brand_name,

        sku.id AS sku_id,
        sku.product_id,
        sku.sku_code,
        sku.price,
        sku.old_price,
        sku.image_url,
        sku.stock_qty,
        sku.sold_qty,
        sku.is_default

    FROM products p

    JOIN categories c ON c.id = p.category_id
    JOIN brands b ON b.id = p.brand_id

    LEFT JOIN product_skus sku 
        ON sku.product_id = p.id 
        AND sku.is_default = 1

    WHERE 
        p.status = 'published'

        AND (:category_id IS NULL OR p.category_id = :category_id_value)
        AND (:brand_id IS NULL OR p.brand_id = :brand_id_value)

    ORDER BY p.id DESC

    LIMIT :limit OFFSET :offset
";

$stmt = $this->db->prepare($sql);

// bind 2 lần riêng biệt
$stmt->bindValue(':category_id', $categoryId, is_null($categoryId) ? PDO::PARAM_NULL : PDO::PARAM_INT);
$stmt->bindValue(':category_id_value', $categoryId, is_null($categoryId) ? PDO::PARAM_NULL : PDO::PARAM_INT);

$stmt->bindValue(':brand_id', $brandId, is_null($brandId) ? PDO::PARAM_NULL : PDO::PARAM_INT);
$stmt->bindValue(':brand_id_value', $brandId, is_null($brandId) ? PDO::PARAM_NULL : PDO::PARAM_INT);

$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

$stmt->execute();

    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':category_id', $categoryId, is_null($categoryId) ? PDO::PARAM_NULL : PDO::PARAM_INT);
    $stmt->bindValue(':brand_id', $brandId, is_null($brandId) ? PDO::PARAM_NULL : PDO::PARAM_INT);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
    public function getAll(): array {
        $stmt = $this->db->query("
            SELECT 
                p.*,
                b.name AS brand_name,
                c.name AS category_name,
                s.price,
                s.old_price,
                CASE 
                    WHEN s.old_price > 0 
                    THEN ROUND((s.old_price - s.price) / s.old_price * 100)
                    ELSE 0
                END AS discount 
            FROM products p
            JOIN brands b ON p.brand_id = b.id
            JOIN categories c ON p.category_id = c.id
            LEFT JOIN product_skus s 
                ON s.product_id = p.id 
                AND s.is_default = 1
            ORDER BY p.id DESC
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getAllCategory(): array {
    // Truy vấn lấy tất cả danh mục (Sắp xếp parent_id tăng dần để danh mục Cha luôn lên trước danh mục Con)
    $stmt = $this->db->query("
        SELECT * 
        FROM categories 
        WHERE status = 1
        ORDER BY parent_id ASC, id ASC
    ");

    $categories = [];
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Ánh xạ mảng dữ liệu vào đối tượng App\Models\Category theo đúng Constructor Châu cung cấp
        $categories[] = new Category($row);
    }

    return $categories;
}

public function getTrendProduct() {
    $stmt = $this->db->query("
        SELECT p.*, s.price, p.view_count
        FROM products p
        JOIN product_skus s 
            ON p.id = s.product_id 
        WHERE s.is_default = 1
        ORDER BY p.view_count DESC
        LIMIT 8
    ");

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function hotProduct() {
    $stmt = $this->db->query("
        SELECT p.*, s.price, s.sold_qty
        FROM products p
        JOIN product_skus s 
            ON p.id = s.product_id 
        WHERE s.is_default = 1
        ORDER BY s.sold_qty DESC
        LIMIT 8
    ");

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function discountProduct() {
    $stmt = $this->db->query("
        SELECT 
            p.*, 
            s.price, 
            s.old_price,
            CASE 
                WHEN s.old_price > 0 
                THEN ROUND((s.old_price - s.price) / s.old_price * 100)
                ELSE 0
            END AS discount
        FROM products p
        JOIN product_skus s 
            ON p.id = s.product_id 
        WHERE s.is_default = 1
        AND s.old_price > s.price
        ORDER BY discount DESC
        LIMIT 6
    ");

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
    public function getAllPublished() {
        $stmt = $this->db->query("
            SELECT p.*, s.price 
            FROM products p 
            JOIN product_skus s 
                ON p.id = s.product_id 
            WHERE s.is_default = 1 
        ");

        $products = [];
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $products[] = new Product($row);
        }

        return $products;
    }



    public function create(ProductCreateDTO $dto): bool {
        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare("
                INSERT INTO products 
                (category_id, brand_id, name, slug, short_description, long_description, base_price) 
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");

            $stmt->execute([
                $dto->category_id,
                $dto->brand_id,
                $dto->name,
                $dto->slug,
                $dto->short_desc,
                $dto->long_desc,
                $dto->base_price
            ]);

            $productId = $this->db->lastInsertId();

            $this->insertSkus($productId, $dto);

            $this->db->commit();
            return true;

        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function updateWithSkus(int $productId, ProductCreateDTO $dto): bool {
        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare("
                UPDATE products 
                SET category_id=?, brand_id=?, name=?, slug=?, 
                    short_description=?, long_description=?, base_price=?
                WHERE id=?
            ");

            $stmt->execute([
                $dto->category_id,
                $dto->brand_id,
                $dto->name,
                $dto->slug,
                $dto->short_desc,
                $dto->long_desc,
                $dto->base_price,
                $productId
            ]);

            // simple strategy
            $this->db->prepare("DELETE FROM product_skus WHERE product_id = ?")
                     ->execute([$productId]);

            $this->insertSkus($productId, $dto);

            $this->db->commit();
            return true;

        } catch (\Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    private function insertSkus($productId, ProductCreateDTO $dto) {
        $stmt = $this->db->prepare("
            INSERT INTO product_skus 
            (product_id, sku_code, price, old_price, stock_qty, is_default)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        foreach ($dto->skus as $sku) {
            $stmt->execute([
                $productId,
                $sku->sku_code,
                $sku->price,
                $sku->old_price,
                $sku->stock_qty,
                $sku->is_default
            ]);
        }
    }
    public function softDelete(int $id): bool {
        return $this->db->prepare("
            UPDATE products SET deleted_at = NOW() WHERE id = ?
        ")->execute([$id]);
    }
}