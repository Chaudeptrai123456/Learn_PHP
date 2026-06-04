<?php
// 1. DỮ LIỆU BACKEND CHÂU CUNG CẤP (Tự động gán nếu chưa có biến truyền từ Controller)
// if (!isset($products)) {
//     $products = [
//         [
//             'id' => 1,
//             'category_id' => 1,
//             'brand_id' => 1,
//             'name' => 'iPhone 15 Pro Max',
//             'slug' => 'iphone-15-pro-max',
//             'short_description' => 'Flagship Apple cao cấp bậc nhất, khung Titan siêu bền.',
//             'long_description' => '',
//             'base_price' => 29990000.00,
//             'status' => 'published',
//             'rating_avg' => 4.8,
//             'view_count' => 122,
//             'created_at' => '2026-05-26 10:56:21',
//             'deleted_at' => null,
//             'price' => 29990000.00,
//             'old_price' => 34990000.00,
//             'discount' => 14
//         ],
//         [
//             'id' => 2,
//             'category_id' => 1,
//             'brand_id' => 2,
//             'name' => 'Samsung Galaxy S24 Ultra',
//             'slug' => 's24-ultra',
//             'short_description' => 'Camera AI zoom 100x đỉnh cao thế hệ mới.',
//             'long_description' => '',
//             'base_price' => 27990000.00,
//             'status' => 'published',
//             'rating_avg' => 4.7,
//             'view_count' => 258,
//             'created_at' => '2026-05-26 10:56:21',
//             'deleted_at' => null,
//             'price' => 27990000.00,
//             'old_price' => 31990000.00,
//             'discount' => 13
//         ],
//         [
//             'id' => 4,
//             'category_id' => 3,
//             'brand_id' => 1,
//             'name' => 'iPad Pro M2',
//             'slug' => 'ipad-pro-m2',
//             'short_description' => 'Sức mạnh tiệm cận Macbook trong thân hình siêu mỏng.',
//             'long_description' => '',
//             'base_price' => 24990000.00,
//             'status' => 'published',
//             'rating_avg' => 4.9,
//             'view_count' => 384,
//             'created_at' => '2026-05-26 10:56:21',
//             'deleted_at' => null,
//             'price' => 24990000.00,
//             'old_price' => 27990000.00,
//             'discount' => 11
//         ],
//         [
//             'id' => 5,
//             'category_id' => 4,
//             'brand_id' => 4,
//             'name' => 'Sony WH-1000XM5',
//             'slug' => 'sony-xm5',
//             'short_description' => 'Chống ồn đỉnh cao, âm thanh vòm chuẩn Hi-Res Audio.',
//             'long_description' => '',
//             'base_price' => 7990000.00,
//             'status' => 'published',
//             'rating_avg' => 4.6,
//             'view_count' => 342,
//             'created_at' => '2026-05-26 10:56:21',
//             'deleted_at' => null,
//             'price' => 7990000.00,
//             'old_price' => 8990000.00,
//             'discount' => 11
//         ],
//         [
//             'id' => 3,
//             'category_id' => 2,
//             'brand_id' => 3,
//             'name' => 'Dell XPS 15',
//             'slug' => 'dell-xps-15',
//             'short_description' => 'Màn hình InfinityEdge tuyệt mỹ, cấu hình lập trình cực mạnh.',
//             'long_description' => '',
//             'base_price' => 38990000.00,
//             'status' => 'published',
//             'rating_avg' => 4.5,
//             'view_count' => 491,
//             'created_at' => '2026-05-26 10:56:21',
//             'deleted_at' => null,
//             'price' => 38990000.00,
//             'old_price' => 42990000.00,
//             'discount' => 9
//         ]
//     ];
// }

// // Khởi tạo danh mục dự phòng
// if (!isset($categories)) {
//     $categories = [
//         ['id' => 1, 'name' => 'Điện thoại', 'slug' => 'dien-thoai'],
//         ['id' => 2, 'name' => 'Laptop', 'slug' => 'laptop'],
//         ['id' => 3, 'name' => 'Tablet', 'slug' => 'tablet'],
//         ['id' => 4, 'name' => 'Phụ kiện', 'slug' => 'phu-kien']
//     ];
// }

