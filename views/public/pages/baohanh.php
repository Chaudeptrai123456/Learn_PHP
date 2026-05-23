 <!-- CSS riêng cho trang Trung tâm bảo hành -->
 <style>
:root {
    --warranty-primary: #0071e3;
    --warranty-dark: #1d1d1f;
    --warranty-light-gray: #f5f5f7;
    --warranty-border: #e5e5e7;
    --warranty-text-gray: #86868b;
    --warranty-transition: all 0.3s cubic-bezier(0.25, 0.1, 0.25, 1);
}

/* Đẩy trang xuống dưới Header */
.warranty-wrapper {
    margin-top: 70px;
    padding-bottom: 100px;
    background-color: #ffffff;
}

/* --- HERO SECTION --- */
.warranty-hero {
    background: linear-gradient(135deg, #1d1d1f 0%, #000000 100%);
    color: #ffffff;
    padding: 90px 20px;
    text-align: center;
}

.warranty-hero .badge {
    display: inline-block;
    background-color: var(--warranty-primary);
    color: #ffffff;
    font-size: 0.8rem;
    font-weight: 700;
    text-transform: uppercase;
    padding: 6px 16px;
    border-radius: 20px;
    margin-bottom: 20px;
    letter-spacing: 1px;
}

.warranty-hero h1 {
    font-size: 3rem;
    font-weight: 800;
    margin-bottom: 20px;
    letter-spacing: -0.5px;
}

.warranty-hero p {
    font-size: 1.15rem;
    color: #a1a1a6;
    max-width: 700px;
    margin: 0 auto;
    line-height: 1.6;
}

/* --- COMMITMENT GRID --- */
.section-title {
    text-align: center;
    font-size: 2rem;
    font-weight: 700;
    color: var(--warranty-dark);
    margin: 80px 0 40px;
    letter-spacing: -0.5px;
}

.commitment-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 30px;
    margin-bottom: 80px;
}

.commitment-card {
    text-align: center;
    padding: 30px;
    border-radius: 20px;
    background-color: var(--warranty-light-gray);
    transition: var(--warranty-transition);
}

.commitment-card:hover {
    transform: translateY(-5px);
    background-color: #ffffff;
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.05);
}

.commitment-card .icon-box {
    width: 64px;
    height: 64px;
    background-color: #ffffff;
    color: var(--warranty-primary);
    font-size: 1.6rem;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
}

.commitment-card h3 {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--warranty-dark);
    margin-bottom: 12px;
}

.commitment-card p {
    font-size: 0.9rem;
    color: var(--warranty-text-gray);
    line-height: 1.6;
}

/* --- LOOKUP BOX (TRA CỨU BẢO HÀNH) --- */
.lookup-section {
    background-color: var(--warranty-light-gray);
    border-radius: 24px;
    padding: 60px 40px;
    max-width: 900px;
    margin: 0 auto 80px;
}

.lookup-header {
    text-align: center;
    margin-bottom: 35px;
}

.lookup-header h2 {
    font-size: 1.8rem;
    font-weight: 700;
    color: var(--warranty-dark);
    margin-bottom: 10px;
}

.lookup-header p {
    font-size: 0.95rem;
    color: var(--warranty-text-gray);
}

.lookup-form-row {
    display: flex;
    gap: 15px;
    max-width: 650px;
    margin: 0 auto;
}

.lookup-input-wrapper {
    position: relative;
    flex: 1;
}

.lookup-input-wrapper i {
    position: absolute;
    left: 18px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--warranty-text-gray);
    font-size: 1.1rem;
}

.lookup-input-wrapper input {
    width: 100%;
    padding: 16px 16px 16px 50px;
    font-family: inherit;
    font-size: 1rem;
    border: 1px solid var(--warranty-border);
    border-radius: 14px;
    outline: none;
    background-color: #ffffff;
    transition: var(--warranty-transition);
}

.lookup-input-wrapper input:focus {
    border-color: var(--warranty-primary);
    box-shadow: 0 0 0 4px rgba(0, 113, 227, 0.15);
}

.btn-lookup-submit {
    padding: 0 35px;
    background-color: var(--warranty-dark);
    color: #ffffff;
    font-size: 0.95rem;
    font-weight: 700;
    border: none;
    border-radius: 14px;
    cursor: pointer;
    transition: var(--warranty-transition);
    white-space: nowrap;
}

