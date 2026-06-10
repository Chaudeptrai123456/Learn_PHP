<?php
// Đảm bảo dữ liệu $dashboard đã tồn tại trước khi xử lý
$stats = $dashboard['stats'] ?? [];
$recentOrders = $dashboard['recent_orders'] ?? [];
$lowStock = $dashboard['low_stock'] ?? [];
$orderStatus = $dashboard['order_status'] ?? [];
$paymentStats = $dashboard['payment_stats'] ?? [];
$topProducts = $dashboard['top_products'] ?? [];

// Hàm định dạng tiền tệ Việt Nam
function formatCurrency($amount) {
    return number_format((float)$amount, 0, ',', '.') . ' ₫';
}

// ==========================================
// XỬ LÝ LOGIC BỘ LỌC THỜI GIAN
// ==========================================
$filterType = $_GET['filter_type'] ?? 'all';
$filterDate = $_GET['filter_date'] ?? '2026-06-05'; 
$filterMonth = $_GET['filter_month'] ?? '2026-06';   
$filterRange = isset($_GET['filter_range']) ? (int)$_GET['filter_range'] : 3; 

$filteredOrders = $recentOrders;
$reportTitleText = "Tất cả thời gian";
$currentSystemTime = strtotime('2026-06-06'); 

if ($filterType === 'date' && !empty($filterDate)) {
    $reportTitleText = "Ngày " . date('d/m/Y', strtotime($filterDate));
    $filteredOrders = array_filter($recentOrders, function($order) use ($filterDate) {
        return date('Y-m-d', strtotime($order['created_at'])) === $filterDate;
    });
} elseif ($filterType === 'month' && !empty($filterMonth)) {
    $reportTitleText = "Tháng " . date('m/Y', strtotime($filterMonth . '-01'));
    $filteredOrders = array_filter($recentOrders, function($order) use ($filterMonth) {
        return date('Y-m', strtotime($order['created_at'])) === $filterMonth;
    });
} elseif ($filterType === 'range' && $filterRange > 0) {
    $reportTitleText = "Trong vòng " . $filterRange . " tháng qua";
    $filteredOrders = array_filter($recentOrders, function($order) use ($filterRange, $currentSystemTime) {
        $orderTime = strtotime($order['created_at']);
        $diffInSeconds = $currentSystemTime - $orderTime;
        $diffInMonths = $diffInSeconds / (30 * 24 * 3600);
        return $diffInMonths >= 0 && $diffInMonths <= $filterRange;
    });
}

// Tính toán doanh thu kỳ báo cáo
$totalRevenue = 0;
$totalOrdersCount = count($filteredOrders);

foreach ($filteredOrders as $order) {
    $totalRevenue += (float)$order['final_amount'];
}

// Tính tỷ lệ dòng tiền thực tế
$originalTotal = (float)($stats['total_revenue'] ?? 1);
$originalPaid = (float)($stats['paid_revenue'] ?? 0);
$paymentRatio = $originalPaid / $originalTotal; 

$paidRevenue = $totalRevenue * $paymentRatio; 
$codRevenue = $totalRevenue - $paidRevenue;
$paymentRate = $totalRevenue > 0 ? ($paidRevenue / $totalRevenue) * 100 : 0;

// ==========================================
// CHUẨN BỊ DỮ LIỆU ĐỒ THỊ DÒNG TIỀN (CHARTS 1 & 2)
// ==========================================
$chronologicalOrders = array_reverse($filteredOrders);
$timelineLabels = [];
$cumulativeRevenue = [];
$cumulativeCashCollected = [];

$tempRevenueSum = 0;
$tempCashSum = 0;

foreach ($chronologicalOrders as $order) {
    $timeLabel = date('H:i', strtotime($order['created_at']));
    $amount = (float)$order['final_amount'];
    
    $tempRevenueSum += $amount;
    $tempCashSum += ($amount * $paymentRatio);
    
    $timelineLabels[] = "ĐH " . $order['id'] . " (" . $timeLabel . ")";
    $cumulativeRevenue[] = $tempRevenueSum;
    $cumulativeCashCollected[] = $tempCashSum;
}

