<!-- Nhúng Bootstrap 5 và Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
.custom-card {
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.075);
}

.gradient-header {
    background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
}

.filter-card {
    border-radius: 16px;
    background: #ffffff;
    border: 1px solid rgba(226, 232, 240, 0.8);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
}

.table-hover tbody tr:hover {
    background-color: rgba(241, 245, 249, 0.85) !important;
    transition: background-color 0.2s ease;
}

.prod-status-published {
    border-left: 5px solid #198754 !important;
}

.prod-status-draft {
    border-left: 5px solid #ffc107 !important;
}

.prod-status-out_of_stock {
    border-left: 5px solid #dc3545 !important;
}

.product-img-th {
    width: 55px;
    height: 55px;
    object-fit: cover;
    border-radius: 10px;
    border: 2px solid #ffffff;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.text-price {
    color: #4f46e5 !important;
    font-size: 0.95rem;
}

.image-preview-box {
    max-height: 140px;
    object-fit: contain;
    border-radius: 8px;
    border: 2px dashed #cbd5e1;
    padding: 4px;
}

.form-section-title {
    font-size: 0.9rem;
    color: #4f46e5;
    border-left: 3px solid #4f46e5;
    padding-left: 8px;
    margin-bottom: 12px;
}
</style>

<div class="container-fluid py-4" style="background-color: #f1f5f9; min-height: 100vh;">

    <!-- KHU VỰC BỘ LỌC TÌM KIẾM SẢN PHẨM -->
    <div class="card filter-card border-0 mb-4">
        <div class="card-body p-3.5">
            <form method="GET" action="/assignment/admin/products" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label fw-bold text-secondary mb-1.5" style="font-size: 0.85rem;">Tìm sản
                        phẩm</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light text-muted border-end-0"><i
                                class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 ps-1"
                            placeholder="Tên hoặc mã SKU..."
                            value="<?php echo htmlspecialchars($filters['search'] ?? ''); ?>">
                    </div>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold text-secondary mb-1.5" style="font-size: 0.85rem;">Danh mục</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light text-muted border-end-0"><i
                                class="bi bi-tag-fill"></i></span>
                        <select name="category_id" class="form-select border-start-0 ps-1">
                            <option value="">-- Tất cả danh mục --</option>
                            <?php foreach (($categories ?? []) as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>"
                                <?php echo ($filters['category_id'] ?? '') == $cat['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat['name'] ?? ''); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-bold text-secondary mb-1.5" style="font-size: 0.85rem;">Thương
                        hiệu</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light text-muted border-end-0"><i
                                class="bi bi-award-fill"></i></span>
                        <select name="brand_id" class="form-select border-start-0 ps-1">
                            <option value="">-- Tất cả --</option>
                            <?php foreach (($brands ?? []) as $brand): ?>
                            <option value="<?php echo $brand['id']; ?>"
                                <?php echo ($filters['brand_id'] ?? '') == $brand['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($brand['name'] ?? ''); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="col-md-2">
                    <label class="form-label fw-bold text-secondary mb-1.5" style="font-size: 0.85rem;">Trạng
                        thái</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light text-muted border-end-0"><i
                                class="bi bi-ui-checks-grid"></i></span>
                        <select name="status" class="form-select border-start-0 ps-1">
                            <option value="">-- Tất cả --</option>
                            <option value="published"
                                <?php echo ($filters['status'] ?? '') === 'published' ? 'selected' : ''; ?>>Đang bán
                            </option>
                            <option value="draft"
                                <?php echo ($filters['status'] ?? '') === 'draft' ? 'selected' : ''; ?>>Bản nháp
                            </option>
                            <option value="out_of_stock"
                                <?php echo ($filters['status'] ?? '') === 'out_of_stock' ? 'selected' : ''; ?>>Hết hàng
                            </option>
                        </select>
                    </div>
                </div>

                <div class="col-md-2 text-end">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-sm btn-primary w-100 fw-bold shadow-sm py-2">
                            <i class="bi bi-funnel-fill me-1"></i> Lọc
                        </button>
                        <a href="/assignment/admin/products"
                            class="btn btn-sm btn-outline-secondary w-100 fw-bold py-2">Xóa lọc</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- KHU VỰC DANH SÁCH SẢN PHẨM -->
    <div class="card custom-card shadow border-0">
        <div
            class="card-header gradient-header py-3.5 d-flex justify-content-between align-items-center border-bottom-0">
            <h5 class="m-0 fw-bold text-white d-flex align-items-center">
                <i class="bi bi-laptop-fill me-2"></i> Quản Lý Sản Phẩm & Biến Thể Kho
            </h5>
            <button class="btn btn-white bg-white text-primary fw-bold px-3.5 py-2 rounded-pill shadow-sm fs-6"
                data-bs-toggle="modal" data-bs-target="#productModal" onclick="openCreateModal()">
                <i class="bi bi-plus-circle-fill me-1"></i> Thêm Sản Phẩm Mới
            </button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark" style="background-color: #1e293b;">
                        <tr>
                            <th class="ps-3" style="width: 80px;">Hình ảnh</th>
                            <th>Thông tin sản phẩm chung</th>
                            <th>Mã SKU</th>
                            <th class="text-center">Tồn kho</th>
                            <th class="text-end" style="width: 180px;">Giá bán lẻ (Giá cũ)</th>
                            <th class="text-center" style="width: 110px;">Trạng thái</th>
                            <th class="text-center" style="width: 180px;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($products)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-folder2-open fs-1 d-block mb-2 text-secondary"></i> Không tìm thấy sản
                                phẩm nào phù hợp.
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($products as $p): ?>
                        <?php 
                            $status = $p['status'] ?? 'published';
                            $rowBorderClass = 'prod-status-published';
                            $statusBadge = 'bg-success-subtle text-success border border-success';
                            $statusText = 'Đang bán';
                            
                            if ($status === 'draft') {
                                $rowBorderClass = 'prod-status-draft';
                                $statusBadge = 'bg-warning-subtle text-warning-emphasis border border-warning';
                                $statusText = 'Bản nháp';
                            } elseif ($status === 'out_of_stock') {
                                $rowBorderClass = 'prod-status-out_of_stock';
                                $statusBadge = 'bg-danger-subtle text-danger border border-danger';
                                $statusText = 'Hết hàng';
                            }
                        ?>
                        <tr class="<?php echo $rowBorderClass; ?>">
                            <!-- Ảnh -->
                            <td class="ps-3">
                                <?php 
                                    $imgUrl = !empty($p['main_image']) ? '/assignment/public/uploads' . $p['main_image'] : '/assignment/public/uploads/products/default.jpg';
                                ?>
                                <img src="<?php echo htmlspecialchars($imgUrl); ?>" class="product-img-th"
                                    alt="product">
                            </td>

                            <!-- Thông tin cha -->
                            <td>
                                <div class="fw-bold text-dark mb-0.5"><?php echo htmlspecialchars($p['name'] ?? ''); ?>
                                </div>
                                <div class="text-muted d-flex gap-2" style="font-size: 0.8rem;">
                                    <span><i
                                            class="bi bi-folder-fill me-1"></i><?php echo htmlspecialchars($p['category_name'] ?? ''); ?></span>
                                    |
                                    <span><i
                                            class="bi bi-award-fill me-1"></i><?php echo htmlspecialchars($p['brand_name'] ?? ''); ?></span>
                                </div>
                            </td>

                            <!-- Mã SKU vật lý -->
                            <td>
                                <code class="text-primary fw-bold" style="font-size: 0.85rem;">
                                    <?php echo htmlspecialchars($p['sku_code'] ?? 'CHƯA CÓ SKU'); ?>
                                </code>
                            </td>

                            <!-- Tồn kho -->
                            <td class="text-center">
                                <?php if (($p['sku_stock_qty'] ?? 0) <= 5): ?>
                                <span class="badge bg-danger-subtle text-danger fw-bold"><i
                                        class="bi bi-exclamation-triangle-fill"></i> Chỉ còn
                                    <?php echo (int)$p['sku_stock_qty']; ?></span>
                                <?php else: ?>
                                <span
                                    class="badge bg-secondary-subtle text-dark fw-bold"><?php echo (int)$p['sku_stock_qty']; ?>
                                    sản phẩm</span>
                                <?php endif; ?>
                            </td>

                            <!-- Giá thực tế của SKU -->
                            <td class="text-end">
                                <div class="fw-bold text-price">
                                    <?php echo number_format((float)($p['sku_price'] ?? $p['base_price'] ?? 0.0), 0, ',', '.'); ?>
                                    ₫</div>
                                <?php if (!empty($p['sku_old_price']) && $p['sku_old_price'] > 0): ?>
                                <del class="text-muted"
                                    style="font-size: 0.75rem;"><?php echo number_format((float)$p['sku_old_price'], 0, ',', '.'); ?>
                                    ₫</del>
                                <?php endif; ?>
                            </td>

                            <!-- Trạng thái -->
                            <td class="text-center">
                                <span class="badge <?php echo $statusBadge; ?> px-2.5 py-1.5 fw-bold">
                                    <?php echo $statusText; ?>
                                </span>
                            </td>

                            <!-- Sửa / Xóa -->
                            <td class="text-center">
                                <div class="d-flex gap-2 justify-content-center">
                                    <button
                                        class="btn btn-sm btn-outline-primary fw-bold px-2.5 py-1.5 d-flex align-items-center"
                                        onclick="openEditModal(<?php echo htmlspecialchars(json_encode($p)); ?>)">
                                        <i class="bi bi-pencil-square me-1"></i> Sửa
                                    </button>
                                    <form action="/assignment/admin/products/delete" method="POST" class="m-0"
                                        onsubmit="return confirm('Mọi SKU và hình ảnh liên kết với sản phẩm này sẽ bị xóa. Bạn có chắc chắn?')">
                                        <input type="hidden" name="product_id" value="<?php echo $p['id']; ?>">
                                        <button type="submit"
                                            class="btn btn-sm btn-danger fw-bold px-2.5 py-1.5 d-flex align-items-center">
                                            <i class="bi bi-trash-fill me-1"></i> Xóa
                                        </button>
                                    </form>
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

<!-- MODAL THÊM / SỬA SẢN PHẨM -->
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content"
            style="border-radius: 12px; overflow: hidden; box-shadow: 0 15px 50px rgba(0,0,0,0.15);">
            <div class="modal-header gradient-header text-white">
                <h5 class="modal-title fw-bold" id="productModalLabel">Khởi tạo sản phẩm mới</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form action="/assignment/admin/products/save" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <!-- ID sản phẩm ẩn -->
                    <input type="hidden" name="product_id" id="form_product_id" value="">

                    <div class="row g-3">

                        <!-- PHẦN 1: THÔNG TIN CHUNG (PARENT PRODUCT) -->
                        <div class="col-md-12">
                            <h6 class="form-section-title"><i class="bi bi-info-circle-fill"></i> Phần 1: Thông tin sản
                                phẩm chung</h6>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold text-secondary mb-1">Tên sản phẩm *</label>
                            <input type="text" name="name" id="form_name" class="form-control form-control-sm border"
                                required placeholder="Ví dụ: Laptop Gaming MSI Katana 15">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold text-secondary mb-1">Danh mục *</label>
                            <select name="category_id" id="form_category_id" class="form-select form-select-sm"
                                required>
                                <option value="">-- Chọn danh mục --</option>
                                <?php foreach (($categories ?? []) as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>">
                                    <?php echo htmlspecialchars($cat['name'] ?? ''); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold text-secondary mb-1">Thương hiệu *</label>
                            <select name="brand_id" id="form_brand_id" class="form-select form-select-sm" required>
                                <option value="">-- Chọn thương hiệu --</option>
                                <?php foreach (($brands ?? []) as $brand): ?>
                                <option value="<?php echo $brand['id']; ?>">
                                    <?php echo htmlspecialchars($brand['name'] ?? ''); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold text-secondary mb-1">Giá tham chiếu tối thiểu *</label>
                            <input type="number" name="base_price" id="form_base_price"
                                class="form-control form-control-sm" required min="0"
                                placeholder="Giá niêm yết tối thiểu">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold text-secondary mb-1">Trạng thái phát hành</label>
                            <select name="status" id="form_status" class="form-select form-select-sm">
                                <option value="published">Đang bán (Published)</option>
                                <option value="draft">Bản nháp (Draft)</option>
                                <option value="out_of_stock">Hết hàng (Out of stock)</option>
                            </select>
                        </div>

                        <!-- Ảnh chính sản phẩm và Preview -->
                        <div class="col-md-8">
                            <label class="form-label fw-bold text-secondary mb-1">Hình ảnh đại diện sản phẩm</label>
                            <input type="file" name="product_image" id="form_product_image"
                                class="form-control form-control-sm" accept="image/*"
                                onchange="previewUploadImage(this)">
                        </div>

                        <div class="col-md-12">
                            <div id="preview_container" class="text-center p-2 border rounded bg-light"
                                style="display: none;">
                                <img id="image_preview_display" src="" class="image-preview-box" alt="Xem trước ảnh">
                            </div>
                        </div>

                        <!-- PHẦN 2: THÔNG TIN KHO VÀ SKU BIẾN THỂ (DEFAULT SKU) -->
                        <div class="col-md-12 mt-4">
                            <h6 class="form-section-title"><i class="bi bi-box-seam-fill"></i> Phần 2: Cấu hình Kho hàng
                                & SKU bán lẻ (Default SKU)</h6>
                        </div>

                        <div class="col-md-12">
                            <div class="card p-3 bg-light border-0 rounded-3">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-secondary mb-1">Mã SKU bán lẻ *</label>
                                        <input type="text" name="sku_code" id="form_sku_code"
                                            class="form-control form-control-sm" required
                                            placeholder="Để trống hệ thống sẽ tự động tạo mã" readonly>
                                        <small class="text-muted" style="font-size: 0.75rem;">Mã quản lý tồn kho cố định
                                            của biến thể</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-secondary mb-1">Số lượng tồn kho ban đầu
                                            *</label>
                                        <input type="number" name="sku_stock_qty" id="form_sku_stock_qty"
                                            class="form-control form-control-sm" required min="0" value="100">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-secondary mb-1">Giá bán thực tế của SKU
                                            *</label>
                                        <input type="number" name="sku_price" id="form_sku_price"
                                            class="form-control form-control-sm" required min="0"
                                            placeholder="Giá bán trực tiếp">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold text-secondary mb-1">Giá trị gốc (Giá so sánh /
                                            Giá cũ)</label>
                                        <input type="number" name="sku_old_price" id="form_sku_old_price"
                                            class="form-control form-control-sm" min="0"
                                            placeholder="Để trống nếu không giảm giá">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Mô tả chi tiết -->
                        <div class="col-md-12">
                            <h6 class="form-section-title mt-2"><i class="bi bi-justify-left"></i> Phần 3: Nội dung mô
                                tả chi tiết</h6>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold text-secondary mb-1">Mô tả ngắn gọn</label>
                            <textarea name="short_description" id="form_short_description"
                                class="form-control form-control-sm" rows="2"
                                placeholder="Tóm tắt tính năng nổi bật..."></textarea>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-bold text-secondary mb-1">Mô tả đầy đủ thông số</label>
                            <textarea name="long_description" id="form_long_description"
                                class="form-control form-control-sm" rows="4"
                                placeholder="Thông số kỹ thuật chi tiết..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-sm btn-outline-secondary fw-bold"
                        data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-sm btn-primary fw-bold px-3">Lưu thông tin</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
const productModal = new bootstrap.Modal(document.getElementById('productModal'));

function previewUploadImage(input) {
    const previewContainer = document.getElementById('preview_container');
    const previewDisplay = document.getElementById('image_preview_display');

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewDisplay.src = e.target.result;
            previewContainer.style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function openCreateModal() {
    document.getElementById('productModalLabel').innerText = "Thêm sản phẩm & SKU mới";

    document.getElementById('form_product_id').value = "";
    document.getElementById('form_name').value = "";
    document.getElementById('form_category_id').value = "";
    document.getElementById('form_brand_id').value = "";
    document.getElementById('form_base_price').value = "";
    document.getElementById('form_status').value = "published";
    document.getElementById('form_short_description').value = "";
    document.getElementById('form_long_description').value = "";
    document.getElementById('form_product_image').value = "";

    // Reset các trường cấu hình của SKU
    const skuField = document.getElementById('form_sku_code');
    skuField.value = "";
    skuField.readOnly = true; // Cho phép hệ thống tự sinh mã
    skuField.placeholder = "Hệ thống tự tạo mã khi lưu";

    document.getElementById('form_sku_stock_qty').value = "100";
    document.getElementById('form_sku_price').value = "";
    document.getElementById('form_sku_old_price').value = "";

    const previewContainer = document.getElementById('preview_container');
    const previewDisplay = document.getElementById('image_preview_display');
    previewDisplay.src = "";
    previewContainer.style.display = "none";
}

function openEditModal(product) {
    document.getElementById('productModalLabel').innerText = "Cập nhật sản phẩm & SKU";

    document.getElementById('form_product_id').value = product.id ?? "";
    document.getElementById('form_name').value = product.name ?? "";
    document.getElementById('form_category_id').value = product.category_id ?? "";
    document.getElementById('form_brand_id').value = product.brand_id ?? "";
    document.getElementById('form_base_price').value = product.base_price ? Math.round(product.base_price) : "";
    document.getElementById('form_status').value = product.status ?? "published";
    document.getElementById('form_short_description').value = product.short_description ?? "";
    document.getElementById('form_long_description').value = product.long_description ?? "";
    document.getElementById('form_product_image').value = "";

    // Đổ dữ liệu SKU mặc định hiện tại
    const skuField = document.getElementById('form_sku_code');
    skuField.value = product.sku_code ?? "";
    skuField.readOnly = true; // Khóa trường SKU không cho sửa mã ở giao diện thông thường để bảo vệ đơn hàng cũ

    document.getElementById('form_sku_stock_qty').value = product.sku_stock_qty ?? 0;
    document.getElementById('form_sku_price').value = product.sku_price ? Math.round(product.sku_price) : "";
    document.getElementById('form_sku_old_price').value = product.sku_old_price ? Math.round(product.sku_old_price) :
    "";

    const previewContainer = document.getElementById('preview_container');
    const previewDisplay = document.getElementById('image_preview_display');

    if (product.main_image) {
        previewDisplay.src = '/assignment/public/uploads' + product.main_image;
        previewContainer.style.display = 'block';
    } else {
        previewDisplay.src = "";
        previewContainer.style.display = 'none';
    }

    productModal.show();
}
</script>