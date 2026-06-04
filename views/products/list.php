<?php
if (!function_exists('formatVND')) {
    function formatVND($n) { 
        return number_format($n, 0, ',', '.') . ' ₫'; 
    }
}
function parseCategorySlug($category_name) {
    switch (mb_strtolower(trim($category_name))) {
        case 'điện thoại':
            return 'dien-thoai';
        case 'laptop':
            return 'laptop';
        case 'tablet':
            return 'tablet';
        case 'phụ kiện':
            return 'phu-kien';
        default:
            return 'other';
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
    <style>
    :root {
        --primary: #0071e3;
        --black: #000000;
        --dark: #1d1d1f;
        --gray-bg: #f5f5f7;
        --border: #e5e5e7;
        --transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
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
    }

    .container {
        max-width: 1300px;
        margin: 0 auto;
        padding: 0 20px;
    }

    header {
        position: fixed;
        width: 100%;
        top: 0;
        z-index: 1000;
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(20px);
        border-bottom: 1px solid var(--border);
    }

    .header-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        height: 70px;
    }

    .logo {
        font-weight: 800;
        font-size: 1.4rem;
        text-decoration: none;
        color: #000;
        letter-spacing: -1px;
    }

    .logo span {
        color: var(--primary);
    }

    .search-bar {
        position: relative;
        flex: 0 0 400px;
        margin: 0 40px;
    }

    .search-bar input {
        width: 100%;
        padding: 10px 15px 10px 40px;
        border-radius: 20px;
        border: 1px solid var(--border);
        background: var(--gray-bg);
        outline: none;
        transition: var(--transition);
    }

    .search-bar input:focus {
        background: #fff;
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(0, 113, 227, 0.1);
    }

    .search-bar i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #999;
    }

    .nav-tools {
        display: flex;
        gap: 25px;
        align-items: center;
        font-size: 1.1rem;
    }

    .cart-icon {
        position: relative;
        cursor: pointer;
        color: var(--dark);
        text-decoration: none;
    }

    .cart-badge {
        position: absolute;
        top: -8px;
        right: -10px;
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
    }

    /* --- HERO SLIDER --- */
    .hero-section {
        height: 75vh;
        margin-top: 70px;
        position: relative;
        overflow: hidden;
        background: #000;
    }

    .slide {
        position: absolute;
        inset: 0;
        opacity: 0;
        transition: opacity 1s;
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
        opacity: 0.5;
    }

    .slide-content {
        position: absolute;
        left: 10%;
        color: #fff;
        z-index: 5;
        max-width: 600px;
    }

    .slide-content h1 {
        font-size: 4rem;
        font-weight: 800;
        margin-bottom: 20px;
    }

    .btn-white {
        background: #fff;
        color: #000;
        padding: 15px 35px;
        border-radius: 30px;
        text-decoration: none;
        font-weight: 700;
        display: inline-block;
        transition: var(--transition);
    }

    .btn-white:hover {
        background: var(--primary);
        color: #fff;
    }

    .hero-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(0, 0, 0, 0.5);
        color: #fff;
        border: none;
        padding: 12px 16px;
        cursor: pointer;
        font-size: 20px;
        border-radius: 50%;
        z-index: 10;
        transition: var(--transition);
    }

    .hero-btn:hover {
        background: var(--primary);
    }

    .hero-btn.prev {
        left: 20px;
    }

    .hero-btn.next {
        right: 20px;
    }

    /* --- FILTER BAR --- */
    .filter-bar {
        background: #fff;
        padding: 20px 0;
        border-bottom: 1px solid var(--border);
        position: sticky;
        top: 70px;
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
        gap: 10px;
        flex-wrap: wrap;
    }

    .tab {
        padding: 8px 20px;
        border-radius: 20px;
        border: 1px solid var(--border);
        cursor: pointer;
        font-weight: 600;
        font-size: 0.9rem;
        transition: var(--transition);
    }

    .tab.active,
    .tab:hover {
        background: var(--dark);
        color: #fff;
        border-color: var(--dark);
    }

    .sort-options select {
        padding: 8px 15px;
        border-radius: 10px;
        border: 1px solid var(--border);
        outline: none;
        font-weight: 600;
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
        border-radius: 20px;
        padding: 20px;
        border: 1px solid transparent;
        transition: var(--transition);
        display: flex;
        flex-direction: column;
        position: relative;
    }

    .p-card:hover {
        border-color: var(--border);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.05);
        transform: translateY(-5px);
    }

    .p-img {
        width: 100%;
        height: 220px;
        object-fit: contain;
        margin-bottom: 15px;
    }

    .p-badge {
        position: absolute;
        top: 15px;
        left: 15px;
        background: #d93838;
        color: #fff;
        font-size: 11px;
        padding: 4px 10px;
        border-radius: 5px;
        font-weight: 700;
        z-index: 2;
    }

    .p-info {
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }

    .p-name {
        font-weight: 700;
        font-size: 1.1rem;
        margin-bottom: 8px;
        min-height: 44px;
    }

    .p-price-row {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 15px;
    }

    .p-new {
        font-weight: 800;
        font-size: 1.2rem;
        color: var(--primary);
    }

    .p-old {
        text-decoration: line-through;
        color: #999;
        font-size: 0.85rem;
    }

    .p-stats-wrap {
        margin-bottom: 15px;
        margin-top: auto;
        font-size: 0.8rem;
        color: #666;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .btn-add {
        width: 100%;
        padding: 12px;
        border-radius: 12px;
        border: none;
        background: var(--dark);
        color: #fff;
        font-weight: 700;
        cursor: pointer;
        transition: 0.3s;
        text-align: center;
        text-decoration: none;
        display: inline-block;
    }

    .btn-add:hover {
        background: var(--primary);
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
        padding: 12px;
        border-radius: 10px;
        border: 1px solid var(--border);
        margin-bottom: 10px;
    }

    .btn-sub {
        width: 100%;
        padding: 12px;
        border-radius: 10px;
        background: var(--primary);
        color: #fff;
        border: none;
        font-weight: 700;
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
            font-size: 2.5rem;
        }
    }
    </style>
