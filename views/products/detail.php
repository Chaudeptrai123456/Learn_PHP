<?php 
    $vouchers = [
        ['code' => 'TECHCHAU', 'discount' => 1000000, 'type' => 'fixed', 'desc' => 'Giảm thẳng 1 Triệu'],
        ['code' => 'APPLE5', 'discount' => 5, 'type' => 'percent', 'desc' => 'Giảm 5% hóa đơn']
    ];
    function formatVND($number) {
        return number_format($number, 0, ',', '.') . ' ₫';
    }
    function getSpecIcon($key) {
        $keyLower = mb_strtolower($key, 'UTF-8');
        if (strpos($keyLower, 'màn hình') !== false) {
            return 'fa-mobile-screen-button';
        } elseif (strpos($keyLower, 'vi xử lý') !== false || strpos($keyLower, 'chip') !== false) {
            return 'fa-microchip';
        } elseif (strpos($keyLower, 'camera') !== false) {
            return 'fa-camera';
        } elseif (strpos($keyLower, 'pin') !== false) {
            return 'fa-battery-three-quarters';
        } elseif (strpos($keyLower, 'kết nối') !== false) {
            return 'fa-bolt-lightning';
        }
            return 'fa-circle-info';
        }
 ?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> | Tech Store</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
    :root {
        --accent: #0071e3;
        --bg: #ffffff;
        --card-bg: #f5f5f7;
        --text-main: #1d1d1f;
        --text-sub: #86868b;
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
    }

    .container {
        max-width: 1250px;
        margin: 0 auto;
        padding: 0 25px;
    }

    /* Header */
    header {
        padding: 20px 0;
        border-bottom: 1px solid #eee;
        position: sticky;
        top: 0;
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(15px);
        z-index: 100;
    }

    .nav {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .logo {
        font-weight: 800;
        font-size: 1.5rem;
        text-decoration: none;
        color: #000;
    }

    /* Product Top Layout */
    .product-top {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 50px;
        padding: 40px 0;
    }

    /* Gallery */
    .gallery-wrap {
        position: sticky;
        top: 100px;
    }

    .main-img {
        background: var(--card-bg);
        border-radius: 24px;
        padding: 30px;
        height: 500px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .main-img img {
        max-width: 100%;
        height: auto;
        transition: 0.4s;
    }

    .thumbs {
        display: flex;
        gap: 12px;
        margin-top: 15px;
        justify-content: center;
    }

    .thumb {
        width: 60px;
        height: 60px;
        border-radius: 10px;
        cursor: pointer;
        border: 2px solid transparent;
        background: var(--card-bg);
    }

    .thumb.active {
        border-color: var(--accent);
    }

    /* Info Column */
    .brand-tag {
        color: var(--accent);
        font-weight: 700;
        font-size: 0.8rem;
        letter-spacing: 1px;
        text-transform: uppercase;
    }

    .title {
        font-size: 2.8rem;
        font-weight: 700;
        margin: 10px 0 20px;
        line-height: 1.1;
    }

    .desc {
        color: var(--text-sub);
        font-size: 1.1rem;
        margin-bottom: 30px;
    }

    /* OPTIONS SECTION */
    .option-box {
        margin-bottom: 25px;
    }

    .label {
        font-weight: 600;
        margin-bottom: 10px;
        display: block;
        font-size: 0.9rem;
    }

    .pills {
        display: flex;
        gap: 10px;
    }

    .pill {
        padding: 12px 25px;
        border: 1.5px solid #e5e5e7;
        border-radius: 12px;
        cursor: pointer;
        font-weight: 600;
        transition: 0.3s;
    }

    .pill.active {
        border-color: var(--accent);
        color: var(--accent);
    }

    /* THE HORIZONTAL PRICE & VOUCHER BOX */
    .price-voucher-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin: 40px 0;
        background: #fbfbfd;
        padding: 30px;
        border-radius: 24px;
        border: 1px solid #f0f0f0;
    }

    .price-side {
        border-right: 1px solid #e5e5e7;
        padding-right: 20px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .price-side .old {
        text-decoration: line-through;
        color: var(--text-sub);
        font-size: 1.1rem;
    }

    .price-side .current {
        font-size: 2.2rem;
        font-weight: 700;
        color: #000;
        margin: 5px 0;
    }

    .voucher-side {
        padding-left: 10px;
    }

    .v-item {
        background: #fff;
        border: 1px dashed var(--accent);
        padding: 12px;
        border-radius: 12px;
        margin-bottom: 10px;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: 0.3s;
    }

    .v-item:hover {
        background: #f0f7ff;
    }

    .v-item.active {
        background: var(--accent);
        color: #fff;
        border-style: solid;
    }

    .v-item.active .v-code {
        color: #fff;
        border-color: #fff;
    }

    .v-code {
        font-size: 0.7rem;
        font-weight: 700;
        border: 1px solid var(--accent);
        padding: 2px 6px;
        border-radius: 4px;
        color: var(--accent);
    }

    .v-name {
        font-size: 0.85rem;
        font-weight: 600;
    }

    /* Final Price Result */
    .final-box {
        grid-column: span 2;
        background: #e8f3ff;
        padding: 15px;
        border-radius: 12px;
        text-align: center;
        margin-top: 10px;
        display: none;
    }

    .final-box.show {
        display: block;
        animation: slideDown 0.3s ease;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Action Buttons */
    .actions {
        display: flex;
        gap: 15px;
    }

    .btn-buy {
        flex: 3;
        background: var(--accent);
        color: #fff;
        border: none;
        padding: 20px;
        border-radius: 15px;
        font-weight: 700;
        cursor: pointer;
        font-size: 1rem;
    }

    .btn-fav {
        flex: 1;
        border: 1px solid #ddd;
        background: #fff;
        border-radius: 15px;
        cursor: pointer;
    }

    /* Specs Section */
    .specs-sec {
        padding: 80px 0;
        background: var(--card-bg);
        margin-top: 50px;
    }

    .specs-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
    }

    .spec-item {
        background: #fff;
        padding: 25px;
        border-radius: 20px;
        text-align: center;
    }

    .spec-item i {
        font-size: 1.5rem;
        color: var(--accent);
        margin-bottom: 15px;
    }

    .spec-key {
        display: block;
        font-size: 0.8rem;
        color: var(--text-sub);
        margin-bottom: 5px;
    }

    .spec-val {
        font-weight: 700;
    }

    @media (max-width: 900px) {

        .product-top,
        .price-voucher-grid {
            grid-template-columns: 1fr;
        }

        .price-side {
            border-right: none;
            border-bottom: 1px solid #eee;
            padding-bottom: 20px;
        }

        .title {
            font-size: 2rem;
        }
    }
    </style>
</head>

<body>



    <main class="container">
        <div class="product-top">
            <!-- LEFT: GALLERY -->
            <div class="gallery-wrap">
                <div class="main-img">
                    <img id="mainImg" src="<?php echo htmlspecialchars($product['images'][0]); ?>" alt="Product">
                </div>
                <div class="thumbs">
                    <?php foreach($product['images'] as $key => $img): ?>
                    <img src="<?php echo htmlspecialchars($img); ?>" class="thumb <?php echo $key==0?'active':''; ?>"
                        onclick="setImg(this)" alt="Thumbnail <?php echo $key + 1; ?>">
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- RIGHT: PRODUCT INFO -->
            <div class="product-info">
                <span class="brand-tag"><?php echo htmlspecialchars($product['brand']); ?></span>
                <h1 class="title"><?php echo htmlspecialchars($product['name']); ?></h1>
                <p class="desc"><?php echo htmlspecialchars($product['short_desc']); ?></p>

                <!-- Options -->
                <div class="option-box">
                    <span class="label">Chọn dung lượng</span>
                    <div class="pills">
                        <div class="pill active">256GB</div>
                        <div class="pill">512GB</div>
                        <div class="pill">1TB</div>
                    </div>
                </div>

                <!-- Price and Voucher Row -->
                <div class="price-voucher-grid">
                    <!-- Left: Price -->
                    <div class="price-side">
                        <span class="old"><?php echo formatVND($product['old_price']); ?></span>
                        <span class="current" id="basePrice" data-val="<?php echo $product['old_price']; ?>">
                            <?php echo formatVND($product['old_price']); ?>
                        </span>
                        <span style="font-size: 0.8rem; color: #d70018; font-weight: 700;">Tiết kiệm:
                            <?php echo formatVND($product['old_price'] - $product['old_price']); ?></span>
                    </div>

                    <!-- Right: Vouchers -->
                    <div class="voucher-side">
                        <span class="label"><i class="fas fa-tags"></i> Voucher ưu đãi</span>
                        <?php foreach($vouchers as $v): ?>
                        <div class="v-item"
                            onclick="applyVoucher(this, '<?php echo $v['type']; ?>', <?php echo $v['discount']; ?>)">
                            <div>
                                <div class="v-name"><?php echo htmlspecialchars($v['desc']); ?></div>
                                <span class="v-code"><?php echo htmlspecialchars($v['code']); ?></span>
                            </div>
                            <i class="fas fa-plus-circle"></i>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Final Result -->
                    <div class="final-box" id="finalPriceBox">
                        <span>Giá cuối cùng sau giảm giá: </span>
                        <strong id="finalAmount" style="font-size: 1.4rem; color: var(--accent);">0 ₫</strong>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="actions">
                    <button class="btn-buy">MUA NGAY</button>
                    <button class="btn-fav"><i class="far fa-heart"></i></button>
                </div>
            </div>
        </div>
    </main>

    <!-- SECTION THÔNG SỐ KỸ THUẬT -->
    <section class="specs-sec">
        <div class="container">
            <h2 style="text-align:center; margin-bottom: 40px; font-size: 2rem;">Thông số kỹ thuật</h2>
            <div class="specs-grid">
                <?php foreach($product['specs'] as $k => $v): ?>
                <div class="spec-item">
                    <i class="fas <?php echo getSpecIcon($k); ?>"></i>
                    <span class="spec-key"><?php echo htmlspecialchars($k); ?></span>
                    <span class="spec-val"><?php echo htmlspecialchars($v); ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <footer>
        <div class="container" style="padding: 50px 0; text-align: center; color: var(--text-sub);">
            <p>© <?php echo date('Y'); ?> Design by Tech Leader Châu. Luxury Ecommerce Interface.</p>
        </div>
    </footer>

    <script>
    // Đổi ảnh chính khi nhấn thumbnail
    function setImg(el) {
        document.getElementById('mainImg').src = el.src;
        document.querySelectorAll('.thumb').forEach(t => t.classList.remove('active'));
        el.classList.add('active');
    }

    // Xử lý click chọn dung lượng (pills)
    document.querySelectorAll('.pill').forEach(pill => {
        pill.addEventListener('click', function() {
            document.querySelectorAll('.pill').forEach(p => p.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // Logic tính voucher
    const basePrice = parseInt(document.getElementById('basePrice').dataset.val);
    const finalBox = document.getElementById('finalPriceBox');
    const finalAmountText = document.getElementById('finalAmount');

    function applyVoucher(el, type, val) {
        const isActive = el.classList.contains('active');

        // Reset trạng thái của tất cả vouchers trước khi áp dụng mới
        document.querySelectorAll('.v-item').forEach(v => {
            v.classList.remove('active');
            v.querySelector('i').className = 'fas fa-plus-circle';
        });

        // Nếu nhấn lại vào voucher đang kích hoạt thì tắt đi
        if (isActive) {
            finalBox.classList.remove('show');
            return;
        }

        // Kích hoạt voucher mới được chọn
        el.classList.add('active');
        el.querySelector('i').className = 'fas fa-check-circle';

        let discount = 0;
        if (type === 'percent') {
            discount = (basePrice * val) / 100;
        } else {
            discount = val;
        }

        const finalPrice = Math.max(0, basePrice - discount);
        finalAmountText.innerText = new Intl.NumberFormat('vi-VN').format(finalPrice) + ' ₫';
        finalBox.classList.add('show');
    }
    </script>

</body>

</html>