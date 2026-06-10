<!DOCTYPE html>
<html lang="vi">
<?php 
if (session_status() === PHP_SESSION_NONE && !headers_sent()) {
    session_start();
}

// Lấy thông tin user từ session
$currentUser = $_SESSION['user'] ?? null;
$avatarUrl = '';

if ($currentUser && !empty($currentUser['avatar_url'])) {
    $avatarUrl = $currentUser['avatar_url'];
    
    // Kiểm tra nếu không phải link tuyệt đối (http/https)
    if (strpos($avatarUrl, 'http') !== 0) {
        $cleanPath = ltrim($avatarUrl, '/'); 
        if (strpos($cleanPath, 'public/uploads/') === false) {
            $avatarUrl = '/assignment/public/uploads/' . $cleanPath;
        } else {
            if (strpos($cleanPath, 'assignment/') !== 0) {
                $avatarUrl = '/assignment/' . $cleanPath;
            } else {
                $avatarUrl = '/' . $cleanPath;
            }
        }
    }
}

// Giả định số lượng thông báo hệ thống
$systemNotificationsCount = 5; 
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechStore | Hệ thống quản trị</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
    /* --- CSS HỆ THỐNG VÀ BIẾN TOÀN CỤC --- */
    :root {
        --primary: #4f46e5;
        /* Màu chính Indigo */
        --primary-hover: #4338ca;
        --black: #0f172a;
        --dark: #1e293b;
        --gray-bg: #f8fafc;
        --border: #e2e8f0;
        --transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: var(--dark);
        background: #f1f5f9;
        scroll-behavior: smooth;
    }

    .container {
        max-width: 1440px;
        /* Tối ưu độ rộng cho màn hình quản trị */
        margin: 0 auto;
        padding: 0 24px;
    }

    /* --- CSS CHO HEADER --- */
    header {
        position: fixed;
        width: 100%;
        top: 0;
        z-index: 1000;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(12px);
        border-bottom: 1px solid var(--border);
    }

    .header-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        height: 65px;
    }

    .logo-section {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .logo {
        font-weight: 800;
        font-size: 1.3rem;
        text-decoration: none;
        color: var(--black);
        letter-spacing: -0.5px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .logo span {
        color: var(--primary);
    }

    .logo-badge {
        font-size: 10px;
        background: #fee2e2;
        color: #ef4444;
        padding: 2px 8px;
        border-radius: 6px;
        font-weight: 700;
        text-transform: uppercase;
    }

    /* --- MENU QUẢN LÝ NHANH --- */
    .admin-menu-links {
        display: flex;
        gap: 8px;
        margin-left: 20px;
        padding-left: 20px;
        border-left: 1px solid var(--border);
    }

    .admin-menu-item {
        display: flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        color: #475569;
        font-size: 0.88rem;
        font-weight: 600;
        padding: 8px 12px;
        border-radius: 8px;
        transition: var(--transition);
    }

    .admin-menu-item:hover {
        color: var(--primary);
        background: var(--gray-bg);
    }

    .admin-menu-item i {
        font-size: 1rem;
        color: #64748b;
        transition: var(--transition);
    }

    .admin-menu-item:hover i {
        color: var(--primary);
    }

    /* --- THANH TÌM KIẾM --- */
    .search-bar {
        position: relative;
        flex: 0 1 320px;
        /* Điều chỉnh kích thước co giãn linh hoạt */
        margin: 0 20px;
    }

    .search-bar input {
        width: 100%;
        padding: 8px 15px 8px 38px;
        border-radius: 8px;
        border: 1px solid var(--border);
        background: var(--gray-bg);
        outline: none;
        transition: var(--transition);
        font-size: 0.85rem;
    }

    .search-bar input:focus {
        background: #fff;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15);
    }

    .search-bar i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #64748b;
        font-size: 0.85rem;
    }

    /* --- CÁC CÔNG CỤ ĐIỀU HƯỚNG --- */
    .nav-tools {
        display: flex;
        gap: 15px;
        align-items: center;
    }

    .nav-link-icon {
        color: #64748b;
        text-decoration: none;
        transition: var(--transition);
        display: flex;
        align-items: center;
        padding: 8px;
        border-radius: 8px;
    }

    .nav-link-icon:hover {
        color: var(--primary);
        background: var(--gray-bg);
    }

    /* TRÌNH ĐIỀU KHIỂN AVATAR VÀ DROPDOWN MENU */
    .user-menu-container {
        position: relative;
        display: inline-block;
    }

    .user-menu-trigger {
        display: flex;
        align-items: center;
        cursor: pointer;
        gap: 10px;
        padding: 4px 8px;
        border-radius: 8px;
        transition: var(--transition);
    }

    .user-menu-trigger:hover {
        background: var(--gray-bg);
    }

    .user-avatar-header {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        object-fit: cover;
        border: 1.5px solid var(--border);
    }

    .user-meta-info {
        display: flex;
        flex-direction: column;
        text-align: left;
    }

    .user-meta-name {
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--dark);
    }

    .user-meta-role {
        font-size: 0.7rem;
        color: #64748b;
        text-transform: capitalize;
    }

    /* Khung Dropdown xổ xuống */
    .user-dropdown {
        position: absolute;
        top: calc(100% + 10px);
        right: 0;
        width: 240px;
        background: #ffffff;
        border: 1px solid var(--border);
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        padding: 8px 0;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-8px);
        transition: opacity 0.2s ease, transform 0.2s ease, visibility 0.2s;
        z-index: 1100;
    }

    .user-dropdown.show {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

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
        color: #64748b;
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

    .user-dropdown-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 16px;
        font-size: 0.85rem;
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
        font-size: 0.95rem;
        width: 16px;
        text-align: center;
        color: #64748b;
    }

    .user-dropdown-item.logout-item {
        color: #ef4444;
    }

    .user-dropdown-item.logout-item:hover {
        background-color: #fef2f2;
        color: #ef4444;
    }

    .user-dropdown-item.logout-item i {
        color: #ef4444;
    }

    /* CHUÔNG THÔNG BÁO */
    .notification-icon {
        position: relative;
    }

    .notification-badge {
        position: absolute;
        top: 2px;
        right: 2px;
        background: #ef4444;
        color: #fff;
        font-size: 8px;
        width: 15px;
        height: 15px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        border: 2px solid #fff;
    }

    .cursor-pointer {
        cursor: pointer;
    }

    /* --- ĐÁP ỨNG THIẾT BỊ (RESPONSIVE) --- */
    @media (max-width: 1200px) {
        .admin-menu-item span {
            display: none;
            /* Thu gọn chữ trên màn hình nhỏ, chỉ giữ lại icon */
        }

        .admin-menu-links {
            gap: 4px;
        }
    }

    @media (max-width: 992px) {
        .search-bar {
            display: none;
            /* Ẩn bớt thanh tìm kiếm trên thiết bị máy tính bảng */
        }

        .user-meta-info {
            display: none;
        }
    }

    @media (max-width: 768px) {
        .admin-menu-links {
            display: none;
        }
    }
    </style>
