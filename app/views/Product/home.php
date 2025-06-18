<?php include 'app/views/shares/header.php'; ?>

<!-- Hero Section -->
<div class="bg-primary text-white py-5 mb-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold">TechPhone Store</h1>
                <p class="lead">Điện thoại chính hãng - Giá tốt nhất thị trường</p>
                <div class="d-flex align-items-center mb-3">
                    <i class="fas fa-star text-warning me-1"></i>
                    <i class="fas fa-star text-warning me-1"></i>
                    <i class="fas fa-star text-warning me-1"></i>
                    <i class="fas fa-star text-warning me-1"></i>
                    <i class="fas fa-star text-warning me-2"></i>
                    <span>4.9/5 (2,000+ đánh giá)</span>
                </div>
                <a href="#products" class="btn btn-light btn-lg">
                    <i class="fas fa-shopping-cart me-2"></i>Mua ngay
                </a>
            </div>
            <div class="col-lg-6 text-center">
                <i class="fas fa-mobile-alt" style="font-size: 150px; opacity: 0.3;"></i>
            </div>
        </div>
    </div>
</div>

<!-- Features Section -->
<div class="container mb-5">
    <div class="row text-center">
        <div class="col-md-3 mb-4">
            <div class="card border-0 h-100">
                <div class="card-body">
                    <i class="fas fa-shipping-fast text-primary mb-3" style="font-size: 2rem;"></i>
                    <h6 class="fw-bold">Giao hàng nhanh</h6>
                    <small class="text-muted">Giao hàng trong 2-4 giờ tại TP.HCM</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card border-0 h-100">
                <div class="card-body">
                    <i class="fas fa-shield-alt text-primary mb-3" style="font-size: 2rem;"></i>
                    <h6 class="fw-bold">Bảo hành chính hãng</h6>
                    <small class="text-muted">Bảo hành 12 tháng toàn quốc</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card border-0 h-100">
                <div class="card-body">
                    <i class="fas fa-undo text-primary mb-3" style="font-size: 2rem;"></i>
                    <h6 class="fw-bold">Đổi trả dễ dàng</h6>
                    <small class="text-muted">Đổi trả trong 7 ngày</small>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card border-0 h-100">
                <div class="card-body">
                    <i class="fas fa-headset text-primary mb-3" style="font-size: 2rem;"></i>
                    <h6 class="fw-bold">Hỗ trợ 24/7</h6>
                    <small class="text-muted">Tư vấn miễn phí mọi lúc</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter and Search Section -->
<div class="container mb-4" id="products">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="fw-bold">Sản phẩm nổi bật</h2>
            <p class="text-muted">Khám phá những mẫu điện thoại mới nhất</p>
        </div>
        <div class="col-md-4">
            <div class="input-group">
                <input type="text" id="search-input" class="form-control" placeholder="Tìm kiếm điện thoại...">
                <button class="btn btn-primary" type="button" id="search-button">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Category Filter -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex flex-wrap gap-2">
                <button class="btn btn-outline-primary active" data-category="all">Tất cả</button>
                <div id="category-filters"></div>
            </div>
        </div>
    </div>
</div>

<!-- Loading -->
<div id="loading" class="text-center py-5">
    <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Đang tải...</span>
    </div>
    <p class="mt-2">Đang tải sản phẩm...</p>
</div>

<!-- No Products -->
<div id="no-products" class="container text-center py-5" style="display: none;">
    <i class="fas fa-mobile-alt text-muted mb-3" style="font-size: 4rem;"></i>
    <h4 class="text-muted">Không tìm thấy sản phẩm nào</h4>
    <p class="text-muted">Thử thay đổi từ khóa tìm kiếm hoặc bộ lọc</p>
</div>

<!-- Products Grid -->
<div class="container mb-5">
    <div id="products-container" class="row"></div>
</div>

<script>
let allProducts = [];
let allCategories = [];

document.addEventListener("DOMContentLoaded", function() {
    loadCategories();
    loadProducts();
    
    // Search functionality
    document.getElementById('search-button').addEventListener('click', handleSearch);
    document.getElementById('search-input').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') handleSearch();
    });
});

function loadCategories() {
    fetch('/hoangduyminh/api/category')
        .then(response => response.json())
        .then(categories => {
            allCategories = categories;
            displayCategoryFilters(categories);
        })
        .catch(error => console.error('Error loading categories:', error));
}

function displayCategoryFilters(categories) {
    const container = document.getElementById('category-filters');
    container.innerHTML = '';
    
    categories.forEach(category => {
        const button = document.createElement('button');
        button.className = 'btn btn-outline-primary';
        button.setAttribute('data-category', category.id);
        button.innerHTML = `<i class="fas fa-mobile-alt me-1"></i>${category.name}`;
        button.addEventListener('click', () => filterByCategory(category.id, button));
        container.appendChild(button);
    });
}

