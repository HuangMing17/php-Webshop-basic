<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h2 class="mb-0">Quản lý tài khoản</h2>
        </div>

        <div class="card-body">
            <div class="d-flex justify-content-between mb-3">
                <a href="/hoangduyminh/Product/" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
                <a href="/hoangduyminh/account/register" class="btn btn-success">
                    <i class="fas fa-plus"></i> Thêm tài khoản mới
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
                                        // Debug: In ra giá trị avatar để kiểm tra
                                        // echo "<!-- Avatar DB: " . htmlspecialchars($account->avatar ?? 'NULL') . " -->";
                                        
                                        // Xử lý đường dẫn avatar an toàn
                                        $defaultAvatar = '/hoangduyminh/images/default-avatar.png'; // Đổi path cho phù hợp
                                        
                                        if (!empty($account->avatar)) {
                                            $avatar = trim($account->avatar);
                                            
                                            // Case 1: Đã là URL đầy đủ (http/https)
                                            if (preg_match('/^https?:\/\//', $avatar)) {
                                                $avatarPath = $avatar;
                                            }
                                            // Case 2: Đã có prefix /hoangduyminh/
                                            elseif (strpos($avatar, '/hoangduyminh/') === 0) {
                                                $avatarPath = $avatar;
                                            }
                                            // Case 3: Có prefix hoangduyminh/ (không có /)
                                            elseif (strpos($avatar, 'hoangduyminh/') === 0) {
                                                $avatarPath = '/' . $avatar;
                                            }
                                            // Case 4: Relative path từ public
                                            elseif (strpos($avatar, 'public/') === 0) {
                                                $avatarPath = '/hoangduyminh/' . $avatar;
                                            }
                                            // Case 5: Relative path từ uploads
                                            elseif (strpos($avatar, 'uploads/') === 0) {
                                                $avatarPath = '/hoangduyminh/public/' . $avatar;
                                            }
                                            // Case 6: Chỉ là filename hoặc path khác
                                            else {
                                                $avatarPath = '/hoangduyminh/public/uploads/avatars/' . basename($avatar);
                                            }
                                        } else {
                                            $avatarPath = $defaultAvatar;
                                        }
                                        
                                        // Clean up double slashes nếu có
                                        $avatarPath = preg_replace('#/+#', '/', $avatarPath);
                                        ?>
                                        <img src="<?php echo $avatarPath; ?>"
                                            alt="Avatar của <?php echo htmlspecialchars($account->username); ?>"
                                            class="img-thumbnail avatar-thumbnail"
                                            style="width: 50px; height: 50px; object-fit: cover; cursor: pointer;"
                                            onerror="this.src='/hoangduyminh/images/default-avatar.png'"
                                            title="<?php echo htmlspecialchars($account->username); ?>"
                                            data-bs-toggle="modal"
                                            data-bs-target="#avatarModal"
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
                                            <a href="/hoangduyminh/account/edit/<?php echo $account->id; ?>"
                                                class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i> Sửa
                                            </a>
                                            <a href="#" onclick="confirmDelete(<?php echo $account->id; ?>)"
                                                class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i> Xóa
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
<div class="modal fade" id="avatarModal" tabindex="-1" aria-labelledby="avatarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="avatarModalLabel">Ảnh đại diện</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img src="" id="modalAvatarImg" class="img-fluid rounded" alt="Avatar" style="max-height: 400px;">
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
            window.location.href = '/hoangduyminh/account/delete/' + id;
        }
    }

    function showAvatarModal(src, username) {
        document.getElementById('modalAvatarImg').src = src;
        document.getElementById('avatarModalLabel').textContent = 'Ảnh đại diện của ' + username;
        
        // Show modal using Bootstrap 5
        const modal = new bootstrap.Modal(document.getElementById('avatarModal'));
        modal.show();
    }
</script>

<?php include 'app/views/shares/footer.php'; ?>