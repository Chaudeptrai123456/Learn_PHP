<?php
if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}


if (!function_exists('formatVND')) {
    function formatVND($number) {
        return number_format((float)$number, 0, ',', '.') . ' ₫';
    }
}

if (!function_exists('getCartItemValue')) {
    function getCartItemValue($item, $key) {
        if (is_object($item)) {
            return isset($item->$key) ? $item->$key : '';
        }
        return isset($item[$key]) ? $item[$key] : '';
    }
}

if (!function_exists('getVoucherDescription')) {
    function getVoucherDescription($v) {
        $type = getCartItemValue($v, 'discount_type');
        $value = (float)getCartItemValue($v, 'discount_value');
        $minVal = (float)getCartItemValue($v, 'min_order_value');
        $maxVal = (float)getCartItemValue($v, 'max_discount_value');
        
        if ($type === 'percent') {
            $desc = "Giảm " . (int)$value . "% đơn hàng";
            if ($maxVal > 0) {
                $desc .= " (Tối đa " . formatVND($maxVal) . ")";
            }
        } else {
            $desc = "Giảm thẳng " . formatVND($value);
        }
        
        if ($minVal > 0) {
            $desc .= " cho đơn hàng từ " . formatVND($minVal);
        }
        
        return $desc;
    }
}

// Kiểm tra trạng thái đơn hàng thành công truyền từ Controller qua GET
$orderSuccess = isset($_GET['success']) && $_GET['success'] === 'true' && isset($_SESSION['last_order']);
$last_order = $_SESSION['last_order'] ?? [];

$orderId = $last_order['id'] ?? '';
$fullname = $last_order['fullname'] ?? '';
$phone = $last_order['phone'] ?? '';
$address = $last_order['address'] ?? '';
$applied_voucher = $last_order['applied_voucher'] ?? '';

$current_page = strtok($_SERVER["REQUEST_URI"], '?');

