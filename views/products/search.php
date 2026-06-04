<?php
$products = isset($products) ? $products : [];
$categories = isset($categories) ? $categories : [];

// 1. Bản đồ ánh xạ thương hiệu cố định để đồng nhất ID từ Cơ sở dữ liệu
$brand_map = [
    1 => 'Apple',
    2 => 'Samsung',
    3 => 'Dell'
];

// Danh sách thương hiệu cố định hiển thị trên Sidebar (Không thay đổi khi lọc)
$static_brands = ['Apple', 'Samsung', 'Dell'];

// Chuẩn hóa dữ liệu sản phẩm để đồng bộ hiển thị tên thương hiệu
foreach ($products as &$prod) {
    if (empty($prod['brand_name']) && isset($prod['brand_id'])) {
        $prod['brand_name'] = $brand_map[$prod['brand_id']] ?? 'Khác';
    }
}
unset($prod);

// Lấy tham số từ URL query
$url_search   = isset($_GET['search']) ? trim($_GET['search']) : (isset($_GET['keyword']) ? trim($_GET['keyword']) : '');
$url_category = isset($_GET['category']) ? trim($_GET['category']) : 'all';
$url_brand    = isset($_GET['brand']) ? trim($_GET['brand']) : 'all';
$url_price    = isset($_GET['price']) ? trim($_GET['price']) : 'all';
$url_sort     = isset($_GET['sort']) ? trim($_GET['sort']) : 'default';

if (!function_exists('formatVND')) {
    function formatVND($n) { 
        return number_format((float)$n, 0, ',', '.') . ' ₫'; 
    }
}

// Xác định tiêu đề danh mục hiện tại
$active_category_title = 'Tất cả sản phẩm';
foreach ($categories as $cat) {
    if (($cat['slug'] ?? '') === $url_category) {
        $active_category_title = $cat['name'] ?? 'Danh mục';
        break;
    }
}

