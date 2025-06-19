<?php include 'app/views/shares/header.php'; ?>
<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="display-5">🛒 Giỏ hàng của bạn</h1>
        </div>
        <div class="col-md-4 text-end">
            <a href="/hoangduyminh/Product/home" class="btn btn-outline-primary">
                <i class="fas fa-arrow-left me-2"></i>Tiếp tục mua sắm
            </a>
        </div>
    </div>

    <!-- Loading -->
    <div id="loading" class="text-center py-5">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Đang tải...</span>
        </div>
        <p class="mt-2">Đang tải giỏ hàng...</p>
    </div>

    <!-- Empty Cart -->
    <div id="empty-cart" class="alert alert-info text-center py-5" style="display: none;">
        <i class="fas fa-shopping-cart text-muted mb-3" style="font-size: 4rem;"></i>
        <h4>Giỏ hàng trống</h4>
        <p class="text-muted">Bạn chưa có sản phẩm nào trong giỏ hàng.</p>
        <a href="/hoangduyminh/Product/home" class="btn btn-primary btn-lg">
            <i class="fas fa-shopping-bag me-2"></i>Bắt đầu mua sắm
        </a>
    </div>

    <!-- Cart Content -->
    <div id="cart-content" style="display: none;">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 100px;">Hình ảnh</th>
                        <th>Tên sản phẩm</th>
                        <th style="width: 120px;">Giá</th>
                        <th style="width: 150px;">Số lượng</th>
                        <th style="width: 120px;">Thành tiền</th>
                        <th style="width: 80px;">Thao tác</th>
                    </tr>
                </thead>
                <tbody id="cart-items">
                    <!-- Cart items will be loaded here -->
                </tbody>
            </table>
        </div>

        <!-- Cart Summary -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">💡 Thông tin hữu ích</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <i class="fas fa-truck text-success me-2"></i>
                                Miễn phí vận chuyển với đơn hàng > 500k
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-shield-alt text-primary me-2"></i>
                                Bảo hành chính hãng 12 tháng
                            </li>
                            <li class="mb-0">
                                <i class="fas fa-undo text-warning me-2"></i>
                                Đổi trả trong 7 ngày
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">📋 Tóm tắt đơn hàng</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tạm tính:</span>
                            <span id="subtotal">0₫</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Phí vận chuyển:</span>
                            <span id="shipping">0₫</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <strong>Tổng cộng:</strong>
                            <strong class="text-danger h5" id="total">0₫</strong>
                        </div>
                        <div class="d-grid">
                            <a href="/hoangduyminh/Cart/checkout" class="btn btn-success btn-lg" id="checkout-btn">
                                <i class="fas fa-credit-card me-2"></i>Thanh toán
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    loadCart();
});

function loadCart() {
    // Get cart from sessionStorage (client-side cart management)
    const cart = JSON.parse(sessionStorage.getItem('cart') || '{}');
    const cartItems = Object.keys(cart);
    
    document.getElementById('loading').style.display = 'none';
    
    if (cartItems.length === 0) {
        document.getElementById('empty-cart').style.display = 'block';
        return;
    }
    
    document.getElementById('cart-content').style.display = 'block';
    
    // Load product details for each cart item
    Promise.all(cartItems.map(productId => loadProductDetails(productId)))
        .then(products => {
            displayCartItems(products, cart);
            updateCartSummary(products, cart);
        })
        .catch(error => {
            console.error('Error loading cart:', error);
            showError('Có lỗi xảy ra khi tải giỏ hàng');
        });
}

function loadProductDetails(productId) {
    return fetch(`/hoangduyminh/api/product/${productId}`)
        .then(response => {
            return response.text().then(text => {
                // Clean FFF prefix
                let cleanText = text;
                if (text.startsWith('FFF')) {
                    cleanText = text.substring(3);
                }
                try {
                    return JSON.parse(cleanText);
                } catch (e) {
                    console.error('Failed to parse JSON:', cleanText);
                    throw new Error('Invalid JSON response');
                }
            });
        });
}