.btn-lookup-submit:hover {
    background-color: var(--warranty-primary);
}

/* --- LOCATIONS SECTION --- */
.location-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 30px;
}

.location-card {
    background-color: #ffffff;
    border: 1px solid var(--warranty-border);
    border-radius: 20px;
    padding: 35px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    transition: var(--warranty-transition);
}

.location-card:hover {
    border-color: transparent;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.05);
    transform: translateY(-4px);
}

.location-card h3 {
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--warranty-dark);
    margin-bottom: 20px;
}

.location-details {
    display: flex;
    flex-direction: column;
    gap: 15px;
    margin-bottom: 30px;
}

.detail-item {
    display: flex;
    gap: 15px;
    align-items: flex-start;
    font-size: 0.95rem;
    line-height: 1.5;
    color: var(--warranty-dark);
}

.detail-item i {
    color: var(--warranty-primary);
    font-size: 1.1rem;
    margin-top: 3px;
    width: 18px;
    text-align: center;
}

.detail-item span strong {
    display: block;
    color: var(--warranty-text-gray);
    font-size: 0.8rem;
    text-transform: uppercase;
    margin-bottom: 4px;
    letter-spacing: 0.5px;
}

.btn-direction {
    width: 100%;
    padding: 12px;
    text-align: center;
    background-color: var(--warranty-light-gray);
    color: var(--warranty-dark);
    font-weight: 600;
    text-decoration: none;
    border-radius: 10px;
    font-size: 0.9rem;
    transition: var(--warranty-transition);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.location-card:hover .btn-direction {
    background-color: var(--warranty-primary);
    color: #ffffff;
}

@media (max-width: 768px) {
    .warranty-hero h1 {
        font-size: 2.2rem;
    }

    .lookup-form-row {
        flex-direction: column;
    }

    .btn-lookup-submit {
        padding: 16px;
    }

    .lookup-section {
        padding: 40px 20px;
    }
}
 </style>

 <div class="warranty-wrapper">
     <!-- Hero Banner -->
     <section class="warranty-hero">
         <div class="container">
             <span class="badge">Dịch vụ ủy quyền</span>
             <h1>Trung tâm Dịch vụ Bảo hành</h1>
             <p>Hệ thống sửa chữa ủy quyền chuẩn Apple cao cấp. Chúng tôi cam kết mang lại sự an tâm tuyệt đối bằng việc
                 chỉ sử dụng linh kiện chính hãng cùng các kỹ thuật viên đạt chứng chỉ chuyên môn cao nhất.</p>
         </div>
     </section>

     <!-- Cam kết dịch vụ -->
     <div class="container">
         <h2 class="section-title">Cam kết chất lượng dịch vụ</h2>
         <div class="commitment-grid">

             <div class="commitment-card">
                 <div class="icon-box">
                     <i class="fas fa-microchip"></i>
                 </div>
                 <h3>100% Linh kiện chính hãng</h3>
                 <p>Mọi linh kiện thay thế đều là hàng chính hãng từ nhà sản xuất, nguyên seal nguyên tem nhập khẩu.</p>
             </div>

             <div class="commitment-card">
                 <div class="icon-box">
                     <i class="fas fa-user-cog"></i>
                 </div>
                 <h3>Kỹ thuật viên chuyên nghiệp</h3>
                 <p>Đội ngũ nhân sự chuyên môn cao, được đào tạo bài bản và thi đạt chứng chỉ kỹ thuật tiêu chuẩn quốc
                     tế.</p>
             </div>

             <div class="commitment-card">
                 <div class="icon-box">
                     <i class="fas fa-bolt"></i>
                 </div>
                 <h3>Xử lý nhanh chóng</h3>
                 <p>Ưu tiên tối đa việc tiết kiệm thời gian cho khách hàng, nhiều dịch vụ hỗ trợ sửa chữa và lấy ngay
                     trong ngày.</p>
             </div>

         </div>
     </div>

     <!-- Ô tra cứu tình trạng sửa chữa trực tuyến -->
     <div class="container">
         <section class="lookup-section">
             <div class="lookup-header">
                 <h2>Tra cứu tình trạng bảo hành</h2>
                 <p>Nhập số Serial/IMEI thiết bị hoặc Mã biên nhận dịch vụ để kiểm tra thông tin chi tiết.</p>
             </div>
             <form action="" method="GET" class="lookup-form-row">
                 <div class="lookup-input-wrapper">
                     <i class="fas fa-search"></i>
                     <input type="text" name="query" placeholder="Ví dụ: IMEI, Serial Number, Mã phiếu sửa chữa..."
                         required>
                 </div>
                 <button type="submit" class="btn-lookup-submit">Tra cứu ngay</button>
             </form>
         </section>
     </div>

     <!-- Hệ thống địa điểm trung tâm -->
     <div class="container">
         <h2 class="section-title" style="margin-top: 0;">Hệ thống trung tâm bảo hành</h2>
         <div class="location-grid">

             <!-- Chi nhánh 1 -->
             <div class="location-card">
                 <h3>Chi nhánh TP. Hồ Chí Minh</h3>
                 <div class="location-details">
                     <div class="detail-item">
                         <i class="fas fa-map-marker-alt"></i>
                         <span>
                             <strong>Địa chỉ</strong>
                             Tòa nhà TechStore, Đường Số 1, Phường Bến Nghé, Quận 1, TP. HCM.
                         </span>
                     </div>
                     <div class="detail-item">
                         <i class="fas fa-phone-alt"></i>
                         <span>
                             <strong>Điện thoại</strong>
                             028.7300.1234
                         </span>
                     </div>
                     <div class="detail-item">
                         <i class="fas fa-clock"></i>
                         <span>
                             <strong>Thời gian làm việc</strong>
                             Thứ 2 - Thứ 7: 8h30 - 18h00 (Chủ nhật nghỉ)
                         </span>
                     </div>
                 </div>
                 <a href="#" class="btn-direction">
                     <span>Xem bản đồ đường đi</span>
                     <i class="fas fa-chevron-right"></i>
                 </a>
             </div>

             <!-- Chi nhánh 2 -->
             <div class="location-card">
                 <h3>Chi nhánh Hà Nội</h3>
                 <div class="location-details">
                     <div class="detail-item">
                         <i class="fas fa-map-marker-alt"></i>
                         <span>
                             <strong>Địa chỉ</strong>
                             Số 99, Đường Thái Hà, Quận Đống Đa, TP. Hà Nội.
                         </span>
                     </div>
                     <div class="detail-item">
                         <i class="fas fa-phone-alt"></i>
                         <span>
                             <strong>Điện thoại</strong>
                             024.7300.5678
                         </span>
                     </div>
                     <div class="detail-item">
                         <i class="fas fa-clock"></i>
                         <span>
                             <strong>Thời gian làm việc</strong>
                             Thứ 2 - Thứ 7: 8h30 - 18h00 (Chủ nhật nghỉ)
                         </span>
                     </div>
                 </div>
                 <a href="#" class="btn-direction">
                     <span>Xem bản đồ đường đi</span>
                     <i class="fas fa-chevron-right"></i>
                 </a>
             </div>

             <!-- Chi nhánh 3 -->
             <div class="location-card">
                 <h3>Chi nhánh Đà Nẵng</h3>
                 <div class="location-details">
                     <div class="detail-item">
                         <i class="fas fa-map-marker-alt"></i>
                         <span>
                             <strong>Địa chỉ</strong>
                             Số 150, Đường Nguyễn Văn Linh, Quận Thanh Khê, TP. Đà Nẵng.
                         </span>
                     </div>
                     <div class="detail-item">
                         <i class="fas fa-phone-alt"></i>
                         <span>
                             <strong>Điện thoại</strong>
                             0236.7300.999
                         </span>
                     </div>
                     <div class="detail-item">
                         <i class="fas fa-clock"></i>
                         <span>
                             <strong>Thời gian làm việc</strong>
                             Thứ 2 - Thứ 7: 8h30 - 18h00 (Chủ nhật nghỉ)
                         </span>
                     </div>
                 </div>
                 <a href="#" class="btn-direction">
                     <span>Xem bản đồ đường đi</span>
                     <i class="fas fa-chevron-right"></i>
                 </a>
             </div>

         </div>
     </div>
 </div>