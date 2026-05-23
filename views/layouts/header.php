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
    /* --- CSS HỆ THỐNG VÀ BIẾN TOÀN CỤC --- */
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
            <a href="/assignment/products" class="logo">TECH<span>STORE</span></a>

            <div class="search-bar">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Tìm sản phẩm, thương hiệu...">
            </div>

            <div class="nav-tools">
                <!-- FIX CHỖ NÀY -->
                <a href="/assignment/login">
                    <i class="far fa-user cursor-pointer"></i>
                </a>

                <i class="far fa-heart cursor-pointer"></i>

                <div class="cart-icon">
                    <i class="fas fa-shopping-bag"></i>
                    <span class="cart-badge">2</span>
                </div>
            </div>
        </div>
    </header>
</body>