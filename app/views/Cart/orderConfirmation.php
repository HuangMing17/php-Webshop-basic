<?php include 'app/views/shares/header.php'; ?>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Success Header -->
            <div class="text-center mb-4">
                <div class="mb-3">
                    <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                </div>
                <h1 class="text-success mb-2">🎉 Đặt hàng thành công!</h1>
                <p class="text-muted">Cảm ơn bạn đã mua sắm tại TechPhone Store</p>
            </div>

            <!-- Order Details Card -->
            <div class="card shadow-lg" id="order-details-card">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-receipt me-2"></i>Thông tin đơn hàng
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Loading -->
                    <div id="loading" class="text-center py-3">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Đang tải...</span>
                        </div>
                        <p class="mt-2">Đang tải thông tin đơn hàng...</p>
                    </div>

                    <!-- Order Content -->
                    <div id="order-content" style="display: none;">
                        <!-- Order Info -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h5 class="text-primary">📋 Thông tin đơn hàng</h5>
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td><strong>Mã đơn hàng:</strong></td>
                                        <td><span id="order-id" class="badge bg-primary"></span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Ngày đặt:</strong></td>
                                        <td id="order-date"></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Trạng thái:</strong></td>
                                        <td><span class="badge bg-warning">Đang xử lý</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Thanh toán:</strong></td>
                                        <td id="payment-method"></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <h5 class="text-primary">👤 Thông tin khách hàng</h5>
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td><strong>Họ tên:</strong></td>
                                        <td id="customer-name"></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Điện thoại:</strong></td>
                                        <td id="customer-phone"></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Email:</strong></td>
                                        <td id="customer-email"></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Địa chỉ:</strong></td>
                                        <td id="customer-address"></td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <!-- Order Items -->
                        <h5 class="text-primary mb-3">🛒 Sản phẩm đã đặt</h5>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Sản phẩm</th>
                                        <th style="width: 100px;">Số lượng</th>
                                        <th style="width: 120px;">Đơn giá</th>
                                        <th style="width: 120px;">Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody id="order-items">
                                    <!-- Order items will be loaded here -->
                                </tbody>
                            </table>
                        </div>

                        <!-- Order Summary -->
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">📞 Hỗ trợ khách hàng</h6>
                                        <p class="card-text small">
                                            <strong>Hotline:</strong> 1900-123-456<br>
                                            <strong>Email:</strong> support@techphone.vn<br>
                                            <strong>Giờ làm việc:</strong> 8:00 - 22:00 (T2-CN)
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-success text-white">
                                    <div class="card-body">
                                        <h6 class="card-title">💰 Tổng thanh toán</h6>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Tạm tính:</span>
                                            <span id="subtotal-display">0₫</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Phí vận chuyển:</span>
                                            <span id="shipping-display">0₫</span>
                                        </div>
                                        <hr class="text-white">
                                        <div class="d-flex justify-content-between">
                                            <strong>Tổng cộng:</strong>
                                            <strong class="h5" id="total-display">0₫</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Next Steps -->
                        <div class="alert alert-info mt-4">
                            <h6 class="alert-heading">
                                <i class="fas fa-info-circle me-2"></i>Bước tiếp theo
                            </h6>
                            <ul class="mb-0">
                                <li>Chúng tôi sẽ liên hệ với bạn trong vòng 30 phút để xác nhận đơn hàng</li>
                                <li>Thời gian giao hàng: 2-4 giờ (nội thành), 1-2 ngày (ngoại thành)</li>
                                <li>Bạn có thể theo dõi đơn hàng qua email hoặc hotline</li>
                                <li>Thanh toán khi nhận hàng (COD) hoặc chuyển khoản trước</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Error State -->
                    <div id="error-state" class="text-center py-4" style="display: none;">
                        <i class="fas fa-exclamation-triangle text-warning mb-3" style="font-size: 3rem;"></i>
                        <h5>Không tìm thấy thông tin đơn hàng</h5>
                        <p class="text-muted">Đơn hàng có thể đã được xử lý hoặc không tồn tại.</p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="text-center mt-4">
                <a href="/hoangduyminh/Product/home" class="btn btn-primary btn-lg me-3">
                    <i class="fas fa-shopping-bag me-2"></i>Tiếp tục mua sắm
                </a>
                <button class="btn btn-outline-secondary btn-lg" onclick="window.print()">
                    <i class="fas fa-print me-2"></i>In đơn hàng
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    loadOrderDetails();
});

