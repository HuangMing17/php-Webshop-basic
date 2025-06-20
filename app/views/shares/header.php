<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechPhone Store - Cửa hàng điện thoại uy tín</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body>
    <!-- Top Bar -->
    <div class="bg-dark text-white py-2">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <small><i class="fas fa-phone"></i> Hotline: 1900-123-456 | <i class="fas fa-envelope"></i> info@techphone.vn</small>
                </div>
                <div class="col-md-6 text-end">
                    <small>Miễn phí vận chuyển toàn quốc với đơn hàng > 500k</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="/hoangduyminh/Product/home">
                <i class="fas fa-mobile-alt me-2"></i>TechPhone Store
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/hoangduyminh/Product/home">
                            <i class="fas fa-home me-1"></i>Trang chủ
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-mobile-alt me-1"></i>Sản phẩm
                        </a>
                        <ul class="dropdown-menu" id="category-dropdown">
                            <li><a class="dropdown-item" href="/hoangduyminh/Product/home">Tất cả sản phẩm</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <!-- Categories will be loaded here -->
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/hoangduyminh/Cart/cart">
                            <i class="fas fa-shopping-cart me-1"></i>Giỏ hàng
                            <span class="badge bg-danger ms-1" id="cart-count">0</span>
                        </a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <!-- Admin Menu - Hidden by default -->
                    <li class="nav-item dropdown" id="admin-menu" style="display: none;">
                        <a class="nav-link dropdown-toggle text-danger" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-cog me-1"></i>Quản trị
                        </a>
                        <ul class="dropdown-menu">
                            <li><h6 class="dropdown-header text-primary">
                                <i class="fas fa-box me-2"></i>Quản lý sản phẩm
                          
                            </a></li>
                            <li><a class="dropdown-item" href="/hoangduyminh/Product/">
                                <i class="fas fa-list me-2"></i>Danh sách sản phẩm
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            
                            <li><h6 class="dropdown-header text-success">
                                <i class="fas fa-tags me-2"></i>Quản lý danh mục
                            </h6></li>
                            
                            </a></li>
                            <li><a class="dropdown-item" href="/hoangduyminh/Category/list">
                                <i class="fas fa-folder-open me-2"></i>Danh sách danh mục
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            
                            <li><h6 class="dropdown-header text-warning">
                                <i class="fas fa-users me-2"></i>Quản lý tài khoản
                            </h6></li>
                            <li><a class="dropdown-item" href="/hoangduyminh/account/list">
                                <i class="fas fa-users-cog me-2"></i>Danh sách tài khoản
                            </a></li>
                            
                        </ul>
                    </li>
                    
                    <li class="nav-item" id="nav-login">
                        <a class="nav-link btn btn-outline-primary ms-2" href="/hoangduyminh/account/login">
                            <i class="fas fa-sign-in-alt me-1"></i>Đăng nhập
                        </a>
                    </li>
                    <li class="nav-item dropdown" id="nav-user" style="display: none;">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user me-1"></i><span id="username-display"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="/hoangduyminh/account/profile">
                                <i class="fas fa-user me-2"></i>Thông tin cá nhân
                            </a></li>
                            <li><a class="dropdown-item" href="/hoangduyminh/account/edit">
                                <i class="fas fa-edit me-2"></i>Chỉnh sửa tài khoản
                            </a></li>
                            <li><a class="dropdown-item" href="#">
                                <i class="fas fa-history me-2"></i>Lịch sử mua hàng
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#" onclick="logout()">
                                <i class="fas fa-sign-out-alt me-2"></i>Đăng xuất
                            </a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function logout() {
            localStorage.removeItem('jwtToken');
            sessionStorage.removeItem('userRole');
            sessionStorage.removeItem('username');
            sessionStorage.removeItem('fullname');
            sessionStorage.removeItem('userId');
            sessionStorage.removeItem('cart');
            location.href = '/hoangduyminh/account/login';
        }

        function checkUserRole() {
            const token = localStorage.getItem('jwtToken');
            const userRole = sessionStorage.getItem('userRole');
            const username = sessionStorage.getItem('username');
            const fullname = sessionStorage.getItem('fullname');
            
            if (token && username) {
                // User is logged in
                document.getElementById('nav-login').style.display = 'none';
                document.getElementById('nav-user').style.display = 'block';
                
                // Display fullname if available, otherwise username
                const displayName = fullname && fullname.trim() ? fullname : username;
                document.getElementById('username-display').textContent = displayName;
                
                // Show admin menu if user is admin
                if (userRole === 'admin') {
                    document.getElementById('admin-menu').style.display = 'block';
                } else {
                    document.getElementById('admin-menu').style.display = 'none';
                }
            } else {
                // User is not logged in
                document.getElementById('nav-login').style.display = 'block';
                document.getElementById('nav-user').style.display = 'none';
                document.getElementById('admin-menu').style.display = 'none';
            }
        }

        function loadCategories() {
            fetch('/hoangduyminh/api/category')
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
                .then(categories => {
                    console.log('Header categories loaded:', categories); // Debug log
                    const dropdown = document.getElementById('category-dropdown');
                    categories.forEach(category => {
                        const li = document.createElement('li');
                        li.innerHTML = `<a class="dropdown-item" href="/hoangduyminh/Product/home?category=${category.id}">
                            <i class="fas fa-mobile-alt me-2"></i>${category.name}
                        </a>`;
                        dropdown.appendChild(li);
                    });
                })
                .catch(error => console.error('Error loading categories:', error));
        }

        function updateCartCount() {
            // Update cart count from session storage or API
            const cart = JSON.parse(sessionStorage.getItem('cart') || '{}');
            const count = Object.values(cart).reduce((total, item) => total + (item.quantity || 0), 0);
            document.getElementById('cart-count').textContent = count;
        }

        document.addEventListener("DOMContentLoaded", function() {
            checkUserRole();
            loadCategories();
            updateCartCount();
        });
    </script>
