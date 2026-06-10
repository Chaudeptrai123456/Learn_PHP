<!-- CSS riêng cho trang Đăng ký kiểu Apple Premium -->
<style>
:root {
    --reg-primary: #0071e3;
    --reg-primary-hover: #0077ed;
    --reg-dark: #1d1d1f;
    --reg-light-gray: #f5f5f7;
    --reg-border: #e5e5e7;
    --reg-text-gray: #86868b;
    --reg-transition: all 0.3s cubic-bezier(0.25, 0.1, 0.25, 1);
    --reg-shadow: 0 12px 40px rgba(0, 0, 0, 0.03);
}

/* Đẩy trang xuống dưới Header và căn giữa */
.register-wrapper {
    margin-top: 70px;
    min-height: calc(100vh - 70px);
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #ffffff;
    padding: 50px 20px;
}

/* Khung đăng ký chính (Rộng hơn đăng nhập để form thông tin cân đối) */
.register-card {
    width: 100%;
    max-width: 480px;
    padding: 40px;
    border-radius: 24px;
    border: 1px solid var(--reg-border);
    background-color: #ffffff;
    box-shadow: var(--reg-shadow);
    text-align: center;
    transition: var(--reg-transition);
}

.register-card:hover {
    border-color: #d2d2d7;
}

/* Tiêu đề & mô tả */
.register-card h2 {
    font-size: 1.8rem;
    font-weight: 800;
    letter-spacing: -0.8px;
    color: var(--reg-dark);
    margin-bottom: 10px;
}

.register-card .subtitle {
    font-size: 0.92rem;
    color: var(--reg-text-gray);
    margin-bottom: 32px;
    line-height: 1.5;
    font-weight: 500;
}

/* Form và trường nhập liệu căn lề trái */
.register-form {
    text-align: left;
}

.register-group {
    margin-bottom: 22px;
}

.register-group label {
    display: block;
    font-size: 0.82rem;
    font-weight: 700;
    color: var(--reg-dark);
    margin-bottom: 8px;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.register-control {
    width: 100%;
    padding: 14px 16px;
    font-family: inherit;
    font-size: 0.95rem;
    color: var(--reg-dark);
    background-color: var(--reg-light-gray);
    border: 1.5px solid transparent;
    border-radius: 12px;
    outline: none;
    transition: var(--reg-transition);
    font-weight: 500;
}

.register-control:focus {
    background-color: #ffffff;
    border-color: var(--reg-primary);
    box-shadow: 0 0 0 4px rgba(0, 113, 227, 0.08);
}

/* --- KHU VỰC TẢI ẢNH ĐẠI DIỆN --- */
.avatar-upload-wrapper {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-bottom: 28px;
}

.avatar-preview-container {
    position: relative;
    width: 100px;
    height: 100px;
    border-radius: 50%;
    border: 2px dashed #d2d2d7;
    background-color: var(--reg-light-gray);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    overflow: hidden;
    transition: var(--reg-transition);
}

.avatar-preview-container:hover {
    border-color: var(--reg-primary);
    background-color: #e8e8ed;
}

.avatar-preview-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: none;
}

.avatar-placeholder {
    display: flex;
    flex-direction: column;
    align-items: center;
    color: var(--reg-text-gray);
    font-size: 0.75rem;
    font-weight: 600;
    text-align: center;
    pointer-events: none;
}

.avatar-placeholder i {
    font-size: 1.4rem;
    margin-bottom: 6px;
    color: var(--reg-text-gray);
}

.avatar-input-hidden {
    display: none;
}

.avatar-upload-label {
    margin-top: 10px;
    font-size: 0.8rem;
    color: var(--reg-primary);
    font-weight: 600;
    cursor: pointer;
}

.avatar-upload-label:hover {
    text-decoration: underline;
}

/* -------------------------------- */

/* KHUNG THÔNG BÁO LỖI (ERROR BANNER) SANG TRỌNG */
.error-banner {
    background-color: rgba(255, 59, 48, 0.06);
    /* 6% Opacity màu đỏ Apple */
    border: 1.5px solid rgba(255, 59, 48, 0.15);
    color: #ff3b30;
    /* Màu đỏ của Apple */
    padding: 14px 18px;
    border-radius: 14px;
    margin-bottom: 28px;
    font-size: 0.88rem;
    font-weight: 600;
    text-align: left;
    display: flex;
    align-items: center;
    gap: 12px;
    line-height: 1.45;
}

.error-banner i {
    font-size: 1.15rem;
    flex-shrink: 0;
}

/* Đồng ý điều khoản dịch vụ */
.terms-agreement {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    margin-bottom: 32px;
    font-size: 0.85rem;
    line-height: 1.45;
    color: var(--reg-text-gray);
    cursor: pointer;
    font-weight: 500;
    user-select: none;
}

.terms-agreement input {
    accent-color: var(--reg-primary);
    width: 16px;
    height: 16px;
    margin-top: 2px;
    cursor: pointer;
    flex-shrink: 0;
}

.terms-agreement a {
    color: var(--reg-primary);
    text-decoration: none;
    font-weight: 700;
    transition: var(--reg-transition);
}

.terms-agreement a:hover {
    color: var(--reg-primary-hover);
    text-decoration: underline;
}

