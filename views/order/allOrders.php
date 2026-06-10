<!-- CSS riêng cho trang Lịch sử đơn hàng Premium -->
<style>
:root {
    --order-primary: #0071e3;
    --order-dark: #1d1d1f;
    --order-border: #e5e5e7;
    --order-gray-bg: #f5f5f7;
    --order-text-gray: #86868b;
    --order-transition: all 0.3s cubic-bezier(0.25, 0.1, 0.25, 1);
}

.order-history-wrapper {
    margin-top: 90px;
    min-height: calc(100vh - 90px);
    background-color: #f5f5f7;
    /* Nền xám nhẹ tôn vinh các card trắng */
    padding: 40px 20px;
}

.order-history-container {
    max-width: 900px;
    margin: 0 auto;
}

/* Tiêu đề trang */
.page-header {
    margin-bottom: 32px;
    text-align: left;
}

.page-header h1 {
    font-size: 2rem;
    font-weight: 800;
    letter-spacing: -0.8px;
    color: var(--order-dark);
    margin-bottom: 6px;
}

.page-header p {
    font-size: 0.95rem;
    color: var(--order-text-gray);
    font-weight: 500;
}

/* Card Đơn hàng */
.order-card {
    background: #ffffff;
    border: 1px solid var(--order-border);
    border-radius: 20px;
    margin-bottom: 24px;
    overflow: hidden;
    transition: var(--order-transition);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
}

.order-card:hover {
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05);
    border-color: #d2d2d7;
}

/* Phần Đầu của Card (Mã đơn, trạng thái) */
.order-card-header {
    padding: 20px 24px;
    border-bottom: 1px solid var(--order-border);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
    background-color: #fafafa;
}

.order-meta {
    text-align: left;
}

.order-id {
    font-size: 1rem;
    font-weight: 700;
    color: var(--order-dark);
    margin-bottom: 4px;
}

.order-date {
    font-size: 0.8rem;
    color: var(--order-text-gray);
    font-weight: 500;
}

.order-statuses {
    display: flex;
    gap: 8px;
    align-items: center;
}

/* Badges trạng thái */
.order-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.78rem;
    font-weight: 700;
    text-transform: capitalize;
}

/* 1. Trạng thái đơn hàng (status) */
.badge-status-pending {
    background-color: #fff3cd;
    color: #856404;
}

.badge-status-processing {
    background-color: #cce5ff;
    color: #004085;
}

.badge-status-shipping {
    background-color: #e2e3e5;
    color: #383d41;
}

.badge-status-completed {
    background-color: #d4edda;
    color: #155724;
}

.badge-status-cancelled {
    background-color: #f8d7da;
    color: #721c24;
}

/* 2. Trạng thái thanh toán (payment_status) */
.badge-pay-unpaid {
    background-color: #fff0f0;
    color: #e30000;
    border: 1px solid rgba(227, 0, 0, 0.1);
}

.badge-pay-paid {
    background-color: #f0fbf0;
    color: #00875a;
    border: 1px solid rgba(0, 135, 90, 0.1);
}

/* Danh sách sản phẩm của đơn */
.order-items-list {
    padding: 8px 24px;
}

.order-item-row {
    display: flex;
    align-items: center;
    padding: 16px 0;
    border-bottom: 1px solid #f5f5f7;
    gap: 16px;
}

.order-item-row:last-child {
    border-bottom: none;
}

.item-image {
    width: 64px;
    height: 64px;
    object-fit: cover;
    border-radius: 10px;
    border: 1px solid var(--order-border);
    background-color: #fafafa;
    flex-shrink: 0;
}

.item-details {
    flex-grow: 1;
    text-align: left;
}

.item-name {
    font-size: 0.95rem;
    font-weight: 700;
    color: var(--order-dark);
    text-decoration: none;
    transition: var(--order-transition);
}

.item-name:hover {
    color: var(--order-primary);
}

.item-sku {
    font-size: 0.75rem;
    color: var(--order-text-gray);
    margin-top: 4px;
    font-weight: 500;
}

.item-pricing {
    text-align: right;
    flex-shrink: 0;
}

.item-price {
    font-size: 0.92rem;
    font-weight: 600;
    color: var(--order-dark);
}

.item-qty {
    font-size: 0.8rem;
    color: var(--order-text-gray);
    margin-top: 2px;
}

/* Phần Cuối của Card (Tổng hóa đơn) */
.order-card-footer {
    padding: 20px 24px;
    background-color: #ffffff;
    border-top: 1px solid var(--order-border);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
}

.payment-method-info {
    text-align: left;
    font-size: 0.85rem;
    color: var(--order-text-gray);
    font-weight: 500;
}

.payment-method-info strong {
    color: var(--order-dark);
}

.order-summary-price {
    text-align: right;
}

.summary-row {
    font-size: 0.88rem;
    color: var(--order-text-gray);
    margin-bottom: 4px;
}

.final-amount-row {
    font-size: 1.15rem;
    font-weight: 800;
    color: var(--order-dark);
    letter-spacing: -0.5px;
}

/* Trạng thái danh sách rỗng */
.empty-orders {
    text-align: center;
    padding: 80px 20px;
    background: #ffffff;
    border: 1px solid var(--order-border);
    border-radius: 20px;
}

.empty-orders i {
    font-size: 3rem;
    color: var(--order-text-gray);
    margin-bottom: 20px;
}

.empty-orders h3 {
    font-size: 1.4rem;
    font-weight: 700;
    color: var(--order-dark);
    margin-bottom: 8px;
}