// Nhãn hiển thị mức giá đang áp dụng
$price_labels = [
    'under_10m' => 'Dưới 10 triệu',
    '10m_25m'   => '10M - 25M',
    '25m_40m'   => '25M - 40M',
    'over_40m'  => 'Trên 40 triệu'
];
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Mục Công Nghệ | TechStore</title>
    <!-- Google Fonts & FontAwesome -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
    :root {
        --primary: #0071e3;
        --primary-hover: #0077ed;
        --black: #000000;
        --dark: #1d1d1f;
        --gray-bg: #f5f5f7;
        --border: #e5e5e7;
        --transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
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
        padding-top: 95px;
        -webkit-font-smoothing: antialiased;
    }

    .container {
        max-width: 1300px;
        margin: 0 auto;
        padding: 0 20px;
    }

    /* --- STICKY BLUR HEADER --- */
    header {
        position: fixed;
        width: 100%;
        top: 0;
        left: 0;
        z-index: 1000;
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border-bottom: 1px solid var(--border);
    }

    .header-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        height: 75px;
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
        flex: 0 1 420px;
        margin: 0 20px;
    }

    .search-bar input {
        width: 100%;
        padding: 11px 16px 11px 42px;
        border-radius: 22px;
        border: 1px solid var(--border);
        background: var(--gray-bg);
        outline: none;
        transition: var(--transition);
        font-family: inherit;
        font-size: 0.9rem;
    }

    .search-bar input:focus {
        background: #fff;
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(0, 113, 227, 0.15);
    }

    .search-bar i {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #86868b;
        font-size: 0.95rem;
    }

    /* --- LAYOUT CHÍNH --- */
    .catalog-layout {
        display: flex;
        gap: 40px;
        margin-top: 30px;
        margin-bottom: 80px;
    }

    /* --- SIDEBAR BỘ LỌC CỐ ĐỊNH --- */
    .sidebar {
        width: 280px;
        flex-shrink: 0;
        position: sticky;
        top: 105px;
        align-self: flex-start;
        max-height: calc(100vh - 130px);
        overflow-y: auto;
        padding-right: 6px;
    }

    .sidebar::-webkit-scrollbar {
        width: 5px;
    }

    .sidebar::-webkit-scrollbar-thumb {
        background: #d2d2d7;
        border-radius: 4px;
    }

    .filter-widget {
        background: #fff;
        border-radius: 16px;
        padding: 22px;
        border: 1px solid var(--border);
        margin-bottom: 20px;
    }

    .filter-header-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
    }

    .filter-title {
        font-size: 0.9rem;
        font-weight: 700;
        color: var(--black);
        letter-spacing: -0.1px;
    }

    .clear-filters-btn {
        font-size: 0.78rem;
        color: var(--primary);
        text-decoration: none;
        font-weight: 600;
        transition: var(--transition);
    }

    .clear-filters-btn:hover {
        color: var(--primary-hover);
    }

    /* Thẻ danh mục */
    .sidebar-categories {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .category-link {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 14px;
        border-radius: 10px;
        text-decoration: none;
        color: #515154;
        font-weight: 500;
        font-size: 0.88rem;
        transition: var(--transition);
        cursor: pointer;
    }

    .category-link:hover,
    .category-link.active {
        background: var(--gray-bg);
        color: var(--primary);
        font-weight: 600;
    }

    .category-link i {
        font-size: 0.75rem;
        opacity: 0;
        transform: translateX(-5px);
        transition: var(--transition);
    }

    .category-link.active i,
    .category-link:hover i {
        opacity: 1;
        transform: translateX(0);
    }

    /* Tùy chỉnh nút Radio Button cao cấp */
    .filter-options-group {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .control-label {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 0.88rem;
        font-weight: 500;
        color: #515154;
        cursor: pointer;
        user-select: none;
        transition: var(--transition);
    }

    .control-label:hover {
        color: var(--primary);
    }

    /* custom radio styling */
    .control-label input[type="radio"] {
        appearance: none;
        -webkit-appearance: none;
        width: 18px;
        height: 18px;
        border: 2px solid #d2d2d7;
        border-radius: 50%;
        outline: none;
        transition: var(--transition);
        display: grid;
        place-content: center;
        background: #fff;
        cursor: pointer;
    }

    .control-label input[type="radio"]::before {
        content: "";
        width: 8px;
        height: 8px;
        border-radius: 50%;
        transform: scale(0);
        transition: 0.15s transform ease-in-out;
        background-color: var(--primary);
    }

    .control-label input[type="radio"]:checked {
        border-color: var(--primary);
    }

    .control-label input[type="radio"]:checked::before {
        transform: scale(1);
    }

    /* --- VÙNG HIỂN THỊ SẢN PHẨM --- */
    .main-catalog {
        flex: 1;
    }

    .catalog-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 15px;
    }

    .catalog-title h1 {
        font-size: 1.65rem;
        font-weight: 800;
        letter-spacing: -0.5px;
    }

    .catalog-title p {
        font-size: 0.85rem;
        color: #86868b;
        margin-top: 4px;
    }

    .sort-select {
        padding: 10px 16px;
        border-radius: 12px;
        border: 1px solid var(--border);
        background: #fff;
        outline: none;
        font-weight: 600;
        font-size: 0.85rem;
        cursor: pointer;
        font-family: inherit;
        transition: var(--transition);
    }

    .sort-select:focus {
        border-color: var(--primary);
    }

    /* --- TAGS CHỈ THỊ BỘ LỌC ĐANG CHỌN --- */
    .active-tags-container {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 25px;
        align-items: center;
    }

    .tag-label {
        font-size: 0.8rem;
        color: #86868b;
        font-weight: 500;
    }

    .filter-tag {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: var(--gray-bg);
        border: 1px solid var(--border);
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--dark);
        cursor: pointer;
        transition: var(--transition);
    }

    .filter-tag:hover {
        background: #e8e8ed;
        border-color: #d2d2d7;
    }

    .filter-tag i {
        font-size: 0.7rem;
        color: #86868b;
        transition: var(--transition);
    }

    .filter-tag:hover i {
        color: var(--black);
    }

    /* --- LƯỚI SẢN PHẨM --- */
    .p-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 25px;
    }

    .p-card {
        background: #fff;
        border-radius: 16px;
        padding: 24px;
        border: 1px solid var(--border);
        transition: var(--transition);
        display: flex;
        flex-direction: column;
        position: relative;
    }

    .p-card:hover {
        border-color: rgba(0, 113, 227, 0.3);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.05);
        transform: translateY(-2px);
    }

    .p-badge {
        position: absolute;
        top: 15px;
        left: 15px;
        background: #e30000;
        color: #fff;
        font-size: 10px;
        padding: 4px 10px;
        border-radius: 6px;
        font-weight: 800;
        z-index: 2;
    }

    .p-img-wrap {
        width: 100%;
        height: 190px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
        overflow: hidden;
    }

    .p-img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        transition: var(--transition);
    }

    .p-card:hover .p-img {
        transform: scale(1.04);
    }

    .p-info {
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }

    .p-brand {
        font-size: 0.75rem;
        text-transform: uppercase;
        font-weight: 700;
        color: var(--primary);
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }

    .p-name {
        font-weight: 700;
        font-size: 0.95rem;
        color: var(--dark);
        margin-bottom: 8px;
        line-height: 1.4;
        min-height: 44px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .p-price-row {
        display: flex;
        align-items: baseline;
        gap: 8px;
        margin-bottom: 15px;
    }

    .p-new {
        font-weight: 800;
        font-size: 1.1rem;
        color: #e30000;
    }

    .p-old {
        text-decoration: line-through;
        color: #86868b;
        font-size: 0.8rem;
    }

    .p-stats-wrap {
        margin-bottom: 18px;
        margin-top: auto;
        font-size: 0.75rem;
        color: #86868b;
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
        transition: var(--transition);
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 0.85rem;
    }

    .btn-add:hover {
        background: var(--primary);
    }

    /* Trạng thái danh sách trống */
    .empty-state {
        grid-column: 1/-1;
        text-align: center;
        padding: 80px 20px;
        border: 1px dashed var(--border);
        border-radius: 16px;
        background: var(--gray-bg);
    }

    .empty-state i {
        font-size: 3rem;
        color: #b2b2b7;
        margin-bottom: 15px;
    }

    /* --- RESPONSIVE --- */
    @media (max-width: 992px) {
        body {
            padding-top: 140px;
        }

        .header-top {
            flex-direction: column;
            height: auto;
            padding: 15px 0;
            gap: 15px;
        }

        .search-bar {
            width: 100%;
            margin: 0;
            flex: none;
        }

        .catalog-layout {
            flex-direction: column;
        }

        .sidebar {
            width: 100%;
            position: static;
            max-height: none;
            overflow-y: visible;
        }
    }
    </style>
</head>

<body>
    <div class="container">
        <div class="catalog-layout">
            <aside class="sidebar">
                <div class="filter-widget" style="border: none; padding: 10px 0 20px; background: transparent;">
                    <div class="filter-header-container">
                        <span class="filter-title"
                            style="text-transform: uppercase; font-size: 0.8rem; letter-spacing: 1px; color: #86868b;">Bộ
                            lọc tìm kiếm</span>
                        <?php if ($url_category !== 'all' || $url_brand !== 'all' || $url_price !== 'all' || !empty($url_search)): ?>
                        <a href="/assignment/search/products" class="clear-filters-btn"><i class="fas fa-undo"></i>
                            Thiết lập lại</a>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="filter-widget">
                    <h3 class="filter-title" style="margin-bottom: 14px;">Danh mục sản phẩm</h3>
                    <div class="sidebar-categories" id="categoryFilterContainer">
                        <div class="category-link <?php echo ($url_category === 'all') ? 'active' : ''; ?>"
                            onclick="setFilter('category', 'all')">
                            <span>Tất cả sản phẩm</span>
                            <i class="fas fa-chevron-right"></i>
                        </div>
                        <?php foreach ($categories as $cat): ?>
                        <?php if (($cat['status'] ?? 0) == 1): 
                                $cat_slug = $cat['slug'] ?? ''; 
                            ?>
                        <div class="category-link <?php echo ($url_category === $cat_slug) ? 'active' : ''; ?>"
                            onclick="setFilter('category', '<?php echo htmlspecialchars($cat_slug); ?>')">
                            <span><?php echo htmlspecialchars($cat['name'] ?? ''); ?></span>
                            <i class="fas fa-chevron-right"></i>
                        </div>
                        <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- 2. Bộ lọc Hãng sản xuất (Luôn giữ cố định) -->
                <div class="filter-widget">
                    <h3 class="filter-title" style="margin-bottom: 14px;">Thương hiệu</h3>
                    <div class="filter-options-group" id="brandFilterContainer">
                        <label class="control-label">
                            <input type="radio" name="brand_filter" value="all"
                                <?php echo ($url_brand === 'all') ? 'checked' : ''; ?>
                                onclick="setFilter('brand', 'all')">
                            <span>Tất cả thương hiệu</span>
                        </label>
                        <?php foreach ($static_brands as $b): 
                            $b_slug = strtolower($b);
                        ?>
                        <label class="control-label">
                            <input type="radio" name="brand_filter" value="<?php echo htmlspecialchars($b_slug); ?>"
                                <?php echo ($url_brand === $b_slug) ? 'checked' : ''; ?>
                                onclick="setFilter('brand', '<?php echo htmlspecialchars($b_slug); ?>')">
                            <span><?php echo htmlspecialchars($b); ?></span>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- 3. Bộ lọc Khoảng giá bán -->
                <div class="filter-widget">
                    <h3 class="filter-title" style="margin-bottom: 14px;">Mức giá</h3>
                    <div class="filter-options-group">
                        <label class="control-label">
                            <input type="radio" name="price_filter" value="all"
                                <?php echo ($url_price === 'all') ? 'checked' : ''; ?>
                                onclick="setFilter('price', 'all')">
                            <span>Tất cả mức giá</span>
                        </label>
                        <label class="control-label">
                            <input type="radio" name="price_filter" value="under_10m"
                                <?php echo ($url_price === 'under_10m') ? 'checked' : ''; ?>
                                onclick="setFilter('price', 'under_10m')">
                            <span>Dưới 10 triệu</span>
                        </label>
                        <label class="control-label">
                            <input type="radio" name="price_filter" value="10m_25m"
                                <?php echo ($url_price === '10m_25m') ? 'checked' : ''; ?>
                                onclick="setFilter('price', '10m_25m')">
                            <span>10 triệu - 25 triệu</span>
                        </label>
                        <label class="control-label">
                            <input type="radio" name="price_filter" value="25m_40m"
                                <?php echo ($url_price === '25m_40m') ? 'checked' : ''; ?>
                                onclick="setFilter('price', '25m_40m')">
                            <span>25 triệu - 40 triệu</span>
                        </label>
                        <label class="control-label">
                            <input type="radio" name="price_filter" value="over_40m"
                                <?php echo ($url_price === 'over_40m') ? 'checked' : ''; ?>
                                onclick="setFilter('price', 'over_40m')">
                            <span>Trên 40 triệu</span>
                        </label>
                    </div>
                </div>
            </aside>

            <!-- KHU VỰC HIỂN THỊ SẢN PHẨM -->
            <section class="main-catalog">
                <div class="catalog-header">
                    <div class="catalog-title">
                        <h1><?php echo htmlspecialchars($active_category_title); ?></h1>
                        <p>Tìm thấy <span
                                style="font-weight:700; color:var(--dark);"><?php echo count($products); ?></span> sản
                            phẩm</p>
                    </div>
                    <select id="sortPrice" onchange="setFilter('sort', this.value)" class="sort-select">
                        <option value="default" <?php echo ($url_sort === 'default') ? 'selected' : ''; ?>>Mặc định
                        </option>
                        <option value="low" <?php echo ($url_sort === 'low') ? 'selected' : ''; ?>>Giá thấp đến cao
                        </option>
                        <option value="high" <?php echo ($url_sort === 'high') ? 'selected' : ''; ?>>Giá cao đến thấp
                        </option>
                    </select>
                </div>

                <!-- HIỂN THỊ NHÃN BỘ LỌC ĐANG HOẠT ĐỘNG (UX NHẤN ĐỂ XÓA) -->
                <?php if ($url_category !== 'all' || $url_brand !== 'all' || $url_price !== 'all' || !empty($url_search)): ?>
                <div class="active-tags-container">
                    <span class="tag-label">Đang chọn:</span>

                    <?php if (!empty($url_search)): ?>
                    <div class="filter-tag" onclick="setFilter('search', '')">
                        <span>Tìm kiếm: "<?php echo htmlspecialchars($url_search); ?>"</span>
                        <i class="fas fa-times"></i>
                    </div>
                    <?php endif; ?>

                    <?php if ($url_category !== 'all'): ?>
                    <div class="filter-tag" onclick="setFilter('category', 'all')">
                        <span>Danh mục: <?php echo htmlspecialchars($active_category_title); ?></span>
                        <i class="fas fa-times"></i>
                    </div>
                    <?php endif; ?>

                    <?php if ($url_brand !== 'all'): ?>
                    <div class="filter-tag" onclick="setFilter('brand', 'all')">
                        <span>Thương hiệu: <?php echo htmlspecialchars(ucfirst($url_brand)); ?></span>
                        <i class="fas fa-times"></i>
                    </div>
                    <?php endif; ?>

                    <?php if ($url_price !== 'all' && isset($price_labels[$url_price])): ?>
                    <div class="filter-tag" onclick="setFilter('price', 'all')">
                        <span>Giá: <?php echo htmlspecialchars($price_labels[$url_price]); ?></span>
                        <i class="fas fa-times"></i>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <!-- Lưới hiển thị sản phẩm -->
                <div class="p-grid" id="productGrid">
                    <?php if (!empty($products)): ?>
                    <?php foreach ($products as $p): 
                            $rating_avg = (float)($p['rating_avg'] ?? 0);
                            $stars = ($rating_avg > 0) ? round($rating_avg) : 5;
                            
                            $cat_slug = 'all';
                            foreach ($categories as $cat) {
                                if (($cat['id'] ?? null) == ($p['category_id'] ?? null)) {
                                    $cat_slug = $cat['slug'] ?? '';
                                    break;
                                }
                            }
                            
                            $current_price = $p['base_price'] ?? ($p['price'] ?? 0);
                            $old_price = $p['old_price'] ?? 0;
                            
                            $p_image = $p['image_url'] ?? ($p['image'] ?? '');
                            if (empty($p_image)) {
                                $name_lower = mb_strtolower($p['name'] ?? '');
                                if (str_contains($name_lower, 'thoại') || str_contains($name_lower, 'iphone') || str_contains($name_lower, 'galaxy')) {
                                    $p_image = 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?auto=format&fit=crop&w=600&q=80';
                                } elseif (str_contains($name_lower, 'laptop') || str_contains($name_lower, 'macbook') || str_contains($name_lower, 'dell')) {
                                    $p_image = 'https://images.unsplash.com/photo-1593642632823-8f785ba67e45?auto=format&fit=crop&w=600&q=80';
                                } else {
                                    $p_image = 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=600&q=80';
                                }
                            }
                        ?>
                    <div class="p-card" data-category="<?php echo htmlspecialchars($cat_slug); ?>"
                        data-brand="<?php echo htmlspecialchars(strtolower($p['brand_name'] ?? '')); ?>"
                        data-price="<?php echo htmlspecialchars($current_price); ?>"
                        data-name="<?php echo htmlspecialchars(strtolower($p['name'] ?? '')); ?>">

                        <?php if (($p['discount'] ?? 0) > 0): ?>
                        <span class="p-badge">-<?php echo (int)$p['discount']; ?>%</span>
                        <?php endif; ?>

                        <div class="p-img-wrap">
                            <img src="<?php echo htmlspecialchars($p_image); ?>"
                                alt="<?php echo htmlspecialchars($p['name'] ?? ''); ?>" class="p-img" loading="lazy">
                        </div>

                        <div class="p-info">
                            <span class="p-brand"><?php echo htmlspecialchars($p['brand_name'] ?? ''); ?></span>
                            <h3 class="p-name"><?php echo htmlspecialchars($p['name'] ?? ''); ?></h3>

                            <div style="color:#ffcc00; font-size:0.75rem; margin-bottom:10px;">
                                <?php echo str_repeat('<i class="fas fa-star"></i>', $stars); ?>
                            </div>

                            <div class="p-price-row">
                                <span class="p-new"><?php echo formatVND($current_price); ?></span>
                                <?php if ($old_price > $current_price): ?>
                                <span class="p-old"><?php echo formatVND($old_price); ?></span>
                                <?php endif; ?>
                            </div>

                            <div class="p-stats-wrap">
                                <span><i class="far fa-eye"></i> <?php echo number_format($p['view_count'] ?? 0); ?>
                                    lượt xem</span>
                                <span style="color: var(--primary); font-weight: 700;">Nổi bật</span>
                            </div>

                            <a href="/assignment/product/<?php echo htmlspecialchars($p['slug'] ?? ''); ?>"
                                class="btn-add">
                                Chi tiết sản phẩm
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <div class="empty-state">
                        <i class="fas fa-box-open"></i>
                        <h3>Không tìm thấy sản phẩm nào phù hợp</h3>
                        <p style="color:#86868b; font-size:0.85rem; margin-top:8px;">Hãy thử điều chỉnh lại bộ lọc hoặc
                            thay đổi từ khóa tìm kiếm của bạn nhé.</p>
                    </div>
                    <?php endif; ?>
                </div>
            </section>
        </div>
    </div>

    <script>
    // Tham số bộ lọc đồng bộ hóa từ máy chủ
    const activeFilters = {
        search: "<?php echo htmlspecialchars($url_search); ?>",
        category: "<?php echo htmlspecialchars($url_category); ?>",
        brand: "<?php echo htmlspecialchars($url_brand); ?>",
        price: "<?php echo htmlspecialchars($url_price); ?>",
        sort: "<?php echo htmlspecialchars($url_sort); ?>"
    };

    // Thay đổi giá trị lọc và kích hoạt điều hướng
    function setFilter(key, value) {
        activeFilters[key] = value;
        applyRoute();
    }

    const searchForm = document.getElementById('searchForm');
    const searchInput = document.getElementById('searchInput');

    if (searchForm && searchInput) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            activeFilters.search = searchInput.value.trim();
            applyRoute();
        });
    }

    function applyRoute() {
        let path = '/categories';


        const segments = [];

        if (activeFilters.category && activeFilters.category !== 'all') {
            segments.push(activeFilters.category);
        }

        if (activeFilters.brand && activeFilters.brand !== 'all') {
            segments.push(activeFilters.brand);
        }

        if (activeFilters.price && activeFilters.price !== 'all') {
            segments.push(activeFilters.price);
        }

        if (segments.length > 0) {
            path += '/' + segments.join('/');
        }

        // query chỉ giữ lại search + sort
        const queryParams = new URLSearchParams();

        if (activeFilters.search) {
            queryParams.set('search', activeFilters.search);
        }

        if (activeFilters.sort && activeFilters.sort !== 'default') {
            queryParams.set('sort', activeFilters.sort);
        }

        const queryString = queryParams.toString();
        console.log(queryString)
        // window.location.href = queryString ? path + '?' + queryString : path;
    }
    </script>
</body>

</html>