<!-- CSS dành riêng cho trang Cá nhân kiểu Apple Premium -->
<style>
:root {
    --profile-primary: #0071e3;
    --profile-primary-hover: #0077ed;
    --profile-dark: #1d1d1f;
    --profile-light-gray: #f5f5f7;
    --profile-border: #e5e5e7;
    --profile-text-gray: #86868b;
    --profile-transition: all 0.3s cubic-bezier(0.25, 0.1, 0.25, 1);
    --profile-shadow: 0 12px 40px rgba(0, 0, 0, 0.03);
}

.profile-wrapper {
    margin-top: 70px;
    min-height: calc(100vh - 70px);
    background-color: #f5f5f7;
    padding: 50px 20px;
}

.profile-container {
    max-width: 1000px;
    margin: 0 auto;
}

/* Tiêu đề trang */
.profile-title-area {
    margin-bottom: 32px;
    text-align: left;
}

.profile-title-area h1 {
    font-size: 2rem;
    font-weight: 800;
    letter-spacing: -0.8px;
    color: var(--profile-dark);
    margin-bottom: 6px;
}

.profile-title-area p {
    font-size: 0.95rem;
    color: var(--profile-text-gray);
    font-weight: 500;
}

/* Layout chia 2 cột */
.profile-grid {
    display: grid;
    grid-template-columns: 320px 1fr;
    gap: 30px;
}

@media (max-width: 900px) {
    .profile-grid {
        grid-template-columns: 1fr;
    }
}

/* Các Card trắng */
.profile-card {
    background-color: #ffffff;
    border: 1px solid var(--profile-border);
    border-radius: 20px;
    padding: 30px;
    box-shadow: var(--profile-shadow);
    transition: var(--profile-transition);
}

.profile-card:hover {
    border-color: #d2d2d7;
}

/* Cột trái: Avatar và Trạng thái */
.profile-sidebar {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}

.avatar-edit-container {
    position: relative;
    width: 130px;
    height: 130px;
    margin-bottom: 20px;
}

.avatar-profile-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
    border: 3px solid #ffffff;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    background-color: var(--profile-light-gray);
    transition: var(--profile-transition);
}

/* Nút Hover thay đổi avatar */
.avatar-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(29, 29, 31, 0.6);
    border-radius: 50%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #ffffff;
    opacity: 0;
    transition: var(--profile-transition);
    cursor: pointer;
    font-size: 0.75rem;
    font-weight: 600;
}

.avatar-overlay i {
    font-size: 1.4rem;
    margin-bottom: 4px;
}

.avatar-edit-container:hover .avatar-overlay {
    opacity: 1;
}

.avatar-edit-container:hover .avatar-profile-image {
    transform: scale(1.02);
}

.avatar-file-input {
    display: none;
}

.user-display-name {
    font-size: 1.25rem;
    font-weight: 800;
    color: var(--profile-dark);
    margin-bottom: 6px;
    letter-spacing: -0.5px;
}

.user-display-role {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 20px;
    background-color: var(--profile-light-gray);
    color: var(--profile-dark);
    font-size: 0.78rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 24px;
}

.sidebar-info-list {
    width: 100%;
    border-top: 1px solid var(--profile-border);
    padding-top: 20px;
    text-align: left;
}

.sidebar-info-item {
    display: flex;
    justify-content: space-between;
    font-size: 0.85rem;
    margin-bottom: 12px;
    color: var(--profile-text-gray);
    font-weight: 500;
}

.sidebar-info-item strong {
    color: var(--profile-dark);
    font-weight: 600;
}

/* Cột phải: Form cập nhật */
.profile-main-content h3 {
    font-size: 1.3rem;
    font-weight: 800;
    color: var(--profile-dark);
    margin-bottom: 24px;
    letter-spacing: -0.5px;
    text-align: left;
}

.profile-form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

@media (max-width: 650px) {
    .profile-form-grid {
        grid-template-columns: 1fr;
    }
}

.profile-group {
    margin-bottom: 20px;
    text-align: left;
}

