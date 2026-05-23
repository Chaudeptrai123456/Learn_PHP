<?php
$slides = [
    ['img' => 'https://www.apple.com/v/iphone-15-pro/c/images/overview/welcome/hero__f19ebubotv6u_large.jpg', 'title' => 'iPhone 15 Pro', 'sub' => 'Titanium mạnh mẽ.'],
    ['img' => 'https://www.apple.com/v/macbook-pro/ak/images/overview/hero/hero_main__cl6bh9at6m6u_large.jpg', 'title' => 'MacBook Pro M3', 'sub' => 'Đỉnh cao đồ họa.']
];
function formatVND($n) { 
    return number_format($n, 0, ',', '.') . ' ₫'; 
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
    .btn-add {
        display: inline-block;
        padding: 10px 16px;
        background: #0071e3;
        color: #fff;
        text-decoration: none;
        border-radius: 6px;
    }

    .hero-section {
        position: relative;
        overflow: hidden;
    }

    .slide {
        display: none;
        position: relative;
    }

    .slide.active {
        display: block;
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
    }

    .hero-btn.prev {
        left: 20px;
    }

    .hero-btn.next {
        right: 20px;
    }

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
    }

    .cart-badge {
        position: absolute;
        left: -10px;
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
    }

    .slide img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: 0.7;
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
        background: #000;
        color: #fff;
        font-size: 10px;
        padding: 4px 10px;
        border-radius: 5px;
        font-weight: 700;
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

    .p-sold-wrap {
        margin-bottom: 15px;
    }

    .p-sold-info {
        display: flex;
        justify-content: space-between;
        font-size: 0.75rem;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .p-bar {
        height: 6px;
        background: #f0f0f0;
        border-radius: 10px;
        overflow: hidden;
    }

    .p-bar-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--primary), #5ac8fa);
        border-radius: 10px;
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

    <section class="hero-section">
        <?php foreach(array_slice($products, 0, 2) as $i => $p): ?>
        <div class="slide <?php echo $i == 0 ? 'active' : ''; ?>">
            <img src="<?php echo $p['image']; ?>" alt="<?php echo $p['name']; ?>">
            <div class="slide-content">
                <h1><?php echo $p['name']; ?></h1>
                <p><?php echo $p['short_desc']; ?></p><br>
                <a href="/assignment/product/<?php echo $p['slug']; ?>" class="btn-white">
                    Mua ngay
                </a>
            </div>
        </div>
        <?php endforeach; ?>
        <button class="hero-btn prev">❮</button>
        <button class="hero-btn next">❯</button>
    </section>
    <div class="filter-bar">
        <div class="container filter-flex">
            <div class="category-tabs">
                <div class="tab active" onclick="filterCat('all', this)">Tất cả</div>
                <div class="tab" onclick="filterCat('phone', this)">iPhone</div>
                <div class="tab" onclick="filterCat('laptop', this)">MacBook</div>
                <div class="tab" onclick="filterCat('watch', this)">Watch</div>
                <div class="tab" onclick="filterCat('audio', this)">Âm thanh</div>
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
    <main class="container products-section" id="products">
        <div class="p-grid" id="productGrid">
            <?php foreach($products as $p): 
            $percent = ($p['sold'] / $p['stock']) * 100;
        ?>
            <div class="p-card" data-cat="<?php echo $p['cat']; ?>" data-price="<?php echo $p['price']; ?>"
                data-name="<?php echo strtolower($p['name']); ?>">
                <span class="p-badge"><?php echo $p['badge']; ?></span>
                <img src="<?php echo $p['image']; ?>" alt="Product" class="p-img">
                <div class="p-info">
                    <div style="color:#ffcc00; font-size:0.7rem; margin-bottom:5px;">
                        <?php echo str_repeat('<i class="fas fa-star"></i>', $p['rating']); ?>
                    </div>
                    <h3 class="p-name"><?php echo $p['name']; ?></h3>
                    <div class="p-price-row">
                        <span class="p-new"><?php echo formatVND($p['price']); ?></span>
                        <span class="p-old"><?php echo formatVND($p['old_price']); ?></span>
                    </div>
                    <div class="p-sold-wrap">
                        <div class="p-sold-info">
                            <span>Đã bán <?php echo $p['sold']; ?></span>
                            <span style="color:var(--primary)">Hot</span>
                        </div>
                        <div class="p-bar">
                            <div class="p-bar-fill" style="width:<?php echo $percent; ?>%"></div>
                        </div>
                    </div>
                    <a href="/assignment/product/<?php echo $p['slug']; ?>" class="btn-add">
                        Thêm vào giỏ hàng
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </main>

    <script>
    const slides = document.querySelectorAll('.slide');
    const prevBtn = document.querySelector('.prev');
    const nextBtn = document.querySelector('.next');

    let index = 0;

    function showSlide(i) {
        slides.forEach(s => s.classList.remove('active'));
        slides[i].classList.add('active');
    }

    nextBtn.onclick = () => {
        index = (index + 1) % slides.length;
        showSlide(index);
    };

    prevBtn.onclick = () => {
        index = (index - 1 + slides.length) % slides.length;
        showSlide(index);
    };
    </script>
    <script>
    // 1. Logic Slider
    let current = 0;
    const slides = document.querySelectorAll('.slide');
    setInterval(() => {
        slides[current].classList.remove('active');
        current = (current + 1) % slides.length;
        slides[current].classList.add('active');
    }, 5000);

    // 2. Logic Lọc Category
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

    // 3. Logic Tìm kiếm
    document.getElementById('searchInput').addEventListener('input', function(e) {
        const val = e.target.value.toLowerCase();
        const cards = document.querySelectorAll('.p-card');
        cards.forEach(card => {
            if (card.dataset.name.includes(val)) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    });

    // 4. Logic Sắp xếp giá
    function sortProducts() {
        const grid = document.getElementById('productGrid');
        const cards = Array.from(grid.getElementsByClassName('p-card'));
        const val = document.getElementById('sortPrice').value;

        if (val === 'default') return;

        cards.sort((a, b) => {
            const priceA = parseInt(a.dataset.price);
            const priceB = parseInt(b.dataset.price);
            return val === 'low' ? priceA - priceB : priceB - priceA;
        });

        grid.innerHTML = "";
        cards.forEach(card => grid.appendChild(card));
    }
    </script>

</body>

</html>