// ==========================================
// CHUẨN BỊ DỮ LIỆU ĐỒ THỊ SẢN PHẨM & DÒNG VỐN TỒN KHO (CHARTS 3 & 4)
// ==========================================
// Ánh xạ đơn giá thực tế cho các thiết bị cao cấp để tính dòng vốn đang bị kẹt (Tied-Up Capital)
$skuUnitPrices = [
    'DELL-XPS15-I9-32G'  => 45000000,  // 45 triệu VNĐ
    'DELL-XPS15-I7-16G'  => 35000000,  // 35 triệu VNĐ
    'HP-SPECTRE-I7-16G'  => 30000000,  // 30 triệu VNĐ
    'ZFOLD5-512GB-BLACK' => 28000000   // 28 triệu VNĐ
];

$stockLabels = [];
$stockQuantities = [];
$stockCapitalValues = []; // Giá trị vốn hóa tồn = Số lượng tồn * Đơn giá sản phẩm

foreach ($lowStock as $item) {
    $sku = $item['sku_code'];
    $qty = (int)$item['stock_qty'];
    $unitPrice = $skuUnitPrices[$sku] ?? 20000000; // Giá mặc định nếu không khớp
    
    $stockLabels[] = $item['name'];
    $stockQuantities[] = $qty;
    $stockCapitalValues[] = $qty * $unitPrice;
}

// 2. Dữ liệu sản phẩm bán chạy (Top Products)
$topProductLabels = [];
$topProductValues = [];
$isTopProductsMocked = empty($topProducts);