</head>

<body>
    < section class="hero-section">
        <?php if (!empty($products)): ?>
        <?php foreach(array_slice($products, 0, 2) as $i => $p): 
                // Tạo ảnh mẫu động theo tên sản phẩm để tránh lỗi hiển thị do thiếu cột ảnh
                $placeholder_img = "https://placehold.co/1200x600/1d1d1f/ffffff?text=" . urlencode($p['name']);
            ?>
        <div class="slide <?php echo $i == 0 ? 'active' : ''; ?>">
            <img src="<?php echo $placeholder_img; ?>" alt="<?php echo htmlspecialchars($p['name']); ?>">
            <div class="slide-content">
                <h1><?php echo htmlspecialchars($p['name']); ?></h1>
                <p><?php echo htmlspecialchars($p['short_description']); ?></p><br>
                <a href="/assignment/product/<?php echo $p['slug']; ?>" class="btn-white">
                    Mua ngay
                </a>
            </div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
        <button class="hero-btn prev">❮</button>
        <button class="hero-btn next">❯</button>
        </section>

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
                    $cat_slug = parseCategorySlug($p['category_name']);
                    // Tạo ảnh mẫu động cho danh sách sản phẩm
                    $placeholder_img = "https://placehold.co/300x300/f5f5f7/1d1d1f?text=" . urlencode($p['name']);
                    
                    // Tính số sao hiển thị từ rating_avg thực tế
                    $rating_avg = (float)$p['rating_avg'];
                    $stars = ($rating_avg > 0) ? round($rating_avg) : 5;
                ?>
                <div class="p-card" data-cat="<?php echo $cat_slug; ?>" data-price="<?php echo $p['price']; ?>"
                    data-name="<?php echo strtolower($p['name']); ?>">
                    <?php if ((int)$p['discount'] > 0): ?>
                    <span class="p-badge">-<?php echo (int)$p['discount']; ?>%</span>
                    <?php endif; ?>

                    <img src="<?php echo $placeholder_img; ?>" alt="<?php echo htmlspecialchars($p['name']); ?>"
                        class="p-img">

                    <div class="p-info">
                        <div style="color:#ffcc00; font-size:0.7rem; margin-bottom:5px;">
                            <?php echo str_repeat('<i class="fas fa-star"></i>', $stars); ?>
                        </div>
                        <h3 class="p-name"><?php echo htmlspecialchars($p['name']); ?></h3>
                        <div class="p-price-row">
                            <span class="p-new"><?php echo formatVND($p['price']); ?></span>
                            <span class="p-old"><?php echo formatVND($p['old_price']); ?></span>
                        </div>

                        <div class="p-stats-wrap">
                            <span><i class="far fa-eye"></i> <?php echo number_format($p['view_count']); ?> lượt
                                xem</span>
                            <span style="color: var(--primary); font-weight: 700;">Hot</span>
                        </div>

                        <a href="/product/<?php echo urlencode($p['slug']); ?>" class="btn-add">
                            Chi tiết sản phẩm
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php else: ?>
                <p style="text-align: center; grid-column: 1/-1; padding: 40px 0; color: #666;">Không tìm thấy sản phẩm
                    nào.
                </p>
                <?php endif; ?>

            </div>
        </main>
        <script>
        // 1. Logic Slider điều khiển bằng tay & Tự động chạy tuần hoàn
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

            // Tự động chuyển đổi slide sau 5 giây
            setInterval(() => {
                sliderIndex = (sliderIndex + 1) % slides.length;
                showSlide(sliderIndex);
            }, 5000);
        }

        // 2. Logic Lọc theo danh mục sản phẩm (Category)
        function filterCat(cat, el) {
            document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
            el.classList.add('active');

            const cards = document.querySelectorAll('.p-card');
            cards.forEach(card => {
                if (cat === 'all' || card.dataset.cat === cat) {
                    card.style.display = 'flex';
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