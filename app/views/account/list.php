<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h2 class="mb-0">Quản lý tài khoản</h2>
        </div>

        <div class="card-body">
            <div class="d-flex justify-content-between mb-3">
                <a href="<?php echo BASE_URL; ?>product" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Quay lại
                </a>
                <a href="<?php echo BASE_URL; ?>account/register" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Thêm tài khoản mới
                </a>
            </div>

            <?php if (empty($accounts)): ?>
                <div class="alert alert-info">Chưa có tài khoản nào.</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th width="5%">ID</th>
                                <th width="15%">Avatar</th>
                                <th width="15%">Tên đăng nhập</th>
                                <th width="15%">Họ tên</th>
                                <th width="15%">Email</th>
                                <th width="10%">Điện thoại</th>
                                <th width="10%">Vai trò</th>
                                <th width="15%">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($accounts as $account): ?>
                                <tr>
                                    <td><?php echo $account->id; ?></td>
                                    <td class="text-center">
                                        <?php
                                        // Xử lý đường dẫn avatar
                                        $avatarPath = !empty($account->avatar)
                                            ? (strpos($account->avatar, 'http') === 0 ? $account->avatar : BASE_URL . $account->avatar)
                                            : BASE_URL . DEFAULT_AVATAR;
                                        ?>
                                        <img src="<?php echo $avatarPath; ?>"
                                            alt="Avatar của <?php echo htmlspecialchars($account->username); ?>"
                                            class="img-thumbnail avatar-thumbnail"
                                            style="width: 50px; height: 50px; object-fit: cover; cursor: pointer;"
                                            onerror="this.src='<?php echo BASE_URL . DEFAULT_AVATAR; ?>'"
                                            title="<?php echo htmlspecialchars($account->username); ?>" data-toggle="modal"
                                            data-target="#avatarModal"
                                            onclick="showAvatarModal(this.src, '<?php echo htmlspecialchars($account->username); ?>')">
                                    </td>
                                    <td><?php echo htmlspecialchars($account->username); ?></td>
                                    <td><?php echo htmlspecialchars($account->fullname); ?></td>
                                    <td><?php echo $account->email ? htmlspecialchars($account->email) : '<span class="text-muted">Chưa cập nhật</span>'; ?>
                                    </td>
                                    <td><?php echo $account->phone ? htmlspecialchars($account->phone) : '<span class="text-muted">Chưa cập nhật</span>'; ?>
                                    </td>
                                    <td>
                                        <span
                                            class="badge <?php echo $account->role === 'admin' ? 'bg-danger' : 'bg-success'; ?>">
                                            <?php echo $account->role; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="<?php echo BASE_URL; ?>account/edit/<?php echo $account->id; ?>"
                                                class="btn btn-sm btn-warning">
                                                <i class="bi bi-pencil-square"></i> Sửa
                                            </a>
                                            <a href="#" onclick="confirmDelete(<?php echo $account->id; ?>)"
                                                class="btn btn-sm btn-danger">
                                                <i class="bi bi-trash"></i> Xóa
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between mt-3">
                    <p class="text-muted">Tổng số tài khoản: <?php echo count($accounts); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal để hiển thị ảnh avatar kích thước lớn -->
<div class="modal fade" id="avatarModal" tabindex="-1" role="dialog" aria-labelledby="avatarModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="avatarModalLabel">Ảnh đại diện</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img src="" id="modalAvatarImg" class="img-fluid" alt="Avatar">
            </div>
        </div>
    </div>
</div>

<style>
    .avatar-thumbnail {
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .avatar-thumbnail:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        z-index: 1;
    }
</style>

<script>
    function confirmDelete(id) {
        if (confirm('Bạn có chắc chắn muốn xóa tài khoản này? Hành động này không thể hoàn tác.')) {
            window.location.href = '<?php echo BASE_URL; ?>account/delete/' + id;
        }
    }

    function showAvatarModal(src, username) {
        document.getElementById('modalAvatarImg').src = src;
        document.getElementById('avatarModalLabel').textContent = 'Ảnh đại diện của ' + username;
    }
</script>

<?php include 'app/views/shares/footer.php'; ?>