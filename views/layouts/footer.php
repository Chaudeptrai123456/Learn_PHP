<!-- CSS dành riêng cho Footer -->
<style>
/* --- FOOTER --- */
footer {
    background: var(--gray-bg);
    padding: 80px 0 40px;
    border-top: 1px solid var(--border);
}

.footer-grid {
    display: grid;
    grid-template-columns: 1.5fr 1fr 1fr 1.2fr;
    gap: 40px;
}

.footer-col h4 {
    margin-bottom: 25px;
    font-weight: 700;
}

.footer-col ul {
    list-style: none;
}

.footer-col ul li {
    margin-bottom: 12px;
}

.footer-col ul li a {
    text-decoration: none;
    color: #666;
    font-size: 0.9rem;
    transition: 0.3s;
}

.footer-col ul li a:hover {
    color: var(--primary);
}

.newsletter input {
    width: 100%;
    padding: 12px;
    border-radius: 10px;
    border: 1px solid var(--border);
    margin-bottom: 10px;
}

.btn-sub {
    width: 100%;
    padding: 12px;
    border-radius: 10px;
    background: var(--primary);
    color: #fff;
    border: none;
    font-weight: 700;
}

@media (max-width: 992px) {
    .footer-grid {
        grid-template-columns: 1fr 1fr;
    }
}

@media (max-width: 600px) {
    .footer-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<!-- HTML Footer -->
<footer>
    <div class="container footer-grid">
        <div class="footer-col">
            <a href="#" class="logo">TECH<span>STORE</span></a>
            <p style="margin-top:20px; color:#666; font-size:0.9rem;">Hệ thống bán lẻ ủy quyền Apple cao cấp nhất Việt
                Nam. Cung cấp trải nghiệm mua sắm đẳng cấp thế giới.</p>
        </div>
        <div class="footer-col">
            <h4>Sản phẩm</h4>
            <ul>
                <li><a href="#">iPhone 15 Series</a></li>
                <li><a href="#">MacBook M3</a></li>
                <li><a href="#">Apple Watch Ultra</a></li>
                <li><a href="#">Phụ kiện Apple</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h4>Hỗ trợ khách hàng</h4>
            <ul>
                <li><a href="/assignment/hotro">Chính sách bảo hành</a></li>
                <li><a href="#">Trả góp 0%</a></li>
                <li><a href="#">Giao hàng tận nơi</a></li>
                <li><a href="/assignment/baohanh">Trung tâm bảo hành</a></li>
            </ul>
        </div>
        <div class="footer-col newsletter">
            <a href="/assignment/lienhe">
                <h4>Liên hệ với chúng tôi</h4>
            </a>
            <p style="font-size:0.85rem; color:#666; margin-bottom:15px;">Nhận thông báo sớm nhất về các đợt giảm giá
                siêu khủng.</p>
            <input type="email" placeholder="Email của bạn">
            <button class="btn-sub">ĐĂNG KÝ</button>
        </div>
    </div>
    <div class="container"
        style="border-top:1px solid #ddd; margin-top:50px; padding-top:30px; text-align:center; color:#999; font-size:0.8rem;">
        © 2024 Design by Tech Leader Châu. Bản quyền thuộc về TechStore Việt Nam.
    </div>
</footer>

<!-- JavaScript hoạt động của trang -->
<script>
// 1. Logic Slider (Đã gộp và tối ưu hóa để tránh lỗi khai báo trùng lặp biến)
const slides = document.querySelectorAll('.slide');
const prevBtn = document.querySelector('.prev');
const nextBtn = document.querySelector('.next');
let index = 0;
let slideInterval;

function showSlide(i) {
    if (slides.length === 0) return;
    slides.forEach(s => s.classList.remove('active'));
    slides[i].classList.add('active');
}

function nextSlide() {
    index = (index + 1) % slides.length;
    showSlide(index);
}

function prevSlide() {
    index = (index - 1 + slides.length) % slides.length;
    showSlide(index);
}

if (nextBtn && prevBtn && slides.length > 0) {
    nextBtn.addEventListener('click', () => {
        nextSlide();
        resetInterval();
    });

    prevBtn.addEventListener('click', () => {
        prevSlide();
        resetInterval();
    });
}

function startInterval() {
    slideInterval = setInterval(nextSlide, 5000);
}

function resetInterval() {
    clearInterval(slideInterval);
    startInterval();
}

// Khởi chạy auto-play cho slider
if (slides.length > 0) {
    startInterval();
}

// 2. Logic Lọc Category
function filterCat(cat, el) {
    document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
    if (el) el.classList.add('active');

    const cards = document.querySelectorAll('.p-card');
    cards.forEach(card => {
        if (cat === 'all' || card.dataset.cat === cat) {
            card.style.display = 'flex';
        } else {
            card.style.display = 'none';
        }
    });
}

// 3. Logic Tìm kiếm
const searchInput = document.getElementById('searchInput');
if (searchInput) {
    searchInput.addEventListener('input', function(e) {
        const val = e.target.value.toLowerCase();
        const cards = document.querySelectorAll('.p-card');
        cards.forEach(card => {
            if (card.dataset.name.includes(val)) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    });
}

// 4. Logic Sắp xếp giá
function sortProducts() {
    const grid = document.getElementById('productGrid');
    if (!grid) return;
    const cards = Array.from(grid.getElementsByClassName('p-card'));
    const sortPrice = document.getElementById('sortPrice');
    if (!sortPrice) return;
    const val = sortPrice.value;

    if (val === 'default') return;

    cards.sort((a, b) => {
        const priceA = parseInt(a.dataset.price) || 0;
        const priceB = parseInt(b.dataset.price) || 0;
        return val === 'low' ? priceA - priceB : priceB - priceA;
    });

    grid.innerHTML = "";
    cards.forEach(card => grid.appendChild(card));
}
</script>
</body>

</html>