function loadOrderDetails() {
    // Get order ID from URL params
    const urlParams = new URLSearchParams(window.location.search);
    const orderId = urlParams.get('order_id');
    
    if (!orderId) {
        showError();
        return;
    }
    
    // Load order from localStorage (in real app, this would be from API)
    const orders = JSON.parse(localStorage.getItem('orders') || '[]');
    const order = orders.find(o => o.id === orderId);
    
    if (!order) {
        showError();
        return;
    }
    
    displayOrderDetails(order);
}

function displayOrderDetails(order) {
    document.getElementById('loading').style.display = 'none';
    document.getElementById('order-content').style.display = 'block';
    
    // Display order info
    document.getElementById('order-id').textContent = order.id;
    document.getElementById('order-date').textContent = formatDate(order.created_at);
    document.getElementById('payment-method').textContent = getPaymentMethodText(order.customer_info.payment_method);
    
    // Display customer info
    document.getElementById('customer-name').textContent = order.customer_info.fullname;
    document.getElementById('customer-phone').textContent = order.customer_info.phone;
    document.getElementById('customer-email').textContent = order.customer_info.email;
    document.getElementById('customer-address').textContent = order.customer_info.address;
    
    // Load and display order items
    loadOrderItems(order.items);
}

function loadOrderItems(cartItems) {
    const productIds = Object.keys(cartItems);
    
    Promise.all(productIds.map(productId => loadProductDetails(productId)))
        .then(products => {
            displayOrderItems(products, cartItems);
            calculateOrderSummary(products, cartItems);
        })
        .catch(error => {
            console.error('Error loading order items:', error);
            document.getElementById('order-items').innerHTML = `
                <tr><td colspan="4" class="text-center text-danger">
                    Có lỗi khi tải thông tin sản phẩm
                </td></tr>
            `;
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

function displayOrderItems(products, cartItems) {
    const tbody = document.getElementById('order-items');
    tbody.innerHTML = '';
    
    products.forEach(product => {
        if (product.message === 'Product not found') return;
        
        const quantity = cartItems[product.id].quantity;
        const itemTotal = product.price * quantity;
        
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        ${product.image ? 
                            `<img src="/hoangduyminh/${product.image}" alt="${escapeHtml(product.name)}" 
                                  class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">` :
                            `<div class="bg-light d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                               <i class="fas fa-mobile-alt text-muted"></i>
                             </div>`
                        }
                    </div>
                    <div>
                        <h6 class="mb-1">${escapeHtml(product.name)}</h6>
                        <small class="text-muted">${product.category_name || 'Chưa phân loại'}</small>
                    </div>
                </div>
            </td>
            <td class="text-center">
                <span class="badge bg-secondary">${quantity}</span>
            </td>
            <td class="text-end">
                ${formatPrice(product.price)}₫
            </td>
            <td class="text-end fw-bold">
                ${formatPrice(itemTotal)}₫
            </td>
        `;
        tbody.appendChild(row);
    });
}

function calculateOrderSummary(products, cartItems) {
    let subtotal = 0;
    
    products.forEach(product => {
        if (product.message === 'Product not found') return;
        const quantity = cartItems[product.id].quantity;
        subtotal += product.price * quantity;
    });
    
    const shipping = subtotal > 500000 ? 0 : 30000;
    const total = subtotal + shipping;
    
    document.getElementById('subtotal-display').textContent = formatPrice(subtotal) + '₫';
    document.getElementById('shipping-display').textContent = shipping === 0 ? 'Miễn phí' : formatPrice(shipping) + '₫';
    document.getElementById('total-display').textContent = formatPrice(total) + '₫';
}

function showError() {
    document.getElementById('loading').style.display = 'none';
    document.getElementById('error-state').style.display = 'block';
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleString('vi-VN', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit'
    });
}

function getPaymentMethodText(method) {
    const methods = {
        'cod': 'Thanh toán khi nhận hàng (COD)',
        'bank_transfer': 'Chuyển khoản ngân hàng'
    };
    return methods[method] || method;
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
@media print {
    .btn, .card-header { display: none !important; }
    .container { max-width: 100% !important; }
}
</style>

<?php include 'app/views/shares/footer.php'; ?>