// Tính tổng giá trị giỏ hàng tạm tính
$subtotal = 0;
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $price = (float)getCartItemValue($item, 'price');
        $qty = (int)getCartItemValue($item, 'quantity');
        $subtotal += $price * $qty;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt hàng &amp; Thanh toán | Tech Store</title>
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
        --shadow-sm: 0 4px 20px rgba(0, 0, 0, 0.02);
        --shadow-md: 0 10px 30px rgba(0, 0, 0, 0.04);
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: var(--bg);
        color: var(--text-main);
        line-height: 1.5;
        -webkit-font-smoothing: antialiased;
        padding-top: 80px;
    }

    .container {
        max-width: 1160px;
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

    /* --- ORDER PAGE LAYOUT --- */
    .order-layout {
        display: grid;
        grid-template-columns: 1.35fr 1fr;
        gap: 60px;
        padding: 40px 0;
    }

    /* --- COLUMN LEFT: GIỎ HÀNG & THÔNG TIN SHIP --- */
    .section-title {
        font-size: 1.5rem;
        font-weight: 800;
        letter-spacing: -0.5px;
        margin-bottom: 24px;
        color: var(--text-main);
    }

    .cart-list {
        display: flex;
        flex-direction: column;
        gap: 16px;
        margin-bottom: 48px;
    }

    .cart-item {
        display: flex;
        align-items: center;
        gap: 20px;
        padding: 20px;
        border: 1px solid var(--border);
        border-radius: 20px;
        background: #fff;
        transition: var(--transition);
    }

    .cart-item:hover {
        border-color: #d2d2d7;
        box-shadow: var(--shadow-sm);
    }

    .item-img-wrap {
        width: 90px;
        height: 90px;
        background: var(--card-bg);
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 12px;
        flex-shrink: 0;
    }

    .item-img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    .item-info {
        flex: 1;
    }

    .item-name {
        font-size: 1rem;
        font-weight: 750;
        color: var(--text-main);
        line-height: 1.35;
    }

    .item-meta {
        display: inline-block;
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--text-sub);
        background: var(--card-bg);
        padding: 4px 10px;
        border-radius: 8px;
        margin-top: 6px;
    }

    /* Bộ tăng giảm số lượng tinh xảo */
    .qty-control {
        display: flex;
        align-items: center;
        background: var(--card-bg);
        border-radius: 12px;
        padding: 4px;
        gap: 14px;
        border: 1px solid transparent;
        transition: var(--transition);
    }

    .qty-control:hover {
        border-color: var(--border);
    }

    .qty-btn {
        width: 28px;
        height: 28px;
        border-radius: 8px;
        background: #fff;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--text-main);
        cursor: pointer;
        transition: var(--transition);
        text-decoration: none;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.03);
    }

    .qty-btn:hover {
        background: var(--accent);
        color: #fff;
        transform: scale(1.05);
    }

    .qty-val {
        font-size: 0.9rem;
        font-weight: 800;
        min-width: 20px;
        text-align: center;
    }

    .item-price-sub {
        font-weight: 800;
        font-size: 1.05rem;
        text-align: right;
        min-width: 120px;
        color: var(--text-main);
    }

    .btn-remove-item {
        color: #ccc;
        font-size: 1.05rem;
        padding: 8px;
        border-radius: 50%;
        transition: var(--transition);
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
    }

    .btn-remove-item:hover {
        color: #ff3b30;
        background: rgba(255, 59, 48, 0.05);
    }

    /* Form thông tin giao hàng tối giản */
    .shipping-card {
        background: #fff;
        border-radius: 24px;
        padding: 32px;
        border: 1px solid var(--border);
    }

    .form-group {
        margin-bottom: 24px;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .form-label {
        font-size: 0.8rem;
        font-weight: 700;
        color: var(--text-sub);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 10px;
        display: block;
    }

    .form-input {
        width: 100%;
        padding: 14px 18px;
        border-radius: 14px;
        border: 1.5px solid var(--border);
        outline: none;
        font-family: inherit;
        font-size: 0.92rem;
        font-weight: 500;
        background: var(--card-bg);
        transition: var(--transition);
    }

    .form-input:focus {
        border-color: var(--accent);
        background: #fff;
        box-shadow: 0 0 0 4px rgba(0, 113, 227, 0.08);
    }

    .form-input[readonly] {
        background: #f1f1f3;
        color: var(--text-sub);
        border-color: var(--border);
        cursor: not-allowed;
    }

    /* --- COLUMN RIGHT: BILLING SUMMARY & VOUCHERS --- */
    .summary-sticky {
        position: sticky;
        top: 110px;
    }

    .billing-card {
        background: var(--card-bg);
        border-radius: 28px;
        padding: 32px;
        border: 1px solid var(--border);
    }

    /* Khối áp dụng Voucher */
    .voucher-section {
        margin-bottom: 32px;
        border-bottom: 1px solid var(--border);
        padding-bottom: 28px;
    }

    .voucher-input-group {
        display: flex;
        gap: 10px;
        margin-top: 12px;
    }

    .voucher-input-group .form-input {
        background: #fff;
    }

    .btn-apply {
        background: var(--text-main);
        color: #fff;
        border: none;
        padding: 0 22px;
        border-radius: 14px;
        font-weight: 700;
        font-size: 0.88rem;
        cursor: pointer;
        transition: var(--transition);
    }

    .btn-apply:hover {
        background: var(--accent);
        box-shadow: 0 4px 12px rgba(0, 113, 227, 0.15);
    }

    .voucher-badges {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-top: 18px;
    }

    .voucher-pill {
        background: #fff;
        border: 1.5px dashed var(--border);
        padding: 14px 18px;
        border-radius: 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
        transition: var(--transition);
        user-select: none;
    }

    .voucher-pill:hover {
        border-color: var(--accent);
        background: rgba(0, 113, 227, 0.02);
    }

    .voucher-pill.active {
        background: var(--accent);
        color: #fff;
        border-color: var(--accent);
        border-style: solid;
        box-shadow: 0 4px 14px rgba(0, 113, 227, 0.2);
    }

    .voucher-pill.active .v-code {
        color: #fff;
        border-color: #fff;
        background: rgba(255, 255, 255, 0.15);
    }

    .voucher-pill.active .voucher-desc {
        color: rgba(255, 255, 255, 0.85);
    }

    .v-code {
        font-size: 0.72rem;
        font-weight: 800;
        border: 1.5px solid var(--accent);
        padding: 3px 8px;
        border-radius: 6px;
        color: var(--accent);
        letter-spacing: 0.5px;
        background: rgba(0, 113, 227, 0.03);
        transition: var(--transition);
    }

    .voucher-desc {
        font-size: 0.8rem;
        font-weight: 600;
        margin-top: 6px;
        color: var(--text-sub);
    }

    /* Hoá đơn thanh toán */
    .bill-row {
        display: flex;
        justify-content: space-between;
        font-size: 0.92rem;
        margin-bottom: 16px;
        color: var(--text-sub);
        font-weight: 500;
    }

    .bill-row.total {
        border-top: 1.5px solid var(--border);
        padding-top: 24px;
        margin-top: 24px;
        font-size: 1.4rem;
        font-weight: 800;
        color: var(--text-main);
        letter-spacing: -0.5px;
    }

    .btn-checkout {
        width: 100%;
        background: var(--accent);
        color: #fff;
        border: none;
        padding: 20px;
        border-radius: 18px;
        font-weight: 750;
        font-size: 0.98rem;
        cursor: pointer;
        transition: var(--transition);
        margin-top: 32px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn-checkout:hover {
        background: var(--accent-hover);
        box-shadow: 0 8px 24px rgba(0, 113, 227, 0.25);
    }

    /* --- EMPTY STATE (GIỎ HÀNG TRỐNG) --- */
    .empty-cart-state {
        text-align: center;
        padding: 100px 24px;
        max-width: 480px;
        margin: 0 auto;
    }

    .empty-cart-state i {
        font-size: 3.5rem;
        color: #ccc;
        margin-bottom: 24px;
    }

    .empty-cart-state h2 {
        font-size: 1.8rem;
        font-weight: 800;
        letter-spacing: -0.5px;
        margin-bottom: 8px;
    }

    .empty-cart-state p {
        color: var(--text-sub);
        font-size: 0.95rem;
        line-height: 1.5;
    }

    .btn-primary-apple {
        display: inline-block;
        background: var(--accent);
        color: #fff;
        padding: 14px 36px;
        border-radius: 20px;
        text-decoration: none;
        font-weight: 700;
        margin-top: 28px;
        font-size: 0.92rem;
        transition: var(--transition);
    }

    .btn-primary-apple:hover {
        background: var(--accent-hover);
        box-shadow: 0 4px 14px rgba(0, 113, 227, 0.2);
    }

    /* --- SUCCESS STATE --- */
    .success-container {
        text-align: center;
        padding: 80px 24px;
        max-width: 580px;
        margin: 0 auto;
    }

    .success-icon-wrap {
        width: 80px;
        height: 80px;
        background: #e8f8f0;
        color: #34c759;
        font-size: 2.2rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 28px;
        box-shadow: 0 4px 14px rgba(52, 199, 89, 0.15);
    }

    .receipt-card {
        background: var(--card-bg);
        padding: 28px 32px;
        border-radius: 24px;
        text-align: left;
        margin-bottom: 36px;
        border: 1px solid var(--border);
    }

    @media (max-width: 900px) {
        .order-layout {
            grid-template-columns: 1fr;
            gap: 40px;
        }
    }
    </style>
</head>

<body>

    <main class="container">

        <?php if ($orderSuccess): ?>
        <!-- ==========================================
            MÀN HÌNH ĐẶT HÀNG THÀNH CÔNG (SUCCESS SCREEN)
            ========================================== -->
        <div class="success-container">
            <div class="success-icon-wrap">
                <i class="fas fa-check"></i>
            </div>
            <h1
                style="font-size: 2.4rem; font-weight: 800; letter-spacing: -0.8px; margin-bottom: 12px; line-height: 1.1;">
                Đặt hàng thành công!</h1>
            <p style="color: var(--text-sub); font-size: 1.02rem; margin-bottom: 32px; line-height: 1.5;">
                Mã số đơn hàng của Khách iu là <strong
                    style="color:var(--text-main);"><?php echo $orderId; ?></strong>.<br>
                Chúng mình đang xử lý đơn hàng và sẽ liên hệ sớm nhất qua số điện thoại nhận hàng.
            </p>
            <div class="receipt-card">
                <h3 style="font-weight: 800; margin-bottom: 16px; font-size: 1.05rem; letter-spacing: -0.2px;">Thông tin
                    nhận hàng của Khách iu :</h3>
                <p style="font-size: 0.92rem; margin-bottom: 8px; color: var(--text-sub);">
                    <strong style="color: var(--text-main); font-weight: 600; width: 140px; display: inline-block;">Họ
                        và tên:</strong>
                    <?php echo htmlspecialchars($fullname); ?>
                </p>
                <p style="font-size: 0.92rem; margin-bottom: 8px; color: var(--text-sub);">
                    <strong style="color: var(--text-main); font-weight: 600; width: 140px; display: inline-block;">Số
                        điện thoại:</strong>
                    <?php echo htmlspecialchars($phone); ?>
                </p>
                <p style="font-size: 0.92rem; margin-bottom: 8px; color: var(--text-sub); line-height: 1.4;">
                    <strong style="color: var(--text-main); font-weight: 600; width: 140px; display: inline-block;">Địa
                        chỉ nhận:</strong>
                    <?php echo htmlspecialchars($address); ?>
                </p>
                <?php if (!empty($applied_voucher)): ?>
                <p style="font-size: 0.92rem; color: var(--text-sub);">
                    <strong
                        style="color: var(--text-main); font-weight: 600; width: 140px; display: inline-block;">Voucher
                        áp dụng:</strong>
                    <span
                        style="color:#e30000; font-weight:800;"><?php echo htmlspecialchars($applied_voucher); ?></span>
                </p>
                <?php endif; ?>
            </div>
            <a href="/assignment/categories" class="btn-primary-apple">Tiếp tục mua sắm</a>
        </div>

        <?php elseif (!empty($_SESSION['cart'])): ?>
        <!-- ==========================================
            MÀN HÌNH CHI TIẾT GIỎ HÀNG & FORM ĐẶT HÀNG
            ========================================== -->
        <!-- Thay đổi action trỏ trực tiếp đến route xử lý của Controller -->
        <form action="/assignment/order/place" method="POST" id="orderForm">
            <!-- Input ẩn lưu danh sách mã Voucher dạng chuỗi cách nhau bằng dấu phẩy -->
            <input type="hidden" id="voucherCodeInput" name="applied_voucher" value="">

            <div class="order-layout">

                <!-- CỘT BÊN TRÁI: DANH SÁCH GIỎ HÀNG & THÔNG TIN SHIP -->
                <div class="column-left">
                    <h2 class="section-title">Giỏ hàng của Khách iu </h2>

                    <div class="cart-list">
                        <?php foreach ($_SESSION['cart'] as $sku_id => $item): 
                            // Đọc dữ liệu từ DTO Object
                            $itemName = getCartItemValue($item, 'product_name');
                            $itemImage = getCartItemValue($item, 'image_url');
                            $skuCode = getCartItemValue($item, 'sku_code');
                            $price = (float)getCartItemValue($item, 'price');
                            $quantity = (int)getCartItemValue($item, 'quantity');
                        ?>
                        <div class="cart-item">
                            <div class="item-img-wrap">
                                <img src="<?php echo htmlspecialchars($itemImage); ?>" alt="Sản phẩm" class="item-img">
                            </div>

                            <div class="item-info">
                                <h3 class="item-name"><?php echo htmlspecialchars($itemName); ?></h3>
                                <span class="item-meta">Phiên bản: <?php echo htmlspecialchars($skuCode); ?></span>
                            </div>

                            <!-- Bộ điều khiển số lượng -->
                            <div class="qty-control">
                                <a href="<?php echo $current_page; ?>?action=decrease&sku_id=<?php echo $sku_id; ?>"
                                    class="qty-btn">-</a>
                                <span class="qty-val"><?php echo $quantity; ?></span>
                                <a href="<?php echo $current_page; ?>?action=increase&sku_id=<?php echo $sku_id; ?>"
                                    class="qty-btn">+</a>
                            </div>

                            <!-- Giá bán -->
                            <div class="item-price-sub">
                                <?php echo formatVND($price * $quantity); ?>
                            </div>

                            <!-- Nút xoá -->
                            <a href="<?php echo $current_page; ?>?action=remove&sku_id=<?php echo $sku_id; ?>"
                                class="btn-remove-item" title="Xóa sản phẩm">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Form Thông Tin Ship hàng (Dùng readonly để truyền được dữ liệu lên Controller) -->
                    <h2 class="section-title">Thông tin giao hàng</h2>
                    <div class="shipping-card">
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Họ và tên của Khách iu </label>
                                <?php 
                                    $displayName = '';
                                    if (isset($_SESSION['user'])) {
                                        $u = $_SESSION['user'];
                                        if (is_array($u)) {
                                            $displayName = $u['name'] ?? $u['fullName'] ?? $u['fullname'] ?? '';
                                        } elseif (is_object($u)) {
                                            $displayName = $u->name ?? $u->fullName ?? $u->fullname ?? '';
                                        }
                                    }
                                ?>
                                <input type="text" name="fullname" class="form-input" required
                                    value="<?php echo htmlspecialchars($displayName); ?>"
                                    placeholder="Họ và tên người nhận" readonly>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Số điện thoại</label>
                                <?php 
                                    $displayPhone = '';
                                    if (isset($_SESSION['user'])) {
                                        $u = $_SESSION['user'];
                                        if (is_array($u)) {
                                            $displayPhone = $u['phone'] ?? $u['phone_number'] ?? $u['phoneNumber'] ?? '';
                                        } elseif (is_object($u)) {
                                            $displayPhone = $u->phone ?? $u->phone_number ?? $u->phoneNumber ?? '';
                                        }
                                    }
                                ?>
                                <input type="tel" name="phone" class="form-input" required
                                    value="<?php echo htmlspecialchars($displayPhone); ?>"
                                    placeholder="Số điện thoại người nhận" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Địa chỉ nhận hàng</label>
                            <?php 
                                $displayAddress = '';
                                if (isset($_SESSION['user'])) {
                                    $u = $_SESSION['user'];
                                    if (is_array($u)) {
                                        $displayAddress = $u['address'] ?? '';
                                    } elseif (is_object($u)) {
                                        $displayAddress = $u->address ?? '';
                                    }
                                }
                            ?>
                            <input type="text" name="address" class="form-input" required readonly
                                value="<?php echo htmlspecialchars($displayAddress); ?>"
                                placeholder="Địa chỉ nhận hàng">
                        </div>
                        <div class="form-group" style="margin-bottom: 0;">
                            <label class="form-label">Ghi chú giao hàng (Tùy chọn)</label>
                            <input type="text" name="notes" class="form-input"
                                placeholder="Ví dụ: Giao giờ hành chính, gọi điện trước khi đến">
                        </div>
                    </div>
                </div>

                <!-- CỘT BÊN PHẢI: HOÁ ĐƠN THANH TOÁN & VOUCHERS -->
                <div class="column-right">
                    <div class="summary-sticky">
                        <h2 class="section-title">Tóm tắt thanh toán</h2>

                        <div class="billing-card">

                            <!-- Khu vực Voucher -->
                            <div class="voucher-section">
                                <span class="form-label"><i class="fas fa-tags"></i> Voucher khuyến mãi</span>
                                <div class="voucher-input-group">
                                    <input type="text" id="voucherTextInput" placeholder="Mã giảm giá..."
                                        class="form-input" style="text-transform:uppercase;" disabled hidden>
                                    <button type="button" onclick="applyVoucherByText()" class="btn-apply">Áp
                                        dụng</button>
                                </div>
                                <!-- Danh sách các Voucher đổ động từ Database -->
                                <div class="voucher-badges">
                                    <?php if (!empty($valid_vouchers)): ?>
                                    <?php foreach ($valid_vouchers as $v): 
                                            $code = getCartItemValue($v, 'code');
                                            $type = getCartItemValue($v, 'discount_type');
                                            $value = (float)getCartItemValue($v, 'discount_value');
                                            $minVal = (float)getCartItemValue($v, 'min_order_value');
                                            $maxVal = (float)getCartItemValue($v, 'max_discount_value');
                                            $desc = getVoucherDescription($v);
                                        ?>
                                    <div class="voucher-pill" id="pill-<?php echo $code; ?>"
                                        data-code="<?php echo $code; ?>" data-type="<?php echo $type; ?>"
                                        data-value="<?php echo $value; ?>" data-min="<?php echo $minVal; ?>"
                                        data-max="<?php echo $maxVal; ?>"
                                        onclick="toggleVoucher('<?php echo $code; ?>')">
                                        <div style="flex: 1;">
                                            <span class="v-code"><?php echo $code; ?></span>
                                            <div class="voucher-desc"><?php echo $desc; ?></div>
                                        </div>
                                        <i class="far fa-circle-check"
                                            style="font-size: 1.15rem; color:#86868b; transition: var(--transition);"></i>
                                    </div>
                                    <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Hoá đơn thanh toán chi tiết -->
                            <div class="billing-details">
                                <div class="bill-row">
                                    <span>Tạm tính giỏ hàng:</span>
                                    <span id="subtotalText"
                                        data-subtotal="<?php echo $subtotal; ?>"><?php echo formatVND($subtotal); ?></span>
                                </div>
                                <div class="bill-row" id="discountRow"
                                    style="display: none; color:#e30000; font-weight:600;">
                                    <span>Giảm giá Voucher:</span>
                                    <span id="discountAmountText">-0 ₫</span>
                                </div>
                                <div class="bill-row">
                                    <span>Phí giao hàng:</span>
                                    <span style="color:#34c759; font-weight:600;">Miễn phí</span>
                                </div>
                                <div class="bill-row total">
                                    <span>Tổng cộng:</span>
                                    <span id="totalAmountText"><?php echo formatVND($subtotal); ?></span>
                                </div>
                            </div>

                            <!-- Nút checkout -->
                            <button type="submit" class="btn-checkout">Xác nhận đặt hàng</button>
                        </div>
                    </div>
                </div>

            </div>
        </form>

        <?php else: ?>
        <!-- ==========================================
            MÀN HÌNH GIỎ HÀNG TRỐNG (EMPTY CART STATE)
            ========================================== -->
        <div class="empty-cart-state animate-fade-in">
            <i class="fas fa-shopping-bag"></i>
            <h2>Giỏ hàng của Khách iu trống</h2>
            <p style="margin-top: 8px;">Hãy tiếp tục tìm kiếm những sản phẩm công nghệ đỉnh cao và thêm chúng vào giỏ
                hàng nhé!</p>
            <a href="/assignment/categories" class="btn-primary-apple">Khám phá sản phẩm</a>
        </div>
        <?php endif; ?>

    </main>

    <script>
    // Định dạng tiền VNĐ phục vụ JS
    function formatMoney(amount) {
        return new Intl.NumberFormat('vi-VN').format(amount) + ' ₫';
    }

    // Mảng toàn cục lưu trữ danh sách mã giảm giá đang kích hoạt
    let activeVoucherCodes = [];

    // Hàm chọn/bỏ chọn Voucher bằng cách Click trực tiếp vào Badges (Có kiểm tra min_order_value)
    function toggleVoucher(code) {
        const pill = document.getElementById('pill-' + code);
        if (!pill) return;

        const subtotal = parseFloat(document.getElementById('subtotalText').dataset.subtotal);
        const minOrderVal = parseFloat(pill.dataset.min) || 0;
        const isActive = pill.classList.contains('active');

        if (isActive) {
            // Hủy kích hoạt
            pill.classList.remove('active');
            pill.querySelector('i').className = 'far fa-circle-check';
            pill.querySelector('i').style.color = '#86868b';
            activeVoucherCodes = activeVoucherCodes.filter(c => c !== code);
        } else {
            // KIỂM TRA ĐIỀU KIỆN ĐƠN HÀNG TỐI THIỂU
            if (subtotal < minOrderVal) {
                alert('Đơn hàng của Khách iu chưa đủ điều kiện tối thiểu ' + formatMoney(minOrderVal) +
                    ' để sử dụng mã này nhé!');
                return;
            }

            // Kích hoạt thêm Voucher
            pill.classList.add('active');
            pill.querySelector('i').className = 'fas fa-check-circle';
            pill.querySelector('i').style.color = '#fff';
            if (!activeVoucherCodes.includes(code)) {
                activeVoucherCodes.push(code);
            }
        }

        // Đồng bộ hóa danh sách voucher dạng chuỗi ngăn cách bằng dấu phẩy
        document.getElementById('voucherCodeInput').value = activeVoucherCodes.join(',');
        document.getElementById('voucherTextInput').value = activeVoucherCodes.join(', ');

        recalculateBill();
    }

    // Hàm áp dụng Voucher bằng cách nhập ký tự chữ vào TextBox
    function applyVoucherByText() {
        const inputVal = document.getElementById('voucherTextInput').value.toUpperCase().trim();

        if (inputVal === '') {
            // Trống thì gỡ bỏ toàn bộ voucher đang chọn
            activeVoucherCodes = [];
            document.querySelectorAll('.voucher-pill').forEach(p => {
                p.classList.remove('active');
                p.querySelector('i').className = 'far fa-circle-check';
                p.querySelector('i').style.color = '#86868b';
            });
            document.getElementById('voucherCodeInput').value = '';
            recalculateBill();
            return;
        }

        // Tìm xem mã nhập có tồn tại trong danh sách pill không
        const targetPill = document.querySelector(`.voucher-pill[data-code="${inputVal}"]`);
        if (targetPill) {
            if (!activeVoucherCodes.includes(inputVal)) {
                toggleVoucher(inputVal);
            }
        } else {
            alert('Mã giảm giá "' + inputVal + '" không tồn tại trên hệ thống!');
            document.getElementById('voucherTextInput').value = activeVoucherCodes.join(', ');
        }
    }

    // Tính toán hóa đơn thực tế thời gian thực cộng dồn (CÓ KHỐNG CHẾ max_discount_value)
    function recalculateBill() {
        const subtotalText = document.getElementById('subtotalText');
        if (!subtotalText) return;

        const subtotal = parseFloat(subtotalText.dataset.subtotal);
        let discount = 0;
        activeVoucherCodes.forEach(code => {
            const activePill = document.getElementById('pill-' + code);
            if (activePill) {
                const type = activePill.dataset.type;
                const value = parseFloat(activePill.dataset.value);
                const maxVal = parseFloat(activePill.dataset.max) || 0;

                if (type === 'percent') {
                    let calcDiscount = (subtotal * value) / 100;
                    // Nếu có giới hạn giảm tối đa thì khống chế mốc tối đa
                    if (maxVal > 0) {
                        calcDiscount = Math.min(calcDiscount, maxVal);
                    }
                    discount += calcDiscount;
                } else if (type === 'fixed') {
                    discount += value;
                }
            }
        });

        // Giới hạn giảm giá tối đa bằng giá trị tạm tính
        discount = Math.min(discount, subtotal);
        const total = Math.max(0, subtotal - discount);

        const discountRow = document.getElementById('discountRow');
        const discountAmountText = document.getElementById('discountAmountText');
        const totalAmountText = document.getElementById('totalAmountText');

        if (discount > 0) {
            discountAmountText.innerText = '-' + formatMoney(discount);
            discountRow.style.display = 'flex';
        } else {
            discountRow.style.display = 'none';
        }

        totalAmountText.innerText = formatMoney(total);
    }
    </script>

</body>

</html>