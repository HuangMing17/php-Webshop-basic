<?php include 'app/views/shares/header.php'; ?>
<div class="container mt-4">
    <h1>Giỏ hàng của bạn</h1>
    
    <?php if (!empty($cart)): ?>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Hình ảnh</th>
                        <th>Tên sản phẩm</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Thành tiền</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $total = 0;
                    foreach ($cart as $id => $item): 
                        $subtotal = $item['price'] * $item['quantity'];
                        $total += $subtotal;
                    ?>
                        <tr>
                            <td>
                                <?php if ($item['image']): ?>
                                    <img src="/hoangduyminh/<?php echo htmlspecialchars($item['image'], ENT_QUOTES, 'UTF-8'); ?>" 
                                         alt="<?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?>" 
                                         style="width: 80px; height: 80px; object-fit: cover;">
                                <?php else: ?>
                                    <img src="/hoangduyminh/images/no-image.png" 
                                         alt="Không có ảnh" 
                                         style="width: 80px; height: 80px; object-fit: cover;">
                                <?php endif; ?>
                            </td>
                            <td>
                                <h5><?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?></h5>
                            </td>
                            <td>
                                <span class="text-danger font-weight-bold">
                                    <?php echo number_format($item['price'], 0, ',', '.'); ?> VND
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <!-- Decrease quantity button -->
                                    <form method="POST" action="/hoangduyminh/Product/updateCart" class="d-inline me-1">
                                        <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                                        <input type="hidden" name="quantity" value="<?php echo max(1, $item['quantity'] - 1); ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-secondary" 
                                                <?php echo $item['quantity'] <= 1 ? 'disabled' : ''; ?>>
                                            -
                                        </button>
                                    </form>
                                    
                                    <!-- Quantity display/input -->
                                    <form method="POST" action="/hoangduyminh/Product/updateCart" class="d-inline mx-1">
                                        <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                                        <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" 
                                               min="1" max="999" class="form-control text-center" 
                                               style="width: 60px;" onchange="this.form.submit()">
                                    </form>
                                    
                                    <!-- Increase quantity button -->
                                    <form method="POST" action="/hoangduyminh/Product/updateCart" class="d-inline ms-1">
                                        <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                                        <input type="hidden" name="quantity" value="<?php echo $item['quantity'] + 1; ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-secondary">+</button>
                                    </form>
                                </div>
                            </td>
                            <td>
                                <span class="text-success font-weight-bold">
                                    <?php echo number_format($subtotal, 0, ',', '.'); ?> VND
                                </span>
                            </td>
                            <td>
                                <a href="/hoangduyminh/Product/removeFromCart/<?php echo $id; ?>" 
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?');">
                                   🗑️ Xóa
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr class="table-warning">
                        <td colspan="4"><strong>Tổng cộng:</strong></td>
                        <td><strong class="text-danger h5"><?php echo number_format($total, 0, ',', '.'); ?> VND</strong></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        
        <div class="row mt-4">
            <div class="col-md-6">
                <a href="/hoangduyminh/Product" class="btn btn-secondary btn-lg">
                    ← Tiếp tục mua sắm
                </a>
            </div>
            <div class="col-md-6 text-end">
                <a href="/hoangduyminh/Product/checkout" class="btn btn-success btn-lg">
                    Thanh toán →
                </a>
            </div>
        </div>
        
    <?php else: ?>
        <div class="alert alert-info text-center">
            <h4>Giỏ hàng trống</h4>
            <p>Bạn chưa có sản phẩm nào trong giỏ hàng.</p>
            <a href="/hoangduyminh/Product" class="btn btn-primary btn-lg">
                Bắt đầu mua sắm
            </a>
        </div>
    <?php endif; ?>
</div>
<?php include 'app/views/shares/footer.php'; ?>