if ($isTopProductsMocked) {
    $topProductLabels = ['Laptop Dell XPS', 'HP Spectre', 'Samsung Galaxy', 'Khác'];
    $topProductValues = [40, 25, 20, 15];
} else {
    foreach ($topProducts as $p) {
        $topProductLabels[] = $p['name'];
        // $topProductValues[] = (int)$p['sales_count'];
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Báo cáo Quản trị & Kinh tế | TechStore</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- THƯ VIỆN ĐỒ THỊ CHART.JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
    :root {
        --primary: #4f46e5;
        --success: #10b981;
        --warning: #f59e0b;
        --danger: #ef4444;
        --info: #06b6d4;
        --dark-blue: #0f172a;
        --slate-gray: #64748b;
        --bg-body: #f8fafc;
        --border: #e2e8f0;
        --card-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.05), 0 2px 4px -2px rgb(0 0 0 / 0.05);
    }

    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background-color: var(--bg-body);
        color: var(--dark-blue);
        margin: 0;
    }

    .dashboard-container {
        max-width: 1400px;
        margin: 0 auto;
        padding-top: 10vh
    }

    .dashboard-header {
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }

    .dashboard-header h1 {
        font-size: 1.6rem;
        font-weight: 800;
        color: var(--dark-blue);
        margin: 0;
    }

    .dashboard-header p {
        color: var(--slate-gray);
        font-size: 0.9rem;
        margin-top: 4px;
    }

    /* --- BỘ LỌC DỮ LIỆU ĐA NĂNG --- */
    .filter-section {
        background: #ffffff;
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 24px;
        box-shadow: var(--card-shadow);
    }

    .filter-form {
        display: flex;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .filter-group label {
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--slate-gray);
        text-transform: uppercase;
    }

    .filter-control {
        padding: 8px 12px;
        border: 1px solid var(--border);
        border-radius: 8px;
        font-family: inherit;
        font-size: 0.88rem;
        color: var(--dark-blue);
        outline: none;
        background-color: #fff;
        min-width: 160px;
    }

    .btn-filter {
        background-color: var(--primary);
        color: #ffffff;
        border: none;
        padding: 9px 20px;
        font-weight: 600;
        font-size: 0.88rem;
        border-radius: 8px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 18px;
    }

    .btn-filter:hover {
        background-color: #3831a9;
    }

    .dynamic-input {
        display: none;
    }

    .dynamic-input.active {
        display: flex;
    }

    /* --- KPI CARD SYSTEM --- */
    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
        margin-bottom: 24px;
    }

    .kpi-card {
        background: #ffffff;
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 20px;
        box-shadow: var(--card-shadow);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .kpi-info h3 {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--slate-gray);
        margin: 0 0 8px 0;
    }

    .kpi-info .value {
        font-size: 1.4rem;
        font-weight: 700;
        color: var(--dark-blue);
    }

    .kpi-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
    }

    .icon-blue {
        background: #e0e7ff;
        color: #4f46e5;
    }

    .icon-green {
        background: #d1fae5;
        color: #10b981;
    }

    .icon-purple {
        background: #f3e8ff;
        color: #9333ea;
    }

    .icon-yellow {
        background: #fef3c7;
        color: #d97706;
    }

    /* --- BÁO CÁO PHÂN TÍCH KINH TẾ --- */
    .financial-report {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        color: #ffffff;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 24px;
    }

    .financial-report h2 {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 10px;
        color: #38bdf8;
    }

    .analysis-text {
        font-size: 0.9rem;
        line-height: 1.6;
        color: #cbd5e1;
    }

    .report-badges {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-top: 15px;
    }

    .report-badge-item {
        background: rgba(255, 255, 255, 0.06);
        padding: 12px;
        border-radius: 8px;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .report-badge-item .label {
        font-size: 0.75rem;
        color: #94a3b8;
        display: block;
        margin-bottom: 4px;
    }

    .report-badge-item .val {
        font-size: 1.1rem;
        font-weight: 700;
    }

    /* --- SECTION CHART STYLING --- */
    .section-title {
        font-size: 1.1rem;
        font-weight: 800;
        color: var(--dark-blue);
        margin: 32px 0 16px 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-title i {
        color: var(--primary);
    }

    .charts-container-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 24px;
        margin-bottom: 24px;
    }

    @media (max-width: 1024px) {
        .charts-container-grid {
            grid-template-columns: 1fr;
        }
    }

    .chart-card {
        background: #ffffff;
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 20px;
        box-shadow: var(--card-shadow);
    }

    .chart-card h3 {
        font-size: 0.9rem;
        font-weight: 700;
        margin: 0 0 16px 0;
        color: var(--dark-blue);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .canvas-wrapper {
        position: relative;
        width: 100%;
        height: 280px;
    }

    .demo-badge {
        font-size: 10px;
        background-color: #fee2e2;
        color: #ef4444;
        padding: 2px 6px;
        border-radius: 4px;
        font-weight: 700;
    }

    /* --- MAIN SECTION LAYOUT --- */
    .dashboard-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 24px;
        margin-bottom: 24px;
    }

    @media (max-width: 1024px) {
        .dashboard-grid {
            grid-template-columns: 1fr;
        }
    }

    .dashboard-card {
        background: #ffffff;
        border: 1px solid var(--border);
        border-radius: 12px;
        box-shadow: var(--card-shadow);
        padding: 20px;
        margin-bottom: 24px;
    }

    .card-title {
        font-size: 1rem;
        font-weight: 700;
        margin: 0 0 16px 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        color: var(--dark-blue);
        border-bottom: 1px solid var(--border);
        padding-bottom: 12px;
    }

    /* --- TABLES --- */
    .table-responsive {
        width: 100%;
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
        font-size: 0.88rem;
    }

    th {
        padding: 12px;
        font-weight: 600;
        color: var(--slate-gray);
        border-bottom: 1px solid var(--border);
        background-color: var(--bg-body);
    }

    td {
        padding: 12px;
        border-bottom: 1px solid var(--border);
        vertical-align: middle;
    }

    tr:hover td {
        background-color: #f8fafc;
    }

    .status-badge {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 700;
    }

    .status-pending {
        background: #fef3c7;
        color: #d97706;
    }

    .status-delivered {
        background: #d1fae5;
        color: #10b981;
    }

    /* --- STOCK STATUS WIDGET --- */
    .stock-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .stock-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 12px;
        background: var(--bg-body);
        border-radius: 8px;
        border: 1px solid var(--border);
    }

    .stock-item-info h4 {
        font-size: 0.85rem;
        font-weight: 700;
        margin: 0 0 4px 0;
    }

    .stock-item-info span {
        font-size: 0.75rem;
        color: var(--slate-gray);
    }

    .stock-qty {
        font-size: 0.8rem;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 20px;
    }

    .qty-critical {
        background: #fee2e2;
        color: #ef4444;
    }

    .qty-warning {
        background: #fef3c7;
        color: #d97706;
    }

    .empty-placeholder {
        text-align: center;
        padding: 40px 20px;
        color: var(--slate-gray);
    }

    .empty-placeholder i {
        font-size: 2rem;
        margin-bottom: 12px;
        color: #cbd5e1;
    }
    </style>
