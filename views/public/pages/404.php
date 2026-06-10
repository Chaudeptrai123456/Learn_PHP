 <!-- CSS riêng cho trang lỗi 404 -->
 <style>
:root {
    --error-primary: #0071e3;
    --error-dark: #1d1d1f;
    --error-light-gray: #f5f5f7;
    --error-border: #d2d2d7;
    --error-text-gray: #86868b;
    --error-transition: all 0.3s cubic-bezier(0.25, 0.1, 0.25, 1);
}

/* Đẩy trang xuống dưới Header và căn giữa nội dung */
.error-wrapper {
    margin-top: 70px;
    min-height: calc(100vh - 70px);
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #ffffff;
    padding: 60px 20px;
}

.error-container {
    text-align: center;
    max-width: 550px;
    width: 100%;
}

/* --- SVG MASCOT ANIMATION --- */
.mascot-box {
    position: relative;
    width: 200px;
    height: 220px;
    margin: 0 auto 30px;
}

/* Hiệu ứng bay bổng cho chú Robot */
.mascot-svg {
    width: 100%;
    height: 100%;
    animation: floatRobot 4s ease-in-out infinite;
}

/* Hiệu ứng bóng đổ co giãn theo độ bay */
.mascot-shadow {
    width: 100px;
    height: 12px;
    background: rgba(29, 29, 31, 0.08);
    border-radius: 50%;
    margin: -15px auto 0;
    animation: scaleShadow 4s ease-in-out infinite;
}

@keyframes floatRobot {
    0% {
        transform: translateY(0px);
    }

    50% {
        transform: translateY(-15px);
    }

    100% {
        transform: translateY(0px);
    }
}

@keyframes scaleShadow {
    0% {
        transform: scale(1);
        opacity: 1;
    }

    50% {
        transform: scale(0.7);
        opacity: 0.4;
    }

    100% {
        transform: scale(1);
        opacity: 1;
    }
}

/* --- NỘI DUNG --- */
.error-code {
    font-size: 6rem;
    font-weight: 800;
    line-height: 1;
    color: var(--error-dark);
    margin-bottom: 10px;
    letter-spacing: -2px;
}

.error-container h1 {
    font-size: 1.8rem;
    font-weight: 700;
    color: var(--error-dark);
    margin-bottom: 16px;
    letter-spacing: -0.5px;
}

.error-container p {
    font-size: 1rem;
    color: var(--error-text-gray);
    line-height: 1.6;
    margin-bottom: 40px;
}

/* --- NÚT ĐIỀU HƯỚNG --- */
.error-actions {
    display: flex;
    gap: 15px;
    justify-content: center;
}

.btn-error-primary {
    padding: 14px 28px;
    background-color: var(--error-dark);
    color: #ffffff;
    font-weight: 600;
    text-decoration: none;
    border-radius: 12px;
    transition: var(--error-transition);
    font-size: 0.95rem;
}

.btn-error-primary:hover {
    background-color: var(--error-primary);
}

.btn-error-secondary {
    padding: 14px 28px;
    background-color: var(--error-light-gray);
    color: var(--error-dark);
    font-weight: 600;
    text-decoration: none;
    border-radius: 12px;
    transition: var(--error-transition);
    font-size: 0.95rem;
}

.btn-error-secondary:hover {
    background-color: var(--error-border);
}

@media (max-width: 480px) {
    .error-actions {
        flex-direction: column;
        gap: 10px;
    }

    .error-code {
        font-size: 5rem;
    }
}
 </style>

 <div class="error-wrapper">
     <div class="error-container">

         <!-- Khung linh vật SVG -->
         <div class="mascot-box">
             <!-- Chú Robot đáng yêu vẽ bằng SVG -->
             <svg class="mascot-svg" viewBox="0 0 200 220" fill="none" xmlns="http://www.w3.org/2000/svg">
                 <!-- Tai trái & phải -->
                 <rect x="30" y="90" width="10" height="25" rx="5" fill="#d2d2d7" />
                 <rect x="160" y="90" width="10" height="25" rx="5" fill="#d2d2d7" />

                 <!-- Ăng-ten -->
                 <rect x="96" y="25" width="8" height="25" fill="#d2d2d7" />
                 <circle cx="100" cy="20" r="10" fill="#0071e3" />
                 <circle cx="100" cy="20" r="5" fill="#5ac8fa" />

                 <!-- Đầu Robot (Bo góc mềm mại) -->
                 <rect x="40" y="50" width="120" height="100" rx="35" fill="#f5f5f7" stroke="#1d1d1f"
                     stroke-width="5" />

                 <!-- Kính chắn mũ phi hành gia / Màn hình mặt -->
                 <rect x="52" y="62" width="96" height="64" rx="22" fill="#1d1d1f" />

                 <!-- Đôi mắt LED xanh dương siêu dễ thương (mắt hơi buồn nhẹ vì đi lạc) -->
                 <ellipse cx="78" cy="94" rx="8" ry="12" fill="#0071e3" />
                 <ellipse cx="122" cy="94" rx="8" ry="12" fill="#0071e3" />

                 <!-- Điểm sáng long lanh trong mắt -->
                 <circle cx="76" cy="90" r="3" fill="#ffffff" />
                 <circle cx="120" cy="90" r="3" fill="#ffffff" />

                 <!-- Đôi má hồng ửng nhẹ cực cưng -->
                 <circle cx="68" cy="112" r="6" fill="#ff9ebb" opacity="0.6" />
                 <circle cx="132" cy="112" r="6" fill="#ff9ebb" opacity="0.6" />

                 <!-- Chiếc miệng nhỏ bối rối -->
                 <path d="M 94 110 Q 100 104 106 110" stroke="#ffffff" stroke-width="3" fill="none"
                     stroke-linecap="round" />

                 <!-- Cổ Robot -->
                 <rect x="85" y="148" width="30" height="12" rx="4" fill="#d2d2d7" stroke="#1d1d1f" stroke-width="4" />

                 <!-- Khối thân nhỏ xinh bên dưới -->
                 <path d="M 75 160 L 125 160 L 115 190 L 85 190 Z" fill="#f5f5f7" stroke="#1d1d1f" stroke-width="4" />
                 <!-- Biểu tượng trái tim nhỏ trên ngực -->
                 <path d="M 100 178 L 97 174 Q 93 170 97 167 Q 100 165 100 169 Q 100 165 103 167 Q 107 170 103 174 Z"
                     fill="#ff453a" />
             </svg>
             <!-- Bóng đổ động phía dưới chân linh vật -->
             <div class="mascot-shadow"></div>
         </div>

         <!-- Mã lỗi và Thông điệp -->
         <div class="error-code">404</div>
         <h1>Úi! Trang bạn tìm không tồn tại</h1>
         <p>Đường dẫn này có thể đã bị thay đổi, bị xóa tạm thời hoặc bạn đã nhập sai địa chỉ URL. Hãy để TechBot dẫn
             bạn
             quay lại lộ trình nhé.</p>

         <!-- Nút hành động quay về -->
         <div class="error-actions">
             <a href="/assignment" class="btn-error-primary">Về Trang chủ</a>
             <a href="/assignment/hotro" class="btn-error-secondary">Trung tâm hỗ trợ</a>
         </div>

     </div>
 </div>