.profile-group label {
    display: block;
    font-size: 0.82rem;
    font-weight: 700;
    color: var(--profile-dark);
    margin-bottom: 8px;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.profile-control {
    width: 100%;
    padding: 14px 16px;
    font-family: inherit;
    font-size: 0.95rem;
    color: var(--profile-dark);
    background-color: var(--profile-light-gray);
    border: 1.5px solid transparent;
    border-radius: 12px;
    outline: none;
    transition: var(--profile-transition);
    font-weight: 500;
}

.profile-control:focus {
    background-color: #ffffff;
    border-color: var(--profile-primary);
    box-shadow: 0 0 0 4px rgba(0, 113, 227, 0.08);
}

.profile-control:disabled {
    background-color: #eaeaea;
    color: var(--profile-text-gray);
    cursor: not-allowed;
}

/* Các banner thông báo */
.profile-alert {
    padding: 14px 18px;
    border-radius: 12px;
    font-size: 0.88rem;
    font-weight: 600;
    margin-bottom: 24px;
    text-align: left;
    display: flex;
    align-items: center;
    gap: 12px;
}

.profile-alert-success {
    background-color: rgba(52, 199, 89, 0.08);
    border: 1px solid rgba(52, 199, 89, 0.2);
    color: #34c759;
}

.profile-alert-error {
    background-color: rgba(255, 59, 48, 0.06);
    border: 1px solid rgba(255, 59, 48, 0.15);
    color: #ff3b30;
}

/* Nút Lưu */
.btn-save-profile {
    display: inline-block;
    padding: 14px 30px;
    background: var(--profile-dark);
    color: #ffffff;
    font-size: 0.95rem;
    font-weight: 700;
    border: none;
    border-radius: 12px;
    cursor: pointer;
    transition: var(--profile-transition);
    margin-top: 10px;
}

.btn-save-profile:hover {
    background: var(--profile-primary);
    box-shadow: 0 4px 12px rgba(0, 113, 227, 0.2);
}
</style>

<?php
// Lấy thông tin user hiện tại từ session
$currentUser = $_SESSION['user'] ?? null;
$avatarUrl = '';

if ($currentUser && !empty($currentUser['avatar_url'])) {
    $avatarUrl = $currentUser['avatar_url'];
    
    // Xử lý chuyển đổi đường dẫn ảnh tương tự file header.php
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
} else {
    // Đặt ảnh mặc định nếu không có avatar
    $avatarUrl = 'https://www.w3schools.com/howto/img_avatar.png';
}
?>

<div class="profile-wrapper">
    <div class="profile-container">
        <!-- Tiêu đề trang cá nhân -->
        <div class="profile-title-area">
            <h1>Cài đặt tài khoản</h1>
            <p>Quản lý thông tin hồ sơ cá nhân và cập nhật địa chỉ giao hàng của bạn.</p>
        </div>

        <!-- HIỂN THỊ THÔNG BÁO THÀNH CÔNG HOẶC THẤT BẠI TỪ CONTROLLER (Nếu có) -->
        <?php if (!empty($_SESSION['profile_success'])): ?>
        <div class="profile-alert profile-alert-success">
            <i class="fas fa-check-circle"></i>
            <span><?= htmlspecialchars($_SESSION['profile_success']); ?></span>
        </div>
        <?php unset($_SESSION['profile_success']); ?>
        <?php endif; ?>

        <?php if (!empty($_SESSION['profile_error'])): ?>
        <div class="profile-alert profile-alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <span><?= htmlspecialchars($_SESSION['profile_error']); ?></span>
        </div>
        <?php unset($_SESSION['profile_error']); ?>
        <?php endif; ?>

        <div class="profile-grid">

            <!-- CỘT BÊN TRÁI: SIDEBAR -->
            <div class="profile-card profile-sidebar">
                <!-- Form upload avatar độc lập (hoặc tích hợp chung form bên phải) -->
                <!-- Ở đây tích hợp sẵn input ẩn để gửi cùng form chính -->
                <form action="/assignment/account/update" method="POST" enctype="multipart/form-data"
                    style="width: 100%; display: flex; flex-direction: column; align-items: center;">

                    <div class="avatar-edit-container" onclick="triggerProfileAvatarSelect()">
                        <img id="profileAvatarPreview" src="<?= htmlspecialchars($avatarUrl); ?>" alt="Avatar"
                            class="avatar-profile-image">
                        <div class="avatar-overlay">
                            <i class="fas fa-camera"></i>
                            <span>Thay đổi</span>
                        </div>
                    </div>

                    <!-- File input ẩn -->
                    <input type="file" id="profileAvatarInput" name="avatar" class="avatar-file-input" accept="image/*"
                        onchange="previewProfileAvatar(this)">

                    <div class="user-display-name"><?= htmlspecialchars($currentUser['name'] ?? 'Chưa đặt tên'); ?>
                    </div>
                    <div class="user-display-role"><?= htmlspecialchars($currentUser['role'] ?? 'user'); ?></div>

                    <div class="sidebar-info-list">
                        <div class="sidebar-info-item">
                            <span>ID tài khoản</span>
                            <strong>#<?= htmlspecialchars($currentUser['id'] ?? '0'); ?></strong>
                        </div>
                        <div class="sidebar-info-item">
                            <span>Ngày đăng ký</span>
                            <strong><?= !empty($currentUser['created_at']) ? date('d/m/Y', strtotime($currentUser['created_at'])) : 'Chưa rõ'; ?></strong>
                        </div>
                        <div class="sidebar-info-item">
                            <span>Phương thức đăng nhập</span>
                            <strong><?= htmlspecialchars(strtoupper($currentUser['provider'] ?? 'local')); ?></strong>
                        </div>
                    </div>
            </div>

            <!-- CỘT BÊN PHẢI: FORM CHỈNH SỬA THÔNG TIN -->
            <div class="profile-card profile-main-content">
                <h3>Thông tin cá nhân</h3>

                <div class="profile-form-grid">
                    <!-- Họ và tên -->
                    <div class="profile-group">
                        <label for="profileName">Họ và tên</label>
                        <input type="text" id="profileName" name="name" class="profile-control"
                            value="<?= htmlspecialchars($currentUser['name'] ?? ''); ?>" required
                            placeholder="Nhập họ và tên">
                    </div>

                    <!-- Email (Khóa không cho sửa hoặc để readonly) -->
                    <div class="profile-group">
                        <label for="profileEmail">Địa chỉ Email <i class="fas fa-lock"
                                style="font-size:0.75rem; color:var(--profile-text-gray); margin-left: 4px;"></i></label>
                        <input type="email" id="profileEmail" class="profile-control"
                            value="<?= htmlspecialchars($currentUser['email'] ?? ''); ?>" disabled>
                        <!-- Gửi email ẩn lên controller xử lý nếu cần -->
                        <input type="hidden" name="email" value="<?= htmlspecialchars($currentUser['email'] ?? ''); ?>">
                    </div>

                    <!-- Số điện thoại -->
                    <div class="profile-group">
                        <label for="profilePhone">Số điện thoại</label>
                        <input type="tel" id="profilePhone" name="phone" class="profile-control"
                            value="<?= htmlspecialchars($currentUser['phone'] ?? ''); ?>"
                            placeholder="Nhập số điện thoại">
                    </div>

                    <!-- Địa chỉ nhận hàng -->
                    <div class="profile-group" style="grid-column: span 2;">
                        <label for="profileAddress">Địa chỉ giao hàng mặc định</label>
                        <input type="text" id="profileAddress" name="address" class="profile-control"
                            value="<?= htmlspecialchars($currentUser['address'] ?? ''); ?>"
                            placeholder="Ví dụ: 123 Lê Lợi, Quận 1, TP. Hồ Chí Minh">
                    </div>
                </div>

                <div
                    style="text-align: right; border-top: 1px solid var(--profile-border); margin-top: 24px; padding-top: 12px;">
                    <button type="submit" class="btn-save-profile">Cập nhật hồ sơ</button>
                </div>
                </form> <!-- Đóng form chính -->
            </div>

        </div>
    </div>
</div>

<!-- Script xử lý sự kiện tải ảnh đại diện và hiển thị Live Preview -->
<script>
function triggerProfileAvatarSelect() {
    document.getElementById('profileAvatarInput').click();
}

function previewProfileAvatar(input) {
    const file = input.files[0];
    if (file) {
        // Kiểm tra đúng định dạng ảnh
        if (!file.type.startsWith('image/')) {
            alert('Vui lòng chọn tệp hình ảnh hợp lệ.');
            input.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('profileAvatarPreview').src = e.target.result;
        }
        reader.readAsDataURL(file);
    }
}
</script>