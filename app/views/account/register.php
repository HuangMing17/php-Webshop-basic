<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0 text-center">Đăng ký tài khoản</h3>
                </div>

                <?php if (isset($errors) && count($errors) > 0): ?>
                    <div class="alert alert-danger m-3">
                        <ul class="mb-0">
                            <?php foreach ($errors as $key => $err): ?>
                                <li><?php echo $err; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <div class="card-body p-4">
                    <form class="user" action="/hoangduyminh/account/save" method="post" enctype="multipart/form-data">
                        <div class="form-group row">
                            <div class="col-sm-6 mb-3 mb-sm-0">
                                <label for="username">Tên đăng nhập <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="username" name="username"
                                    value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"
                                    required>
                            </div>
                            <div class="col-sm-6">
                                <label for="fullname">Họ và tên <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="fullname" name="fullname"
                                    value="<?php echo isset($_POST['fullname']) ? htmlspecialchars($_POST['fullname']) : ''; ?>"
                                    required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-6 mb-3 mb-sm-0">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                                <small class="form-text text-muted">Địa chỉ email hợp lệ (không bắt buộc)</small>
                            </div>
                            <div class="col-sm-6">
                                <label for="phone">Số điện thoại</label>
                                <input type="tel" class="form-control" id="phone" name="phone"
                                    value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>"
                                    pattern="[0-9]{10,11}">
                                <small class="form-text text-muted">Số điện thoại 10-11 số (không bắt buộc)</small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-6 mb-3 mb-sm-0">
                                <label for="password">Mật khẩu <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="col-sm-6">
                                <label for="confirmpassword">Xác nhận mật khẩu <span
                                        class="text-danger">*</span></label>
                                <input type="password" class="form-control" id="confirmpassword" name="confirmpassword"
                                    required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="avatar">Ảnh đại diện</label>
                            <input type="file" class="form-control-file" id="avatar" name="avatar" accept="image/*">
                            <small class="form-text text-muted">Chấp nhận định dạng: JPG, PNG, GIF (tối đa 2MB)</small>
                        </div>

                        <input type="hidden" name="role" value="user">

                        <div class="form-group text-center mt-4">
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                Đăng ký
                            </button>
                        </div>
                    </form>
                </div>

                <div class="card-footer text-center">
                    <p class="mb-0">Đã có tài khoản? <a href="/hoangduyminh/account/login">Đăng nhập ngay</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>