// Đồng bộ biến ưu đãi danh mục $dis_products nếu chưa truyền từ Controller
if (!isset($dis_products)) {
    $dis_products = $categories;
}

if (!function_exists('formatVND')) {
    function formatVND($n) { 
        return number_format($n, 0, ',', '.') . ' ₫'; 
    }
}

// Hàm phân tích danh mục theo tên hoặc ID linh hoạt
function parseCategorySlug($category_id, $category_name = '') {
    $name = mb_strtolower(trim($category_name));
    if ($category_id == 1 || $name === 'điện thoại') return 'dien-thoai';
    if ($category_id == 2 || $name === 'laptop') return 'laptop';
    if ($category_id == 3 || $name === 'tablet') return 'tablet';
    if ($category_id == 4 || $name === 'phụ kiện') return 'phu-kien';
    return 'other';
}

// Hàm lấy ảnh mẫu Unsplash chất lượng cao theo sản phẩm
function getPremiumProductImage($slug) {
    $images = [
        'iphone-15-pro-max' => 'https://images.unsplash.com/photo-1695048133142-1a20484d2569?w=600&auto=format&fit=crop&q=80',
        's24-ultra'         => 'https://images.unsplash.com/photo-1610945265064-0e34e5519bbf?w=600&auto=format&fit=crop&q=80',
        'ipad-pro-m2'       => 'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?w=600&auto=format&fit=crop&q=80',
        'sony-xm5'          => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=600&auto=format&fit=crop&q=80',
        'dell-xps-15'       => 'https://images.unsplash.com/photo-1593642632823-8f785ba67e45?w=600&auto=format&fit=crop&q=80'
    ];
    return isset($images[$slug]) ? $images[$slug] : 'https://images.unsplash.com/photo-1526738549149-8e07eca6c147?w=600&auto=format&fit=crop&q=80';
}

