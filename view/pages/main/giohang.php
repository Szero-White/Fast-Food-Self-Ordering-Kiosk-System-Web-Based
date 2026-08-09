<?php
$tongtien = 0;
if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $tongtien += (float)$item['gia'] * (int)$item['soluong'];
    }
}
?>

<div class="cart-container">
    <div class="cart-header">
        <h1><i class="fas fa-shopping-cart"></i> Giỏ hàng của bạn</h1>
    </div>

    <?php if (!empty($_SESSION['cart'])) { ?>
        <?php foreach ($_SESSION['cart'] as $item) {
            $idSanPham = (int)$item['id'];
            $tenSanPham = htmlspecialchars($item['ten'], ENT_QUOTES, 'UTF-8');
            $hinhAnh = htmlspecialchars(upload_url($item['hinhanh']), ENT_QUOTES, 'UTF-8');
            $giaSanPham = (float)$item['gia'];
            $soLuong = (int)$item['soluong'];
        ?>
            <div class="cart-item">
                <img src="<?php echo $hinhAnh; ?>" alt="<?php echo $tenSanPham; ?>">
                <div class="cart-item-info">
                    <div class="cart-item-name"><?php echo $tenSanPham; ?></div>
                    <div class="cart-item-price"><?php echo number_format($giaSanPham, 0, ',', '.'); ?>đ</div>
                </div>

                <form method="POST" class="quantity-control">
                    <input type="hidden" name="id" value="<?php echo $idSanPham; ?>">
                    <input type="number" name="soluong" value="<?php echo $soLuong; ?>" min="0" max="10">
                    <button type="submit" name="capnhat" class="btn-update">Cập nhật</button>
                </form>

                <a href="index.php?quanly=giohang&xoa=<?php echo $idSanPham; ?>" class="btn-delete">
                    <i class="fas fa-trash"></i>
                    Xóa
                </a>
            </div>
        <?php } ?>

        <div class="cart-total">
            <p>Tổng tiền:</p>
            <div class="total-amount"><?php echo number_format($tongtien, 0, ',', '.'); ?>đ</div>
        </div>

        <div class="cart-actions">
            <a href="index.php?quanly=index" class="btn-continue">
                <i class="fas fa-arrow-left"></i>
                Tiếp tục chọn món
            </a>
            <a href="index.php?quanly=thanhtoan" class="btn-checkout">
                Thanh toán
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    <?php } else { ?>
        <div class="empty-cart">
            <div class="empty-cart-icon"><i class="fas fa-shopping-cart"></i></div>
            <h2>Giỏ hàng trống</h2>
            <p>Hãy chọn món ngon nhé!</p>
            <a href="index.php?quanly=index" class="btn-checkout">Chọn món ngay</a>
        </div>
    <?php } ?>
</div>

<script src="js/timeout.js"></script>