/* Nút đăng ký */
.btn-register-submit {
    width: 100%;
    padding: 15px;
    background: var(--reg-dark);
    color: #ffffff;
    font-size: 0.95rem;
    font-weight: 700;
    border: none;
    border-radius: 12px;
    cursor: pointer;
    transition: var(--reg-transition);
    margin-bottom: 24px;
}

.btn-register-submit:hover {
    background: var(--reg-primary);
    box-shadow: 0 4px 12px rgba(0, 113, 227, 0.2);
}

/* Chuyển hướng sang Đăng nhập */
.login-redirect {
    font-size: 0.88rem;
    color: var(--reg-text-gray);
    font-weight: 500;
}

.login-redirect a {
    color: var(--reg-primary);
    text-decoration: none;
    font-weight: 700;
    transition: var(--reg-transition);
}

.login-redirect a:hover {
    color: var(--reg-primary-hover);
    text-decoration: underline;
}
</style>

<div class="register-wrapper">
    <div class="register-card">
        <h2>Tạo tài khoản TechStore</h2>
        <p class="subtitle">Tham gia cùng chúng tôi để nhận các ưu đãi đặc quyền và quản lý đơn hàng dễ dàng.</p>

        <!-- HIỂN THỊ BANNER THÔNG BÁO LỖI NỘI BỘ (Chỉ hiển thị khi có lỗi từ Session) -->
        <?php if (!empty($_SESSION['error'])): ?>
        <div class="error-banner animate-fade-in">
            <i class="fas fa-circle-exclamation"></i>
            <span><?= htmlspecialchars($_SESSION['error']); ?></span>
        </div>
        <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <!-- Form đăng ký thông tin -->
        <!-- Đã thêm enctype="multipart/form-data" để gửi file ảnh -->
        <form action="/assignment/handleSignup" method="POST" enctype="multipart/form-data" class="register-form">


            <div class="register-group">
                <label for="regName">Họ và tên</label>
                <input type="text" id="regName" name="fullName" class="register-control" placeholder="Nguyễn Văn A"
                    required>
            </div>

            <div class="register-group">
                <label for="regAddress">Địa chỉ</label>
                <input type="text" id="regAddress" name="address" class="register-control"
                    placeholder="123 Lê Lợi, Quận 1, TP.HCM" required>
            </div>

            <div class="register-group">
                <label for="regEmail">Địa chỉ Email</label>
                <input type="email" id="regEmail" name="email" class="register-control" placeholder="name@example.com"
                    required>
            </div>

            <div class="register-group">
                <label for="regPhone">Số điện thoại</label>
                <input type="tel" id="regPhone" name="phone" class="register-control" placeholder="0901 234 567"
                    required>
            </div>

            <div class="register-group">
                <label for="regPassword">Mật khẩu</label>
                <input type="password" id="regPassword" name="password" class="register-control"
                    placeholder="Tối thiểu 8 ký tự" required>
            </div>

            <div class="register-group">
                <label for="regConfirmPassword">Xác nhận mật khẩu</label>
                <input type="password" id="regConfirmPassword" name="confirmPassword" class="register-control"
                    placeholder="Nhập lại mật khẩu" required>
            </div>
            <div class="avatar-upload-wrapper">
                <div class="avatar-preview-container" onclick="triggerAvatarSelect()">
                    <div class="avatar-placeholder" id="avatarPlaceholder">
                        <i class="fas fa-camera"></i>
                        <span>Chọn ảnh</span>
                    </div>
                    <img id="avatarPreview" class="avatar-preview-image" src="" alt="Avatar Preview">
                </div>
                <!-- Input chọn ảnh thực tế (được ẩn đi) -->
                <input type="file" id="regAvatar" name="avatar" class="avatar-input-hidden" accept="image/*"
                    onchange="previewSelectedAvatar(this)">
                <span class="avatar-upload-label" onclick="triggerAvatarSelect()">Tải ảnh đại diện</span>
            </div>

            <!-- Điều khoản bảo mật -->
            <label class="terms-agreement">
                <input type="checkbox" name="agree" required>
                <span>Tôi đã đọc và đồng ý với các <a href="#">Điều khoản dịch vụ</a> cùng <a href="#">Chính sách bảo
                        mật</a> của TechStore.</span>
            </label>
            <!-- Khu vực tải lên Ảnh đại diện -->

            <button type="submit" class="btn-register-submit">Tạo tài khoản</button>
        </form>

        <!-- Chuyển hướng sang Đăng nhập -->
        <div class="login-redirect">
            Bạn đã có tài khoản? <a href="/assignment/login">Đăng nhập ngay</a>
        </div>
    </div>
</div>

<script>
function triggerAvatarSelect() {
    document.getElementById('regAvatar').click();
}

function previewSelectedAvatar(input) {
    const file = input.files[0];
    if (file) {
        // Kiểm tra định dạng file ảnh
        if (!file.type.startsWith('image/')) {
            alert('Vui lòng chọn một file ảnh hợp lệ.');
            input.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            const previewImg = document.getElementById('avatarPreview');
            const placeholder = document.getElementById('avatarPlaceholder');

            previewImg.src = e.target.result;
            previewImg.style.display = 'block';
            placeholder.style.display = 'none';
        }
        reader.readAsDataURL(file);
    }
}
</script>