<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Repositories\ProductRepository;
use App\Repositories\VoucherRepository;
use App\Repositories\UserRepository;
use App\Repositories\OrderRepository;
use App\DTOs\OrderItem;

class OrderController extends Controller {
    private VoucherRepository $voucherRepo;
    private UserRepository $useRepos;
    private OrderRepository $orderRepo;
    public function __construct() {
        $this->orderRepo = new OrderRepository();
        $this->useRepos = new UserRepository();
        $this->voucherRepo = new VoucherRepository();
    }

    public function index() {
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

        return $this->view("order/index", [
            'valid_vouchers' => $this->voucherRepo->getAllVoucher()
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

public function placeOrder() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (empty($_SESSION['user']) || empty($_SESSION['cart'])) {
        die('Thiếu dữ liệu');
    }

    $user = $_SESSION['user'];
    $cart = $_SESSION['cart'];

    $conn = $this->orderRepo->db; 

    try {
        $conn->beginTransaction();
        $total = 0;
        foreach ($cart as $item) {
            $total += $item->price * $item->quantity;
        }

        $discount = 0;
        $final = $total;

        // 🧾 tạo order
        $orderId = $this->orderRepo->createOrder([
            'user_id' => $user['id'],
            'total_amount' => $total,
            'discount_amount' => $discount,
            'final_amount' => $final,
            'payment_method' => 'cod',
            'note' => $_POST['note'] ?? null
        ]);

        $this->orderRepo->insertOrderItems($orderId, $cart);

        $conn->commit();
        $this->historyOrder();
        } catch (\Exception $e) {
        $conn->rollBack();
        die($e->getMessage());
    }
}
public function historyOrder() {
    session_start();
        $temp =  $this->orderRepo->getOrdersByUserId($_SESSION['user']['id']);
        $groupedOrders = [];

        foreach ($temp as $row) {
            $orderId = $row['order_id'];

            if (!isset($groupedOrders[$orderId])) {
            $groupedOrders[$orderId] = [
                'order_id'        => $row['order_id'],
                'total_amount'    => $row['total_amount'],
                'discount_amount' => $row['discount_amount'],
                'final_amount'    => $row['final_amount'],
                'status'          => $row['status'],
                'payment_status'  => $row['payment_status'],
                'payment_method'  => $row['payment_method'],
                'created_at'      => $row['created_at'],
                'voucher_code'    => $row['voucher_code'],
                'items'           => []
            ];
        }

        if (!empty($row['order_item_id'])) {
            $groupedOrders[$orderId]['items'][] = [
                'product_name' => $row['product_name'],
                'sku_code'     => $row['sku_code'],
                'price'        => $row['price'],
                'quantity'     => $row['quantity'],
                'image_url'    => $row['image_url'],
                'slug'         => $row['slug']
            ];
        }
    }

    $groupedOrders = array_values($groupedOrders);
    return $this->view("order/allOrders", [
            'groupedOrders' => $groupedOrders
        ]);
    }
}