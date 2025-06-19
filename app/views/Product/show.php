<?php include 'app/views/shares/header.php'; ?>

<div class="container my-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/hoangduyminh/Product/home" class="text-decoration-none">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="/hoangduyminh/Product/home" class="text-decoration-none">Sản phẩm</a></li>
            <li class="breadcrumb-item active" aria-current="page" id="product-breadcrumb">Chi tiết sản phẩm</li>
        </ol>
    </nav>

    <!-- Loading -->
    <div id="loading" class="text-center py-5">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Đang tải...</span>
        </div>
        <p class="mt-2">Đang tải thông tin sản phẩm...</p>
    </div>

    <!-- Product Details -->
    <div id="product-details" style="display: none;"></div>
</div>

<!-- Related Products Section -->
<div class="container my-5" id="related-products" style="display: none;">
    <h3 class="fw-bold mb-4">Sản phẩm liên quan</h3>
    <div class="row" id="related-products-container"></div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const urlPath = window.location.pathname;
    const productId = urlPath.split('/').pop();
    
    if (!productId) {
        showError('ID sản phẩm không hợp lệ');
        return;
    }

    loadProduct(productId);
});

function loadProduct(productId) {
    fetch(`/hoangduyminh/api/product/${productId}`)
        .then(response => response.json())
        .then(product => {
            if (product.message && product.message === 'Product not found') {
                showError('Không tìm thấy sản phẩm!');
                return;
            }

            displayProduct(product);
            loadRelatedProducts(product.category_id, productId);
        })
        .catch(error => {
            console.error('Error loading product:', error);
            showError('Có lỗi xảy ra khi tải thông tin sản phẩm');
        });
}

