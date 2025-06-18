<?php include 'app/views/shares/header.php'; ?>
<div class="container mt-4">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white text-center">
            <h2 class="mb-0">Chi tiết sản phẩm</h2>
        </div>
        <div class="card-body" id="product-details">
            <div class="text-center">
                <div class="spinner-border" role="status">
                    <span class="sr-only">Đang tải...</span>
                </div>
                <p>Đang tải thông tin sản phẩm...</p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Get product ID from URL
    const urlPath = window.location.pathname;
    const productId = urlPath.split('/').pop();
    
    if (!productId) {
        showError('ID sản phẩm không hợp lệ');
        return;
    }

    loadProduct(productId);
});

function loadProduct(productId) {
    fetch(`/hoangduyminh/api/product/${productId}`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(product => {
        if (product.message && product.message === 'Product not found') {
            showError('Không tìm thấy sản phẩm!');
            return;
        }

        displayProduct(product);
    })
    .catch(error => {
        console.error('Error loading product:', error);
        showError('Có lỗi xảy ra khi tải thông tin sản phẩm');
    });
}

function displayProduct(product) {
    const productDetails = document.getElementById('product-details');
    
    const imageHtml = product.image
        ? `<img src="/hoangduyminh/${product.image}" class="img-fluid rounded" alt="${product.name}">`
        : `<img src="/hoangduyminh/images/no-image.png" class="img-fluid rounded" alt="Không có ảnh">`;

    const stockBadgeClass = (product.SoLuong || 0) > 0 ? 'badge-success' : 'badge-danger';
    const stockText = product.SoLuong || '0';

    const addToCartButton = (product.SoLuong || 0) > 0
        ? `<a href="/hoangduyminh/Product/addToCart/${product.id}" class="btn btn-success px-4">➕ Thêm vào giỏ hàng</a>`
        : `<button class="btn btn-secondary px-4" disabled>Hết hàng</button>`;

    productDetails.innerHTML = `
        <div class="row">
            <div class="col-md-6">
                ${imageHtml}
            </div>
            <div class="col-md-6">
                <h3 class="card-title text-dark font-weight-bold">
                    ${escapeHtml(product.name)}
                </h3>
                <p class="card-text">
                    ${escapeHtml(product.description).replace(/\n/g, '<br>')}
                </p>
                <p class="text-danger font-weight-bold h4">
                    💰 ${formatPrice(product.price)} VND
                </p>
                <p><strong>Số lượng còn lại:</strong>
                    <span class="badge ${stockBadgeClass} text-white">
                        ${stockText}
                    </span>
                </p>
                <p><strong>Danh mục:</strong>
                    <span class="badge badge-info text-white">
                        ${product.category_name || 'Chưa có danh mục'}
                    </span>
                </p>
                <div class="mt-4">
                    ${addToCartButton}
                    <a href="/hoangduyminh/Product/" class="btn btn-secondary px-4 ml-2">Quay lại danh sách</a>
                </div>
            </div>
        </div>
    `;
}

function showError(message) {
    const productDetails = document.getElementById('product-details');
    productDetails.innerHTML = `
        <div class="alert alert-danger text-center">
            <h4>${message}</h4>
            <a href="/hoangduyminh/Product/" class="btn btn-primary mt-3">Quay lại danh sách sản phẩm</a>
        </div>
    `;
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function formatPrice(price) {
    return new Intl.NumberFormat('vi-VN').format(price);
}
</script>

<?php include 'app/views/shares/footer.php'; ?>