.empty-orders p {
    color: var(--order-text-gray);
    font-size: 0.92rem;
    margin-bottom: 24px;
}

.btn-shopping {
    display: inline-block;
    padding: 12px 24px;
    background-color: var(--order-primary);
    color: #ffffff;
    text-decoration: none;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.9rem;
    transition: var(--order-transition);
}

.btn-shopping:hover {
    background-color: #0077ed;
    box-shadow: 0 4px 12px rgba(0, 113, 227, 0.15);
}

@media (max-width: 600px) {

    .order-card-header,
    .order-card-footer {
        flex-direction: column;
        align-items: flex-start;
    }

    .order-statuses,
    .order-summary-price {
        align-self: flex-start;
        text-align: left;
    }
}
</style>

<?php
// Hàm định dạng tiền tệ Việt Nam (VND)
function formatCurrency($amount) {
    return number_format($amount, 0, ',', '.') . 'đ';
}

// Hàm chuẩn hóa nhãn trạng thái đơn hàng
function getStatusLabel($status) {
    $labels = [
        'pending' => 'Chờ xử lý',
        'processing' => 'Đang xử lý',
        'shipping' => 'Đang giao hàng',
        'completed' => 'Đã hoàn thành',
        'cancelled' => 'Đã hủy'
    ];
    return $labels[$status] ?? $status;
}

// Hàm chuẩn hóa nhãn trạng thái thanh toán
function getPaymentStatusLabel($status) {
    $labels = [
        'unpaid' => 'Chưa thanh toán',
        'paid' => 'Đã thanh toán'
    ];
    return $labels[$status] ?? $status;
}

// Hàm hiển thị phương thức thanh toán thân thiện
function getPaymentMethodLabel($method) {
    $methods = [
        'cod' => 'COD (Thanh toán khi nhận hàng)',
        'momo' => 'Ví MoMo',
        'vnpay' => 'VNPay',
        'banking' => 'Chuyển khoản ngân hàng'
    ];
    return $methods[$method] ?? strtoupper($method);
}
?>

<div class="order-history-wrapper">
    <div class="order-history-container">

        <div class="page-header">
            <h1>Lịch sử đơn hàng</h1>
            <p>Theo dõi tiến trình xử lý và xem lại các giao dịch mua hàng của bạn tại TechStore.</p>
        </div>

        <?php if (!empty($groupedOrders)): ?>
        <?php foreach ($groupedOrders as $order): ?>
        <div class="order-card">
            <!-- Đầu thẻ đơn hàng -->
            <div class="order-card-header">
                <div class="order-meta">
                    <div class="order-id">Đơn hàng #<?= htmlspecialchars($order['order_id']); ?></div>
                    <div class="order-date">Đặt ngày: <?= date('d/m/Y H:i', strtotime($order['created_at'])); ?></div>
                </div>
                <div class="order-statuses">
                    <!-- Trạng thái đơn hàng -->
                    <span class="order-badge badge-status-<?= htmlspecialchars($order['status']); ?>">
                        <?= htmlspecialchars(getStatusLabel($order['status'])); ?>
                    </span>
                    <!-- Trạng thái thanh toán -->
                    <span class="order-badge badge-pay-<?= htmlspecialchars($order['payment_status']); ?>">
                        <?= htmlspecialchars(getPaymentStatusLabel($order['payment_status'])); ?>
                    </span>
                </div>
            </div>

            <!-- Danh sách sản phẩm -->
            <div class="order-items-list">
                <?php foreach ($order['items'] as $item): ?>
                <div class="order-item-row">
                    <img src="<?= htmlspecialchars($item['image_url']); ?>"
                        alt="<?= htmlspecialchars($item['product_name']); ?>" class="item-image">
                    <div class="item-details">
                        <a href="/assignment/product/<?= htmlspecialchars($item['slug']); ?>" class="item-name">
                            <?= htmlspecialchars($item['product_name']); ?>
                        </a>
                        <div class="item-sku">Mã phân loại: <?= htmlspecialchars($item['sku_code']); ?></div>
                    </div>
                    <div class="item-pricing">
                        <div class="item-price"><?= formatCurrency($item['price']); ?></div>
                        <div class="item-qty">Số lượng: x<?= htmlspecialchars($item['quantity']); ?></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Cuối thẻ đơn hàng (Hóa đơn và cổng thanh toán) -->
            <div class="order-card-footer">
                <div class="payment-method-info">
                    Phương thức thanh toán:<br>
                    <strong><?= htmlspecialchars(getPaymentMethodLabel($order['payment_method'])); ?></strong>
                </div>
                <div class="order-summary-price">
                    <?php if ($order['discount_amount'] > 0): ?>
                    <div class="summary-row">Giảm giá: -<?= formatCurrency($order['discount_amount']); ?></div>
                    <?php endif; ?>
                    <div class="final-amount-row">
                        Tổng tiền: <?= formatCurrency($order['final_amount']); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        <?php else: ?>
        <!-- Giao diện trống khi người dùng chưa có đơn hàng nào -->
        <div class="empty-orders">
            <i class="fas fa-box-open"></i>
            <h3>Chưa có đơn hàng nào</h3>
            <p>Bạn chưa thực hiện bất kỳ giao dịch mua sắm nào tại hệ thống cửa hàng TechStore.</p>
            <a href="/assignment" class="btn-shopping">Khám phá sản phẩm ngay</a>
        </div>
        <?php endif; ?>

    </div>
</div>