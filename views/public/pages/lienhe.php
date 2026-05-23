 <!-- CSS riêng cho trang Liên hệ -->
 <style>
:root {
    --contact-primary: #0071e3;
    --contact-dark: #1d1d1f;
    --contact-light-gray: #f5f5f7;
    --contact-border: #d2d2d7;
    --contact-text-gray: #86868b;
    --contact-transition: all 0.3s cubic-bezier(0.25, 0.1, 0.25, 1);
}

/* Đẩy nội dung xuống dưới để không bị đè bởi Header fixed */
.contact-wrapper {
    margin-top: 70px;
    padding: 60px 0 100px;
    background-color: #ffffff;
}

/* --- HERO SECTION --- */
.contact-hero {
    text-align: center;
    max-width: 800px;
    margin: 0 auto 60px;
    padding: 0 20px;
}

.contact-hero .tagline {
    font-size: 0.9rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    color: var(--contact-primary);
    margin-bottom: 12px;
    display: block;
}

.contact-hero h1 {
    font-size: 3rem;
    font-weight: 700;
    letter-spacing: -0.5px;
    color: var(--contact-dark);
    margin-bottom: 20px;
}

.contact-hero p {
    font-size: 1.15rem;
    line-height: 1.6;
    color: var(--contact-text-gray);
}

/* --- CONTACT LAYOUT GRID --- */
.contact-grid {
    display: grid;
    grid-template-columns: 1fr 1.2fr;
    gap: 60px;
    align-items: start;
}

/* --- LEFT COLUMN: INFO --- */
.contact-info-col {
    display: flex;
    flex-direction: column;
    gap: 40px;
}

.info-card {
    background: var(--contact-light-gray);
    border-radius: 18px;
    padding: 30px;
    transition: var(--contact-transition);
    border: 1px solid transparent;
}

.info-card:hover {
    background: #ffffff;
    border-color: var(--contact-border);
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.04);
    transform: translateY(-2px);
}

.info-card-header {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 15px;
}

.info-card-header .icon-box {
    width: 46px;
    height: 46px;
    background: #ffffff;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--contact-primary);
    font-size: 1.2rem;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.03);
}

.info-card-header h3 {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--contact-dark);
}

.info-card p {
    color: var(--contact-text-gray);
    line-height: 1.6;
    font-size: 0.95rem;
}

.info-card a {
    color: var(--contact-primary);
    text-decoration: none;
    font-weight: 600;
    transition: opacity 0.2s;
}

.info-card a:hover {
    opacity: 0.8;
}

/* --- RIGHT COLUMN: FORM --- */
.contact-form-col {
    background: #ffffff;
    border: 1px solid var(--contact-border);
    border-radius: 24px;
    padding: 40px;
}

.contact-form-col h2 {
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 10px;
    color: var(--contact-dark);
}

.contact-form-col p {
    color: var(--contact-text-gray);
    font-size: 0.95rem;
    margin-bottom: 30px;
}

.form-group {
    margin-bottom: 24px;
    position: relative;
}

.form-group label {
    display: block;
    font-size: 0.85rem;
    font-weight: 600;
    color: var(--contact-dark);
    margin-bottom: 8px;
}

.form-control {
    width: 100%;
    padding: 14px 18px;
    font-family: inherit;
    font-size: 0.95rem;
    color: var(--contact-dark);
    background-color: var(--contact-light-gray);
    border: 1px solid transparent;
    border-radius: 12px;
    outline: none;
    transition: var(--contact-transition);
}

.form-control:focus {
    background-color: #ffffff;
    border-color: var(--contact-primary);
    box-shadow: 0 0 0 4px rgba(0, 113, 227, 0.15);
}

textarea.form-control {
    resize: vertical;
    min-height: 120px;
}