// Hàm tự động lấy ảnh sản phẩm chất lượng cao tương ứng với slug danh mục ưu đãi
if (!function_exists('getDiscountProductImage')) {
    function getDiscountProductImage($slug) {
        $images = [
            'dien-thoai' => 'https://images.unsplash.com/photo-1695048133142-1a20484d2569?w=500&auto=format&fit=crop&q=80',
            'laptop'     => 'https://images.unsplash.com/photo-1593642632823-8f785ba67e45?w=500&auto=format&fit=crop&q=80',
            'tablet'     => 'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?w=500&auto=format&fit=crop&q=80',
            'phu-kien'   => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500&auto=format&fit=crop&q=80'
        ];
        return isset($images[$slug]) ? $images[$slug] : 'https://images.unsplash.com/photo-1526738549149-8e07eca6c147?w=500&auto=format&fit=crop&q=80';
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechStore | Hệ thống bán lẻ công nghệ cao cấp</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- GỘP VÀ TỐI ƯU TOÀN BỘ CSS LAYOUT -->
    <style>
    :root {
        --primary: #0071e3;
        --black: #000000;
        --dark: #1d1d1f;
        --gray-bg: #f5f5f7;
        --border: rgba(0, 0, 0, 0.08);
        --transition: all 0.35s cubic-bezier(0.25, 1, 0.5, 1);
        --card-radius: 24px;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: var(--dark);
        background: #fff;
        scroll-behavior: smooth;
        -webkit-font-smoothing: antialiased;
    }

    .container {
        max-width: 1340px;
        margin: 0 auto;
        padding: 0 24px;
    }

    /* --- PREMIUM GLASS HEADER --- */
    header {
        position: fixed;
        width: 100%;
        top: 0;
        z-index: 1000;
        background: rgba(255, 255, 255, 0.75);
        backdrop-filter: blur(24px) saturate(180%);
        -webkit-backdrop-filter: blur(24px) saturate(180%);
        border-bottom: 1px solid var(--border);
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.02);
    }

    .header-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        height: 76px;
    }

    .logo {
        font-weight: 800;
        font-size: 1.5rem;
        text-decoration: none;
        color: var(--black);
        letter-spacing: -1px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .logo span {
        color: var(--primary);
    }

    .search-bar {
        position: relative;
        flex: 0 0 460px;
        margin: 0 40px;
    }

    .search-bar input {
        width: 100%;
        padding: 12px 18px 12px 46px;
        border-radius: 30px;
        border: 1px solid transparent;
        background: rgba(0, 0, 0, 0.04);
        outline: none;
        font-size: 0.9rem;
        font-weight: 500;
        transition: var(--transition);
        color: var(--dark);
    }

    .search-bar input:focus {
        background: #fff;
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(0, 113, 227, 0.12);
    }

    .search-bar i {
        position: absolute;
        left: 18px;
        top: 50%;
        transform: translateY(-50%);
        color: #777;
        font-size: 1rem;
    }

    .nav-tools {
        display: flex;
        gap: 25px;
        align-items: center;
    }

    .cart-icon {
        position: relative;
        cursor: pointer;
        color: var(--dark);
        text-decoration: none;
        font-size: 1.25rem;
        transition: var(--transition);
        display: flex;
        align-items: center;
        justify-content: center;
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: rgba(0, 0, 0, 0.03);
    }

    .cart-icon:hover {
        background: rgba(0, 113, 227, 0.08);
        color: var(--primary);
    }

    .cart-badge {
        position: absolute;
        top: -2px;
        right: -2px;
        background: var(--primary);
        color: #fff;
        font-size: 10px;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        border: 2px solid #fff;
    }

    /* --- HERO SLIDER --- */
    .hero-section {
        height: 70vh;
        margin-top: 76px;
        position: relative;
        overflow: hidden;
        background: #000;
    }

    .slide {
        position: absolute;
        inset: 0;
        opacity: 0;
        transition: opacity 1s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
    }

    .slide.active {
        opacity: 1;
        z-index: 1;
    }

    .slide img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: 0.55;
        transform: scale(1.05);
        transition: transform 6s cubic-bezier(0.25, 1, 0.5, 1);
    }

    .slide.active img {
        transform: scale(1);
    }

    .slide-content {
        position: absolute;
        left: 8%;
        color: #fff;
        z-index: 5;
        max-width: 650px;
        padding-right: 20px;
    }

    .slide-content h1 {
        font-size: 3.8rem;
        font-weight: 800;
        line-height: 1.15;
        margin-bottom: 20px;
        letter-spacing: -1.5px;
    }

    .slide-content p {
        font-size: 1.15rem;
        font-weight: 400;
        color: rgba(255, 255, 255, 0.85);
        margin-bottom: 30px;
    }

    .btn-white {
        background: #fff;
        color: #000;
        padding: 14px 38px;
        border-radius: 30px;
        text-decoration: none;
        font-weight: 700;
        font-size: 0.95rem;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: var(--transition);
        box-shadow: 0 4px 15px rgba(255, 255, 255, 0.15);
    }

    .btn-white:hover {
        background: var(--primary);
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 113, 227, 0.35);
    }

    .hero-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        color: #fff;
        border: 1px solid rgba(255, 255, 255, 0.15);
        width: 50px;
        height: 50px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        z-index: 10;
        transition: var(--transition);
    }

    .hero-btn:hover {
        background: var(--primary);
        color: #fff;
        border-color: var(--primary);
    }

    .hero-btn.prev {
        left: 24px;
    }

    .hero-btn.next {
        right: 24px;
    }

    /* --- PREMIUM DISCOUNT HUB (FLASH SALE) --- */
    .premium-discount-hub {
        padding: 80px 0;
        background: #09090b;
        color: #fff;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    .discount-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
        margin-bottom: 40px;
    }

    .discount-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #ff3b30;
        color: #fff;
        padding: 6px 14px;
        border-radius: 30px;
        font-size: 0.8rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        box-shadow: 0 4px 15px rgba(255, 59, 48, 0.3);
        margin-bottom: 12px;
    }

    .discount-title {
        font-size: 2.2rem;
        font-weight: 800;
        letter-spacing: -1px;
        background: linear-gradient(180deg, #ffffff 0%, #a1a1a6 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .countdown-wrapper {
        display: flex;
        align-items: center;
        gap: 15px;
        background: rgba(255, 255, 255, 0.03);
        padding: 10px 20px;
        border-radius: 16px;
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    .countdown-label {
        font-size: 0.85rem;
        color: #8e8e93;
        font-weight: 600;
    }

    .timer {
        font-weight: 800;
        font-size: 1rem;
        color: #fff;
        letter-spacing: 1px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .time-block {
        background: #ff3b30;
        padding: 4px 8px;
        border-radius: 6px;
        color: #fff;
    }

    .discount-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 24px;
    }

    .discount-card {
        background: rgba(255, 255, 255, 0.02);
        border-radius: 28px;
        padding: 24px;
        border: 1px solid rgba(255, 255, 255, 0.05);
        cursor: pointer;
        position: relative;
        overflow: hidden;
        transition: var(--transition);
    }

    .discount-card-inner {
        position: relative;
        z-index: 2;
        display: flex;
        flex-direction: column;
        height: 100%;
        align-items: flex-start;
    }

    .promo-tag {
        font-size: 0.75rem;
        font-weight: 800;
        color: var(--accent-color);
        background: rgba(255, 255, 255, 0.05);
        padding: 6px 14px;
        border-radius: 20px;
        margin-bottom: 20px;
        border: 1px solid rgba(255, 255, 255, 0.03);
    }

    .discount-image-wrapper {
        width: 100%;
        height: 160px;
        overflow: hidden;
        border-radius: 18px;
        margin-bottom: 20px;
        position: relative;
        background: rgba(255, 255, 255, 0.01);
        border: 1px solid rgba(255, 255, 255, 0.03);
    }

    .discount-prod-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s cubic-bezier(0.25, 1, 0.5, 1);
    }

    .discount-sub-icon {
        position: absolute;
        bottom: 10px;
        right: 10px;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        color: #fff;
    }

    .discount-info {
        margin-bottom: 20px;
    }

    .discount-name {
        font-size: 1.35rem;
        font-weight: 700;
        color: #fff;
        margin-bottom: 6px;
    }

    .discount-sub {
        font-size: 0.85rem;
        color: #8e8e93;
        font-weight: 500;
    }

    .discount-link {
        font-size: 0.9rem;
        font-weight: 700;
        color: var(--accent-color);
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: var(--transition);
        text-decoration: none;
    }

    .card-glow-effect {
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at center, var(--accent-color) 0%, transparent 70%);
        opacity: 0;
        transition: opacity 0.4s ease;
        z-index: 1;
        mix-blend-mode: screen;
    }

    .discount-card:hover {
        transform: translateY(-8px);
        border-color: var(--accent-color);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.6);
    }

    .discount-card:hover .card-glow-effect {
        opacity: 0.14;
    }

    .discount-card:hover .discount-prod-img {
        transform: scale(1.08) rotate(-1deg);
    }

    .discount-card:hover .discount-link {
        transform: translateX(4px);
    }

    /* --- FILTER BAR --- */
    .filter-bar {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        padding: 24px 0;
        border-bottom: 1px solid var(--border);
        position: sticky;
        top: 76px;
        z-index: 900;
    }

    .filter-flex {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
    }

    .category-tabs {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .tab {
        padding: 10px 24px;
        border-radius: 30px;
        border: 1px solid var(--border);
        cursor: pointer;
        font-weight: 600;
        font-size: 0.9rem;
        transition: var(--transition);
        background: #fff;
        color: #555;
    }

    .tab.active,
    .tab:hover {
        background: var(--dark);
        color: #fff;
        border-color: var(--dark);
    }

    .sort-options select {
        padding: 10px 18px;
        border-radius: 12px;
        border: 1px solid var(--border);
        outline: none;
        font-weight: 600;
        font-size: 0.9rem;
        background: #fff;
        cursor: pointer;
        transition: var(--transition);
    }

    .sort-options select:focus {
        border-color: var(--primary);
    }

    /* --- PRODUCT GRID --- */
    .products-section {
        padding: 60px 0;
    }

    .p-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 30px;
    }

    .p-card {
        background: #fff;
        border-radius: var(--card-radius);
        padding: 24px;
        border: 1px solid var(--border);
        transition: var(--transition);
        display: flex;
        flex-direction: column;
        position: relative;
        overflow: hidden;
    }

    .p-card:hover {
        border-color: rgba(0, 113, 227, 0.2);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.06);
        transform: translateY(-8px);
    }

    .p-badge {
        position: absolute;
        top: 16px;
        left: 16px;
        background: #ff3b30;
        color: #fff;
        font-size: 11px;
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: 700;
        z-index: 2;
        box-shadow: 0 4px 10px rgba(255, 59, 48, 0.25);
    }

    .p-img-wrapper {
        width: 100%;
        height: 220px;
        overflow: hidden;
        border-radius: 16px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--gray-bg);
    }

    .p-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: var(--transition);
    }

    .p-card:hover .p-img {
        transform: scale(1.06);
    }

    .p-info {
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }

    .p-rating {
        display: flex;
        align-items: center;
        gap: 4px;
        color: #ff9500;
        font-size: 0.8rem;
        margin-bottom: 8px;
    }

    .p-rating span {
        color: #8e8e93;
        font-weight: 500;
        margin-left: 4px;
    }

    .p-name {
        font-weight: 700;
        font-size: 1.15rem;
        line-height: 1.35;
        margin-bottom: 10px;
        min-height: 48px;
        color: var(--dark);
    }

    .p-price-row {
        display: flex;
        align-items: baseline;
        gap: 10px;
        margin-bottom: 18px;
    }

    .p-new {
        font-weight: 800;
        font-size: 1.3rem;
        color: var(--primary);
    }

    .p-old {
        text-decoration: line-through;
        color: #8e8e93;
        font-size: 0.9rem;
    }

    .p-stats-wrap {
        margin-bottom: 20px;
        margin-top: auto;
        font-size: 0.8rem;
        color: #8e8e93;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top: 1px dashed var(--border);
        padding-top: 14px;
    }

    .p-stats-wrap span i {
        margin-right: 4px;
    }

    .btn-add {
        width: 100%;
        padding: 14px;
        border-radius: 16px;
        border: none;
        background: var(--dark);
        color: #fff;
        font-weight: 700;
        font-size: 0.9rem;
        cursor: pointer;
        transition: var(--transition);
        text-align: center;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-add:hover {
        background: var(--primary);
        box-shadow: 0 8px 20px rgba(0, 113, 227, 0.3);
    }

    /* --- FOOTER --- */
    footer {
        background: var(--gray-bg);
        padding: 80px 0 40px;
        border-top: 1px solid var(--border);
    }

    .footer-grid {
        display: grid;
        grid-template-columns: 1.5fr 1fr 1fr 1.2fr;
        gap: 40px;
    }

    .footer-col h4 {
        margin-bottom: 25px;
        font-weight: 700;
        font-size: 1.1rem;
    }

    .footer-col ul {
        list-style: none;
    }

    .footer-col ul li {
        margin-bottom: 12px;
    }

    .footer-col ul li a {
        text-decoration: none;
        color: #666;
        font-size: 0.9rem;
        transition: 0.3s;
    }

    .footer-col ul li a:hover {
        color: var(--primary);
    }

    .newsletter input {
        width: 100%;
        padding: 14px 18px;
        border-radius: 12px;
        border: 1px solid var(--border);
        margin-bottom: 10px;
        outline: none;
        transition: var(--transition);
    }

    .newsletter input:focus {
        border-color: var(--primary);
    }

    .btn-sub {
        width: 100%;
        padding: 14px;
        border-radius: 12px;
        background: var(--primary);
        color: #fff;
        border: none;
        font-weight: 700;
        cursor: pointer;
        transition: var(--transition);
    }

    .btn-sub:hover {
        box-shadow: 0 6px 18px rgba(0, 113, 227, 0.25);
    }

    @media (max-width: 992px) {
        .footer-grid {
            grid-template-columns: 1fr 1fr;
        }

        .search-bar {
            flex: 1;
            margin: 0 20px;
        }
    }

    @media (max-width: 600px) {
        .footer-grid {
            grid-template-columns: 1fr;
        }

        .search-bar {
            display: none;
        }

        .slide-content h1 {
            font-size: 2.6rem;
        }
    }
    </style>
</head>

<body>



    <!-- HERO SLIDER -->
    <section class="hero-section">
        <?php if (!empty($products)): ?>
        <?php foreach(array_slice($products, 0, 2) as $i => $p): 
                $slide_img = getPremiumProductImage($p['slug']);
            ?>
        <div class="slide <?php echo $i == 0 ? 'active' : ''; ?>">
            <img src="<?php echo $slide_img; ?>" alt="<?php echo htmlspecialchars($p['name']); ?>">
            <div class="slide-content">
                <h1><?php echo htmlspecialchars($p['name']); ?></h1>
                <p><?php echo htmlspecialchars($p['short_description']); ?></p>
                <a href="/assignment/product/<?php echo $p['slug']; ?>" class="btn-white">
                    Mua ngay <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
        <button class="hero-btn prev"><i class="fas fa-chevron-left"></i></button>
        <button class="hero-btn next"><i class="fas fa-chevron-right"></i></button>
    </section>

    <!-- SECTION: FLASH DEALS - ƯU ĐÃI ĐỘC QUYỀN -->
    <section class="premium-discount-hub">
        <div class="container">
            <div class="discount-header">
                <div class="header-left">
                    <span class="discount-badge"><i class="fas fa-bolt"></i> Flash Sale</span>
                    <h2 class="discount-title">Danh Mục Đang Giảm Sâu</h2>
                </div>
                <div class="countdown-wrapper">
                    <span class="countdown-label">Kết thúc trong:</span>
                    <div class="timer">
                        <span class="time-block">02</span> :
                        <span class="time-block">14</span> :
                        <span class="time-block">36</span>
                    </div>
                </div>
            </div>

            <div class="discount-grid">
                <?php if (!empty($dis_products) && is_array($dis_products)): ?>
                <?php foreach ($dis_products as $item): 
                    // Tự động gán cấu hình giao diện dựa theo Category ID của sản phẩm
                    $iconClass = "fas fa-tag";
                    $accentColor = "#0071e3"; 
                    $cat_id = isset($item['category_id']) ? $item['category_id'] : 1;
                    
                    if ($cat_id == 1) {
                        $iconClass = "fas fa-mobile-screen-button";
                        $accentColor = "#ff3b30"; // Điện thoại - Đỏ
                    } elseif ($cat_id == 2) {
                        $iconClass = "fas fa-laptop";
                        $accentColor = "#ff9500"; // Laptop - Cam
                    } elseif ($cat_id == 3) {
                        $iconClass = "fas fa-tablet-screen-button";
                        $accentColor = "#34c759"; // Tablet - Xanh lá
                    } elseif ($cat_id == 4) {
                        $iconClass = "fas fa-headphones";
                        $accentColor = "#af52de"; // Phụ kiện - Tím
                    }

                    // Lấy ảnh mẫu chất lượng cao và % giảm giá thực tế từ DB của Châu
                    $productImage = getPremiumProductImage($item['slug']);
                    $discountPercent = isset($item['discount']) ? (int)$item['discount'] : 0;
                ?>
                <!-- Thẻ card chuyển hướng chính xác đến trang sản phẩm -->
                <div class="discount-card"
                    onclick="window.location.href='/assignment/product/<?php echo htmlspecialchars($item['slug']); ?>'"
                    style="--accent-color: <?php echo $accentColor; ?>">
                    <div class="card-glow-effect"></div>
                    <div class="discount-card-inner">

                        <!-- NHÃN % GIẢM GIÁ ĐỘNG -->
                        <?php if ($discountPercent > 0): ?>
                        <span class="promo-tag">Giảm <?php echo $discountPercent; ?>%</span>
                        <?php else: ?>
                        <span class="promo-tag">Giá đặc biệt</span>
                        <?php endif; ?>

                        <!-- HÌNH ẢNH SẢN PHẨM -->
                        <div class="discount-image-wrapper">
                            <img src="<?php echo $productImage; ?>" alt="<?php echo htmlspecialchars($item['name']); ?>"
                                class="discount-prod-img" loading="lazy">
                            <span class="discount-sub-icon"><i class="<?php echo $iconClass; ?>"></i></span>
                        </div>

                        <!-- THÔNG TIN SẢN PHẨM & GIÁ CẢ -->
                        <div class="discount-info">
                            <h3 class="discount-name"><?php echo htmlspecialchars($item['name']); ?></h3>

                            <!-- BỔ SUNG KHỐI GIÁ ĐỘNG TỪ DB -->
                            <div class="discount-price-block">
                                <span class="discount-price-new"><?php echo formatVND($item['price']); ?></span>
                                <?php if (isset($item['old_price']) && $item['old_price'] > $item['price']): ?>
                                <span class="discount-price-old"><?php echo formatVND($item['old_price']); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <a href="/assignment/product/<?php echo htmlspecialchars($item['slug']); ?>"
                            class="discount-link" onclick="event.stopPropagation();">
                            Săn deal ngay <i class="fas fa-chevron-right"></i>
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php else: ?>
                <p style="grid-column: 1/-1; text-align: center; color: #8e8e93; padding: 40px 0;">Không có chương trình
                    ưu đãi nào hiện hành.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- CSS BỔ SUNG CHO PHẦN GIÁ TIỀN VÀ HÌNH ẢNH SẢN PHẨM -->
    <style>
    /* CSS cho khối giá cả */
    .discount-price-block {
        display: flex;
        align-items: baseline;
        gap: 10px;
        margin-top: 8px;
    }

    .discount-price-new {
        font-size: 1.3rem;
        font-weight: 800;
        color: #fff;
    }

    .discount-price-old {
        font-size: 0.9rem;
        text-decoration: line-through;
        color: #8e8e93;
        font-weight: 500;
    }

    .discount-info {
        width: 100%;
        margin-bottom: 20px;
    }

    /* Đồng bộ căn lề của tiêu đề danh mục */
    .discount-name {
        font-size: 1.35rem;
        font-weight: 700;
        color: #fff;
        margin-bottom: 4px;
        line-height: 1.3;
    }
    </style>

    <!-- FILTER BAR -->
    <div class="filter-bar">
        <div class="container filter-flex">
            <div class="category-tabs">
                <div class="tab active" onclick="filterCat('all', this)">Tất cả</div>
                <?php if (!empty($categories) && is_array($categories)): ?>
                <?php foreach ($categories as $category): ?>
                <div class="tab" onclick="filterCat('<?php echo htmlspecialchars($category['slug']); ?>', this)">
                    <?php echo htmlspecialchars($category['name']); ?>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <div class="sort-options">
                <select id="sortPrice" onchange="sortProducts()">
                    <option value="default">Sắp xếp theo</option>
                    <option value="low">Giá thấp đến cao</option>
                    <option value="high">Giá cao đến thấp</option>
                </select>
            </div>
        </div>
    </div>

    <!-- MAIN PRODUCT GRID -->
    <main class="container products-section" id="products">
        <div class="p-grid" id="productGrid">
            <?php if (!empty($products)): ?>
            <?php foreach($products as $p): 
                    $cat_slug = parseCategorySlug($p['category_id'], isset($p['category_name']) ? $p['category_name'] : '');
                    $product_img = getPremiumProductImage($p['slug']);
                    $rating_avg = (float)$p['rating_avg'];
                    $stars = ($rating_avg > 0) ? round($rating_avg) : 5;
                ?>
            <div class="p-card" data-cat="<?php echo $cat_slug; ?>" data-price="<?php echo $p['price']; ?>"
                data-name="<?php echo strtolower($p['name']); ?>">
                <?php if ((int)$p['discount'] > 0): ?>
                <span class="p-badge">-<?php echo (int)$p['discount']; ?>% OFF</span>
                <?php endif; ?>

                <div class="p-img-wrapper">
                    <img src="<?php echo $product_img; ?>" alt="<?php echo htmlspecialchars($p['name']); ?>"
                        class="p-img" loading="lazy">
                </div>

                <div class="p-info">
                    <div class="p-rating">
                        <?php for($star = 1; $star <= 5; $star++): ?>
                        <i class="<?php echo ($star <= $stars) ? 'fas' : 'far'; ?> fa-star"></i>
                        <?php endfor; ?>
                        <span>(<?php echo $rating_avg > 0 ? $rating_avg : "5.0"; ?>)</span>
                    </div>

                    <h3 class="p-name"><?php echo htmlspecialchars($p['name']); ?></h3>

                    <div class="p-price-row">
                        <span class="p-new"><?php echo formatVND($p['price']); ?></span>
                        <?php if ($p['old_price'] > $p['price']): ?>
                        <span class="p-old"><?php echo formatVND($p['old_price']); ?></span>
                        <?php endif; ?>
                    </div>

                    <div class="p-stats-wrap">
                        <span><i class="far fa-eye"></i> <?php echo number_format($p['view_count']); ?> lượt xem</span>
                        <span
                            style="color: var(--primary); font-weight: 700; font-size: 0.75rem; letter-spacing: 0.5px; text-transform: uppercase;">Mới
                            về</span>
                    </div>

                    <a href="/assignment/product/<?php echo $p['slug']; ?>" class="btn-add">
                        Chi tiết sản phẩm <i class="fas fa-arrow-right" style="font-size: 0.8rem;"></i>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
            <?php else: ?>
            <div style="text-align: center; grid-column: 1/-1; padding: 60px 0;">
                <i class="fas fa-box-open" style="font-size: 3rem; color: #ccc; margin-bottom: 15px;"></i>
                <p style="color: #8e8e93;">Không tìm thấy sản phẩm nào.</p>
            </div>
            <?php endif; ?>
        </div>
    </main>
    <!-- LOGIC JAVASCRIPT ĐỒNG BỘ -->
    <script>
    // 1. Logic Slider điều khiển bằng tay & Tự động chạy tuần hoàn mượt mà
    const slides = document.querySelectorAll('.slide');
    const prevBtn = document.querySelector('.prev');
    const nextBtn = document.querySelector('.next');
    let sliderIndex = 0;

    function showSlide(i) {
        if (slides.length === 0) return;
        slides.forEach(s => s.classList.remove('active'));
        slides[i].classList.add('active');
    }

    if (slides.length > 0) {
        if (nextBtn) {
            nextBtn.onclick = () => {
                sliderIndex = (sliderIndex + 1) % slides.length;
                showSlide(sliderIndex);
            };
        }

        if (prevBtn) {
            prevBtn.onclick = () => {
                sliderIndex = (sliderIndex - 1 + slides.length) % slides.length;
                showSlide(sliderIndex);
            };
        }

        setInterval(() => {
            sliderIndex = (sliderIndex + 1) % slides.length;
            showSlide(sliderIndex);
        }, 5000);
    }

    function filterCat(cat, el) {
        document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));

        const tabs = document.querySelectorAll('.tab');
        tabs.forEach(tab => {
            if (tab.getAttribute('onclick') && tab.getAttribute('onclick').includes(`'${cat}'`)) {
                tab.classList.add('active');
            }
        });

        const cards = document.querySelectorAll('.p-card');
        cards.forEach(card => {
            if (cat === 'all' || card.dataset.cat === cat) {
                card.style.opacity = '0';
                card.style.display = 'flex';
                setTimeout(() => {
                    card.style.opacity = '1';
                }, 50);
            } else {
                card.style.display = 'none';
            }
        });
    }

    // 3. Logic Tìm kiếm sản phẩm thời gian thực
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const val = e.target.value.toLowerCase().trim();
            const cards = document.querySelectorAll('.p-card');
            cards.forEach(card => {
                if (card.dataset.name.includes(val)) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    }

    // 4. Logic Sắp xếp theo mức giá
    function sortProducts() {
        const grid = document.getElementById('productGrid');
        if (!grid) return;

        const cards = Array.from(grid.getElementsByClassName('p-card'));
        const val = document.getElementById('sortPrice').value;

        if (val === 'default') return;

        cards.sort((a, b) => {
            const priceA = parseFloat(a.dataset.price);
            const priceB = parseFloat(b.dataset.price);
            return val === 'low' ? priceA - priceB : priceB - priceA;
        });

        grid.innerHTML = "";
        cards.forEach(card => grid.appendChild(card));
    }
    </script>
</body>

</html>