</head>

<body>
    <header>
        <div class="container header-top">
            <div class="logo-section">
                <a href="/assignment/admin/dashboard" class="logo">
                    TECH<span>STORE</span>
                    <span class="logo-badge">Admin</span>
                </a>

                <!-- MENU QUẢN LÝ TRỰC TIẾP TRÊN HEADER -->
                <nav class="admin-menu-links">
                    <!-- 1. Quản lý Sản phẩm -->
                    <a href="/assignment/admin/products" class="admin-menu-item" title="Quản lý Sản phẩm">
                        <i class="fas fa-box"></i>
                        <span>Sản phẩm</span>
                    </a>

                    <!-- 2. Quản lý Đơn hàng -->
                    <a href="/assignment/admin/orders" class="admin-menu-item" title="Quản lý Đơn hàng">
                        <i class="fas fa-file-invoice-dollar"></i>
                        <span>Đơn hàng</span>
                    </a>

                    <!-- 3. Quản lý Người dùng -->
                    <a href="/assignment/admin/users" class="admin-menu-item" title="Quản lý Người dùng">
                        <i class="fas fa-users"></i>
                        <span>Người dùng</span>
                    </a>
                </nav>
            </div>

            <!-- Thanh tìm kiếm nội dung quản trị -->
            <div class="search-bar">
                <form action="/assignment/admin/search" method="GET" style="width: 100%; position: relative;">
                    <i class="fas fa-search"></i>
                    <input type="text" name="q" id="adminSearchInput" placeholder="Tìm kiếm nhanh hệ thống..."
                        value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
                </form>
            </div>

            <div class="nav-tools">
                <!-- Nút chuyển hướng nhanh về trang mua hàng dành cho khách -->
                <a href="/assignment" class="nav-link-icon" title="Xem trang chủ bán hàng" target="_blank">
                    <i class="fas fa-globe" style="font-size: 1.1rem;"></i>
                </a>

                <!-- Chuông thông báo hệ thống -->
                <div class="notification-icon">
                    <a href="/assignment/admin/notifications" class="nav-link-icon" title="Thông báo hệ thống">
                        <i class="far fa-bell" style="font-size: 1.1rem;"></i>
                    </a>
                    <?php if ($systemNotificationsCount > 0): ?>
                    <span class="notification-badge"><?= $systemNotificationsCount ?></span>
                    <?php endif; ?>
                </div>

                <!-- MENU TÀI KHOẢN ADMIN -->
                <?php if ($currentUser): ?>
                <div class="user-menu-container">
                    <div class="user-menu-trigger" onclick="toggleUserDropdown(event)" title="Tài khoản quản lý">
                        <?php if (!empty($avatarUrl)): ?>
                        <img src="<?= htmlspecialchars($avatarUrl) ?>" alt="Admin Avatar" class="user-avatar-header">
                        <?php else: ?>
                        <img src="https://www.gravatar.com/avatar/?d=mp" alt="Admin Avatar" class="user-avatar-header">
                        <?php endif; ?>

                        <div class="user-meta-info">
                            <span
                                class="user-meta-name"><?= htmlspecialchars($currentUser['name'] ?? 'Quản lý') ?></span>
                            <span class="user-meta-role"><?= htmlspecialchars($currentUser['role'] ?? 'Staff') ?></span>
                        </div>
                    </div>

                    <!-- Dropdown Menu -->
                    <div class="user-dropdown" id="userDropdown">
                        <div class="user-dropdown-header">
                            <span
                                class="user-dropdown-name"><?= htmlspecialchars($currentUser['name'] ?? 'Quản lý') ?></span>
                            <span
                                class="user-dropdown-email"><?= htmlspecialchars($currentUser['email'] ?? '') ?></span>
                        </div>

                        <div class="user-dropdown-divider"></div>

                        <a href="/assignment/account" class="user-dropdown-item">
                            <i class="far fa-user"></i> Hồ sơ cá nhân
                        </a>

                        <a href="/assignment/admin/settings" class="user-dropdown-item">
                            <i class="fas fa-cog"></i> Cài đặt hệ thống
                        </a>

                        <a href="/assignment" class="user-dropdown-item">
                            <i class="fas fa-store"></i> Quay lại cửa hàng
                        </a>

                        <div class="user-dropdown-divider"></div>

                        <!-- Đăng xuất -->
                        <a href="/assignment/login" class="user-dropdown-item logout-item">
                            <i class="fas fa-sign-out-alt"></i> Đăng xuất
                        </a>
                    </div>
                </div>
                <?php else: ?>
                <a href="/assignment/login" class="nav-link-icon" title="Đăng nhập">
                    <i class="far fa-user cursor-pointer"></i>
                </a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- Chừa khoảng trống bên dưới header để tránh đè nội dung chính -->
    <div style="margin-top: 65px;"></div>

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