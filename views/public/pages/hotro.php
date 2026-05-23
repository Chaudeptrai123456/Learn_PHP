 <!-- CSS riêng cho trang Hỗ trợ khách hàng -->
 <style>
:root {
    --support-primary: #0071e3;
    --support-dark: #1d1d1f;
    --support-light-gray: #f5f5f7;
    --support-border: #e5e5e7;
    --support-text-gray: #86868b;
    --support-transition: all 0.3s cubic-bezier(0.25, 0.1, 0.25, 1);
}

/* Đẩy trang xuống dưới Header */
.support-wrapper {
    margin-top: 70px;
    padding-bottom: 100px;
    background-color: #ffffff;
}

/* --- SUPPORT HERO --- */
.support-hero {
    background-color: var(--support-light-gray);
    padding: 80px 20px;
    text-align: center;
}

.support-hero h1 {
    font-size: 2.8rem;
    font-weight: 700;
    color: var(--support-dark);
    margin-bottom: 16px;
    letter-spacing: -0.5px;
}

.support-hero p {
    font-size: 1.1rem;
    color: var(--support-text-gray);
    margin-bottom: 30px;
}

/* Thanh tìm kiếm bài viết hỗ trợ */
.support-search {
    max-width: 600px;
    margin: 0 auto;
    position: relative;
}

.support-search input {
    width: 100%;
    padding: 16px 20px 16px 50px;
    font-family: inherit;
    font-size: 1rem;
    border: 1px solid var(--support-border);
    border-radius: 14px;
    outline: none;
    background-color: #ffffff;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
    transition: var(--support-transition);
}

.support-search input:focus {
    border-color: var(--support-primary);
    box-shadow: 0 0 0 4px rgba(0, 113, 227, 0.15);
}

.support-search i {
    position: absolute;
    left: 20px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--support-text-gray);
    font-size: 1.1rem;
}

/* --- SUPPORT CATEGORIES GRID --- */
.support-section-title {
    text-align: center;
    font-size: 1.8rem;
    font-weight: 700;
    color: var(--support-dark);
    margin: 70px 0 40px;
}

.support-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 24px;
    margin-bottom: 80px;
}

.support-card {
    background-color: #ffffff;
    border: 1px solid var(--support-border);
    border-radius: 20px;
    padding: 30px;
    text-align: center;
    text-decoration: none;
    transition: var(--support-transition);
}

.support-card:hover {
    border-color: transparent;
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.05);
    transform: translateY(-4px);
}

.support-card .icon-wrapper {
    width: 60px;
    height: 60px;
    background-color: var(--support-light-gray);
    color: var(--support-primary);
    font-size: 1.5rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    transition: var(--support-transition);
}

.support-card:hover .icon-wrapper {
    background-color: var(--support-primary);
    color: #ffffff;
}

.support-card h3 {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--support-dark);
    margin-bottom: 10px;
}

.support-card p {
    font-size: 0.9rem;
    color: var(--support-text-gray);
    line-height: 1.5;
}

/* --- FAQ SECTION (ACCORDION) --- */
.faq-container {
    max-width: 800px;
    margin: 0 auto 80px;
}

.faq-item {
    border-bottom: 1px solid var(--support-border);
    padding: 10px 0;
}

.faq-item details summary {
    list-style: none;
    font-size: 1.05rem;
    font-weight: 600;
    color: var(--support-dark);
    padding: 15px 0;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    user-select: none;
}

/* Ẩn dấu mũi tên mặc định của Safari */
.faq-item details summary::-webkit-details-marker {
    display: none;
}

/* Tạo icon mũi tên tùy chỉnh bằng FontAwesome hoặc ký tự */
.faq-item details summary::after {
    content: "\f078";
    font-family: "Font Awesome 5 Free";
    font-weight: 900;
    font-size: 0.85rem;
    color: var(--support-text-gray);
    transition: var(--support-transition);
}

.faq-item details[open] summary::after {
    transform: rotate(180deg);
    color: var(--support-primary);
}

.faq-item details p {
    font-size: 0.95rem;
    color: var(--support-text-gray);
    line-height: 1.6;
    padding: 5px 0 20px;
}

/* --- CTA CONTACT BANNER --- */
.support-cta-banner {
    background-color: var(--support-light-gray);
    border-radius: 24px;
    padding: 50px 40px;
    text-align: center;
    max-width: 1000px;
    margin: 0 auto;
}

.support-cta-banner h2 {
    font-size: 1.8rem;
    font-weight: 700;
    color: var(--support-dark);
    margin-bottom: 12px;
}

.support-cta-banner p {
    font-size: 1rem;
    color: var(--support-text-gray);
    margin-bottom: 24px;
}

.btn-support-contact {
    display: inline-block;
    padding: 14px 30px;
    background-color: var(--support-dark);
    color: #ffffff;
    font-weight: 600;
    text-decoration: none;
    border-radius: 12px;
    transition: var(--support-transition);
}

.btn-support-contact:hover {
    background-color: var(--support-primary);
}

