</div> <!-- Close container from header -->

<!-- Footer -->
<footer class="bg-dark text-white mt-5">
    <div class="container py-5">
        <div class="row">
            <!-- Company Info -->
            <div class="col-lg-4 col-md-6 mb-4">
                <h5 class="fw-bold text-primary">
                    <i class="fas fa-mobile-alt me-2"></i>TechPhone Store
                </h5>
                <p class="text-light">
                    Cửa hàng điện thoại uy tín với hơn 10 năm kinh nghiệm.
                    Chuyên cung cấp các sản phẩm điện thoại chính hãng,
                    bảo hành tốt nhất thị trường.
                </p>
                <div class="d-flex">
                    <a href="#" class="text-white me-3"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-white me-3"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-white me-3"><i class="fab fa-youtube"></i></a>
                    <a href="#" class="text-white me-3"><i class="fab fa-tiktok"></i></a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="col-lg-2 col-md-6 mb-4">
                <h6 class="fw-bold text-primary">Sản phẩm</h6>
                <ul class="list-unstyled">
                    <li><a href="/hoangduyminh/Product/home" class="text-light text-decoration-none">Tất cả sản phẩm</a></li>
                    <li><a href="#" class="text-light text-decoration-none">iPhone</a></li>
                    <li><a href="#" class="text-light text-decoration-none">Samsung</a></li>
                    <li><a href="#" class="text-light text-decoration-none">Xiaomi</a></li>
                    <li><a href="#" class="text-light text-decoration-none">OPPO</a></li>
                </ul>
            </div>

            <!-- Support -->
            <div class="col-lg-3 col-md-6 mb-4">
                <h6 class="fw-bold text-primary">Hỗ trợ</h6>
                <ul class="list-unstyled">
                    <li><a href="#" class="text-light text-decoration-none">Chính sách bảo hành</a></li>
                    <li><a href="#" class="text-light text-decoration-none">Chính sách đổi trả</a></li>
                    <li><a href="#" class="text-light text-decoration-none">Hướng dẫn mua hàng</a></li>
                    <li><a href="#" class="text-light text-decoration-none">Phương thức thanh toán</a></li>
                    <li><a href="#" class="text-light text-decoration-none">Giao hàng & vận chuyển</a></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div class="col-lg-3 col-md-6 mb-4">
                <h6 class="fw-bold text-primary">Liên hệ</h6>
                <div class="text-light">
                    <p><i class="fas fa-map-marker-alt me-2 text-primary"></i>
                        123 Đường ABC, Quận 1, TP.HCM</p>
                    <p><i class="fas fa-phone me-2 text-primary"></i>
                        Hotline: 1900-123-456</p>
                    <p><i class="fas fa-envelope me-2 text-primary"></i>
                        Email: info@techphone.vn</p>
                    <p><i class="fas fa-clock me-2 text-primary"></i>
                        Giờ làm việc: 8:00 - 22:00 (T2-CN)</p>
                </div>
            </div>
        </div>

        <hr class="my-4">
        
        <!-- Bottom Info -->
        <div class="row align-items-center">
            <div class="col-md-6">
                <p class="mb-0 text-light">
                    © 2025 TechPhone Store. All rights reserved.
                </p>
            </div>
            <div class="col-md-6 text-end">
                <img src="https://via.placeholder.com/40x25/007bff/ffffff?text=VISA" alt="VISA" class="me-2">
                <img src="https://via.placeholder.com/40x25/ff6b35/ffffff?text=MC" alt="MasterCard" class="me-2">
                <img src="https://via.placeholder.com/40x25/00aced/ffffff?text=ATM" alt="ATM" class="me-2">
                <img src="https://via.placeholder.com/40x25/1877f2/ffffff?text=COD" alt="COD">
            </div>
        </div>
    </div>
</footer>

<!-- Back to top button -->
<button type="button" class="btn btn-primary btn-floating btn-lg" id="btn-back-to-top"
        style="position: fixed; bottom: 20px; right: 20px; display: none; z-index: 1000;">
    <i class="fas fa-arrow-up"></i>
</button>

<script>
// Back to top functionality
let mybutton = document.getElementById("btn-back-to-top");

window.onscroll = function () {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        mybutton.style.display = "block";
    } else {
        mybutton.style.display = "none";
    }
};

mybutton.addEventListener("click", function () {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
});
</script>

</body>
</html>