function displayProduct(product) {
    document.getElementById('loading').style.display = 'none';
    document.getElementById('product-details').style.display = 'block';
    
    // Update breadcrumb
    document.getElementById('product-breadcrumb').textContent = product.name;
    
    const inStock = (product.SoLuong || 0) > 0;
    const stockStatus = inStock ? 
        `<span class="badge bg-success fs-6"><i class="fas fa-check me-1"></i>Còn hàng (${product.SoLuong})</span>` :
        `<span class="badge bg-danger fs-6"><i class="fas fa-times me-1"></i>Hết hàng</span>`;
    
    const imageHtml = product.image 
        ? `<img src="/hoangduyminh/${product.image}" class="img-fluid rounded shadow" alt="${escapeHtml(product.name)}" style="max-height: 500px;">`
        : `<div class="bg-light d-flex align-items-center justify-content-center rounded" style="height: 500px;">
             <i class="fas fa-mobile-alt text-muted" style="font-size: 5rem;"></i>
           </div>`;

    const price = formatPrice(product.price);
    
    document.getElementById('product-details').innerHTML = `
        <div class="row">
            <!-- Product Image -->
            <div class="col-lg-6 mb-4">
                <div class="text-center">
                    ${imageHtml}
                </div>
            </div>
            
            <!-- Product Info -->
            <div class="col-lg-6">
                <div class="ps-lg-4">
                    <!-- Category Badge -->
                    <div class="mb-3">
                        <span class="badge bg-primary fs-6">
                            <i class="fas fa-tag me-1"></i>${product.category_name || 'Chưa phân loại'}
                        </span>
                    </div>
                    
                    <!-- Product Name -->
                    <h1 class="fw-bold mb-3">${escapeHtml(product.name)}</h1>
                    
                    <!-- Rating (mock data) -->
                    <div class="d-flex align-items-center mb-3">
                        <div class="me-3">
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                        </div>
                        <span class="text-muted">(4.8 - 152 đánh giá)</span>
                    </div>
                    
                    <!-- Price -->
                    <div class="mb-4">
                        <h2 class="text-danger fw-bold mb-2">${price}₫</h2>
                        <small class="text-muted text-decoration-line-through">
                            ${formatPrice(product.price * 1.2)}₫
                        </small>
                        <span class="badge bg-danger ms-2">-17%</span>
                    </div>
                    
                    <!-- Stock Status -->
                    <div class="mb-4">
                        ${stockStatus}
                    </div>
                    
                    <!-- Description -->
                    <div class="mb-4">
                        <h5 class="fw-bold">Mô tả sản phẩm</h5>
                        <p class="text-muted">${escapeHtml(product.description)}</p>
                    </div>
                    
                    <!-- Key Features -->
                    <div class="mb-4">
                        <h5 class="fw-bold">Đặc điểm nổi bật</h5>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Bảo hành chính hãng 12 tháng</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Giao hàng miễn phí toàn quốc</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Đổi trả trong 7 ngày</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Hỗ trợ kỹ thuật 24/7</li>
                        </ul>
                    </div>
                    
                    <!-- Quantity and Add to Cart -->
                    <div class="row mb-4">
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Số lượng:</label>
                            <div class="input-group">
                                <button class="btn btn-outline-secondary" type="button" onclick="decreaseQuantity()">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="number" class="form-control text-center" id="quantity" value="1" min="1" max="${product.SoLuong || 0}">
                                <button class="btn btn-outline-secondary" type="button" onclick="increaseQuantity()">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="d-grid gap-3 d-md-flex">
                        ${inStock ?
                            `<button class="btn btn-danger btn-lg flex-fill" onclick="addToCart(${product.id})">
                                <i class="fas fa-cart-plus me-2"></i>Thêm vào giỏ hàng
                             </button>
                             <button class="btn btn-success btn-lg flex-fill" onclick="buyNow(${product.id})">
                                <i class="fas fa-bolt me-2"></i>Mua ngay
                             </button>` :
                            `<button class="btn btn-secondary btn-lg flex-fill" disabled>
                                <i class="fas fa-times me-2"></i>Hết hàng
                             </button>`
                        }
                    </div>
                    
                    <!-- Contact Info -->
                    <div class="mt-4 p-3 bg-light rounded">
                        <div class="row text-center">
                            <div class="col-md-4 mb-2">
                                <i class="fas fa-phone text-primary"></i>
                                <small class="d-block">Hotline: 1900-123-456</small>
                            </div>
                            <div class="col-md-4 mb-2">
                                <i class="fas fa-comments text-primary"></i>
                                <small class="d-block">Chat tư vấn</small>
                            </div>
                            <div class="col-md-4 mb-2">
                                <i class="fas fa-map-marker-alt text-primary"></i>
                                <small class="d-block">Tìm cửa hàng</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
}

function loadRelatedProducts(categoryId, currentProductId) {
    if (!categoryId) return;
    
    fetch('/hoangduyminh/api/product')
        .then(response => response.json())
        .then(products => {
            const relatedProducts = products
                .filter(p => p.category_id == categoryId && p.id != currentProductId)
                .slice(0, 4);
            
            if (relatedProducts.length > 0) {
                displayRelatedProducts(relatedProducts);
            }
        })
        .catch(error => console.error('Error loading related products:', error));
}

function displayRelatedProducts(products) {
    const container = document.getElementById('related-products-container');
    container.innerHTML = '';
    
    products.forEach(product => {
        const inStock = (product.SoLuong || 0) > 0;
        const imageHtml = product.image 
            ? `<img src="/hoangduyminh/${product.image}" class="card-img-top" alt="${escapeHtml(product.name)}" style="height: 200px; object-fit: cover;">`
            : `<div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                 <i class="fas fa-mobile-alt text-muted" style="font-size: 2rem;"></i>
               </div>`;
        
        const col = document.createElement('div');
        col.className = 'col-lg-3 col-md-6 mb-4';
        col.innerHTML = `
            <div class="card h-100 shadow-sm">
                ${imageHtml}
                <div class="card-body">
                    <h6 class="card-title">
                        <a href="/hoangduyminh/Product/show/${product.id}" class="text-decoration-none text-dark">
                            ${escapeHtml(product.name)}
                        </a>
                    </h6>
                    <p class="text-danger fw-bold">${formatPrice(product.price)}₫</p>
                    <div class="d-grid">
                        ${inStock ? 
                            `<a href="/hoangduyminh/Product/show/${product.id}" class="btn btn-outline-primary btn-sm">
                                Xem chi tiết
                             </a>` :
                            `<button class="btn btn-secondary btn-sm" disabled>Hết hàng</button>`
                        }
                    </div>
                </div>
            </div>
        `;
        container.appendChild(col);
    });
    
    document.getElementById('related-products').style.display = 'block';
}

function increaseQuantity() {
    const input = document.getElementById('quantity');
    const max = parseInt(input.getAttribute('max'));
    const current = parseInt(input.value);
    if (current < max) {
        input.value = current + 1;
    }
}

function decreaseQuantity() {
    const input = document.getElementById('quantity');
    const current = parseInt(input.value);
    if (current > 1) {
        input.value = current - 1;
    }
}

function addToCart(productId) {
    const quantity = parseInt(document.getElementById('quantity').value);
    
    // Simple cart management using sessionStorage
    const cart = JSON.parse(sessionStorage.getItem('cart') || '{}');
    if (cart[productId]) {
        cart[productId].quantity += quantity;
    } else {
        cart[productId] = { quantity: quantity };
    }
    sessionStorage.setItem('cart', JSON.stringify(cart));
    
    // Update cart count in header
    updateCartCount();
    
    // Show success message
    showToast('Đã thêm sản phẩm vào giỏ hàng!', 'success');
}

function buyNow(productId) {
    const quantity = parseInt(document.getElementById('quantity').value);
    
    // Add to cart first
    const cart = JSON.parse(sessionStorage.getItem('cart') || '{}');
    cart[productId] = { quantity: quantity };
    sessionStorage.setItem('cart', JSON.stringify(cart));
    
    // Update cart count
    updateCartCount();
    
    // Redirect to checkout
    window.location.href = '/hoangduyminh/Cart/checkout';
}

function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `alert alert-${type} position-fixed top-0 end-0 m-3`;
    toast.style.zIndex = '9999';
    toast.innerHTML = `
        ${message}
        <button type="button" class="btn-close ms-2" onclick="this.parentElement.remove()"></button>
    `;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        if (toast.parentElement) {
            toast.remove();
        }
    }, 3000);
}

function updateCartCount() {
    const cart = JSON.parse(sessionStorage.getItem('cart') || '{}');
    const count = Object.values(cart).reduce((total, item) => total + item.quantity, 0);
    const cartCountElement = document.getElementById('cart-count');
    if (cartCountElement) {
        cartCountElement.textContent = count;
    }
}

function showError(message) {
    document.getElementById('loading').style.display = 'none';
    document.getElementById('product-details').innerHTML = `
        <div class="text-center py-5">
            <i class="fas fa-exclamation-triangle text-danger mb-3" style="font-size: 4rem;"></i>
            <h4 class="text-danger">${message}</h4>
            <a href="/hoangduyminh/Product/home" class="btn btn-primary mt-3">
                <i class="fas fa-arrow-left me-2"></i>Quay lại trang chủ
            </a>
        </div>
    `;
    document.getElementById('product-details').style.display = 'block';
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

<?php include 'app/views/shares/footer.php'; ?>