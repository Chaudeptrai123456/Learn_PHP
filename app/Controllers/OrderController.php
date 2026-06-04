<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Repositories\ProductRepository;
use App\Repositories\VoucherRepository;
use App\Repositories\UserRepository;
use App\DTOs\OrderItem;
class OrderController extends Controller {
    private ProductRepository $proRepo;
    private VoucherRepository $voucherRepo;
    private UserRepository $useRepos;
    public function __construct() {
        $this->proRepo = new ProductRepository();
        $this->useRepos= new UserRepository();
        $this->voucherRepo= new VoucherRepository();
    }
public function index() {
    if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $action = $_GET['action'] ?? null;
    $skuId  = $_GET['sku_id'] ?? null;

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

if ($action === 'increase' && $skuId) {
    if (isset($_SESSION['cart'][$skuId])) {
        $_SESSION['cart'][$skuId]->quantity++;
    }
}

if ($action === 'decrease' && $skuId) {
    if (isset($_SESSION['cart'][$skuId])) {
        $_SESSION['cart'][$skuId]->quantity--;

        if ($_SESSION['cart'][$skuId]->quantity <= 0) {
            unset($_SESSION['cart'][$skuId]);
        }
    }
}

    if ($action === 'remove' && $skuId) {
        unset($_SESSION['cart'][$skuId]);
    }

    if ($action) {
        $current_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        header("Location: " . $current_uri);
        exit;
    }

    return $this->view("order/index",[
        'valid_vouchers'=>$this->voucherRepo->getAllVoucher()
    ]);
}
 
public function addToCart() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $skuId   = $_POST['sku_id'] ?? null;
    $quantity = $_POST['quantity'] ?? 1;
    if (!$skuId) {
        echo "Thiếu SKU";
        return;
    }
    $productRepo = new ProductRepository();
    $data = $productRepo->findSkuWithProduct($skuId);
    if (!$data) {
        echo "Không tìm thấy sản phẩm";
        return;
    }
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    if (isset($_SESSION['cart'][$skuId])) {
        $_SESSION['cart'][$skuId]->quantity += $quantity;
    } else {
        $orderItem = new OrderItem();
        $orderItem->product_id   = $data['product_id'];
        $orderItem->sku_id       = $data['sku_id'];
        $orderItem->product_name = $data['product_name'];
        $orderItem->sku_code     = $data['sku_code'];
        $orderItem->price        = $data['price'];
        $orderItem->quantity     = $quantity;
        $orderItem->image_url    = $data['image_url'];
        $_SESSION['cart'][$skuId] = $orderItem;
    }
header("Location: /assignment/order");
exit;    
}
}