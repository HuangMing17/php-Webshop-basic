<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Bán Điện Thoại</title>
    <!-- Bootstrap và các thư viện JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <style>
        .cart-badge {
            position: relative;
            top: -8px;
            right: -8px;
            background-color: #dc3545;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 12px;
            font-weight: bold;
            min-width: 18px;
            text-align: center;
        }

        .navbar-brand img {
            height: 40px;
        }

        .search-bar input {
            border-radius: 20px;
        }

        .search-btn {
            border-radius: 20px;
        }

        .avatar-small {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 5px;
            border: 2px solid #fff;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .dropdown-menu {
            min-width: 200px;
            border-radius: 0.5rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            border: none;
        }

        .dropdown-item {
            padding: 8px 15px;
            border-radius: 0.3rem;
            transition: background-color 0.2s ease;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
        }

        .dropdown-item i {
            width: 20px;
            text-align: center;
            margin-right: 8px;
        }

        .dropdown-divider {
            margin: 5px 0;
        }

        .navbar {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .nav-link {
            padding: 0.5rem 1rem;
            transition: color 0.2s;
        }

        .nav-link:hover {
            color: #007bff !important;
        }
    </style>
</head>

<body>
    <?php
    // Start session if not already started
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    // Calculate cart item count
    $cartCount = 0;
    if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $cartCount += $item['quantity'];
        }
    }
    ?>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="<?php echo BASE_URL; ?>Product/">
                <span>PhoneStore</span>
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>Product/">Trang chủ</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>Product/add">Thêm sản phẩm</a>
                    </li>

                    <?php if (SessionHelper::isLoggedIn() && isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>account/list">
                                <span class="text-danger">Quản lý tài khoản</span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if (SessionHelper::isLoggedIn() && isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>Product/index">
                                <span class="text-danger">Quản lý sản phẩm</span>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>

                <ul class="navbar-nav">
                    <?php if (SessionHelper::isLoggedIn()): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <?php
                                $avatarPath = !empty($_SESSION['avatar'])
                                    ? (strpos($_SESSION['avatar'], 'http') === 0 ? $_SESSION['avatar'] : BASE_URL . $_SESSION['avatar'])
                                    : BASE_URL . DEFAULT_AVATAR;
                                ?>
                                <img src="<?php echo $avatarPath; ?>" alt="Avatar" class="avatar-small"
                                    onerror="this.src='<?php echo BASE_URL . DEFAULT_AVATAR; ?>'">
                                <?php echo $_SESSION['username']; ?>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="<?php echo BASE_URL; ?>account/profile">
                                    <i class="bi bi-person"></i> Hồ sơ cá nhân
                                </a>
                                <a class="dropdown-item" href="<?php echo BASE_URL; ?>account/updatePassword">
                                    <i class="bi bi-key"></i> Đổi mật khẩu
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="<?php echo BASE_URL; ?>account/logout">
                                    <i class="bi bi-box-arrow-right"></i> Đăng xuất
                                </a>
                            </div>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>account/login">
                                <i class="bi bi-box-arrow-in-right"></i> Đăng nhập
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_URL; ?>account/register">
                                <i class="bi bi-person-plus"></i> Đăng ký
                            </a>
                        </li>
                    <?php endif; ?>

                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_URL; ?>Product/cart">
                            <i class="bi bi-cart3"></i> Giỏ hàng
                            <?php if ($cartCount > 0): ?>
                                <span class="cart-badge"><?php echo $cartCount; ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Content container is opened here, will be closed in the footer -->
    <div class="container mt-4">
        <!-- Content goes here -->