@media (max-width: 768px) {
    .support-hero h1 {
        font-size: 2.2rem;
    }

    .support-cta-banner {
        padding: 40px 20px;
    }
}
 </style>

 <div class="support-wrapper">
     <!-- Hero Header và Ô Tìm kiếm -->
     <section class="support-hero">
         <div class="container">
             <h1>Chúng tôi có thể giúp gì cho bạn?</h1>
             <p>Nhập từ khóa hoặc câu hỏi của bạn để tìm kiếm hướng dẫn nhanh.</p>
             <div class="support-search">
                 <i class="fas fa-search"></i>
                 <input type="text" placeholder="Tìm kiếm bài viết, hướng dẫn hỗ trợ...">
             </div>
         </div>
     </section>

     <div class="container">
         <!-- Danh mục chủ đề Hỗ trợ -->
         <h2 class="support-section-title">Chủ đề hỗ trợ phổ biến</h2>
         <div class="support-grid">

             <a href="#" class="support-card">
                 <div class="icon-wrapper">
                     <i class="fas fa-truck"></i>
                 </div>
                 <h3>Giao nhận & Vận chuyển</h3>
                 <p>Theo dõi lộ trình đơn hàng của bạn và chính sách giao hàng toàn quốc.</p>
             </a>

             <a href="#" class="support-card">
                 <div class="icon-wrapper">
                     <i class="fas fa-undo-alt"></i>
                 </div>
                 <h3>Đổi trả & Hoàn tiền</h3>
                 <p>Quy trình đổi mới sản phẩm lỗi và thủ tục hoàn tiền nhanh chóng.</p>
             </a>

             <a href="#" class="support-card">
                 <div class="icon-wrapper">
                     <i class="fas fa-shield-alt"></i>
                 </div>
                 <h3>Chính sách bảo hành</h3>
                 <p>Tra cứu thời hạn bảo hành của thiết bị Apple và các phụ kiện đi kèm.</p>
             </a>

             <a href="#" class="support-card">
                 <div class="icon-wrapper">
                     <i class="fas fa-credit-card"></i>
                 </div>
                 <h3>Thanh toán & Trả góp</h3>
                 <p>Các phương thức thanh toán bảo mật và chính sách trả góp 0% lãi suất.</p>
             </a>

         </div>

         <!-- FAQ Câu hỏi thường gặp -->
         <h2 class="support-section-title">Câu hỏi thường gặp</h2>
         <div class="faq-container">

             <div class="faq-item">
                 <details>
                     <summary>Thời gian giao hàng của TechStore mất bao lâu?</summary>
                     <p>Đối với các khu vực nội thành (Hà Nội, TP. HCM), đơn hàng sẽ được giao hỏa tốc trong vòng 2-4
                         giờ. Các khu vực tỉnh thành khác, thời gian vận chuyển dao động từ 2 đến 4 ngày làm việc kể từ
                         thời điểm xác nhận đơn hàng thành công.</p>
                 </details>
             </div>

             <div class="faq-item">
                 <details>
                     <summary>Sản phẩm mua tại hệ thống có được đổi mới nếu phát sinh lỗi không?</summary>
                     <p>Có. TechStore áp dụng chính sách "1 Đổi 1" trong vòng 30 ngày đầu tiên kể từ ngày bàn giao thiết
                         bị nếu sản phẩm xuất hiện lỗi phần cứng từ nhà sản xuất (yêu cầu sản phẩm còn đầy đủ hộp, phụ
                         kiện gốc và không có dấu hiệu va đập vật lý).</p>
                 </details>
             </div>

             <div class="faq-item">
                 <details>
                     <summary>Tôi cần mang giấy tờ gì khi đăng ký mua trả góp 0%?</summary>
                     <p>Nếu thanh toán qua thẻ tín dụng liên kết ngân hàng, bạn có thể thực hiện đăng ký 0% trực tuyến
                         mà
                         không cần giấy tờ. Nếu làm hồ sơ qua công ty tài chính, bạn cần mang theo Căn cước công dân gắn
                         chip bản gốc (đối với người từ đủ 18 tuổi trở lên).</p>
                 </details>
             </div>

             <div class="faq-item">
                 <details>
                     <summary>Làm thế nào để tôi có thể kích hoạt bảo hành điện tử?</summary>
                     <p>Tất cả sản phẩm bán ra từ TechStore đều được tự động kích hoạt bảo hành điện tử dựa trên số IMEI
                         hoặc số Serial của máy thông qua hệ thống lưu trữ trực tuyến. Bạn có thể tra cứu nhanh thời hạn
                         bảo hành tại website bất kỳ lúc nào.</p>
                 </details>
             </div>

         </div>

         <!-- Banner liên hệ trực tiếp -->
         <section class="support-cta-banner">
             <h2>Vẫn không tìm thấy câu trả lời?</h2>
             <p>Đừng lo lắng, đội ngũ hỗ trợ kỹ thuật và chăm sóc khách hàng của TechStore luôn sẵn sàng đồng hành cùng
                 bạn.</p>
             <a href="lien-he.php" class="btn-support-contact">Liên hệ trực tiếp</a>
         </section>

     </div>
 </div>