function loadProducts() {
    fetch('/hoangduyminh/api/product')
        .then(response => response.json())
        .then(products => {
            document.getElementById('loading').style.display = 'none';
            allProducts = products;
            
            if (products.length === 0) {
                document.getElementById('no-products').style.display = 'block';
            } else {
                displayProducts(products);
            }
        })
        .catch(error => {
            console.error('Error loading products:', error);
            document.getElementById('loading').style.display = 'none';
            showError();
        });
}

function displayProducts(products) {
    const container = document.getElementById('products-container');
    const noProductsDiv = document.getElementById('no-products');
    
    if (products.length === 0) {
        noProductsDiv.style.display = 'block';
        container.innerHTML = '';
        return;
    }
    
    noProductsDiv.style.display = 'none';
    container.innerHTML = '';
    
    products.forEach(product => {
        const productCard = createProductCard(product);
        container.appendChild(productCard);
    });
}

function createProductCard(product) {
    const col = document.createElement('div');
    col.className = 'col-lg-3 col-md-4 col-sm-6 mb-4';
    
    const inStock = (product.SoLuong || 0) > 0;
    const stockBadge = !inStock ? '<span class="badge bg-danger position-absolute top-0 end-0 m-2">Hết hàng</span>' :
                     (product.SoLuong < 10) ? '<span class="badge bg-warning position-absolute top-0 end-0 m-2">Sắp hết</span>' : '';
    
    const imageHtml = product.image 
        ? `<img src="/hoangduyminh/${product.image}" class="card-img-top" alt="${escapeHtml(product.name)}" style="height: 250px; object-fit: cover;">`
        : `<div class="bg-light d-flex align-items-center justify-content-center" style="height: 250px;">
             <i class="fas fa-mobile-alt text-muted" style="font-size: 3rem;"></i>
           </div>`;
    
    const price = formatPrice(product.price);
    
    col.innerHTML = `
        <div class="card h-100 shadow-sm product-card">
            <div class="position-relative">
                ${imageHtml}
                ${stockBadge}
                ${inStock ? '<span class="badge bg-success position-absolute top-0 start-0 m-2">Còn hàng</span>' : ''}
            </div>
            <div class="card-body d-flex flex-column">
                <h6 class="card-title fw-bold">
                    <a href="/hoangduyminh/Product/show/${product.id}" class="text-decoration-none text-dark">
                        ${escapeHtml(product.name)}
                    </a>
                </h6>
                <p class="card-text text-muted small flex-grow-1">
                    ${escapeHtml(product.description).substring(0, 80)}...
                </p>
                <div class="mt-auto">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="text-danger fw-bold mb-0">${price}đ</h5>
                        <small class="text-muted">${product.category_name || 'Chưa phân loại'}</small>
                    </div>
                    <div class="d-grid gap-2">
                        ${inStock ? 
                            `<a href="/hoangduyminh/Product/addToCart/${product.id}" class="btn btn-primary">
                                <i class="fas fa-cart-plus me-1"></i>Thêm vào giỏ
                             </a>` :
                            `<button class="btn btn-secondary" disabled>
                                <i class="fas fa-times me-1"></i>Hết hàng
                             </button>`
                        }
                        <a href="/hoangduyminh/Product/show/${product.id}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-eye me-1"></i>Xem chi tiết
                        </a>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    return col;
}

function filterByCategory(categoryId, button) {
    // Update active button
    document.querySelectorAll('[data-category]').forEach(btn => btn.classList.remove('active'));
    button.classList.add('active');
    
    // Filter products
    let filteredProducts = allProducts;
    if (categoryId !== 'all') {
        filteredProducts = allProducts.filter(product => product.category_id == categoryId);
    }
    
    displayProducts(filteredProducts);
}

function handleSearch() {
    const searchTerm = document.getElementById('search-input').value.toLowerCase().trim();
    
    if (!searchTerm) {
        displayProducts(allProducts);
        return;
    }
    
    const filteredProducts = allProducts.filter(product => 
        product.name.toLowerCase().includes(searchTerm) ||
        product.description.toLowerCase().includes(searchTerm) ||
        (product.category_name && product.category_name.toLowerCase().includes(searchTerm))
    );
    
    displayProducts(filteredProducts);
}

function showError() {
    const container = document.getElementById('products-container');
    container.innerHTML = `
        <div class="col-12">
            <div class="alert alert-danger text-center">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Có lỗi xảy ra khi tải sản phẩm. Vui lòng thử lại sau.
                <button class="btn btn-primary ms-3" onclick="loadProducts()">
                    <i class="fas fa-redo me-1"></i>Thử lại
                </button>
            </div>
        </div>
    `;
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text || '';
    return div.innerHTML;
}

function formatPrice(price) {
    return new Intl.NumberFormat('vi-VN').format(price);
}
</script>

<style>
.product-card {
    transition: transform 0.2s, box-shadow 0.2s;
    border: none !important;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}

.btn-outline-primary.active {
    background-color: var(--bs-primary);
    color: white;
}

.hero-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
</style>

<?php include 'app/views/shares/footer.php'; ?>