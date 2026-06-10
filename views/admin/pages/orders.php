<!-- Nhúng Bootstrap 5 và Bootstrap Icons để tăng tính trực quan -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
/* Bo góc và hiệu ứng chuyển màu mượt cho các thẻ */
.custom-card {
    border-radius: 12px;
    overflow: hidden;
}

.gradient-header {
    background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
}

/* Hover nhẹ cho dòng trong bảng */
.table-hover tbody tr:hover {
    background-color: rgba(241, 245, 249, 0.75) !important;
    transition: background-color 0.2s ease;
}

/* Các tùy chọn dải màu border cho từng trạng thái */
.status-pending {
    border-left: 5px solid #ffc107 !important;
}

.status-confirmed {
    border-left: 5px solid #0d6efd !important;
}

.status-shipping {
    border-left: 5px solid #0dcaf0 !important;
}

.status-delivered {
    border-left: 5px solid #198754 !important;
}

.status-cancelled {
    border-left: 5px solid #dc3545 !important;
}
</style>

<div class="container-fluid py-4" style="background-color: #f1f5f9; min-height: 100vh;">

    <!-- KHU VỰC BỘ LỌC TÌM KIẾM (MỚI THÊM) -->
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
        <div class="card-body p-3">
            <form method="GET" action="/assignment/admin/orders" class="row g-3 align-items-end">
                <!-- Lọc theo Tên khách hàng -->
                <div class="col-md-3">
                    <label class="form-label fw-bold text-secondary" style="font-size: 0.85rem;">Tên khách hàng</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                        <input type="text" name="customer_name" class="form-control"
                            placeholder="Nhập tên khách hàng..."
                            value="<?php echo htmlspecialchars($filters['customer_name'] ?? ''); ?>">
                    </div>
                </div>

                <!-- Lọc theo Trạng thái đơn hàng -->
                <div class="col-md-3">
                    <label class="form-label fw-bold text-secondary" style="font-size: 0.85rem;">Trạng thái đơn
                        hàng</label>
                    <?php $fStatus = $filters['status'] ?? ''; ?>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">-- Tất cả trạng thái --</option>
                        <option value="pending" <?php echo $fStatus === 'pending' ? 'selected' : ''; ?>>⏳ Chờ xử lý
                        </option>
                        <option value="confirmed" <?php echo $fStatus === 'confirmed' ? 'selected' : ''; ?>>⚙️ Đã xác
                            nhận</option>
                        <option value="shipping" <?php echo $fStatus === 'shipping' ? 'selected' : ''; ?>>🚚 Đang giao
                            hàng</option>
                        <option value="delivered" <?php echo $fStatus === 'delivered' ? 'selected' : ''; ?>>🎉 Đã giao
                            hàng</option>
                        <option value="cancelled" <?php echo $fStatus === 'cancelled' ? 'selected' : ''; ?>>🚫 Đã hủy
                        </option>
                    </select>
                </div>

                <!-- Lọc từ Ngày -->
                <div class="col-md-2">
                    <label class="form-label fw-bold text-secondary" style="font-size: 0.85rem;">Từ ngày đặt</label>
                    <input type="date" name="start_date" class="form-control form-control-sm"
                        value="<?php echo htmlspecialchars($filters['start_date'] ?? ''); ?>">
                </div>

                <!-- Lọc đến Ngày -->
                <div class="col-md-2">
                    <label class="form-label fw-bold text-secondary" style="font-size: 0.85rem;">Đến ngày đặt</label>
                    <input type="date" name="end_date" class="form-control form-control-sm"
                        value="<?php echo htmlspecialchars($filters['end_date'] ?? ''); ?>">
                </div>

                <!-- Các nút hành động bộ lọc -->
                <div class="col-md-2 text-end">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-sm btn-primary w-100 fw-bold">
                            <i class="bi bi-filter"></i> Lọc
                        </button>
                        <a href="/assignment/admin/orders" class="btn btn-sm btn-outline-secondary w-100 fw-bold">
                            <i class="bi bi-arrow-counterclockwise"></i> Xóa lọc
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- KHU VỰC BẢNG DANH SÁCH ĐƠN HÀNG -->
    <div class="card custom-card shadow border-0">
        <!-- Header với dải màu Gradient chuyển động hiện đại -->
        <div
            class="card-header gradient-header py-3.5 d-flex justify-content-between align-items-center border-bottom-0">
            <h5 class="m-0 fw-bold text-white d-flex align-items-center">
                <i class="bi bi-box-seam-fill me-2"></i> Hệ Thống Quản Lý Đơn Hàng
            </h5>
            <span class="badge bg-white text-primary fw-bold px-3 py-2 rounded-pill shadow-sm fs-6">
                <i class="bi bi-cart-check-fill me-1"></i> <?php echo count($orders ?? []); ?> Đơn hàng
            </span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark" style="background-color: #1e293b;">
                        <tr>
                            <th class="ps-3" style="width: 90px;">Mã ĐH</th>
                            <th>Khách hàng</th>
                            <th>Phương thức</th>
                            <th style="width: 190px;">Trạng thái thanh toán</th>
                            <th style="width: 190px;">Trạng thái đơn hàng</th>
                            <th class="text-end">Tổng tiền</th>
                            <th>Ngày đặt</th>
                            <th class="text-center" style="width: 250px;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($orders)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                                Không tìm thấy đơn hàng nào khớp với bộ lọc.
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($orders as $order): ?>
                        <?php 
                            // Định hình màu sắc cho từng dòng dựa trên Trạng thái đơn hàng
                            $status = $order['status'] ?? 'pending';
                            $rowClass = 'status-pending';
                            $statusSelectColor = 'bg-warning-subtle text-warning-emphasis border-warning';
                            switch ($status) {
                                case 'confirmed':
                                    $rowClass = 'status-confirmed';
                                    $statusSelectColor = 'bg-primary-subtle text-primary border-primary';
                                    break;
                                case 'shipping':
                                    $rowClass = 'status-shipping';
                                    $statusSelectColor = 'bg-info-subtle text-info-emphasis border-info';
                                    break;
                                case 'delivered':
                                    $rowClass = 'status-delivered';
                                    $statusSelectColor = 'bg-success-subtle text-success border-success';
                                    break;
                                case 'cancelled':
                                    $rowClass = 'status-cancelled';
                                    $statusSelectColor = 'bg-danger-subtle text-danger border-danger';
                                    break;
                            }

                            // Định hình màu sắc cho trạng thái thanh toán
                            $payStatus = $order['payment_status'] ?? 'unpaid';
                            $paySelectColor = 'bg-warning-subtle text-warning-emphasis border-warning';
                            if ($payStatus === 'paid') {
                                $paySelectColor = 'bg-success-subtle text-success border-success';
                            } elseif ($payStatus === 'failed') {
                                $paySelectColor = 'bg-danger-subtle text-danger border-danger';
                            }
                        ?>
                        <tr class="<?php echo $rowClass; ?>">
                            <!-- Form cập nhật trạng thái đơn hàng -->
                            <form action="/assignment/admin/orders/update" method="POST">
                                <input type="hidden" name="order_id"
                                    value="<?php echo htmlspecialchars($order['order_id'] ?? ''); ?>">

                                <!-- Mã Đơn hàng dạng nhãn nổi bật -->
                                <td class="ps-3">
                                    <span class="badge bg-dark-subtle text-dark-emphasis border px-2.5 py-1.5 fw-bold">
                                        #<?php echo htmlspecialchars($order['order_id'] ?? ''); ?>
                                    </span>
                                </td>

                                <!-- Thông tin Khách hàng chi tiết kèm biểu tượng -->
                                <td>
                                    <div class="fw-bold text-dark mb-1">
                                        <i
                                            class="bi bi-person-fill text-indigo me-1 text-primary"></i><?php echo htmlspecialchars($order['user']['name'] ?? ''); ?>
                                    </div>
                                    <div class="text-muted mb-0.5" style="font-size: 0.85rem;">
                                        <i
                                            class="bi bi-telephone-fill text-muted me-1.5"></i><?php echo htmlspecialchars($order['user']['phone'] ?? ''); ?>
                                    </div>
                                    <div class="text-muted" style="font-size: 0.8rem;">
                                        <i
                                            class="bi bi-envelope-fill text-muted me-1.5"></i><?php echo htmlspecialchars($order['user']['email'] ?? ''); ?>
                                    </div>
                                </td>

                                <!-- Phương thức thanh toán -->
                                <td>
                                    <span
                                        class="badge bg-secondary-subtle text-secondary-emphasis text-uppercase border px-2 py-1.5">
                                        <i
                                            class="bi bi-wallet2 me-1"></i><?php echo htmlspecialchars($order['payment_method'] ?? ''); ?>
                                    </span>
                                </td>

                                <!-- Dropdown Trạng thái thanh toán (đã đổi màu nền) -->
                                <td>
                                    <select name="payment_status"
                                        class="form-select form-select-sm fw-semibold <?php echo $paySelectColor; ?>"
                                        style="font-size: 0.85rem;" onchange="updateSelectColor(this)">
                                        <option value="unpaid" class="bg-white text-dark"
                                            <?php echo $payStatus === 'unpaid' ? 'selected' : ''; ?>>⚠️ Chưa thanh toán
                                        </option>
                                        <option value="paid" class="bg-white text-dark"
                                            <?php echo $payStatus === 'paid' ? 'selected' : ''; ?>>✅ Đã thanh toán
                                        </option>
                                        <option value="failed" class="bg-white text-dark"
                                            <?php echo $payStatus === 'failed' ? 'selected' : ''; ?>>❌ Thất bại</option>
                                    </select>
                                </td>

                                <!-- Dropdown Trạng thái đơn hàng (đã đổi màu nền) -->
                                <td>
                                    <select name="status"
                                        class="form-select form-select-sm fw-semibold <?php echo $statusSelectColor; ?>"
                                        style="font-size: 0.85rem;" onchange="updateSelectColor(this)">
                                        <option value="pending" class="bg-white text-dark"
                                            <?php echo $status === 'pending' ? 'selected' : ''; ?>>⏳ Chờ xử lý</option>
                                        <option value="confirmed" class="bg-white text-dark"
                                            <?php echo $status === 'confirmed' ? 'selected' : ''; ?>>⚙️ Đã xác nhận
                                        </option>
                                        <option value="shipping" class="bg-white text-dark"
                                            <?php echo $status === 'shipping' ? 'selected' : ''; ?>>🚚 Đang giao hàng
                                        </option>
                                        <option value="delivered" class="bg-white text-dark"
                                            <?php echo $status === 'delivered' ? 'selected' : ''; ?>>🎉 Đã giao hàng
                                        </option>
                                        <option value="cancelled" class="bg-white text-dark"
                                            <?php echo $status === 'cancelled' ? 'selected' : ''; ?>>🚫 Đã hủy</option>
                                    </select>
                                </td>

                                <!-- Tổng tiền nổi bật -->
                                <td class="text-end fw-bold text-dark fs-6">
                                    <span
                                        class="text-primary"><?php echo number_format($order['final_amount'] ?? 0, 0, ',', '.'); ?>
                                        ₫</span>
                                </td>

                                <!-- Ngày đặt kèm biểu tượng thời gian -->
                                <td class="text-muted" style="font-size: 0.85rem;">
                                    <span class="d-flex align-items-center">
                                        <i class="bi bi-calendar3 me-1.5 text-secondary"></i>
                                        <?php echo !empty($order['created_at']) ? date('d/m/Y H:i', strtotime($order['created_at'])) : ''; ?>
                                    </span>
                                </td>

                                <!-- Nhóm nút Hành động đã sửa gap-2 giúp cách nhau -->
                                <td class="text-center">
                                    <div class="d-flex gap-2 justify-content-center">
                                        <button type="submit"
                                            class="btn btn-sm btn-success px-2.5 py-1.5 fw-bold d-flex align-items-center shadow-sm">
                                            <i class="bi bi-save-fill me-1"></i> Lưu
                                        </button>
                                        <button
                                            class="btn btn-sm btn-indigo btn-outline-primary px-2.5 py-1.5 fw-bold d-flex align-items-center shadow-sm"
                                            type="button"
                                            onclick="toggleOrderItems(<?php echo htmlspecialchars($order['order_id'] ?? ''); ?>)">
                                            <i class="bi bi-eye-fill me-1"></i> Chi tiết
                                        </button>
                                    </div>
                                </td>
                            </form>
                        </tr>

                        <!-- Bảng chi tiết sản phẩm con -->
                        <tr id="items-row-<?php echo htmlspecialchars($order['order_id'] ?? ''); ?>" class="d-none"
                            style="background-color: #f8fafc;">
                            <td colspan="8" class="p-3">
                                <div class="card border-0 shadow-sm mx-3" style="border-radius: 8px;">
                                    <div class="card-header bg-light py-2 px-3 border-bottom d-flex align-items-center">
                                        <i class="bi bi-cart4 text-primary me-2"></i>
                                        <span class="fw-bold text-secondary" style="font-size: 0.85rem;">
                                            DANH SÁCH SẢN PHẨM (ĐƠN HÀNG
                                            #<?php echo htmlspecialchars($order['order_id'] ?? ''); ?>)
                                        </span>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-bordered align-middle mb-0 table-sm">
                                                <thead class="table-light text-secondary" style="font-size: 0.82rem;">
                                                    <tr>
                                                        <th class="ps-3">Sản phẩm</th>
                                                        <th class="text-center" style="width: 150px;">Mã SKU</th>
                                                        <th class="text-end" style="width: 150px;">Đơn giá</th>
                                                        <th class="text-center" style="width: 100px;">Số lượng</th>
                                                        <th class="text-end" style="width: 180px;">Thành tiền</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach (($order['items'] ?? []) as $item): ?>
                                                    <tr style="font-size: 0.88rem;">
                                                        <td class="ps-3">
                                                            <span class="fw-bold text-dark">
                                                                <i class="bi bi-arrow-right-short text-primary"></i>
                                                                <?php echo htmlspecialchars($item['product_name'] ?? ''); ?>
                                                            </span>
                                                        </td>
                                                        <td class="text-center">
                                                            <span
                                                                class="badge bg-secondary-subtle text-secondary-emphasis"><?php echo htmlspecialchars($item['sku_code'] ?? ''); ?></span>
                                                        </td>
                                                        <td class="text-end text-muted">
                                                            <?php echo number_format($item['price'] ?? 0, 0, ',', '.'); ?>
                                                            ₫
                                                        </td>
                                                        <td class="text-center fw-semibold">
                                                            <?php echo htmlspecialchars($item['quantity'] ?? 0); ?></td>
                                                        <td class="text-end fw-bold text-success">
                                                            <?php echo number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 0), 0, ',', '.'); ?>
                                                            ₫
                                                        </td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
