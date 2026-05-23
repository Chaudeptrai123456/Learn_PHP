<!-- CSS riêng cho trang Đăng ký -->
<style>
:root {
    --reg-primary: #0071e3;
    --reg-dark: #1d1d1f;
    --reg-light-gray: #f5f5f7;
    --reg-border: #d2d2d7;
    --reg-text-gray: #86868b;
    --reg-transition: all 0.3s cubic-bezier(0.25, 0.1, 0.25, 1);
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

/* Khung đăng ký chính (rộng hơn trang đăng nhập một chút để form cân đối) */
.register-card {
    width: 100%;
    max-width: 480px;
    padding: 40px;
    border-radius: 24px;
    border: 1px solid var(--reg-border);
    background-color: #ffffff;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.02);
    text-align: center;
}

/* Tiêu đề & mô tả */
.register-card h2 {
    font-size: 1.8rem;
    font-weight: 700;
    letter-spacing: -0.5px;
    color: var(--reg-dark);
    margin-bottom: 8px;
}

.register-card .subtitle {
    font-size: 0.95rem;
    color: var(--reg-text-gray);
    margin-bottom: 32px;
    line-height: 1.4;
}

/* Form và trường nhập liệu */
.register-form {
    text-align: left;
}

.register-group {
    margin-bottom: 20px;
}

.register-group label {
    display: block;
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--reg-dark);
    margin-bottom: 8px;
}

.register-control {
    width: 100%;
    padding: 14px 16px;
    font-family: inherit;
    font-size: 0.95rem;
    color: var(--reg-dark);
    background-color: var(--reg-light-gray);
    border: 1px solid transparent;
    border-radius: 12px;
    outline: none;
    transition: var(--reg-transition);
}

.register-control:focus {
    background-color: #ffffff;
    border-color: var(--reg-primary);
    box-shadow: 0 0 0 4px rgba(0, 113, 227, 0.15);
}

/* Đồng ý điều khoản dịch vụ */
.terms-agreement {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    margin-bottom: 28px;
    font-size: 0.85rem;
    line-height: 1.4;
    color: var(--reg-text-gray);
    cursor: pointer;
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
    font-weight: 600;
}

.terms-agreement a:hover {
    text-decoration: underline;
}

/* Nút đăng ký */
.btn-register-submit {
    width: 100%;
    padding: 14px;
    background: var(--reg-dark);
    color: #ffffff;
    font-size: 1rem;
    font-weight: 700;
    border: none;
    border-radius: 12px;
    cursor: pointer;
    transition: var(--reg-transition);
    margin-bottom: 24px;
}

.btn-register-submit:hover {
    background: var(--reg-primary);
}

/* Chuyển hướng sang Đăng nhập */
.login-redirect {
    font-size: 0.9rem;
    color: var(--reg-text-gray);
}

.login-redirect a {
    color: var(--reg-primary);
    text-decoration: none;
    font-weight: 600;
}

.login-redirect a:hover {
    text-decoration: underline;
}
</style>

<div class="register-wrapper">
    <div class="register-card">
        <h2>Tạo tài khoản TechStore</h2>
        <p class="subtitle">Tham gia cùng chúng tôi để nhận các ưu đãi đặc quyền và quản lý đơn hàng dễ dàng.</p>

        <!-- Form Đăng ký -->
        <form action="" method="POST" class="register-form">

            <div class="register-group">
                <label for="regName">Họ và tên</label>
                <input type="text" id="regName" name="fullName" class="register-control" placeholder="Nguyễn Văn A"
                    required>
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

            <!-- Điều khoản bảo mật -->
            <label class="terms-agreement">
                <input type="checkbox" name="agree" required>
                <span>Tôi đã đọc và đồng ý với các <a href="#">Điều khoản dịch vụ</a> cùng <a href="#">Chính sách bảo
                        mật</a> của TechStore.</span>
            </label>

            <button type="submit" class="btn-register-submit">Tạo tài khoản</button>
        </form>

        <!-- Chuyển hướng sang Đăng nhập -->
        <div class="login-redirect">
            Bạn đã có tài khoản? <a href="/assignment/login">Đăng nhập ngay</a>
        </div>
    </div>
</div>