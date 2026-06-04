<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'add_to_cart') {
    echo($skuId);
    $skuId = (int) ($_POST['sku_id'] ?? 0);
    $quantity = (int) ($_POST['quantity'] ?? 1);
    if ($skuId > 0 && $quantity > 0) {
        if (isset($_SESSION['cart'][$skuId])) {
            $_SESSION['cart'][$skuId] += $quantity;
        } else {
            $_SESSION['cart'][$skuId] = $quantity;
        }
    }
    $total = array_sum($_SESSION['cart']);
    echo "<script>
        window.parent.updateCartBadge($total);
        window.parent.updateDebugPanel();
        window.parent.showToast('Đã thêm vào giỏ hàng!');
    </script>";
    exit;
}

function getTotalCartItems() {
    return array_sum($_SESSION['cart'] ?? []);
}
$totalCartItems = getTotalCartItems();
if (!function_exists('formatVND')) {
    function formatVND($number) {
        return number_format($number, 0, ',', '.') . ' ₫';
    }
}
if (!function_exists('getSpecIcon')) {
    function getSpecIcon($key) {
        $keyLower = mb_strtolower($key, 'UTF-8');
        if (strpos($keyLower, 'màn hình') !== false) {
            return 'fa-mobile-screen-button';
        } elseif (strpos($keyLower, 'vi xử lý') !== false || strpos($keyLower, 'chip') !== false || strpos($keyLower, 'cpu') !== false) {
            return 'fa-microchip';
        } elseif (strpos($keyLower, 'ram') !== false || strpos($keyLower, 'bộ nhớ') !== false) {
            return 'fa-memory';
        } elseif (strpos($keyLower, 'ổ cứng') !== false || strpos($keyLower, 'ssd') !== false) {
            return 'fa-hard-drive';
        } elseif (strpos($keyLower, 'pin') !== false) {
            return 'fa-battery-three-quarters';
        } elseif (strpos($keyLower, 'camera') !== false) {
            return 'fa-camera';
        }
        return 'fa-circle-info';
    }
}
$galleryImages = [];
if (!empty($product->skus)) {
    foreach ($product->skus as $sku) {
        if (!empty($sku->image_url) && !in_array($sku->image_url, $galleryImages)) {
            $galleryImages[] = $sku->image_url;
        }
    }
}

if (!empty($product->images)) {
    foreach ($product->images as $img) {
        $imgUrl = is_object($img) ? $img->image_url : $img;
        if (!empty($imgUrl) && !in_array($imgUrl, $galleryImages)) {
            $galleryImages[] = $imgUrl;
        }
    }
}

if (empty($galleryImages)) {
    $galleryImages[] = 'https://images.unsplash.com/photo-1593642632823-8f785ba67e45?auto=format&fit=crop&w=800&q=80';
}

// TÌM SKU MẶC ĐỊNH
$defaultSku = null;
if (!empty($product->skus)) {
    foreach ($product->skus as $sku) {
        if (isset($sku->is_default) && $sku->is_default == 1) {
            $defaultSku = $sku;
            break;
        }
    }
    if (!$defaultSku) {
        $defaultSku = $product->skus[0];
    }
}

