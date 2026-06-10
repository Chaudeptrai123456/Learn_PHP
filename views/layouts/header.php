<!DOCTYPE html>
<html lang="vi">
<?php 
if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$cartLength = count($_SESSION['cart']);

// Lấy thông tin user từ session
$currentUser = $_SESSION['user'] ?? null;
$avatarUrl = '';

if ($currentUser && !empty($currentUser['avatar_url'])) {
    $avatarUrl = $currentUser['avatar_url'];
    
    // Kiểm tra nếu không phải link tuyệt đối (http/https)
    if (strpos($avatarUrl, 'http') !== 0) {
        // Chuẩn hóa đường dẫn: Loại bỏ các ký tự gạch chéo dư thừa ở đầu
        $cleanPath = ltrim($avatarUrl, '/'); // Chuyển '/avatar/...' thành 'avatar/...'
        
        // Nếu đường dẫn chưa đi qua public/uploads, ta thêm tiền tố chuẩn vào
        if (strpos($cleanPath, 'public/uploads/') === false) {
            $avatarUrl = '/assignment/public/uploads/' . $cleanPath;
        } else {
            // Nếu đã có sẵn public/uploads nhưng chưa có base /assignment/
            if (strpos($cleanPath, 'assignment/') !== 0) {
                $avatarUrl = '/assignment/' . $cleanPath;
            } else {
                $avatarUrl = '/' . $cleanPath;
            }
        }
    }
}
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

    /* CSS CHO TRÌNH ĐIỀU KHIỂN AVATAR VÀ DROPDOWN MENU */
    .user-menu-container {
        position: relative;
        display: inline-block;
    }

    .user-menu-trigger {
        display: flex;
        align-items: center;
        cursor: pointer;
    }

    .user-avatar-header {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        object-fit: cover;
        border: 1.5px solid var(--border);
        transition: var(--transition);
    }

    .user-avatar-header:hover {
        border-color: var(--primary);
        transform: scale(1.08);
    }

    /* Khung Dropdown xổ xuống */
    .user-dropdown {
        position: absolute;
        top: calc(100% + 15px);
        right: 0;
        width: 220px;
        background: #ffffff;
        border: 1px solid var(--border);
        border-radius: 14px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        padding: 8px 0;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px);
        transition: opacity 0.25s ease, transform 0.25s ease, visibility 0.25s;
        z-index: 1100;
    }

    /* Trạng thái hiển thị dropdown */
    .user-dropdown.show {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    /* Thông tin tiêu đề dropdown */
    .user-dropdown-header {
        padding: 12px 16px;
        display: flex;
        flex-direction: column;
        text-align: left;
    }

    .user-dropdown-name {
        font-weight: 700;
        font-size: 0.9rem;
        color: var(--dark);
    }

    .user-dropdown-email {
        font-size: 0.78rem;
        color: #86868b;
        margin-top: 2px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .user-dropdown-divider {
        height: 1px;
        background-color: var(--border);
        margin: 6px 0;
    }

    /* Các dòng lựa chọn bên trong dropdown */
    .user-dropdown-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 16px;
        font-size: 0.88rem;
        color: var(--dark);
        text-decoration: none;
        transition: var(--transition);
        font-weight: 500;
        text-align: left;
    }

    .user-dropdown-item:hover {
        background-color: var(--gray-bg);
        color: var(--primary);
    }

    .user-dropdown-item i {
        font-size: 1rem;
        width: 16px;
        text-align: center;
        color: #515154;
    }

    /* Dropdown lựa chọn Đăng xuất */
    .user-dropdown-item.logout-item {
        color: #ff3b30;
    }

    .user-dropdown-item.logout-item:hover {
        background-color: rgba(255, 59, 48, 0.05);
        color: #ff3b30;
    }

    .user-dropdown-item.logout-item i {
        color: #ff3b30;
    }

    /* --- CÁC THÀNH PHẦN KHÁC --- */
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

                <!-- KIỂM TRA ĐĂNG NHẬP ĐỂ HIỂN THỊ DROPDOWN AVATAR HOẶC ICON USER MẶC ĐỊNH -->
                <?php if ($currentUser): ?>
                <div class="user-menu-container">
                    <div class="user-menu-trigger" onclick="toggleUserDropdown(event)"
                        title="Tài khoản: <?= htmlspecialchars($currentUser['name'] ?? 'User') ?>">
                        <?php if (!empty($avatarUrl)): ?>
                        <img src="<?= htmlspecialchars($avatarUrl) ?>" alt="User Avatar" class="user-avatar-header">
                        <?php else: ?>
                        <i class="far fa-user cursor-pointer"></i>
                        <?php endif; ?>
                    </div>

                    <!-- Bảng menu xổ xuống (Dropdown Menu) -->
                    <div class="user-dropdown" id="userDropdown">
                        <div class="user-dropdown-header">
                            <span
                                class="user-dropdown-name"><?= htmlspecialchars($currentUser['name'] ?? 'Khách hàng') ?></span>
                            <span
                                class="user-dropdown-email"><?= htmlspecialchars($currentUser['email'] ?? '') ?></span>
                        </div>
                        <div class="user-dropdown-divider"></div>

                        <!-- Trang cá nhân -->
                        <a href="/assignment/account" class="user-dropdown-item">
                            <i class="far fa-user"></i> Trang cá nhân
                        </a>

                        <!-- Lịch sử đơn hàng (Trong dropdown) -->
                        <a href="/assignment/order/history" class="user-dropdown-item">
                            <i class="fas fa-receipt"></i> Lịch sử đơn hàng
                        </a>

                        <!-- Hiển thị liên kết trang quản trị nếu là Admin hoặc Staff -->
                        <?php if (in_array($currentUser['role'] ?? '', ['admin', 'staff'])): ?>
                        <a href="/assignment/admin" class="user-dropdown-item">
                            <i class="fas fa-user-shield"></i> Trang quản trị
                        </a>
                        <?php endif; ?>

                        <div class="user-dropdown-divider"></div>

                        <!-- Đăng xuất -->
                        <a href="/assignment/login" class="user-dropdown-item logout-item">
                            <i class="fas fa-sign-out-alt"></i> Đăng xuất
                        </a>
                    </div>
                </div>
                <?php else: ?>
                <a href="/assignment/login" class="nav-link-icon" title="Đăng nhập / Đăng ký">
                    <i class="far fa-user cursor-pointer"></i>
                </a>
                <?php endif; ?>

                <a href="#" class="nav-link-icon" title="Yêu thích">
                    <i class="far fa-heart cursor-pointer"></i>
                </a>

                <!-- ICON LỊCH SỬ ĐƠN HÀNG TRÊN THANH ĐIỀU HƯỚNG CHÍNH (MỚI THÊM) -->
                <a href="/assignment/order/history" class="nav-link-icon" title="Lịch sử đơn hàng">
                    <i class="fas fa-history cursor-pointer"></i>
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

    <script>
    function toggleUserDropdown(event) {
        event.stopPropagation();
        const dropdown = document.getElementById('userDropdown');
        if (dropdown) {
            dropdown.classList.toggle('show');
        }
    }

    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('userDropdown');
        const trigger = document.querySelector('.user-menu-trigger');

        if (dropdown && dropdown.classList.contains('show')) {
            if (!dropdown.contains(event.target) && !trigger.contains(event.target)) {
                dropdown.classList.remove('show');
            }
        }
    });
    </script>
</body>

</html>