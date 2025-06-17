<!-- filepath: app/views/account/profile.php -->
<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Thông tin tài khoản</h4>
                </div>
                <div class="card-body text-center">
                    <?php
                    // Xử lý đường dẫn avatar
                    $avatarPath = !empty($account->avatar)
                        ? (strpos($account->avatar, 'http') === 0 ? $account->avatar : BASE_URL . $account->avatar)
                        : BASE_URL . DEFAULT_AVATAR;
                    ?>
                    <img src="<?php echo $avatarPath; ?>" alt="Avatar"
                        class="img-fluid rounded-circle mb-3 profile-avatar"
                        style="width: 150px; height: 150px; object-fit: cover;"
                        onerror="this.src='<?php echo BASE_URL . DEFAULT_AVATAR; ?>'">

                    <h5 class="card-title"><?php echo htmlspecialchars($account->fullname); ?></h5>
                    <p class="card-text text-muted"><?php echo htmlspecialchars($account->username); ?></p>

                    <div class="badge bg-<?php echo $account->role === 'admin' ? 'danger' : 'success'; ?> mb-3">
                        <?php echo ucfirst($account->role); ?>
                    </div>

                    <div class="d-grid gap-2">
                        <a href="<?php echo BASE_URL; ?>account/updatePassword" class="btn btn-outline-primary">
                            <i class="bi bi-key"></i> Đổi mật khẩu
                        </a>
                    </div>

                    <?php if (!empty($account->email) || !empty($account->phone)): ?>
                        <div class="mt-4 text-start">
                            <?php if (!empty($account->email)): ?>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="bi bi-envelope-fill text-primary me-2"></i>
                                    <span><?php echo htmlspecialchars($account->email); ?></span>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($account->phone)): ?>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-telephone-fill text-primary me-2"></i>
                                    <span><?php echo htmlspecialchars($account->phone); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Thông tin tài khoản bổ sung -->
            <div class="card shadow">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Thông tin bổ sung</h5>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <small class="text-muted">ID tài khoản:</small>
                        <p class="mb-1">#<?php echo $account->id; ?></p>
                    </div>
                    <div class="mb-2">
                        <small class="text-muted">Vai trò:</small>
                        <p class="mb-1"><?php echo ucfirst($account->role); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Cập nhật thông tin cá nhân</h4>
                </div>
                <div class="card-body">
                    <?php if (isset($success)): ?>
                        <div class="alert alert-success">
                            <?php echo $success; ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger">
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo BASE_URL; ?>account/updateProfile" method="POST"
                        enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="username" class="form-label">Tên đăng nhập</label>
                            <input type="text" class="form-control" id="username"
                                value="<?php echo htmlspecialchars($account->username); ?>" readonly>
                            <small class="text-muted">Tên đăng nhập không thể thay đổi.</small>
                        </div>

                        <div class="mb-3">
                            <label for="fullname" class="form-label">Họ và tên</label>
                            <input type="text" class="form-control" id="fullname" name="fullname"
                                value="<?php echo htmlspecialchars($account->fullname); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                value="<?php echo htmlspecialchars($account->email ?? ''); ?>">
                            <small class="text-muted">Địa chỉ email hợp lệ (không bắt buộc).</small>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Số điện thoại</label>
                            <input type="tel" class="form-control" id="phone" name="phone"
                                value="<?php echo htmlspecialchars($account->phone ?? ''); ?>" pattern="[0-9]{10,11}">
                            <small class="text-muted">Số điện thoại 10-11 số (không bắt buộc).</small>
                        </div>

                        <div class="mb-4">
                            <label for="avatar" class="form-label">Ảnh đại diện mới</label>
                            <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*">
                            <small class="text-muted">Chấp nhận định dạng: JPG, PNG, GIF (tối đa 2MB)</small>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="<?php echo BASE_URL; ?>product" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Quay lại
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Lưu thay đổi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .profile-avatar {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: 5px solid #fff;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .profile-avatar:hover {
        transform: scale(1.05);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .card {
        border-radius: 0.75rem;
        overflow: hidden;
    }

    .card-header {
        border-bottom: none;
    }

    .badge {
        padding: 0.5em 1em;
        font-size: 0.9em;
    }
</style>

<?php include 'app/views/shares/footer.php'; ?>