// 4. XỬ LÝ LOGIC "THÊM VÀO GIỎ HÀNG" NATIVE (GỬI LÊN BẰNG IFRAME ẨN)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_to_cart') {
    $sub_sku_id = isset($_POST['sku_id']) ? (int)$_POST['sku_id'] : 0;
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

    $targetSku = null;
    if (!empty($product->skus)) {
        foreach ($product->skus as $sku) {
            if ($sku->id == $sub_sku_id) {
                $targetSku = $sku;
                break;
            }
        }
    }

    if ($targetSku) {
        if (isset($_SESSION['cart'][$sub_sku_id])) {
            $_SESSION['cart'][$sub_sku_id]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$sub_sku_id] = [
                'product_id' => $product->id,
                'sku_id'     => $targetSku->id,
                'name'       => $product->name,
                'sku_code'   => $targetSku->sku_code,
                'price'      => (float)$targetSku->price,
                'image_url'  => !empty($targetSku->image_url) ? $targetSku->image_url : $galleryImages[0],
                'quantity'   => $quantity
            ];
        }
        
        // Tính tổng số lượng mới trong giỏ hàng
        $totalCartItems = 0;
        if (!empty($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $item) {
                $totalCartItems += $item['quantity'];
            }
        }

        // Gọi hàm showToast và updateCartBadge từ trang cha thông qua iframe
        echo "
        <script>
            window.parent.showToast('Đã thêm thành công " . addslashes($product->name) . " (" . addslashes($targetSku->sku_code) . ") vào giỏ hàng!');
            window.parent.updateCartBadge(" . $totalCartItems . ");
            window.parent.updateDebugPanel();
        </script>
        ";
        exit;
    }
}

// 5. TÍNH TỔNG SỐ LƯỢNG SẢN PHẨM HIỆN CÓ TRONG GIỎ HÀNG (Cho lần đầu tải trang)
$totalCartItems = 0;
 
