            <div class="dashboard-header">
                <div class="dashboard-title">
                    <h1>Tổng quan hệ thống</h1>
                    <p>Số liệu phân tích, quản lý hóa đơn và kiểm soát kho hàng TechStore.</p>
                </div>
                <div class="dashboard-date">
                    <i class="far fa-calendar-alt" style="margin-right: 6px;"></i> Hôm nay, <?= date('d/m/Y'); ?>
                </div>
            </div>

            <!-- KPI SỐ LIỆU ĐO LƯỜNG CHÍNH -->
            <div class="kpi-grid">
                <div class="kpi-card">
                    <div class="kpi-label">Tổng Doanh Thu</div>
                    <div class="kpi-value" style="color: var(--dash-primary);">
                        <?= number_format($total_revenue, 0, ',', '.'); ?>đ</div>
                    <div class="kpi-subtext">Hợp nhất từ ví MoMo và ship COD.</div>
                </div>

                <div class="kpi-card">
                    <div class="kpi-label">Tổng Đơn Hàng</div>
                    <div class="kpi-value"><?= number_format($total_orders, 0, ',', '.'); ?></div>
                    <div class="kpi-subtext">Gồm đơn hoàn tất và đang xử lý.</div>
                </div>

                <div class="kpi-card">
                    <div class="kpi-label">Chờ Xử Lý (Pending)</div>
                    <div class="kpi-value" style="color: #ff9f0a;">
                        <?= number_format($pending_orders_count, 0, ',', '.'); ?></div>
                    <div class="kpi-subtext">Đơn hàng mới cần quản trị duyệt.</div>
                </div>

                <div class="kpi-card">
                    <div class="kpi-label">Cảnh báo tồn kho</div>
                    <div class="kpi-value" style="color: #ff3b30;"><?= count($low_stock); ?></div>
                    <div class="kpi-subtext">Mẫu sản phẩm có mức tồn dưới 10.</div>
                </div>
            </div>

            <!-- GRID CHÍNH: BẢNG SỐ LIỆU TỔNG QUAN -->
            <div class="dashboard-grid-main">
                <div class="dash-card">
                    <div class="card-title">Đơn hàng mới nhận gần đây</div>
                    <div class="table-wrapper">
                        <table class="dash-table">
                            <thead>
                                <tr>
                                    <th>Mã đơn</th>
                                    <th>Khách hàng</th>
                                    <th>Thời gian nhận</th>
                                    <th>Thành tiền</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($recent_orders)): ?>
                                <?php foreach (array_slice($recent_orders, 0, 5) as $order): ?>
                                <tr>
                                    <td><strong>#<?= htmlspecialchars($order['id']); ?></strong></td>
                                    <td><?= htmlspecialchars($order['name']); ?></td>
                                    <td><?= date('d/m/Y H:i', strtotime($order['created_at'])); ?></td>
                                    <td><strong><?= number_format($order['final_amount'], 0, ',', '.'); ?>đ</strong>
                                    </td>
                                    <td><span class="status-badge badge-pending">Chờ xử lý</span></td>
                                </tr>
                                <?php endforeach; ?>
                                <?php else: ?>
                                <tr>
                                    <td colspan="5" class="empty-data-msg">Không có đơn hàng mới nào.</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="dash-card">
                    <div class="card-title">Kênh thanh toán doanh thu</div>
                    <div style="margin-top: 10px;">
                        <?php foreach ($payment_stats as $payment): ?>
                        <?php $percent = $total_revenue > 0 ? ($payment['revenue'] / $total_revenue) * 100 : 0; ?>
                        <div class="payment-stat-item">
                            <div class="payment-stat-header">
                                <span><?= strtoupper(htmlspecialchars($payment['payment_method'])); ?>
                                    (<?= $payment['total']; ?> đơn)</span>
                                <strong><?= number_format($payment['revenue'], 0, ',', '.'); ?>đ</strong>
                            </div>
                            <div class="payment-progress-bar">
                                <div class="payment-progress-fill"
                                    style="width: <?= $percent; ?>%; background-color: <?= $payment['payment_method'] == 'momo' ? '#a50064' : '#0071e3'; ?>;">
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>