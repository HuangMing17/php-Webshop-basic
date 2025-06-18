<!-- filepath: app/views/Product/home.php -->
<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="display-4">Danh sách sản phẩm</h1>
        </div>
        <div class="col-md-4">
            <div class="input-group">
                <input type="text" id="search-input" class="form-control" placeholder="Tìm kiếm sản phẩm...">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="button" id="search-button">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="loading" class="text-center">
        <div class="spinner-border" role="status">
            <span class="sr-only">Đang tải...</span>
        </div>
        <p>Đang tải sản phẩm...</p>
    </div>

    <div id="no-products" class="alert alert-info" style="display: none;">
        <i class="bi bi-info-circle"></i> Hiện chưa có sản phẩm nào.
    </div>

    <div id="products-container" class="row"></div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    loadProducts();
    
    // Handle search functionality
    document.getElementById('search-button').addEventListener('click', function() {
        const searchTerm = document.getElementById('search-input').value;
        searchProducts(searchTerm);
    });
    
    document.getElementById('search-input').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            const searchTerm = this.value;
            searchProducts(searchTerm);
        }
    });
});

function loadProducts() {
    fetch('/hoangduyminh/api/product', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(products => {
        document.getElementById('loading').style.display = 'none';
        
        if (products.length === 0) {
            document.getElementById('no-products').style.display = 'block';
        } else {
            displayProducts(products);
        }
    })
    .catch(error => {
        console.error('Error loading products:', error);
        document.getElementById('loading').style.display = 'none';
        document.getElementById('products-container').innerHTML = `
            <div class="col-12">
                <div class="alert alert-danger">
                    Có lỗi xảy ra khi tải sản phẩm. Vui lòng thử lại sau.
                </div>
            </div>
        `;
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
    col.className = 'col-md-4 mb-4';
    
    const stockBadge = (product.SoLuong || 0) < 10 
        ? `<span class="badge badge-pill badge-danger position-absolute" style="top: 10px; right: 10px;">
            ${(product.SoLuong || 0) > 0 ? 'Sắp hết hàng' : 'Hết hàng'}
           </span>`
        : '';
    
    const imageHtml = product.image 
        ? `<img src="/hoangduyminh/${product.image}" class="card-img-top product-image" alt="${escapeHtml(product.name)}">`
        : `<img src="/hoangduyminh/public/images/default-product.jpg" class="card-img-top product-image" alt="Default Product Image">`;
    
    const addToCartButton = (product.SoLuong || 0) > 0 
        ? `<a href="/hoangduyminh/Product/addToCart/${product.id}" class="btn btn-success btn-sm">➕ Thêm vào giỏ</a>`
        : `<button class="btn btn-secondary btn-sm" disabled>Hết hàng</button>`;
    
    col.innerHTML = `
        <div class="card h-100 product-card">
            <div class="position-relative">
                ${imageHtml}
                ${stockBadge}
            </div>
            <div class="card-body d-flex flex-column">
                <h5 class="card-title">
                    <a href="/hoangduyminh/Product/show/${product.id}" class="text-decoration-none text-dark product-name">
                        ${escapeHtml(product.name)}
                    </a>
                </h5>
                <p class="card-text flex-grow-1">
                    ${escapeHtml(product.description)}
                </p>
                <p class="text-danger font-weight-bold h5">
                    💰 ${formatPrice(product.price)} VND
                </p>
                <p class="text-muted small mb-2">
                    <strong>Danh mục:</strong> ${product.category_name || 'Chưa có danh mục'}
                </p>
                <p class="text-muted small mb-3">
                    <strong>Còn lại:</strong> ${product.SoLuong || 0} sản phẩm
                </p>
                <div class="mt-auto">
                    ${addToCartButton}
                    <a href="/hoangduyminh/Product/show/${product.id}" class="btn btn-outline-primary btn-sm">Xem chi tiết</a>
                </div>
            </div>
        </div>
    `;
    
    return col;
}

function searchProducts(searchTerm) {
    if (!searchTerm.trim()) {
        loadProducts();
        return;
    }
    
    document.getElementById('loading').style.display = 'block';
    document.getElementById('products-container').innerHTML = '';
    document.getElementById('no-products').style.display = 'none';
    
    fetch('/hoangduyminh/api/product', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(products => {
        // Filter products by search term
        const filteredProducts = products.filter(product => 
            product.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
            product.description.toLowerCase().includes(searchTerm.toLowerCase()) ||
            (product.category_name && product.category_name.toLowerCase().includes(searchTerm.toLowerCase()))
        );
        
        document.getElementById('loading').style.display = 'none';
        displayProducts(filteredProducts);
    })
    .catch(error => {
        console.error('Error searching products:', error);
        document.getElementById('loading').style.display = 'none';
    });
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

<style>
.product-image {
    height: 200px;
    object-fit: cover;
}

.product-card {
    transition: transform 0.2s;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.product-name:hover {
    color: #007bff !important;
}
</style>

<?php include 'app/views/shares/footer.php'; ?>