</head>

<body>

    <div class="dashboard-container">

        <!-- TIÊU ĐỀ TRANG -->
        <div class="dashboard-header">
            <div>
                <h1>Tổng Quan Hoạt Động Cửa Hàng</h1>
                <p>Kỳ báo cáo đang hiển thị: <strong><?= htmlspecialchars($reportTitleText) ?></strong></p>
            </div>
            <div>
                <span
                    style="font-size: 0.85rem; background: #fff; padding: 8px 16px; border: 1px solid var(--border); border-radius: 8px; font-weight: 600;">
                    <i class="far fa-calendar-alt"></i> Ngày báo cáo hệ thống: 06/06/2026
                </span>
            </div>
        </div>

        <!-- BẢN BỘ LỌC ĐA NĂNG -->
        <div class="filter-section">
            <form method="GET" class="filter-form">
                <div class="filter-group">
                    <label for="filter_type">Phương pháp lọc</label>
                    <select name="filter_type" id="filter_type" class="filter-control"
                        onchange="handleFilterTypeChange()">
                        <option value="all" <?= $filterType === 'all' ? 'selected' : '' ?>>Tất cả thời gian</option>
                        <option value="date" <?= $filterType === 'date' ? 'selected' : '' ?>>Lọc theo ngày cụ thể
                        </option>
                        <option value="month" <?= $filterType === 'month' ? 'selected' : '' ?>>Lọc theo tháng</option>
                        <option value="range" <?= $filterType === 'range' ? 'selected' : '' ?>>Lọc theo số tháng gần đây
                        </option>
                    </select>
                </div>

                <div class="filter-group dynamic-input" id="input_date_container">
                    <label for="filter_date">Chọn ngày cụ thể</label>
                    <input type="date" name="filter_date" id="filter_date" class="filter-control"
                        value="<?= htmlspecialchars($filterDate) ?>">
                </div>

                <div class="filter-group dynamic-input" id="input_month_container">
                    <label for="filter_month">Chọn tháng</label>
                    <input type="month" name="filter_month" id="filter_month" class="filter-control"
                        value="<?= htmlspecialchars($filterMonth) ?>">
                </div>

                <div class="filter-group dynamic-input" id="input_range_container">
                    <label for="filter_range">Số tháng gần đây</label>
                    <select name="filter_range" id="filter_range" class="filter-control">
                        <option value="1" <?= $filterRange === 1 ? 'selected' : '' ?>>1 tháng qua</option>
                        <option value="3" <?= $filterRange === 3 ? 'selected' : '' ?>>3 tháng qua</option>
                        <option value="6" <?= $filterRange === 6 ? 'selected' : '' ?>>6 tháng qua</option>
                        <option value="12" <?= $filterRange === 12 ? 'selected' : '' ?>>12 tháng qua</option>
                    </select>
                </div>

                <button type="submit" class="btn-filter">
                    <i class="fas fa-filter"></i> Áp dụng bộ lọc
                </button>
            </form>
        </div>

        <!-- KPI CARD SYSTEM -->
        <div class="kpi-grid">
            <div class="kpi-card">
                <div class="kpi-info">
                    <h3>Doanh thu kỳ báo cáo</h3>
                    <div class="value"><?= formatCurrency($totalRevenue) ?></div>
                </div>
                <div class="kpi-icon icon-blue">
                    <i class="fas fa-coins"></i>
                </div>
            </div>

            <div class="kpi-card">
                <div class="kpi-info">
                    <h3>Khả năng thu hồi vốn</h3>
                    <div class="value" style="color: var(--success);"><?= formatCurrency($paidRevenue) ?></div>
                </div>
                <div class="kpi-icon icon-green">
                    <i class="fas fa-wallet"></i>
                </div>
            </div>

            <div class="kpi-card">
                <div class="kpi-info">
                    <h3>Số đơn hàng phát sinh</h3>
                    <div class="value"><?= $totalOrdersCount ?></div>
                </div>
                <div class="kpi-icon icon-purple">
                    <i class="fas fa-shopping-bag"></i>
                </div>
            </div>

            <div class="kpi-card">
                <div class="kpi-info">
                    <h3>Khách hàng giao dịch</h3>
                    <div class="value"><?= $stats['total_customers'] ?? 0 ?></div>
                </div>
                <div class="kpi-icon icon-yellow">
                    <i class="fas fa-users"></i>
                </div>
            </div>
        </div>

        <!-- BÁO CÁO PHÂN TÍCH KINH TẾ KỲ BÁO CÁO -->
        <div class="financial-report">
            <h2><i class="fas fa-chart-line"></i> Phân Tích Kinh Tế Kỳ Báo Cáo
                (<?= htmlspecialchars($reportTitleText) ?>)</h2>
            <div class="analysis-text">
                Kết quả ghi nhận doanh số đạt mức <strong><?= formatCurrency($totalRevenue) ?></strong> với
                <strong><?= $totalOrdersCount ?> giao dịch thành công hoặc đang xử lý</strong>.
                Tỷ lệ hoàn tất thu hồi dòng tiền mặt trực tuyến của chu kỳ này ước tính duy trì ở mức
                <strong><?= number_format($paymentRate, 2) ?>%</strong>.
                Toàn bộ dữ liệu doanh số còn lại nằm ở biên độ thanh toán sau (COD). Khuyến nghị bộ phận bán hàng tối ưu
                quy trình xử lý đơn hàng để đảm bảo dòng tiền vận hành liên tục.
            </div>
            <div class="report-badges">
                <div class="report-badge-item">
                    <span class="label">Tổng giá trị đơn hàng kỳ này</span>
                    <span class="val text-info"><i class="fas fa-wallet"></i>
                        <?= formatCurrency($totalRevenue) ?></span>
                </div>
                <div class="report-badge-item">
                    <span class="label">Khả năng thu hồi vốn nhanh</span>
                    <span class="val" style="color: var(--success);"><?= number_format($paymentRate, 1) ?>%</span>
                </div>
                <div class="report-badge-item">
                    <span class="label">Đơn hàng trung bình (AOV)</span>
                    <span class="val"
                        style="color: var(--warning);"><?= $totalOrdersCount > 0 ? formatCurrency($totalRevenue / $totalOrdersCount) : '0 ₫' ?></span>
                </div>
            </div>
        </div>

        <!-- ========================================================= -->
        <!-- PHÂN KHU 1: PHÂN TÍCH DOANH THU & DÒNG TIỀN (CHARTS DYNAMIC) -->
        <!-- ========================================================= -->
        <div class="section-title">
            <i class="fas fa-funnel-dollar"></i> Phân khu 1: Thống kê Tài chính & Dòng tiền
        </div>
        <div class="charts-container-grid">
            <div class="chart-card">
                <h3><i class="fas fa-chart-area" style="color: var(--primary);"></i> Xu hướng tích lũy doanh số & dòng
                    tiền thực thu</h3>
                <div class="canvas-wrapper">
                    <canvas id="cashflowTrendLineChart"></canvas>
                </div>
            </div>

            <div class="chart-card">
                <h3><i class="fas fa-balance-scale" style="color: var(--warning);"></i> Phân bổ dòng tiền thanh toán
                </h3>
                <div class="canvas-wrapper">
                    <canvas id="cashflowChannelsBarChart"></canvas>
                </div>
            </div>
        </div>

        <!-- ========================================================= -->
        <!-- PHÂN KHU 2: PHÂN TÍCH SẢN PHẨM & KHO VẬN -->
        <!-- ========================================================= -->
        <div class="section-title">
            <i class="fas fa-boxes-stacked"></i> Phân khu 2: Quản trị Sản phẩm & Kho vận
        </div>
        <div class="charts-container-grid">
            <!-- ĐỒ THỊ 3: DUAL-AXIS CHART (SỐ LƯỢNG TỒN VS GIÁ TRỊ VỐN ĐỌC) -->
            <div class="chart-card">
                <h3><i class="fas fa-coins" style="color: var(--danger);"></i> Tương quan giữa Số lượng tồn & Giá trị
                    vốn tồn kho</h3>
                <div class="canvas-wrapper">
                    <canvas id="inventoryStockFinancialChart"></canvas>
                </div>
            </div>

            <!-- ĐỒ THỊ 4: PIE CHART (TỶ LỆ BÁN CHẠY) -->
            <div class="chart-card">
                <h3>
                    <i class="fas fa-pie-chart" style="color: var(--info);"></i> Tỷ lệ cơ cấu sản phẩm bán chạy
                    <?php if ($isTopProductsMocked): ?>
                    <span class="demo-badge">Dữ liệu mẫu</span>
                    <?php endif; ?>
                </h3>
                <div class="canvas-wrapper">
                    <canvas id="topProductsPieChart"></canvas>
                </div>
            </div>
        </div>

        <!-- GRID CHỨA DANH SÁCH DỮ LIỆU ĐÃ ĐƯỢC LỌC -->
        <div class="dashboard-grid">

            <!-- DANH SÁCH ĐƠN HÀNG TRONG KỲ LỌC -->
            <div>
                <div class="dashboard-card">
                    <div class="card-title">
                        <span><i class="fas fa-clock"></i> Đơn hàng ghi nhận trong kỳ lọc</span>
                    </div>

                    <div class="table-responsive">
                        <?php if (!empty($filteredOrders)): ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>Mã ĐH</th>
                                    <th>Khách hàng</th>
                                    <th>Giá trị đơn</th>
                                    <th>Ngày đặt</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($filteredOrders as $order): ?>
                                <tr>
                                    <td><strong>#<?= $order['id'] ?></strong></td>
                                    <td><?= htmlspecialchars($order['name']) ?></td>
                                    <td><strong><?= formatCurrency($order['final_amount']) ?></strong></td>
                                    <td><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></td>
                                    <td>
                                        <span
                                            class="status-badge <?= $order['status'] === 'delivered' ? 'status-delivered' : 'status-pending' ?>">
                                            <?= $order['status'] === 'delivered' ? 'Đã giao' : 'Chờ xử lý' ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php else: ?>
                        <div class="empty-placeholder">
                            <i class="fas fa-folder-open"></i>
                            <p>Không có dữ liệu đơn hàng nào khớp với khoảng thời gian đã lọc.</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- CỘT PHẢI: KIỂM SOÁT HÀNG TỒN VÀ THỐNG KÊ PHƯƠNG THỨC -->
            <div>
                <!-- CẢNH BÁO TỒN KHO THẤP -->
                <div class="dashboard-card">
                    <div class="card-title">
                        <span><i class="fas fa-exclamation-triangle" style="color: var(--danger);"></i> Cảnh báo tồn kho
                            thấp</span>
                    </div>

                    <div class="stock-list">
                        <?php if (!empty($lowStock)): ?>
                        <?php foreach ($lowStock as $item): ?>
                        <?php 
                                    $qty = (int)$item['stock_qty'];
                                    $levelClass = $qty <= 5 ? 'qty-critical' : 'qty-warning';
                                ?>
                        <div class="stock-item">
                            <div class="stock-item-info">
                                <h4><?= htmlspecialchars($item['name']) ?></h4>
                                <span>SKU: <?= htmlspecialchars($item['sku_code']) ?></span>
                            </div>
                            <span class="stock-qty <?= $levelClass ?>"><?= $qty ?> sản phẩm</span>
                        </div>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <div class="empty-placeholder">
                            <i class="fas fa-check-circle" style="color: var(--success);"></i>
                            <p>Tất cả sản phẩm đều ở mức tồn kho an toàn.</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- PHƯƠNG THỨC THANH TOÁN -->
                <div class="dashboard-card">
                    <div class="card-title">
                        <span><i class="fas fa-credit-card"></i> Kênh thanh toán áp dụng</span>
                    </div>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Cổng toán</th>
                                    <th>Đơn</th>
                                    <th>Tổng thu</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($paymentStats as $pay): ?>
                                <tr>
                                    <td><strong><?= strtoupper($pay['payment_method']) ?></strong></td>
                                    <td><?= $pay['total'] ?></td>
                                    <td><?= formatCurrency($pay['revenue']) ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- SCRIPT CHẠY ĐỒ THỊ CHUYÊN SÂU -->
    <script>
    function handleFilterTypeChange() {
        const type = document.getElementById('filter_type').value;
        const dateContainer = document.getElementById('input_date_container');
        const monthContainer = document.getElementById('input_month_container');
        const rangeContainer = document.getElementById('input_range_container');

        dateContainer.classList.remove('active');
        monthContainer.classList.remove('active');
        rangeContainer.classList.remove('active');

        if (type === 'date') {
            dateContainer.classList.add('active');
        } else if (type === 'month') {
            monthContainer.classList.add('active');
        } else if (type === 'range') {
            rangeContainer.classList.add('active');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        handleFilterTypeChange();

        const formatTooltipValue = function(value) {
            return new Intl.NumberFormat('vi-VN', {
                style: 'currency',
                currency: 'VND'
            }).format(value);
        };

        // =========================================================
        // ĐỒ THỊ 1: LINE CHART (TÍCH LŨY DOANH THU & THỰC THU)
        // =========================================================
        const timelineLabels = <?= json_encode($timelineLabels) ?>;
        const cumulativeRevenue = <?= json_encode($cumulativeRevenue) ?>;
        const cumulativeCashCollected = <?= json_encode($cumulativeCashCollected) ?>;

        const ctxLine = document.getElementById('cashflowTrendLineChart').getContext('2d');
        new Chart(ctxLine, {
            type: 'line',
            data: {
                labels: timelineLabels,
                datasets: [{
                        label: 'Doanh thu dự kiến tích lũy',
                        data: cumulativeRevenue,
                        borderColor: '#4f46e5',
                        backgroundColor: 'rgba(79, 70, 229, 0.08)',
                        fill: true,
                        tension: 0.25,
                        borderWidth: 3,
                        pointRadius: 4
                    },
                    {
                        label: 'Dòng tiền thực thu tích lũy',
                        data: cumulativeCashCollected,
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.05)',
                        fill: true,
                        tension: 0.25,
                        borderWidth: 3,
                        pointRadius: 4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: {
                                family: 'Plus Jakarta Sans',
                                size: 11,
                                weight: '600'
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        callback: value => value >= 1000000 ? (value / 1000000) + ' Tr' :
                            formatTooltipValue(value)
                    }
                }
            }
        });

        // =========================================================
        // ĐỒ THỊ 2: PHÂN BỔ DÒNG TIỀN THEO KÊNH (DOUGHNUT)
        // =========================================================
        const paidAmount = <?= (float)$paidRevenue ?>;
        const codAmount = <?= (float)$codRevenue ?>;

        const ctxBar = document.getElementById('cashflowChannelsBarChart').getContext('2d');
        new Chart(ctxBar, {
            type: 'doughnut',
            data: {
                labels: ['Momo (Thực thu)', 'COD (Chưa thu)'],
                datasets: [{
                    data: [paidAmount, codAmount],
                    backgroundColor: ['#10b981', '#f59e0b']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: {
                                family: 'Plus Jakarta Sans',
                                size: 11,
                                weight: '600'
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: context => context.label + ': ' + formatTooltipValue(context.raw)
                        }
                    }
                }
            }
        });

        // =========================================================
        // ĐỒ THỊ 3: KHO VẬN TRỤC KÉP - SỐ LƯỢNG VS GIÁ TRỊ VỐN ĐỌC
        // =========================================================
        const stockLabels = <?= json_encode($stockLabels) ?>;
        const stockQuantities = <?= json_encode($stockQuantities) ?>;
        const stockCapitalValues = <?= json_encode($stockCapitalValues) ?>;

        const ctxStockFin = document.getElementById('inventoryStockFinancialChart').getContext('2d');
        new Chart(ctxStockFin, {
            type: 'bar',
            data: {
                labels: stockLabels,
                datasets: [{
                        label: 'Số lượng tồn (Cột)',
                        data: stockQuantities,
                        backgroundColor: 'rgba(245, 158, 11, 0.85)',
                        borderColor: '#f59e0b',
                        borderWidth: 1,
                        yAxisID: 'y_qty' // Ánh xạ sang trục Y bên trái (Số lượng)
                    },
                    {
                        label: 'Vốn đọng dự kiến (Đường)',
                        data: stockCapitalValues,
                        borderColor: '#ef4444',
                        backgroundColor: '#ef4444',
                        borderWidth: 3,
                        type: 'line', // Line kết hợp chung với cột
                        tension: 0.1,
                        pointRadius: 4,
                        yAxisID: 'y_val' // Ánh xạ sang trục Y bên phải (Giá trị tiền tệ)
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: {
                                family: 'Plus Jakarta Sans',
                                size: 11,
                                weight: '600'
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                if (context.datasetIndex === 1) {
                                    return context.dataset.label + ': ' + formatTooltipValue(context
                                        .raw);
                                }
                                return context.dataset.label + ': ' + context.raw + ' sản phẩm';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        }
                    },
                    y_qty: {
                        type: 'linear',
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Số lượng (sản phẩm)',
                            font: {
                                family: 'Plus Jakarta Sans',
                                size: 10,
                                weight: '600'
                            }
                        },
                        ticks: {
                            stepSize: 2
                        },
                        grid: {
                            color: '#f1f5f9'
                        }
                    },
                    y_val: {
                        type: 'linear',
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Giá trị vốn hóa (VNĐ)',
                            font: {
                                family: 'Plus Jakarta Sans',
                                size: 10,
                                weight: '600'
                            }
                        },
                        ticks: {
                            callback: value => value >= 1000000 ? (value / 1000000) + ' Tr' :
                                formatTooltipValue(value)
                        },
                        grid: {
                            display: false
                        } // Tắt dòng lưới trục phụ để tránh chồng chéo rối mắt
                    }
                }
            }
        });

        // =========================================================
        // ĐỒ THỊ 4: SẢN PHẨM BÁN CHẠY (PIE CHART)
        // =========================================================
        const topProductLabels = <?= json_encode($topProductLabels) ?>;
        const topProductValues = <?= json_encode($topProductValues) ?>;

        const ctxPie = document.getElementById('topProductsPieChart').getContext('2d');
        new Chart(ctxPie, {
            type: 'pie',
            data: {
                labels: topProductLabels,
                datasets: [{
                    data: topProductValues,
                    backgroundColor: ['#4f46e5', '#3b82f6', '#06b6d4', '#cbd5e1'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: {
                                family: 'Plus Jakarta Sans',
                                size: 11,
                                weight: '600'
                            }
                        }
                    }
                }
            }
        });
    });
    </script>
</body>

</html>