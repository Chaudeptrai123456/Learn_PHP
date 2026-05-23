<?php
namespace App\Controllers;
use App\Core\Controller;
class ProductController extends Controller {
private array $products = [
    [
        'id' => 1,
        'name' => 'iPhone 15 Pro Max Titanium',
        'slug' => 'iphone-15-pro-max',
        'brand' => 'Apple Store Official',

        'price' => 29990000,
        'old_price' => 34990000,

        'cat' => 'phone',

        // ảnh chính (dùng list)
        'image' => 'https://images.unsplash.com/photo-1696446701796-da61225697cc?auto=format&fit=crop&q=80&w=1200',

        // nhiều ảnh (dùng detail)
        'images' => [
            'https://images.unsplash.com/photo-1696446701796-da61225697cc?auto=format&fit=crop&q=80&w=1200',
            'https://images.unsplash.com/photo-1695048133142-1a20484d2569?auto=format&fit=crop&q=80&w=1200',
            'https://images.unsplash.com/photo-1695048132833-2a83236329c0?auto=format&fit=crop&q=80&w=1200'
        ],

        'short_desc' => 'Thiết kế Titan cấp độ hàng không vũ trụ. Chip A17 Pro thay đổi cuộc chơi. Hệ thống camera iPhone mạnh mẽ nhất.',

        'specs' => [
            'Màn hình' => '6.7" Super Retina XDR, ProMotion 120Hz',
            'Vi xử lý' => 'A17 Pro Bionic 6 nhân GPU',
            'Camera' => '48MP Main | 12MP Ultra Wide | 5x Telephoto',
            'Pin' => 'Xem video lên đến 29 giờ',
            'Kết nối' => 'USB-C (hỗ trợ USB 3 tốc độ 10Gb/s)'
        ],

        'sold' => 856,
        'stock' => 1000,
        'rating' => 5,
        'badge' => 'BÁN CHẠY'
    ],

    [
        'id' => 2,
        'name' => 'MacBook Pro M3 Max 14',
        'slug' => 'macbook-pro-m3-max',
        'brand' => 'Apple Store Official',

        'price' => 45990000,
        'old_price' => 52990000,

        'cat' => 'laptop',

        'image' => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?auto=format&fit=crop&q=80&w=1200',

        'images' => [
            'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?auto=format&fit=crop&q=80&w=1200'
        ],

        'short_desc' => 'MacBook Pro M3 Max hiệu năng cực khủng dành cho dev và creator.',

        'specs' => [
            'CPU' => 'Apple M3 Max',
            'RAM' => '36GB',
            'SSD' => '1TB',
            'Màn hình' => '14" Liquid Retina XDR'
        ],

        'sold' => 234,
        'stock' => 300,
        'rating' => 5,
        'badge' => 'MỚI VỀ'
    ],
];
    public function index() {
        return $this->view('products/list', [
            'products' => $this->products
        ]);
    }
    public function detail(string $slug) {
        $product = null;
        foreach ($this->products as $p) {
            if ($p['slug'] === $slug) {
                $product = $p;
                break;
            }
        }
        if (!$product) {
            http_response_code(404);
            echo "Product not found";
            return;
        }
        return $this->view('products/detail', [
            'product' => $product
        ]);
    }
}