function displayCartItems(products, cart) {
    const tbody = document.getElementById('cart-items');
    tbody.innerHTML = '';
    
    products.forEach(product => {
        if (product.message === 'Product not found') return;
        
        const quantity = cart[product.id].quantity;
        const subtotal = product.price * quantity;
        
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>
                ${product.image ? 
                    `<img src="/hoangduyminh/${product.image}" alt="${escapeHtml(product.name)}" 
                          class="img-thumbnail" style="width: 80px; height: 80px; object-fit: cover;">` :
                    `<div class="bg-light d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                       <i class="fas fa-mobile-alt text-muted"></i>
                     </div>`
                }
            </td>
            <td>
                <h6 class="mb-1">
                    <a href="/hoangduyminh/Product/show/${product.id}" class="text-decoration-none">
                        ${escapeHtml(product.name)}
                    </a>
                </h6>
                <small class="text-muted">${product.category_name || 'Chưa phân loại'}</small>
            </td>
            <td>
                <span class="fw-bold text-danger">${formatPrice(product.price)}₫</span>
            </td>
            <td>
                <div class="d-flex align-items-center">
                    <button class="btn btn-sm btn-outline-secondary" onclick="updateQuantity(${product.id}, ${quantity - 1})" 
                            ${quantity <= 1 ? 'disabled' : ''}>
                        <i class="fas fa-minus"></i>
                    </button>
                    <input type="number" class="form-control mx-2 text-center" style="width: 60px;" 
                           value="${quantity}" min="1" max="${product.SoLuong || 999}" 
                           onchange="updateQuantity(${product.id}, this.value)">
                    <button class="btn btn-sm btn-outline-secondary" onclick="updateQuantity(${product.id}, ${quantity + 1})"
                            ${quantity >= (product.SoLuong || 999) ? 'disabled' : ''}>
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
            </td>
            <td>
                <span class="fw-bold text-success">${formatPrice(subtotal)}₫</span>
            </td>
            <td>
                <button class="btn btn-sm btn-danger" onclick="removeFromCart(${product.id})" 
                        title="Xóa khỏi giỏ hàng">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;
        tbody.appendChild(row);
    });
}

function updateCartSummary(products, cart) {
    let subtotal = 0;
    
    products.forEach(product => {
        if (product.message === 'Product not found') return;
        const quantity = cart[product.id].quantity;
        subtotal += product.price * quantity;
    });
    
    const shipping = subtotal > 500000 ? 0 : 30000; // Free shipping over 500k
    const total = subtotal + shipping;
    
    document.getElementById('subtotal').textContent = formatPrice(subtotal) + '₫';
    document.getElementById('shipping').textContent = shipping === 0 ? 'Miễn phí' : formatPrice(shipping) + '₫';
    document.getElementById('total').textContent = formatPrice(total) + '₫';
}

function updateQuantity(productId, newQuantity) {
    newQuantity = parseInt(newQuantity);
    if (newQuantity < 1) return;
    
    const cart = JSON.parse(sessionStorage.getItem('cart') || '{}');
    
    if (cart[productId]) {
        cart[productId].quantity = newQuantity;
        sessionStorage.setItem('cart', JSON.stringify(cart));
        
        // Update cart count in header
        updateCartCount();
        
        // Reload cart display
        loadCart();
        
        showToast('Đã cập nhật số lượng!', 'success');
    }
}

function removeFromCart(productId) {
    if (!confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?')) return;
    
    const cart = JSON.parse(sessionStorage.getItem('cart') || '{}');
    delete cart[productId];
    sessionStorage.setItem('cart', JSON.stringify(cart));
    
    // Update cart count in header
    updateCartCount();
    
    // Reload cart display
    loadCart();
    
    showToast('Đã xóa sản phẩm khỏi giỏ hàng!', 'success');
}

function updateCartCount() {
    const cart = JSON.parse(sessionStorage.getItem('cart') || '{}');
    const count = Object.values(cart).reduce((total, item) => total + item.quantity, 0);
    const cartCountElement = document.getElementById('cart-count');
    if (cartCountElement) {
        cartCountElement.textContent = count;
    }
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

function showError(message) {
    document.getElementById('loading').style.display = 'none';
    document.getElementById('cart-content').innerHTML = `
        <div class="alert alert-danger text-center">
            <i class="fas fa-exclamation-triangle me-2"></i>
            ${message}
            <button class="btn btn-primary ms-3" onclick="loadCart()">
                <i class="fas fa-redo me-1"></i>Thử lại
            </button>
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

<?php include 'app/views/shares/footer.php'; ?>