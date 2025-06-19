<?php include 'app/views/shares/header.php'; ?>
<div class="container mt-4">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0">
                        <i class="fas fa-credit-card me-2"></i>Thông tin thanh toán
                    </h3>
                </div>
                <div class="card-body">
                    <!-- Error Messages -->
                    <div id="error-messages" class="alert alert-danger" style="display: none;">
                        <ul id="error-list"></ul>
                    </div>

                    <!-- Success Messages -->
                    <div id="success-message" class="alert alert-success" style="display: none;"></div>

                    <form id="checkout-form">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="fullname" class="form-label fw-bold">
                                        <i class="fas fa-user me-2 text-primary"></i>Họ và tên *
                                    </label>
                                    <input type="text" id="fullname" name="fullname" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label fw-bold">
                                        <i class="fas fa-phone me-2 text-primary"></i>Số điện thoại *
                                    </label>
                                    <input type="tel" id="phone" name="phone" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold">
                                <i class="fas fa-envelope me-2 text-primary"></i>Email *
                            </label>
                            <input type="email" id="email" name="email" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label fw-bold">
                                <i class="fas fa-map-marker-alt me-2 text-primary"></i>Địa chỉ giao hàng *
                            </label>
                            <textarea id="address" name="address" class="form-control" rows="3" 
                                      placeholder="Số nhà, tên đường, phường/xã, quận/huyện, tỉnh/thành phố" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="payment_method" class="form-label fw-bold">
                                <i class="fas fa-credit-card me-2 text-primary"></i>Phương thức thanh toán
                            </label>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment_method" 
                                               id="cod" value="cod" checked>
                                        <label class="form-check-label" for="cod">
                                            <i class="fas fa-money-bill-wave me-2"></i>Thanh toán khi nhận hàng (COD)
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="payment_method" 
                                               id="bank_transfer" value="bank_transfer">
                                        <label class="form-check-label" for="bank_transfer">
                                            <i class="fas fa-university me-2"></i>Chuyển khoản ngân hàng
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="note" class="form-label fw-bold">
                                <i class="fas fa-sticky-note me-2 text-primary"></i>Ghi chú đơn hàng
                            </label>
                            <textarea id="note" name="note" class="form-control" rows="2" 
                                      placeholder="Ghi chú thêm về đơn hàng, thời gian giao hàng..."></textarea>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="/hoangduyminh/Cart/cart" class="btn btn-secondary btn-lg me-md-2">
                                <i class="fas fa-arrow-left me-2"></i>Quay lại giỏ hàng
                            </a>
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-check me-2"></i>Đặt hàng
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Order Summary -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-shopping-cart me-2"></i>Đơn hàng của bạn
                    </h5>
                </div>
                <div class="card-body">
                    <div id="order-loading" class="text-center py-3">
                        <div class="spinner-border spinner-border-sm" role="status"></div>
                        <span class="ms-2">Đang tải...</span>
                    </div>
                    <div id="order-items"></div>
                </div>
            </div>

            <!-- Delivery Info -->
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-truck me-2"></i>Thông tin giao hàng
                    </h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-clock text-primary me-2"></i>
                            <strong>Thời gian:</strong> 2-4 giờ (nội thành), 1-2 ngày (ngoại thành)
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-shield-alt text-success me-2"></i>
                            <strong>Bảo hành:</strong> 12 tháng chính hãng
                        </li>
                        <li class="mb-0">
                            <i class="fas fa-undo text-warning me-2"></i>
                            <strong>Đổi trả:</strong> 7 ngày miễn phí
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    loadOrderSummary();
    loadUserInfo();
    
    document.getElementById('checkout-form').addEventListener('submit', function(event) {
        event.preventDefault();
        processCheckout();
    });
});

function loadOrderSummary() {
    const cart = JSON.parse(sessionStorage.getItem('cart') || '{}');
    const cartItems = Object.keys(cart);
    
    if (cartItems.length === 0) {
        document.getElementById('order-items').innerHTML = `
            <div class="alert alert-warning text-center">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Giỏ hàng trống!
                <a href="/hoangduyminh/Product/home" class="btn btn-primary btn-sm ms-2">Mua sắm ngay</a>
            </div>
        `;
        return;
    }
    
    Promise.all(cartItems.map(productId => loadProductDetails(productId)))
        .then(products => {
            displayOrderSummary(products, cart);
        })
        .catch(error => {
            console.error('Error loading order summary:', error);
            document.getElementById('order-items').innerHTML = `
                <div class="alert alert-danger">Có lỗi khi tải thông tin đơn hàng</div>
            `;
        })
        .finally(() => {
            document.getElementById('order-loading').style.display = 'none';
        });
}

