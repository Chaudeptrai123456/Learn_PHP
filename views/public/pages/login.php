 <!-- CSS riêng cho trang Đăng nhập -->
 <style>
:root {
    --login-primary: #0071e3;
    --login-dark: #1d1d1f;
    --login-light-gray: #f5f5f7;
    --login-border: #d2d2d7;
    --login-text-gray: #86868b;
    --login-transition: all 0.3s cubic-bezier(0.25, 0.1, 0.25, 1);
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
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.02);
    text-align: center;
}

/* Tiêu đề & mô tả */
.login-card h2 {
    font-size: 1.8rem;
    font-weight: 700;
    letter-spacing: -0.5px;
    color: var(--login-dark);
    margin-bottom: 8px;
}

.login-card .subtitle {
    font-size: 0.95rem;
    color: var(--login-text-gray);
    margin-bottom: 32px;
    line-height: 1.4;
}

/* Form và các trường nhập liệu */
.login-form {
    text-align: left;
}

.login-group {
    margin-bottom: 20px;
    position: relative;
}

.login-group label {
    display: block;
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--login-dark);
    margin-bottom: 8px;
}

.login-control {
    width: 100%;
    padding: 14px 16px;
    font-family: inherit;
    font-size: 0.95rem;
    color: var(--login-dark);
    background-color: var(--login-light-gray);
    border: 1px solid transparent;
    border-radius: 12px;
    outline: none;
    transition: var(--login-transition);
}

.login-control:focus {
    background-color: #ffffff;
    border-color: var(--login-primary);
    box-shadow: 0 0 0 4px rgba(0, 113, 227, 0.15);
}

/* Hàng ghi nhớ và quên mật khẩu */
.login-options {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 28px;
    font-size: 0.85rem;
}

.remember-me {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    color: var(--login-dark);
    font-weight: 500;
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
    font-weight: 600;
    transition: opacity 0.2s;
}

.forgot-password:hover {
    opacity: 0.8;
}

/* Nút Đăng nhập chính */
.btn-login-submit {
    width: 100%;
    padding: 14px;
    background: var(--login-dark);
    color: #ffffff;
    font-size: 1rem;
    font-weight: 700;
    border: none;
    border-radius: 12px;
    cursor: pointer;
    transition: var(--login-transition);
    margin-bottom: 24px;
}

.btn-login-submit:hover {
    background: var(--login-primary);
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
    padding: 0 12px;
    font-size: 0.8rem;
    color: var(--login-text-gray);
    font-weight: 500;
    text-transform: uppercase;
    z-index: 2;
    letter-spacing: 0.5px;
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
    padding: 12px;
    background-color: #ffffff;
    border: 1px solid var(--login-border);
    border-radius: 12px;
    font-family: inherit;
    font-size: 0.9rem;
    font-weight: 600;
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

/* Chuyển hướng sang Đăng ký */
.register-link {
    font-size: 0.9rem;
    color: var(--login-text-gray);
}

.register-link a {
    color: var(--login-primary);
    text-decoration: none;
    font-weight: 600;
}

.register-link a:hover {
    text-decoration: underline;
}
 </style>

 <div class="login-wrapper">
     <div class="login-card">
         <h2>Đăng nhập TechStore</h2>
         <p class="subtitle">Quản lý tài khoản của bạn để tiếp cận trải nghiệm dịch vụ tốt nhất.</p>

         <!-- Form Đăng nhập chính -->
         <form action="" method="POST" class="login-form">
             <div class="login-group">
                 <label for="loginEmail">Email hoặc Số điện thoại</label>
                 <input type="text" id="loginEmail" name="username" class="login-control" placeholder="name@example.com"
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
                 <img src="https://upload.wikimedia.org/wikipedia/commons/c/c1/Google_\" _Logo\".svg" alt="Google"
                     style="width: 16px; height: 16px; display: none;" onerror="this.style.display='none';">
                 <i class="fab fa-google" style="color: #db4437;"></i>
                 <span>Tiếp tục với Google</span>
             </button>
             <button class="btn-social">
                 <i class="fab fa-apple" style="font-size: 1.1rem;"></i>
                 <span>Tiếp tục với Apple</span>
             </button>
         </div>

         <!-- Chuyển hướng Đăng ký -->
         <div class="register-link">
             Bạn mới biết đến TechStore? <a href="/assignment/signup">Tạo tài khoản</a>
         </div>
     </div>
 </div>