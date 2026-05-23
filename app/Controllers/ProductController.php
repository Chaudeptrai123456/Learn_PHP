<?php
namespace App\Controllers;
use App\Core\Controller;
class ProductController extends Controller {
private array $products = [
    [
    'id' => 3,
    'name' => 'Samsung Galaxy S24 Ultra',
    'slug' => 'samsung-galaxy-s24-ultra',
    'brand' => 'Samsung Official',

    'price' => 27990000,
    'old_price' => 31990000,

    'cat' => 'phone',

    'image' => 'https://images.unsplash.com/photo-1705585174800-5c6a9d9b0b2d?auto=format&fit=crop&q=80&w=1200',

    'images' => [
        'https://images.unsplash.com/photo-1705585174800-5c6a9d9b0b2d?auto=format&fit=crop&q=80&w=1200',
        'https://images.unsplash.com/photo-1705585174832-1a1a2d0c4c2f?auto=format&fit=crop&q=80&w=1200'
    ],

    'short_desc' => 'Flagship mạnh mẽ với AI, camera zoom 100x cực đỉnh.',

    'specs' => [
        'Màn hình' => '6.8" Dynamic AMOLED 2X 120Hz',
        'Chip' => 'Snapdragon 8 Gen 3',
        'Camera' => '200MP + 50MP + 12MP + 10MP',
        'Pin' => '5000mAh',
        'Kết nối' => 'USB-C'
    ],

    'sold' => 512,
    'stock' => 800,
    'rating' => 5,
    'badge' => 'HOT'
],

[
    'id' => 4,
    'name' => 'Dell XPS 15 9530',
    'slug' => 'dell-xps-15-9530',
    'brand' => 'Dell Official',

    'price' => 38990000,
    'old_price' => 42990000,

    'cat' => 'laptop',

    'image' => 'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?auto=format&fit=crop&q=80&w=1200',

    'images' => [
        'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?auto=format&fit=crop&q=80&w=1200'
    ],

    'short_desc' => 'Laptop cao cấp cho dev với màn OLED cực đẹp.',

    'specs' => [
        'CPU' => 'Intel Core i7-13700H',
        'RAM' => '16GB',
        'SSD' => '1TB',
        'Màn hình' => '15.6" OLED 3.5K'
    ],

    'sold' => 178,
    'stock' => 200,
    'rating' => 5,
    'badge' => 'CAO CẤP'
],

[
    'id' => 5,
    'name' => 'iPad Pro M2 12.9',
    'slug' => 'ipad-pro-m2',
    'brand' => 'Apple Store Official',

    'price' => 24990000,
    'old_price' => 27990000,

    'cat' => 'tablet',

    'image' => 'https://images.unsplash.com/photo-1585790050230-5dd28404ccb9?auto=format&fit=crop&q=80&w=1200',

    'images' => [
        'https://images.unsplash.com/photo-1585790050230-5dd28404ccb9?auto=format&fit=crop&q=80&w=1200'
    ],

    'short_desc' => 'Tablet mạnh như laptop với chip M2.',

    'specs' => [
        'Màn hình' => '12.9" Liquid Retina XDR',
        'Chip' => 'Apple M2',
        'Camera' => '12MP',
        'Pin' => '10 giờ',
        'Kết nối' => 'USB-C'
    ],

    'sold' => 321,
    'stock' => 400,
    'rating' => 5,
    'badge' => 'PRO'
],

[
    'id' => 6,
    'name' => 'Sony WH-1000XM5',
    'slug' => 'sony-wh-1000xm5',
    'brand' => 'Sony Official',

    'price' => 7990000,
    'old_price' => 8990000,

    'cat' => 'accessory',

    'image' => 'https://images.unsplash.com/photo-1618366712010-f4ae9c647dcb?auto=format&fit=crop&q=80&w=1200',

    'images' => [
        'https://images.unsplash.com/photo-1618366712010-f4ae9c647dcb?auto=format&fit=crop&q=80&w=1200'
    ],

    'short_desc' => 'Tai nghe chống ồn tốt nhất hiện nay.',

    'specs' => [
        'Loại' => 'Over-ear',
        'Pin' => '30 giờ',
        'Kết nối' => 'Bluetooth 5.2',
        'Tính năng' => 'Chống ồn ANC'
    ],

    'sold' => 640,
    'stock' => 500,
    'rating' => 5,
    'badge' => 'BEST SELLER'
],

[
    'id' => 7,
    'name' => 'Apple Watch Series 9',
    'slug' => 'apple-watch-series-9',
    'brand' => 'Apple Store Official',

    'price' => 9990000,
    'old_price' => 11990000,

    'cat' => 'accessory',

    'image' => 'https://images.unsplash.com/photo-1579586337278-3befd40fd17a?auto=format&fit=crop&q=80&w=1200',

    'images' => [
        'https://images.unsplash.com/photo-1579586337278-3befd40fd17a?auto=format&fit=crop&q=80&w=1200'
    ],

    'short_desc' => 'Smartwatch cao cấp với nhiều tính năng sức khỏe.',

    'specs' => [
        'Màn hình' => 'OLED Always-On',
        'Chip' => 'S9',
        'Pin' => '18 giờ',
        'Chống nước' => '50m'
    ],

    'sold' => 410,
    'stock' => 350,
    'rating' => 5,
    'badge' => 'NEW'
],

[
    'id' => 8,
    'name' => 'Asus ROG Strix G16',
    'slug' => 'asus-rog-strix-g16',
    'brand' => 'Asus Official',

    'price' => 32990000,
    'old_price' => 36990000,

    'cat' => 'laptop',

    'image' => 'https://images.unsplash.com/photo-1603302576837-37561b2e2302?auto=format&fit=crop&q=80&w=1200',

    'images' => [
        'https://images.unsplash.com/photo-1603302576837-37561b2e2302?auto=format&fit=crop&q=80&w=1200'
    ],

    'short_desc' => 'Laptop gaming mạnh mẽ với RTX series.',

    'specs' => [
        'CPU' => 'Intel i9-13980HX',
        'GPU' => 'RTX 4060',
        'RAM' => '16GB',
        'SSD' => '1TB'
    ],

    'sold' => 290,
    'stock' => 150,
    'rating' => 5,
    'badge' => 'GAMING'
],
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