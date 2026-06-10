<?php
namespace App\Controllers;

use App\admin\AdminDashboardService;
use App\Core\Controller;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Core\Database;
use Exception;
use PDO;

class AdminController extends Controller {
    public $db;
    private UserRepository $userRepo;
    private AdminDashboardService $adminDashboard;

    public function __construct(){
        $this->db = Database::getInstance()->getConnection();
        $this->adminDashboard = new AdminDashboardService($this->db);
        $this->userRepo = new UserRepository();
    }

    public function getOrders() {
        $filters = [
            'status'        => $_GET['status'] ?? '',
            'customer_name' => $_GET['customer_name'] ?? '',
            'start_date'    => $_GET['start_date'] ?? '',
            'end_date'      => $_GET['end_date'] ?? '',
        ];

        $orders = $this->adminDashboard->getDashboardOrders($filters);
        
        return $this->view('/admin/pages/orders', [
            'orders'  => $orders,
            'filters' => $filters 
        ]);
    }

    public function updateOrder() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $orderId = $_POST['order_id'] ?? null;
            $status = $_POST['status'] ?? null;
            $paymentStatus = $_POST['payment_status'] ?? null;

            if ($orderId && $status && $paymentStatus) {
                $this->adminDashboard->updateOrderStatus($orderId, $status, $paymentStatus);
            }
        }
        header('Location: /assignment/admin/orders'); 
        exit;
    }

    public function getProducts(){
        $filters = [
            'search'      => $_GET['search'] ?? '',
            'category_id' => $_GET['category_id'] ?? '',
            'brand_id'    => $_GET['brand_id'] ?? '',
            'status'      => $_GET['status'] ?? '',
        ];

        $products = $this->adminDashboard->getDashboardProducts($filters);
        $categories = $this->adminDashboard->getCategories();
        $brands = $this->adminDashboard->getBrands();

        return $this->view('/admin/pages/products', [
            'products'   => $products,
            'categories' => $categories,
            'brands'     => $brands,
            'filters'    => $filters
        ]);
    }

    /**
     * Thu thập dữ liệu, phân loại thông số chung của sản phẩm và thông số SKU biến thể
     */
    public function saveProduct() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['product_id'] ?? null;
            
            // 1. Trích xuất nhóm thông tin chung (Product)
            $productData = [
                'category_id'       => isset($_POST['category_id']) ? (int)$_POST['category_id'] : null,
                'brand_id'          => isset($_POST['brand_id']) ? (int)$_POST['brand_id'] : null,
                'name'              => trim($_POST['name'] ?? ''),
                'slug'              => $this->createSlug($_POST['name'] ?? ''),
                'short_description' => trim($_POST['short_description'] ?? ''),
                'long_description'  => trim($_POST['long_description'] ?? ''),
                'base_price'        => isset($_POST['base_price']) ? (float)$_POST['base_price'] : 0.0,
                'status'            => $_POST['status'] ?? 'published'
            ];

            // 2. Trích xuất nhóm thông tin biến thể/kho hàng (SKU)
            $skuData = [
                'sku_code'   => trim($_POST['sku_code'] ?? ''),
                'price'      => isset($_POST['sku_price']) && $_POST['sku_price'] !== '' ? (float)$_POST['sku_price'] : $productData['base_price'],
                'old_price'  => isset($_POST['sku_old_price']) && $_POST['sku_old_price'] !== '' ? (float)$_POST['sku_old_price'] : null,
                'stock_qty'  => isset($_POST['sku_stock_qty']) ? (int)$_POST['sku_stock_qty'] : 100
            ];

            // Ràng buộc dữ liệu nghiêm ngặt
            if (empty($productData['name']) || !$productData['category_id'] || !$productData['brand_id']) {
                error_log("Lỗi: Các thông tin có gắn dấu sao (*) là bắt buộc.");
                header('Location: /assignment/admin/products');
                exit;
            }

            // Xử lý tải hình ảnh
            $productImgUrl = null;
            if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
                $projectRootDir = dirname(__DIR__, 2); 
                $uploadDir = $projectRootDir . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'products' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR;
                
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $fileTmp  = $_FILES['product_image']['tmp_name'];
                $fileName = time() . '_' . basename($_FILES['product_image']['name']);
                $fileName = preg_replace('/[^a-zA-Z0-9._-]/', '', $fileName);
                $ext      = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                $allowExt = ['jpg', 'jpeg', 'png', 'webp'];

                if (in_array($ext, $allowExt)) {
                    $newPath = $uploadDir . $fileName;
                    if (move_uploaded_file($fileTmp, $newPath)) {
                        $productImgUrl = '/products/images/' . $fileName;
                    }
                }
            }

            try {
                if ($id) {
                    $this->adminDashboard->updateProduct($id, $productData, $skuData, $productImgUrl);
                } else {
                    $this->adminDashboard->createProduct($productData, $skuData, $productImgUrl);
                }
            } catch (Exception $e) {
                error_log("Failed to process saveProduct in Controller: " . $e->getMessage());
            }
        }

        header('Location: /assignment/admin/products');
        exit;
    }

    public function deleteProduct() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['product_id'] ?? null;
            if ($id) {
                $this->adminDashboard->deleteProduct($id);
            }
        }
        header('Location: /assignment/admin/products');
        exit;
    }

    private function createSlug($str) {
        $str = trim(mb_strtolower($str));
        $str = preg_replace('/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/', 'a', $str);
        $str = preg_replace('/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/', 'e', $str);
        $str = preg_replace('/(ì|í|ị|ỉ|ĩ)/', 'i', $str);
        $str = preg_replace('/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/', 'o', $str);
        $str = preg_replace('/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/', 'u', $str);
        $str = preg_replace('/(ỳ|ý|ỵ|ỷ|ỹ)/', 'y', $str);
        $str = preg_replace('/(đ)/', 'd', $str);
        $str = preg_replace('/[^a-z0-9-\s]/', '', $str);
        $str = preg_replace('/([\s]+)/', '-', $str);
        return trim($str, '-');
    }

    public function getUsers(){
        return $this->view('/admin/pages/users',[
            'user'=>'users'
        ]);
    }
}