// 6. KHỞI TẠO THÔNG SỐ KỸ THUẬT ĐỘNG DỰA TRÊN TÊN SẢN PHẨM
$dynamicSpecs = [];
$productNameLower = mb_strtolower($product->name ?? '', 'UTF-8');
if (strpos($productNameLower, 'phone') !== false || strpos($productNameLower, 'galaxy') !== false || strpos($productNameLower, 'iphone') !== false) {
    $dynamicSpecs = [
        'Màn hình' => '6.7" OLED Super Retina, 120Hz',
        'Vi xử lý' => 'Chip xử lý cao cấp thế hệ mới',
        'Camera sau' => 'Chính 48 MP & Phụ 12 MP',
        'Dung lượng Pin' => 'Thời lượng pin sử dụng cả ngày dài',
        'Kết nối' => 'Hỗ trợ mạng di động 5G siêu tốc'
    ];
} else {
    $dynamicSpecs = [
        'Bộ vi xử lý (CPU)' => 'Intel Core i7 / AMD Ryzen 7 cao cấp',
        'Bộ nhớ RAM' => '16GB DDR5 thế hệ mới',
        'Ổ cứng SSD' => '512GB PCIe NVMe tốc độ cao',
        'Màn hình' => '14" IPS / OLED sắc nét',
        'Đồ họa (GPU)' => 'NVIDIA GeForce RTX Studio'
    ];
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product->name ?? 'Chi tiết sản phẩm'); ?> | Tech Store</title>
    <!-- Google Font & FontAwesome Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
    :root {
        --accent: #0071e3;
        --accent-hover: #0077ed;
        --bg: #ffffff;
        --card-bg: #f5f5f7;
        --border: #e5e5e7;
        --text-main: #1d1d1f;
        --text-sub: #86868b;
        --shadow: 0 8px 30px rgba(0, 0, 0, 0.04);
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: var(--text-main);
        background: var(--bg);
        -webkit-font-smoothing: antialiased;
        padding-top: 70px;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 24px;
    }

    /* --- STICKY BLUR HEADER --- */
    header {
        position: fixed;
        width: 100%;
        top: 0;
        left: 0;
        z-index: 1000;
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(20px);
        border-bottom: 1px solid var(--border);
    }

    .nav {
        display: flex;
        justify-content: space-between;
        align-items: center;
        height: 70px;
    }

    .logo {
        font-weight: 800;
        font-size: 1.3rem;
        text-decoration: none;
        color: #000;
        letter-spacing: -0.5px;
    }

    .logo span {
        color: var(--accent);
    }

    .cart-btn-icon {
        position: relative;
        color: var(--text-main);
        font-size: 1.3rem;
        text-decoration: none;
        display: flex;
        align-items: center;
    }

    .cart-badge {
        position: absolute;
        top: -6px;
        right: -8px;
        background: var(--accent);
        color: #fff;
        font-size: 0.65rem;
        font-weight: 800;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 8px rgba(0, 113, 227, 0.3);
    }

    /* --- LAYOUT CHÍNH --- */
    .product-grid {
        display: grid;
        grid-template-columns: 1.1fr 0.9fr;
        gap: 60px;
        padding: 40px 0;
    }

    /* --- PHẦN HÌNH ẢNH (LEFT COLUMN) --- */
    .gallery-wrap {
        position: sticky;
        top: 110px;
    }

    .main-img-card {
        background: var(--card-bg);
        border-radius: 28px;
        height: 480px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        position: relative;
        border: 1px solid transparent;
        transition: var(--transition);
    }

    .main-img-card:hover {
        border-color: var(--border);
        box-shadow: var(--shadow);
    }

    .main-img-card img {
        max-width: 80%;
        max-height: 80%;
        object-fit: contain;
        transition: transform 0.5s ease;
    }

    .main-img-card:hover img {
        transform: scale(1.04);
    }

    .thumbs-container {
        display: flex;
        gap: 12px;
        margin-top: 20px;
        justify-content: center;
    }

    .thumb {
        width: 68px;
        height: 68px;
        border-radius: 12px;
        cursor: pointer;
        border: 2px solid transparent;
        background: var(--card-bg);
        padding: 6px;
        object-fit: contain;
        transition: var(--transition);
    }

    .thumb:hover {
        transform: translateY(-2px);
    }

    .thumb.active {
        border-color: var(--accent);
        background: #fff;
        box-shadow: 0 4px 12px rgba(0, 113, 227, 0.1);
    }

    /* --- THÔNG TIN CHI TIẾT (RIGHT COLUMN) --- */
    .product-meta {
        display: flex;
        flex-direction: column;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #e8f3ff;
        color: var(--accent);
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 700;
        width: fit-content;
        margin-bottom: 16px;
    }

    .product-title {
        font-size: 2.6rem;
        font-weight: 800;
        letter-spacing: -1px;
        line-height: 1.15;
        margin-bottom: 12px;
    }

    .product-rating {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 24px;
        font-size: 0.9rem;
    }

    .stars {
        color: #ffb100;
        display: flex;
        gap: 2px;
    }

    .rating-text {
        color: var(--text-sub);
        font-weight: 500;
    }

    .product-desc {
        color: var(--text-sub);
        font-size: 1.05rem;
        line-height: 1.5;
        margin-bottom: 32px;
    }

    /* --- CẤU HÌNH (PILLS) --- */
    .option-section {
        margin-bottom: 30px;
        border-top: 1px solid var(--border);
        padding-top: 24px;
    }

    .option-title {
        font-size: 0.9rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-main);
        margin-bottom: 14px;
    }

    .sku-pills {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .sku-pill {
        padding: 14px 24px;
        border: 2px solid var(--border);
        border-radius: 16px;
        font-weight: 700;
        font-size: 0.88rem;
        cursor: pointer;
        transition: var(--transition);
        background: #fff;
        user-select: none;
    }

    .sku-pill:hover {
        border-color: #b2b2b3;
    }

    .sku-pill.active {
        border-color: var(--accent);
        color: var(--accent);
        background: rgba(0, 113, 227, 0.03);
    }

    /* --- KHỐI GIÁ SANG TRỌNG --- */
    .price-card {
        background: var(--card-bg);
        border-radius: 24px;
        padding: 24px 30px;
        margin-bottom: 30px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 15px;
    }

    .price-col {
        display: flex;
        flex-direction: column;
    }

    .old-price-label {
        font-size: 0.9rem;
        color: var(--text-sub);
        text-decoration: line-through;
        font-weight: 500;
    }

    .current-price {
        font-size: 2.1rem;
        font-weight: 800;
        color: #000;
        letter-spacing: -0.5px;
        margin-top: 2px;
    }

    .discount-badge {
        background: #ff3b30;
        color: #fff;
        font-size: 0.78rem;
        font-weight: 800;
        padding: 6px 14px;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(255, 59, 48, 0.15);
    }

    /* --- NÚT HÀNH ĐỘNG MUA --- */
    .action-group {
        display: flex;
        gap: 16px;
    }

    .btn-buy-primary {
        flex: 1;
        background: var(--text-main);
        color: #fff;
        border: none;
        padding: 20px;
        border-radius: 18px;
        font-weight: 700;
        font-size: 1rem;
        cursor: pointer;
        transition: var(--transition);
        letter-spacing: -0.1px;
    }

    .btn-buy-primary:hover {
        background: var(--accent);
        box-shadow: 0 8px 24px rgba(0, 113, 227, 0.25);
    }

    .btn-wishlist {
        width: 60px;
        border: 2px solid var(--border);
        background: #fff;
        border-radius: 18px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        color: var(--text-main);
        transition: var(--transition);
    }

    .btn-wishlist:hover {
        border-color: #ff3b30;
        color: #ff3b30;
        background: rgba(255, 59, 48, 0.02);
    }

    /* --- THÔNG SỐ KỸ THUẬT --- */
    .specs-section {
        padding: 80px 0;
        background: var(--card-bg);
        margin-top: 60px;
        border-top: 1px solid var(--border);
    }

    .specs-container-title {
        text-align: center;
        font-size: 2rem;
        font-weight: 800;
        letter-spacing: -0.5px;
        margin-bottom: 40px;
    }

    .specs-layout {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
    }

    .spec-card {
        background: #fff;
        padding: 28px;
        border-radius: 24px;
        text-align: center;
        transition: var(--transition);
        border: 1px solid transparent;
    }

    .spec-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow);
        border-color: var(--border);
    }

    .spec-card i {
        font-size: 1.6rem;
        color: var(--accent);
        margin-bottom: 16px;
    }

    .spec-name {
        display: block;
        font-size: 0.8rem;
        color: var(--text-sub);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
    }

    .spec-value {
        font-weight: 700;
        font-size: 0.95rem;
        color: var(--text-main);
    }

    /* --- BỘ HIỂN THỊ TOAST NOTIFICATION SANG TRỌNG --- */
    #toast-container {
        position: fixed;
        top: 24px;
        right: 24px;
        z-index: 9999;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .toast-notification {
        background: #1d1d1f;
        color: #fff;
        padding: 16px 24px;
        border-radius: 16px;
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.16);
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 0.88rem;
        font-weight: 600;
        transform: translateX(120%);
        transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        max-width: 380px;
    }

    .toast-notification.show {
        transform: translateX(0);
    }

    .toast-notification i {
        color: #30d158;
        /* Màu xanh lá chuẩn Apple */
        font-size: 1.15rem;
    }

    /* --- BẢNG DEBUG SESSION ĐẸP MẮT --- */
    .debug-badge {
        position: fixed;
        bottom: 24px;
        left: 24px;
        background: rgba(29, 29, 31, 0.95);
        backdrop-filter: blur(10px);
        color: #fff;
        padding: 14px 20px;
        border-radius: 16px;
        font-size: 0.78rem;
        font-family: monospace;
        z-index: 9999;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.25);
        border: 1px solid rgba(255, 255, 255, 0.1);
        max-width: 300px;
        cursor: pointer;
        transition: var(--transition);
    }

    .debug-badge:hover {
        transform: scale(1.02);
    }

    .debug-badge h4 {
        color: #30d158;
        font-weight: 800;
        margin-bottom: 6px;
        display: flex;
        justify-content: space-between;
    }

    /* --- FOOTER --- */
    footer {
        padding: 50px 0;
        text-align: center;
        color: var(--text-sub);
        font-size: 0.88rem;
        border-top: 1px solid var(--border);
    }

    /* --- ĐÁP ỨNG THIẾT BỊ DI ĐỘNG --- */
    @media (max-width: 992px) {
        .product-grid {
            grid-template-columns: 1fr;
            gap: 40px;
        }

        .gallery-wrap {
            position: relative;
            top: 0;
        }

        .main-img-card {
            height: 380px;
        }

        .product-title {
            font-size: 2rem;
        }

        #toast-container {
            top: auto;
            bottom: 24px;
            right: 24px;
            left: 24px;
        }

        .toast-notification {
            max-width: 100%;
        }
    }
    </style>
