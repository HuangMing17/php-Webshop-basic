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

    <?php if (empty($products)): ?>
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> Hiện chưa có sản phẩm nào.
        </div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($products as $product): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 product-card">
                        <div class="position-relative">
                            <?php if ($product->image): ?>
                                <img src="<?php echo BASE_URL . $product->image; ?>" class="card-img-top product-image"
                                    alt="<?php echo htmlspecialchars($product->name); ?>">
                            <?php else: ?>
                                <img src="<?php echo BASE_URL; ?>public/images/default-product.jpg"
                                    class="card-img-top product-image" alt="Default Product Image">
                            <?php endif; ?>

                            <?php if (($product->SoLuong ?? 0) < 10): ?>
                                <span class="badge badge-pill badge-danger position-absolute" style="top: 10px; right: 10px;">
                                    <?php echo ($product->SoLuong ?? 0) > 0 ? 'Sắp hết hàng' : 'Hết hàng'; ?>
                                </span>
                            <?php endif; ?>
                        </div>

                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">
                                <a href="<?php echo BASE_URL; ?>Product/show/<?php echo $product->id; ?>"
                                    class="text-decoration-none text-dark product-name">
                                    <?php echo htmlspecialchars($product->name); ?>
                                </a>
                            </h5>

                            <p class="card-text text-muted small">
                                <?php echo htmlspecialchars(substr($product->description, 0, 100)) . (strlen($product->description) > 100 ? '...' : ''); ?>
                            </p>

                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span
                                        class="font-weight-bold text-danger"><?php echo number_format($product->price, 0, ',', '.'); ?>
                                        ₫</span>
                                    <span
                                        class="badge <?php echo ($product->SoLuong ?? 0) > 0 ? 'badge-success' : 'badge-secondary'; ?>">
                                        <?php echo ($product->SoLuong ?? 0) > 0 ? 'Còn hàng' : 'Hết hàng'; ?>
                                    </span>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <a href="<?php echo BASE_URL; ?>Product/show/<?php echo $product->id; ?>"
                                        class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-eye"></i> Chi tiết
                                    </a>

                                    <?php if (($product->SoLuong ?? 0) > 0): ?>
                                        <a href="<?php echo BASE_URL; ?>Product/addToCart/<?php echo $product->id; ?>"
                                            class="btn btn-sm btn-primary">
                                            <i class="bi bi-cart-plus"></i> Thêm vào giỏ
                                        </a>
                                    <?php else: ?>
                                        <button class="btn btn-sm btn-secondary" disabled>
                                            <i class="bi bi-cart-x"></i> Hết hàng
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
    .product-card {
        transition: transform 0.3s, box-shadow 0.3s;
        border-radius: 0.5rem;
        overflow: hidden;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .product-image {
        height: 200px;
        object-fit: cover;
    }

    .product-name {
        height: 48px;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    .badge-pill {
        padding: 0.4em 0.8em;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Tìm kiếm sản phẩm
        const searchInput = document.getElementById('search-input');
        const searchButton = document.getElementById('search-button');
        const productCards = document.querySelectorAll('.product-card');

        function searchProducts() {
            const searchText = searchInput.value.toLowerCase().trim();

            productCards.forEach(card => {
                const productName = card.querySelector('.product-name').textContent.toLowerCase();
                if (productName.includes(searchText)) {
                    card.parentElement.style.display = '';
                } else {
                    card.parentElement.style.display = 'none';
                }
            });
        }

        searchButton.addEventListener('click', searchProducts);
        searchInput.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                searchProducts();
            }
        });
    });
</script>

<?php include 'app/views/shares/footer.php'; ?>