.btn-submit {
    width: 100%;
    padding: 15px;
    background: var(--contact-dark);
    color: #ffffff;
    font-size: 1rem;
    font-weight: 700;
    border: none;
    border-radius: 12px;
    cursor: pointer;
    transition: var(--contact-transition);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.btn-submit:hover {
    background: var(--contact-primary);
}

/* --- RESPONSIVE --- */
@media (max-width: 992px) {
    .contact-grid {
        grid-template-columns: 1fr;
        gap: 40px;
    }

    .contact-hero h1 {
        font-size: 2.3rem;
    }
}
 </style>

 <div class="contact-wrapper">
     <!-- Hero Header -->
     <section class="contact-hero">
         <span class="tagline">TechStore Support</span>
         <h1>Liên hệ với chúng tôi</h1>
         <p>Chúng tôi luôn sẵn sàng lắng nghe mọi ý kiến đóng góp, thắc mắc về sản phẩm hoặc yêu cầu hỗ trợ kỹ thuật từ
             bạn.</p>
     </section>

     <!-- Content Grid -->
     <div class="container">
         <div class="contact-grid">

             <!-- Cột thông tin liên hệ -->
             <div class="contact-info-col">

                 <div class="info-card">
                     <div class="info-card-header">
                         <div class="icon-box">
                             <i class="fas fa-phone-alt"></i>
                         </div>
                         <h3>Đường dây nóng</h3>
                     </div>
                     <p>Giải đáp thắc mắc dịch vụ & mua hàng (8h00 - 22h00 hàng ngày).</p>
                     <p style="margin-top: 10px;">
                         <a href="tel:19001234">1900.1234</a> (Miễn phí cuộc gọi)
                     </p>
                 </div>

                 <div class="info-card">
                     <div class="info-card-header">
                         <div class="icon-box">
                             <i class="fas fa-envelope"></i>
                         </div>
                         <h3>Hòm thư điện tử</h3>
                     </div>
                     <p>Bộ phận Chăm sóc khách hàng hoặc Hợp tác truyền thông doanh nghiệp.</p>
                     <p style="margin-top: 10px;">
                         <a href="mailto:support@techstore.vn">support@techstore.vn</a>
                     </p>
                 </div>

                 <div class="info-card">
                     <div class="info-card-header">
                         <div class="icon-box">
                             <i class="fas fa-map-marker-alt"></i>
                         </div>
                         <h3>Trụ sở chính</h3>
                     </div>
                     <p>Tòa nhà TechStore, Đường Số 1, Phường Bến Nghé, Quận 1, TP. Hồ Chí Minh.</p>
                 </div>

             </div>

             <!-- Cột gửi tin nhắn hỗ trợ -->
             <div class="contact-form-col">
                 <h2>Gửi yêu cầu hỗ trợ</h2>
                 <p>Vui lòng điền thông tin chi tiết dưới đây, bộ phận tư vấn sẽ liên hệ lại với bạn trong vòng 24 giờ
                     làm việc.</p>

                 <form action="" method="POST" autocomplete="off">
                     <div class="form-group">
                         <label for="fullName">Họ và tên *</label>
                         <input type="text" id="fullName" name="fullName" class="form-control"
                             placeholder="Nguyễn Văn A" required>
                     </div>

                     <div class="form-group">
                         <label for="email">Địa chỉ Email *</label>
                         <input type="email" id="email" name="email" class="form-control" placeholder="name@example.com"
                             required>
                     </div>

                     <div class="form-group">
                         <label for="phone">Số điện thoại *</label>
                         <input type="tel" id="phone" name="phone" class="form-control" placeholder="0901 234 567"
                             required>
                     </div>

                     <div class="form-group">
                         <label for="message">Nội dung tin nhắn *</label>
                         <textarea id="message" name="message" class="form-control"
                             placeholder="Nhập câu hỏi hoặc yêu cầu của bạn tại đây..." required></textarea>
                     </div>

                     <button type="submit" class="btn-submit">
                         <span>Gửi tin nhắn</span>
                         <i class="fas fa-paper-plane"></i>
                     </button>
                 </form>
             </div>

         </div>
     </div>
 </div>