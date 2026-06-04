<!-- CSS riêng cho trang Đăng nhập kiểu Apple Premium -->
<style>
:root {
    --login-primary: #0071e3;
    --login-primary-hover: #0077ed;
    --login-dark: #1d1d1f;
    --login-light-gray: #f5f5f7;
    --login-border: #e5e5e7;
    --login-text-gray: #86868b;
    --login-transition: all 0.3s cubic-bezier(0.25, 0.1, 0.25, 1);
    --login-shadow: 0 12px 40px rgba(0, 0, 0, 0.03);
}

/* Đẩy trang xuống dưới Header và thiết lập căn giữa */
.login-wrapper {
    margin-top: 70px;
    min-height: calc(100vh - 70px);
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #ffffff;
    padding: 40px 20px;
}

/* Khung đăng nhập chính */
.login-card {
    width: 100%;
    max-width: 440px;
    padding: 40px;
    border-radius: 24px;
    border: 1px solid var(--login-border);
    background-color: #ffffff;
    box-shadow: var(--login-shadow);
    text-align: center;
    transition: var(--login-transition);
}

.login-card:hover {
    border-color: #d2d2d7;
}

/* Tiêu đề & mô tả */
.login-card h2 {
    font-size: 1.8rem;
    font-weight: 800;
    letter-spacing: -0.8px;
    color: var(--login-dark);
    margin-bottom: 10px;
}

.login-card .subtitle {
    font-size: 0.92rem;
    color: var(--login-text-gray);
    margin-bottom: 32px;
    line-height: 1.5;
    font-weight: 500;
}

/* Form và các trường nhập liệu căn lề bên trái */
.login-form {
    text-align: left;
}

.login-group {
    margin-bottom: 22px;
    position: relative;
}

.login-group label {
    display: block;
    font-size: 0.82rem;
    font-weight: 700;
    color: var(--login-dark);
    margin-bottom: 8px;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.login-control {
    width: 100%;
    padding: 14px 16px;
    font-family: inherit;
    font-size: 0.95rem;
    color: var(--login-dark);
    background-color: var(--login-light-gray);
    border: 1.5px solid transparent;
    border-radius: 12px;
    outline: none;
    transition: var(--login-transition);
    font-weight: 500;
}

.login-control:focus {
    background-color: #ffffff;
    border-color: var(--login-primary);
    box-shadow: 0 0 0 4px rgba(0, 113, 227, 0.08);
}

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

/* Hàng ghi nhớ và quên mật khẩu */
.login-options {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 28px;
    font-size: 0.88rem;
}

.remember-me {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    color: var(--login-dark);
    font-weight: 600;
    user-select: none;
}

.remember-me input {
    accent-color: var(--login-primary);
    width: 16px;
    height: 16px;
    cursor: pointer;
}

.forgot-password {
    color: var(--login-primary);
    text-decoration: none;
    font-weight: 700;
    transition: var(--login-transition);
}

.forgot-password:hover {
    color: var(--login-primary-hover);
}

/* Nút Đăng nhập chính */
.btn-login-submit {
    width: 100%;
    padding: 15px;
    background: var(--login-dark);
    color: #ffffff;
    font-size: 0.95rem;
    font-weight: 700;
    border: none;
    border-radius: 12px;
    cursor: pointer;
    transition: var(--login-transition);
    margin-bottom: 24px;
}

.btn-login-submit:hover {
    background: var(--login-primary);
    box-shadow: 0 4px 12px rgba(0, 113, 227, 0.2);
}

/* Đường chia ngăn "hoặc" */
.login-divider {
    position: relative;
    text-align: center;
    margin-bottom: 24px;
}

.login-divider::before {
    content: "";
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: 1px;
    background-color: var(--login-border);
    z-index: 1;
}

.login-divider span {
    position: relative;
    background-color: #ffffff;
    padding: 0 16px;
    font-size: 0.75rem;
    color: var(--login-text-gray);
    font-weight: 700;
    text-transform: uppercase;
    z-index: 2;
    letter-spacing: 1px;
}

/* Nút đăng nhập bên thứ ba */
.social-login-group {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-bottom: 28px;
}

.btn-social {
    width: 100%;
    padding: 13px;
    background-color: #ffffff;
    border: 1px solid var(--login-border);
    border-radius: 12px;
    font-family: inherit;
    font-size: 0.9rem;
    font-weight: 700;
    color: var(--login-dark);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    transition: var(--login-transition);
}

.btn-social:hover {
    background-color: var(--login-light-gray);
    border-color: var(--login-dark);
}

.btn-social img {
    width: 16px;
    height: 16px;
    object-fit: contain;
}

/* Chuyển hướng sang Đăng ký */
.register-link {
    font-size: 0.88rem;
    color: var(--login-text-gray);
    font-weight: 500;
}

.register-link a {
    color: var(--login-primary);
    text-decoration: none;
    font-weight: 700;
    transition: var(--login-transition);
}

.register-link a:hover {
    color: var(--login-primary-hover);
    text-decoration: underline;
}
</style>

<div class="login-wrapper">
    <div class="login-card">
        <h2>Đăng nhập TechStore</h2>
        <p class="subtitle">Quản lý tài khoản của bạn để tiếp cận trải nghiệm dịch vụ tốt nhất.</p>

        <!-- HIỂN THỊ BANNER THÔNG BÁO LỖI NỘI BỘ (Chỉ hiển thị khi có lỗi từ Session) -->
        <?php if (!empty($_SESSION['error'])): ?>
        <div class="error-banner animate-fade-in">
            <i class="fas fa-circle-exclamation"></i>
            <span><?= htmlspecialchars($_SESSION['error']); ?></span>
        </div>
        <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <!-- Form Đăng nhập chính (Đã căn lề trái chuẩn xác) -->
        <form action="/assignment/handleLogin" method="POST" class="login-form">
            <div class="login-group">
                <label for="loginEmail">Email</label>
                <input type="text" id="loginEmail" name="email" class="login-control" placeholder="name@example.com"
                    required>
            </div>

            <div class="login-group">
                <label for="loginPassword">Mật khẩu</label>
                <input type="password" id="loginPassword" name="password" class="login-control"
                    placeholder="Nhập mật khẩu" required>
            </div>

            <div class="login-options">
                <label class="remember-me">
                    <input type="checkbox" name="remember">
                    <span>Ghi nhớ thiết bị</span>
                </label>
                <a href="#" class="forgot-password">Quên mật khẩu?</a>
            </div>

            <button type="submit" class="btn-login-submit">Tiếp tục</button>
        </form>

        <!-- Đường phân cách -->
        <div class="login-divider">
            <span>Hoặc</span>
        </div>

        <!-- Các hình thức đăng nhập khác -->
        <div class="social-login-group">
            <button class="btn-social">
                <!-- Sử dụng đường dẫn ảnh SVG Google sạch, không bị lỗi kí tự -->
                <img src="https://www.vectorlogo.zone/logos/google/google-icon.svg" alt="Google">
                <span>Tiếp tục với Google</span>
            </button>
            <button class="btn-social">
                <i class="fab fa-apple" style="font-size: 1.15rem;"></i>
                <span>Tiếp tục với Apple</span>
            </button>
        </div>

        <!-- Chuyển hướng Đăng ký -->
        <div class="register-link">
            Bạn mới biết đến TechStore? <a href="/assignment/signup">Tạo tài khoản</a>
        </div>
    </div>
</div>