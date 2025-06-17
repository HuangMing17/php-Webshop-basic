<?php include 'app/views/shares/header.php'; ?>
<h1>Thanh toán</h1>
<form method="POST" action="/hoangduyminh/Product/processCheckout">
    <div class="form-group">
        <label for="name">Họ tên:</label>
        <input type="text" id="name" name="name" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="phone">Số điện thoại:</label>
        <input type="text" id="phone" name="phone" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="address">Địa chỉ:</label>
        <textarea id="address" name="address" class="form-control" required></textarea>
    </div>
    <div class="form-group">
        <label for="email">Gmail:</label>
        <input type="email" id="email" name="email" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="note">Ghi chú:</label>
        <textarea id="note" name="note" class="form-control"></textarea>|
    </div>
    <button type="submit" class="btn btn-primary">Thanh toán</button>
</form>
<a href="/hoangduyminh/Product/cart" class="btn btn-secondary mt-2">Quay lại giỏ hàng</a>
<?php include 'app/views/shares/footer.php'; ?>