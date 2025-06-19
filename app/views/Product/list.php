<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="display-6">
                <i class="fas fa-boxes me-2 text-primary"></i>Danh sách sản phẩm
            </h1>
            <p class="text-muted">Quản lý tất cả sản phẩm trong cửa hàng</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="/hoangduyminh/Product/add" class="btn btn-success btn-lg">
                <i class="fas fa-plus me-2"></i>Thêm sản phẩm mới
            </a>
        </div>
    </div>

    <!-- Loading -->
    <div id="loading" class="text-center py-5">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Đang tải...</span>
        </div>
        <p class="mt-2 text-muted">Đang tải danh sách sản phẩm...</p>
    </div>

    <!-- Error State -->
    <div id="error-state" class="alert alert-danger" style="display: none;">
        <h5 class="alert-heading">
            <i class="fas fa-exclamation-triangle me-2"></i>Có lỗi xảy ra!
        </h5>
        <p>Không thể tải danh sách sản phẩm</p>
        <button class="btn btn-outline-danger" onclick="loadProducts()">
            <i class="fas fa-redo me-2"></i>Thử lại
        </button>
    </div>

    <!-- Products Container -->
    <div id="products-container" style="display: none;">
        <div id="product-list">
            <!-- Products will be loaded here -->
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const token = localStorage.getItem('jwtToken');
        if (!token) {
            alert('Vui lòng đăng nhập');
            location.href = '/hoangduyminh/account/login'; // Điều hướng đến trang đăng nhập
            return;
        }
        fetch('/hoangduyminh/api/product', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
                // Removed Authorization header since GET /api/product doesn't require auth
            }
        })
            .then(response => {
                return response.text().then(text => {
                    // Clean any FFF prefix
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
            })
            .then(data => {
                console.log('Products loaded:', data); // Debug log
                document.getElementById('loading').style.display = 'none';
                
                if (data.length === 0) {
                    document.getElementById('product-list').innerHTML = `
                        <div class="text-center py-5">
                            <i class="fas fa-box-open text-muted mb-3" style="font-size: 4rem;"></i>
                            <h3 class="text-muted">Chưa có sản phẩm nào</h3>
                            <p class="text-muted">Hãy thêm sản phẩm đầu tiên cho cửa hàng</p>
                            <a href="/hoangduyminh/Product/add" class="btn btn-primary btn-lg">
                                <i class="fas fa-plus me-2"></i>Thêm sản phẩm đầu tiên
                            </a>
                        </div>
                    `;
                } else {
                    document.getElementById('products-container').style.display = 'block';
                    displayProducts(data);
                }
            })
            .catch(error => {
                console.error('Error loading products:', error);
                document.getElementById('loading').style.display = 'none';
                document.getElementById('error-state').style.display = 'block';
            });
    });

    function loadProducts() {
        document.getElementById('loading').style.display = 'block';
        document.getElementById('error-state').style.display = 'none';
        document.getElementById('products-container').style.display = 'none';
        
        // Re-run the fetch logic
        const event = new Event('DOMContentLoaded');
        document.dispatchEvent(event);
    }

    function displayProducts(products) {
        const productList = document.getElementById('product-list');
        productList.innerHTML = products.map(product => {
            const stock = product.SoLuong || 0;
            const stockClass = stock > 10 ? 'success' : stock > 0 ? 'warning' : 'danger';
            const stockText = stock > 10 ? 'Còn hàng' : stock > 0 ? `Còn ${stock}` : 'Hết hàng';
            
            return `
                <div class="card mb-3 shadow-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-2">
                                ${product.image ?
                                    `<img src="/hoangduyminh/${product.image}" alt="${escapeHtml(product.name)}"
                                         class="img-fluid rounded" style="height: 80px; width: 80px; object-fit: cover;">` :
                                    `<div class="bg-light rounded d-flex align-items-center justify-content-center"
                                         style="height: 80px; width: 80px;">
                                        <i class="fas fa-mobile-alt text-muted fa-2x"></i>
                                     </div>`
                                }
                            </div>
                            <div class="col-md-4">
                                <h5 class="card-title mb-1">
                                    <a href="/hoangduyminh/Product/show/${product.id}"
                                       class="text-decoration-none text-primary">${escapeHtml(product.name)}</a>
                                </h5>
                                <p class="text-muted small mb-2">${escapeHtml(product.description.substring(0, 100))}...</p>
                                <span class="badge bg-secondary">${product.category_name || 'Chưa phân loại'}</span>
                            </div>
                            <div class="col-md-2">
                                <h5 class="text-danger mb-0">${formatPrice(product.price)}₫</h5>
                            </div>
                            <div class="col-md-2">
                                <span class="badge bg-${stockClass} fs-6">${stockText}</span>
                            </div>
                            <div class="col-md-2 text-end">
                                <div class="btn-group" role="group">
                                    <a href="/hoangduyminh/Product/show/${product.id}"
                                       class="btn btn-outline-info btn-sm" title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="/hoangduyminh/Product/edit/${product.id}"
                                       class="btn btn-outline-warning btn-sm" title="Sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button class="btn btn-outline-danger btn-sm"
                                            onclick="deleteProduct(${product.id})" title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }).join('');
    }
    function deleteProduct(id) {
        if (confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')) {
            const token = localStorage.getItem('jwtToken');
            if (!token) {
                alert('Vui lòng đăng nhập để thực hiện thao tác này');
                location.href = '/hoangduyminh/account/login';
                return;
            }

            fetch(`/hoangduyminh/api/product/${id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + token
                }
            })
                .then(response => {
                    return response.text().then(text => {
                        // Clean any FFF prefix
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
                })
                .then(data => {
                    console.log('Delete product response:', data); // Debug log
                    if (data.message === 'Product deleted successfully') {
                        alert('Xóa sản phẩm thành công!');
                        location.reload();
                    } else {
                        alert('Xóa sản phẩm thất bại: ' + (data.message || 'Lỗi không xác định'));
                    }
                })
                .catch(error => {
                    console.error('Error deleting product:', error);
                    alert('Có lỗi xảy ra khi xóa sản phẩm');
                });
        }
    }

    // Helper functions
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text || '';
        return div.innerHTML;
    }

    function formatPrice(price) {
        return new Intl.NumberFormat('vi-VN').format(price);
    }
</script>