/**
 * Ẩn hiển thị chi tiết sản phẩm của đơn hàng
 */
function toggleOrderItems(orderId) {
    const row = document.getElementById('items-row-' + orderId);
    if (row) {
        if (row.classList.contains('d-none')) {
            row.classList.remove('d-none');
        } else {
            row.classList.add('d-none');
        }
    }
}

/**
 * Xử lý đổi màu trực tiếp khi Admin thay đổi trạng thái chọn của Dropdown trước khi bấm Lưu
 */
function updateSelectColor(select) {
    // Xóa bỏ tất cả class màu nền cũ
    select.classList.remove(
        'bg-warning-subtle', 'text-warning-emphasis', 'border-warning',
        'bg-primary-subtle', 'text-primary', 'border-primary',
        'bg-info-subtle', 'text-info-emphasis', 'border-info',
        'bg-success-subtle', 'text-success', 'border-success',
        'bg-danger-subtle', 'text-danger', 'border-danger'
    );

    const value = select.value;

    // Áp dụng lớp màu nền mới phù hợp với lựa chọn vừa thay đổi
    if (value === 'pending' || value === 'unpaid') {
        select.classList.add('bg-warning-subtle', 'text-warning-emphasis', 'border-warning');
    } else if (value === 'confirmed') {
        select.classList.add('bg-primary-subtle', 'text-primary', 'border-primary');
    } else if (value === 'shipping') {
        select.classList.add('bg-info-subtle', 'text-info-emphasis', 'border-info');
    } else if (value === 'delivered' || value === 'paid') {
        select.classList.add('bg-success-subtle', 'text-success', 'border-success');
    } else if (value === 'cancelled' || value === 'failed') {
        select.classList.add('bg-danger-subtle', 'text-danger', 'border-danger');
    }
}
</script>