<!DOCTYPE html>
<html lang="vi">
<?php 
 
if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
if (!isset($_SESSION['user'])){
}
$cartLength = count($_SESSION['cart']);
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechStore | Hệ thống bán lẻ công nghệ cao cấp</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
    /* --- CSS HỆ THỐNG VÀ BIẾN TOÀN CỤC --- */
    :root {
        --primary: #0071e3;
        --primary-hover: #0077ed;
        --black: #000000;
        --dark: #1d1d1f;
        --gray-bg: #f5f5f7;
        --border: #e5e5e7;
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
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

    /* --- CSS CHO HEADER --- */
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

    .nav-link-icon {
        color: var(--dark);
        text-decoration: none;
        transition: var(--transition);
        display: flex;
        align-items: center;
    }

    .nav-link-icon:hover {
        color: var(--primary);
        transform: scale(1.05);
    }

    .cart-icon {
        position: relative;
        cursor: pointer;
        display: flex;
        align-items: center;
    }

    .cart-badge {
        position: absolute;
        top: -6px;
        right: -10px;
        background: var(--primary);
        color: #fff;
        font-size: 9px;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        box-shadow: 0 2px 8px rgba(0, 113, 227, 0.2);
    }

    .cursor-pointer {
        cursor: pointer;
    }

    @media (max-width: 992px) {
        .search-bar {
            flex: 1;
            margin: 0 20px;
        }
    }

    @media (max-width: 600px) {
        .search-bar {
            display: none;
        }
    }
    </style>
</head>

<body>
    <header>
        <div class="container header-top">
            <a href="/assignment" class="logo">TECH<span>STORE</span></a>

            <div class="search-bar">
                <form action="/assignment/product/search" method="GET" style="width: 100%; position: relative;">
                    <i class="fas fa-search"
                        style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #999;"></i>
                    <input type="text" name="q" id="searchInput" placeholder="Tìm sản phẩm, thương hiệu..."
                        value="<?= htmlspecialchars($_GET['q'] ?? '') ?>"
                        style="width: 100%; padding: 10px 15px 10px 40px; border-radius: 20px; border: 1px solid var(--border); background: var(--gray-bg); outline: none;">
                </form>
            </div>

            <div class="nav-tools">
                <!-- 1. ICON DANH MỤC SẢN PHẨM MỚI BỔ SUNG -->
                <a href="/assignment/categories" class="nav-link-icon" title="Danh mục sản phẩm">
                    <i class="fas fa-th-large cursor-pointer"></i>
                </a>

                <a href="/assignment/login" class="nav-link-icon" title="Tài khoản">
                    <i class="far fa-user cursor-pointer"></i>
                </a>

                <a href="#" class="nav-link-icon" title="Yêu thích">
                    <i class="far fa-heart cursor-pointer"></i>
                </a>

                <!-- 2. GIỎ HÀNG CẬP NHẬT BADGE ĐỘNG THEO SESSION -->
                <div class="cart-icon">
                    <a href="/assignment/order" class="nav-link-icon" title="Giỏ hàng">
                        <i class="fas fa-shopping-bag"></i>
                    </a>
                    <?php if ($cartLength > 0): ?>
                    <span class="cart-badge"><?php echo $cartLength; ?></span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>
</body>

</html>