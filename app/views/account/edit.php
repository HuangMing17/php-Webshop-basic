<!-- filepath: app/views/account/edit.php -->
<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-4">
    <h1>Chỉnh sửa tài khoản</h1>

    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>account/list">Danh sách tài khoản</a></li>
            <li class="breadcrumb-item active" aria-current="page">Chỉnh sửa</li>
        </ol>
    </nav>

    <?php if (!$account): ?>
        <div class="alert alert-danger">Không tìm thấy tài khoản!</div>
        <a href="<?php echo BASE_URL; ?>account/list" class="btn btn-primary">Quay lại</a>
    <?php else: ?>
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Thông tin tài khoản</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 text-center mb-4">
                        <?php
                        // Xử lý đường dẫn avatar
                        $avatarPath = !empty($account->avatar)
                            ? (strpos($account->avatar, 'http') === 0 ? $account->avatar : BASE_URL . $account->avatar)
                            : BASE_URL . DEFAULT_AVATAR;
                        ?>
                        <img src="<?php echo $avatarPath; ?>" alt="Avatar" class="img-fluid rounded-circle mb-3"
                            style="width: 150px; height: 150px; object-fit: cover;"
                            onerror="this.src='<?php echo BASE_URL . DEFAULT_AVATAR; ?>'">
                        <p class="text-muted">ID: <?php echo $account->id; ?></p>
                    </div>
                    <div class="col-md-9">
                        <form action="<?php echo BASE_URL; ?>account/update/<?php echo $account->id; ?>" method="POST"
                            enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="username" class="form-label">Tên đăng nhập</label>
                                <input type="text" class="form-control" id="username" name="username"
                                    value="<?php echo htmlspecialchars($account->username); ?>" required>
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
                                <small class="text-muted">Địa chỉ email hợp lệ (không bắt buộc)</small>
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">Số điện thoại</label>
                                <input type="tel" class="form-control" id="phone" name="phone"
                                    value="<?php echo htmlspecialchars($account->phone ?? ''); ?>" pattern="[0-9]{10,11}">
                                <small class="text-muted">Số điện thoại 10-11 số (không bắt buộc)</small>
                            </div>

                            <div class="mb-3">
                                <label for="role" class="form-label">Vai trò</label>
                                <select class="form-select" id="role" name="role">
                                    <option value="user" <?php echo $account->role === 'user' ? 'selected' : ''; ?>>User
                                    </option>
                                    <option value="admin" <?php echo $account->role === 'admin' ? 'selected' : ''; ?>>Admin
                                    </option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label for="avatar" class="form-label">Ảnh đại diện mới</label>
                                <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*">
                                <small class="text-muted">Chấp nhận định dạng: JPG, PNG, GIF (tối đa 2MB)</small>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="<?php echo BASE_URL; ?>account/list" class="btn btn-secondary">Hủy</a>
                                <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
    /* Thêm hiệu ứng cho avatar */
    .rounded-circle {
        transition: transform 0.3s ease;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .rounded-circle:hover {
        transform: scale(1.05);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }
</style>

<?php include 'app/views/shares/footer.php'; ?>