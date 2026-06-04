<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Repositories\ProductRepository;
use App\Repositories\CategoryRepository;
use App\DTOs\ProductCreateDTO;

class ProductController extends Controller {

    private ProductRepository $proRepo;
    private CategoryRepository $cateRepo;
   public function __construct() {
        $this->proRepo = new ProductRepository();
        $this->cateRepo = new CategoryRepository();
    }
public function search($category = null, $brand = null, $price = null) {

    $category = ($category === 'all') ? null : $category;
    $brand    = ($brand === 'all') ? null : $brand;
    $price    = ($price === 'all') ? null : $price;

    $keyword  = trim($_GET['search'] ?? '');
    $sort     = $_GET['sort'] ?? 'default';
    $page     = max(1, (int)($_GET['page'] ?? 1));

    $limit  = 12;
    $offset = ($page - 1) * $limit;

    if ($price) {
        $price = str_replace('-', '_', $price);
    }

    $result = $this->proRepo->searchAdvanced([
        'keyword'  => $keyword ?: null,
        'category' => $category,
        'brand'    => $brand,
        'price'    => $price,
        'sort'     => $sort,
        'limit'    => $limit,
        'offset'   => $offset
    ]);

    return $this->view('products/categories', [
        'products'   => $result['data'],
        'total'      => $result['total'],
        'page'       => $page,
        'limit'      => $limit,
        'categories' => $this->cateRepo->getAllCategory(),
        'filters'    => [
            'keyword'  => $keyword,
            'category' => $category,
            'brand'    => $brand,
            'price'    => $price,
            'sort'     => $sort
        ]
    ]);
}
    public function categoriesPage() {
        return $this->view('products/categories', [
            'searchproduct'=>[],
            'products' => $this->proRepo->getAll(),
            'categories' => $this->cateRepo->getAllCategory()
        ]); 
    }
    public function index() {
        return $this->view('products/list', [
            'products' => $this->proRepo->getAll(),
            'categories' => $this->cateRepo->getAllCategory()
        ]);
    }
    public function test() {
        return $this->view('home/test', [
            'products' => $this->proRepo->getAll(),
            'categories' => $this->cateRepo->getAllCategory()
        ]);
    }
    public function detail(string $slug) {
        $product = $this->proRepo->getDetailProduct($slug);

        if (!$product) {
            http_response_code(404);
            return $this->view('errors/404');
        }

        return $this->view('products/detail', compact('product'));
    }

    public function store() {
        try {
            $dto = $this->buildDTO();

            $this->validate($dto);
            $this->ensureDefaultSku($dto);

            $dto->base_price = $this->calcBasePrice($dto);

            if ($this->proRepo->getDetailProduct($dto->slug)) {
                throw new \Exception("Slug already exists");
            }

            $this->proRepo->create($dto);

            header("Location: /products");
        } catch (\Exception $e) {
            return $this->view('products/create', [
                'error' => $e->getMessage()
            ]);
        }
    }

    public function update($id) {
        try {
            $dto = $this->buildDTO();

            $this->validate($dto);
            $this->ensureDefaultSku($dto);

            $dto->base_price = $this->calcBasePrice($dto);

            $this->proRepo->updateWithSkus($id, $dto);

            header("Location: /products");

        } catch (\Exception $e) {
            return $this->view('products/edit', [
                'error' => $e->getMessage()
            ]);
        }
    }

    public function delete($id) {
        $this->proRepo->softDelete($id);
        header("Location: /products");
    }

    private function buildDTO(): ProductCreateDTO {

        $dto = new ProductCreateDTO();

        $dto->name = $_POST['name'] ?? '';
        $dto->slug = $_POST['slug'] ?? '';
        $dto->category_id = $_POST['category_id'] ?? null;
        $dto->brand_id = $_POST['brand_id'] ?? null;
        $dto->short_desc = $_POST['short_desc'] ?? '';
        $dto->long_desc = $_POST['long_desc'] ?? '';

        $dto->skus = [];

        if (!empty($_POST['skus'])) {
            foreach ($_POST['skus'] as $s) {
                $sku = new \stdClass();
                $sku->sku_code = $s['sku_code'];
                $sku->price = (float)$s['price'];
                $sku->old_price = (float)$s['old_price'];
                $sku->stock_qty = (int)$s['stock_qty'];
                $sku->is_default = isset($s['is_default']) ? 1 : 0;

                $dto->skus[] = $sku;
            }
        }

        return $dto;
    }

    private function validate(ProductCreateDTO $dto) {

        if (!$dto->name) {
            throw new \Exception("Name is required");
        }

        if (!$dto->slug) {
            throw new \Exception("Slug is required");
        }

        if (empty($dto->skus)) {
            throw new \Exception("At least 1 SKU required");
        }

        $codes = [];

        foreach ($dto->skus as $s) {

            if ($s->price <= 0) {
                throw new \Exception("Price must > 0");
            }

            if (in_array($s->sku_code, $codes)) {
                throw new \Exception("Duplicate SKU code");
            }

            $codes[] = $s->sku_code;
        }
    }

    private function ensureDefaultSku(ProductCreateDTO $dto) {

        $count = 0;

        foreach ($dto->skus as $s) {
            if ($s->is_default) $count++;
        }

        if ($count == 0) {
            $dto->skus[0]->is_default = 1;
        }

        if ($count > 1) {
            throw new \Exception("Only 1 default SKU allowed");
        }
    }

    private function calcBasePrice(ProductCreateDTO $dto): float {
        return min(array_map(fn($s) => $s->price, $dto->skus));
    }
    function getAllPRoduct() {
        // lấy toànb ộ dữ liệu liên quna đến products
        // trả về array || objects
    }
}