function loadProductDetails(productId) {
    return fetch(`/hoangduyminh/api/product/${productId}`)
        .then(response => {
            return response.text().then(text => {
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

function displayOrderSummary(products, cart) {
    let html = '';
    let subtotal = 0;
    
    products.forEach(product => {
        if (product.message === 'Product not found') return;
        
        const quantity = cart[product.id].quantity;
        const itemTotal = product.price * quantity;
        subtotal += itemTotal;
        
        html += `
            <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                <div class="flex-grow-1">
                    <h6 class="mb-1">${escapeHtml(product.name)}</h6>
                    <small class="text-muted">Số lượng: ${quantity}</small>
                </div>
                <div class="text-end">
                    <span class="fw-bold">${formatPrice(itemTotal)}₫</span>
                </div>
            </div>
        `;
    });
    
    const shipping = subtotal > 500000 ? 0 : 30000;
    const total = subtotal + shipping;
    
    html += `
        <div class="mt-3">
            <div class="d-flex justify-content-between mb-2">
                <span>Tạm tính:</span>
                <span>${formatPrice(subtotal)}₫</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span>Phí vận chuyển:</span>
                <span>${shipping === 0 ? 'Miễn phí' : formatPrice(shipping) + '₫'}</span>
            </div>
            <hr>
            <div class="d-flex justify-content-between">
                <strong>Tổng cộng:</strong>
                <strong class="text-danger h5">${formatPrice(total)}₫</strong>
            </div>
        </div>
    `;
    
    document.getElementById('order-items').innerHTML = html;
}

function loadUserInfo() {
    // Pre-fill user info if logged in
    const username = sessionStorage.getItem('username');
    const fullname = sessionStorage.getItem('fullname');
    
    if (fullname && fullname.trim()) {
        document.getElementById('fullname').value = fullname;
    } else if (username) {
        document.getElementById('fullname').value = username;
    }
}

function processCheckout() {
    const formData = new FormData(document.getElementById('checkout-form'));
    const cart = JSON.parse(sessionStorage.getItem('cart') || '{}');
    
    if (Object.keys(cart).length === 0) {
        showError(['Giỏ hàng trống! Vui lòng thêm sản phẩm trước khi đặt hàng.']);
        return;
    }
    
    const orderData = {
        customer_info: {
            fullname: formData.get('fullname'),
            phone: formData.get('phone'),
            email: formData.get('email'),
            address: formData.get('address'),
            payment_method: formData.get('payment_method'),
            note: formData.get('note')
        },
        items: cart,
        order_date: new Date().toISOString()
    };
    
    // Validate form
    const errors = validateCheckoutForm(orderData.customer_info);
    if (errors.length > 0) {
        showError(errors);
        return;
    }
    
    // Show loading state
    const submitBtn = document.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Đang xử lý...';
    submitBtn.disabled = true;
    
    // Save order to localStorage (in real app, this would be sent to server)
    const orderId = 'ORD' + Date.now();
    const orders = JSON.parse(localStorage.getItem('orders') || '[]');
    orders.push({
        id: orderId,
        ...orderData,
        status: 'pending',
        created_at: new Date().toISOString()
    });
    localStorage.setItem('orders', JSON.stringify(orders));
    
    // Clear cart
    sessionStorage.removeItem('cart');
    
    // Update cart count
    const cartCountElement = document.getElementById('cart-count');
    if (cartCountElement) {
        cartCountElement.textContent = '0';
    }
    
    // Simulate API delay
    setTimeout(() => {
        showSuccess('Đặt hàng thành công! Đang chuyển hướng...');
        
        setTimeout(() => {
            window.location.href = `/hoangduyminh/Cart/orderConfirmation?order_id=${orderId}`;
        }, 2000);
    }, 1500);
}

function validateCheckoutForm(customerInfo) {
    const errors = [];
    
    if (!customerInfo.fullname || customerInfo.fullname.trim().length < 2) {
        errors.push('Họ tên phải có ít nhất 2 ký tự');
    }
    
    if (!customerInfo.phone || !/^[0-9]{10,11}$/.test(customerInfo.phone.replace(/\s/g, ''))) {
        errors.push('Số điện thoại không hợp lệ (10-11 số)');
    }
    
    if (!customerInfo.email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(customerInfo.email)) {
        errors.push('Email không hợp lệ');
    }
    
    if (!customerInfo.address || customerInfo.address.trim().length < 10) {
        errors.push('Địa chỉ phải có ít nhất 10 ký tự');
    }
    
    return errors;
}

function showError(errors) {
    const errorDiv = document.getElementById('error-messages');
    const errorList = document.getElementById('error-list');
    const successDiv = document.getElementById('success-message');
    
    successDiv.style.display = 'none';
    errorList.innerHTML = '';
    
    errors.forEach(error => {
        const li = document.createElement('li');
        li.textContent = error;
        errorList.appendChild(li);
    });
    
    errorDiv.style.display = 'block';
    errorDiv.scrollIntoView({ behavior: 'smooth' });
}

function showSuccess(message) {
    const errorDiv = document.getElementById('error-messages');
    const successDiv = document.getElementById('success-message');
    
    errorDiv.style.display = 'none';
    successDiv.textContent = message;
    successDiv.style.display = 'block';
    successDiv.scrollIntoView({ behavior: 'smooth' });
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