</head>

<body>
    <div id="toast-container"></div>
    <div class="debug-badge" onclick="this.style.display='none'" title="Nhấp để ẩn bảng debug">
        <h4><span>[DEBUG] Giỏ Hàng Session</span> <i class="fas fa-times"></i></h4>
        <div id="debugContent">
            <?php if (empty($_SESSION['cart'])): ?>
            Không có sản phẩm nào.
            <?php else: ?>
            Đang có: <?php echo count($_SESSION['cart']); ?> SKU phiên bản.<br>
            Tổng số lượng: <?php echo $totalCartItems; ?> máy.<br>
            ID Session của Châu: <b style="color:#30d158;"><?php echo session_id(); ?></b>
            <?php endif; ?>
        </div>
    </div>

    <main class="container">

        <div class="product-grid">

            <!-- LEFT COLUMN: GALLERY -->
            <div class="gallery-wrap">
                <div class="main-img-card">
                    <img id="mainImg" src="<?php echo htmlspecialchars($galleryImages[0]); ?>" alt="Product">
                </div>

                <?php if (count($galleryImages) > 1): ?>
                <div class="thumbs-container">
                    <?php foreach($galleryImages as $key => $img): ?>
                    <img src="<?php echo htmlspecialchars($img); ?>"
                        class="thumb <?php echo $key == 0 ? 'active' : ''; ?>" onclick="setImg(this)"
                        alt="Thumbnail <?php echo $key + 1; ?>">
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
            <div class="product-meta">
                <?php 
                    $isOutOfStock = ($defaultSku && $defaultSku->stock_qty <= 0);
                ?>
                <div class="status-badge">
                    <i class="fas <?php echo $isOutOfStock ? 'fa-circle-xmark' : 'fa-circle-check'; ?>"></i>
                    <span id="stockStatusText"><?php echo $isOutOfStock ? 'Hết hàng' : 'Còn hàng'; ?></span>
                </div>
                <h1 class="product-title"><?php echo htmlspecialchars($product->name ?? 'Sản phẩm công nghệ'); ?></h1>
                <div class="product-rating">
                    <div class="stars">
                        <?php 
                            $rating = isset($product->rating_avg) ? (float)$product->rating_avg : 5.0;
                            $roundedStars = round($rating);
                            echo str_repeat('<i class="fas fa-star"></i>', $roundedStars);
                            echo str_repeat('<i class="far fa-star"></i>', 5 - $roundedStars);
                        ?>
                    </div>
                    <span class="rating-text">
                        <?php echo number_format($rating, 1); ?> / 5.0
                        (<?php echo isset($product->view_count) ? $product->view_count : 0; ?> lượt xem)
                    </span>
                </div>
                <p class="product-desc"><?php echo htmlspecialchars($product->short_desc ?? ''); ?></p>
                <?php if (!empty($product->skus)): ?>
                <div class="option-section">
                    <h3 class="option-title">Chọn phiên bản cấu hình</h3>
                    <div class="sku-pills">
                        <?php foreach($product->skus as $index => $sku): 
                            $parts = explode('-', $sku->sku_code);
                            $label = isset($parts[2]) ? ($parts[2] . ' ' . ($parts[3] ?? '')) : $sku->sku_code;
                        ?>
                        <div class="sku-pill <?php echo ($defaultSku && $defaultSku->id == $sku->id) ? 'active' : ''; ?>"
                            data-id="<?php echo $sku->id; ?>" data-price="<?php echo (float)$sku->price; ?>"
                            data-old-price="<?php echo (float)$sku->old_price; ?>"
                            data-stock="<?php echo (int)$sku->stock_qty; ?>"
                            data-img="<?php echo htmlspecialchars($sku->image_url ?? $galleryImages[0]); ?>"
                            onclick="selectSku(this)">
                            <?php echo htmlspecialchars($label); ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                <div class="price-card">
                    <div class="price-col">
                        <?php if ($defaultSku && !empty($defaultSku->old_price) && $defaultSku->old_price > $defaultSku->price): ?>
                        <span class="old-price-label"
                            id="oldPriceText"><?php echo formatVND($defaultSku->old_price); ?></span>
                        <?php else: ?>
                        <span class="old-price-label" id="oldPriceText"></span>
                        <?php endif; ?>
                        <span class="current-price" id="basePrice"
                            data-val="<?php echo $defaultSku ? $defaultSku->price : 0; ?>">
                            <?php echo $defaultSku ? formatVND($defaultSku->price) : '0 ₫'; ?>
                        </span>
                    </div>
                    <?php if ($defaultSku && !empty($defaultSku->old_price) && $defaultSku->old_price > $defaultSku->price): 
                        $percent = round((($defaultSku->old_price - $defaultSku->price) / $defaultSku->old_price) * 100);
                    ?>
                    <span class="discount-badge" id="discountBadge">Tiết kiệm <?php echo $percent; ?>%</span>
                    <?php else: ?>
                    <span class="discount-badge" id="discountBadge" style="display: none;"></span>
                    <?php endif; ?>
                </div>
                <form method="POST" action="/assignment/addToCart">
                    <input type="hidden" name="action" value="add_to_cart">
                    <input type="hidden" name="sku_id" id="selectedSkuId"
                        value="<?php echo $defaultSku ? $defaultSku->id : ''; ?>">
                    <input type="hidden" name="quantity" value="1">
                    <div class="action-group">
                        <button type="submit" class="btn-buy-primary" id="buyButton">
                            <?php echo $isOutOfStock ? 'LIÊN HỆ ĐẶT TRƯỚC' : 'THÊM VÀO GIỎ HÀNG'; ?>
                        </button>
                        <button type="button" class="btn-wishlist"><i class="far fa-heart"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <section class="specs-section">
        <div class="container">
            <h2 class="specs-container-title">Thông số kỹ thuật</h2>
            <div class="specs-layout">
                <?php foreach($dynamicSpecs as $k => $v): ?>
                <div class="spec-card">
                    <i class="fas <?php echo getSpecIcon($k); ?>"></i>
                    <span class="spec-name"><?php echo htmlspecialchars($k); ?></span>
                    <span class="spec-value"><?php echo htmlspecialchars($v); ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <iframe name="cart_target" id="cart_target" style="display: none;"></iframe>

    <script>
    function setImg(el) {
        document.getElementById('mainImg').src = el.src;
        document.querySelectorAll('.thumb').forEach(t => t.classList.remove('active'));
        el.classList.add('active');
    }

    function selectSku(el) {
        document.querySelectorAll('.sku-pill').forEach(p => p.classList.remove('active'));
        el.classList.add('active');
        const skuId = el.dataset.id;
        const newPrice = parseFloat(el.dataset.price);
        const oldPrice = parseFloat(el.dataset.oldPrice);
        const stock = parseInt(el.dataset.stock);
        const imgUrl = el.dataset.img;
        document.getElementById('selectedSkuId').value = skuId;
        document.getElementById('basePrice').innerText = new Intl.NumberFormat('vi-VN').format(newPrice) + ' ₫';
        document.getElementById('basePrice').dataset.val = newPrice;
        const oldPriceText = document.getElementById('oldPriceText');
        const discountBadge = document.getElementById('discountBadge');
        if (oldPrice > newPrice) {
            oldPriceText.innerText = new Intl.NumberFormat('vi-VN').format(oldPrice) + ' ₫';
            const percent = Math.round(((oldPrice - newPrice) / oldPrice) * 100);
            discountBadge.innerText = 'Tiết kiệm ' + percent + '%';
            discountBadge.style.display = 'inline-block';
        } else {
            oldPriceText.innerText = '';
            discountBadge.style.display = 'none';
        }
        if (imgUrl) {
            document.getElementById('mainImg').src = imgUrl;
            document.querySelectorAll('.thumb').forEach(thumb => {
                if (thumb.src === imgUrl) {
                    thumb.classList.add('active');
                } else {
                    thumb.classList.remove('active');
                }
            });
        }
        const statusBadgeText = document.getElementById('stockStatusText');
        const statusBadgeIcon = document.querySelector('.status-badge i');
        const btnBuy = document.getElementById('buyButton');
        if (stock <= 0) {
            statusBadgeText.innerText = 'Hết hàng';
            statusBadgeIcon.className = 'fas fa-circle-xmark';
            btnBuy.innerText = 'LIÊN HỆ ĐẶT TRƯỚC';
        } else {
            statusBadgeText.innerText = 'Còn hàng';
            statusBadgeIcon.className = 'fas fa-circle-check';
            btnBuy.innerText = 'THÊM VÀO GIỎ HÀNG';
        }
    }

    function updateCartBadge(count) {
        const cartBtnIcon = document.querySelector('.cart-btn-icon');
        if (cartBtnIcon) {
            let badge = cartBtnIcon.querySelector('.cart-badge');
            if (count > 0) {
                if (!badge) {
                    badge = document.createElement('span');
                    badge.className = 'cart-badge';
                    cartBtnIcon.appendChild(badge);
                }
                badge.innerText = count;
            } else if (badge) {
                badge.remove();
            }
        }
    }

    // ============================================================
    // CẬP NHẬT BẢNG DEBUG PHÍA DƯỚI SAU KHI THÊM GIỎ HÀNG THÀNH CÔNG
    // ============================================================
    function updateDebugPanel() {
        const debugContent = document.getElementById('debugContent');
        if (debugContent) {
            // Đọc số lượng hiện tại từ Badge để hiển thị gián tiếp
            const badge = document.querySelector('.cart-badge');
            const totalCount = badge ? parseInt(badge.innerText) : 0;

            if (totalCount > 0) {
                debugContent.innerHTML = `Đang có sản phẩm trong Session.<br>Tổng số lượng: <b>` + totalCount +
                    ` máy.</b><br>ID Session của Châu: <b style="color:#30d158;"><?php echo session_id(); ?></b>`;
            } else {
                debugContent.innerHTML = `Không có sản phẩm nào.`;
            }
        }
    }

    // ============================================================
    // HÀM TẠO VÀ HIỂN THỊ TOAST THÔNG BÁO SANG TRỌNG TRÊN MÀN HÌNH
    // ============================================================
    function showToast(message) {
        const container = document.getElementById('toast-container');
        if (!container) return;

        // Khởi tạo phần tử Toast mới
        const toast = document.createElement('div');
        toast.className = 'toast-notification';
        toast.innerHTML = `<i class="fas fa-circle-check"></i> <span>${message}</span>`;

        container.appendChild(toast);

        // Kích hoạt animation slide-in trượt ra
        setTimeout(() => {
            toast.classList.add('show');
        }, 10);

        // Tự động ẩn đi sau 3 giây
        setTimeout(() => {
            toast.classList.remove('show');
            // Chờ hiệu ứng chuyển cảnh hoàn tất rồi xóa hoàn toàn phần tử khỏi DOM
            setTimeout(() => {
                toast.remove();
            }, 400);
        }, 3000);
    }
    </script>

</body>

</html>