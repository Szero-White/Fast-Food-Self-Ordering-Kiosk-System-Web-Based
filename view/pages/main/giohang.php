<?php
// Xử lý giỏ hàng đã chuyển lên index.php
// Tính tổng tiền
$tongtien = 0;
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $tongtien += $item['gia'] * $item['soluong'];
    }
}
?>

<style>
    .cart-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
    }
    .cart-header {
        text-align: center;
        margin-bottom: 30px;
        padding: 30px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        color: white;
    }
    .cart-header h1 {
        font-size: 2rem;
    }
    
    .cart-item {
        display: flex;
        align-items: center;
        background: white;
        border-radius: 15px;
        padding: 15px;
        margin-bottom: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .cart-item img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 10px;
        margin-right: 15px;
    }
    .cart-item-info {
        flex: 1;
    }
    .cart-item-name {
        font-weight: bold;
        color: #333;
        margin-bottom: 5px;
    }
    .cart-item-price {
        color: #e74c3c;
        font-weight: bold;
    }
    
    .quantity-control {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-right: 20px;
    }
    .quantity-control input {
        width: 50px;
        text-align: center;
        padding: 8px;
        border: 2px solid #667eea;
        border-radius: 8px;
        font-size: 1rem;
    }
    .btn-update {
        background: #667eea;
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 8px;
        cursor: pointer;
    }
    .btn-delete {
        background: #e74c3c;
        color: white;
        text-decoration: none;
        padding: 10px 15px;
        border-radius: 8px;
    }
    
    .cart-total {
        background: white;
        border-radius: 15px;
        padding: 20px;
        text-align: center;
        margin-top: 20px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .total-amount {
        font-size: 2rem;
        color: #e74c3c;
        font-weight: bold;
    }
    
    .cart-actions {
        display: flex;
        gap: 15px;
        justify-content: center;
        margin-top: 20px;
    }
    .btn-continue {
        background: #95a5a6;
        color: white;
        text-decoration: none;
        padding: 15px 30px;
        border-radius: 25px;
        font-size: 1.1rem;
    }
    .btn-checkout {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        text-decoration: none;
        padding: 15px 30px;
        border-radius: 25px;
        font-size: 1.1rem;
        font-weight: bold;
    }
    
    .empty-cart {
        text-align: center;
        padding: 50px;
        color: white;
    }
    .empty-cart-icon {
        font-size: 5rem;
        margin-bottom: 20px;
    }
</style>

<div class="cart-container">
    <div class="cart-header">
        <h1>🛒 Giỏ hàng của bạn</h1>
    </div>
    
    <?php if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) { ?>
        <?php foreach ($_SESSION['cart'] as $item) { ?>
            <div class="cart-item">
                <img src="uploads/<?php echo $item['hinhanh']; ?>" alt="<?php echo $item['ten']; ?>">
                <div class="cart-item-info">
                    <div class="cart-item-name"><?php echo $item['ten']; ?></div>
                    <div class="cart-item-price"><?php echo number_format($item['gia'], 0, ',', '.'); ?>đ</div>
                </div>
                
                <form method="POST" class="quantity-control">
                    <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                    <input type="number" name="soluong" value="<?php echo $item['soluong']; ?>" min="0" max="10">
                    <button type="submit" name="capnhat" class="btn-update">Cập nhật</button>
                </form>
                
                <a href="index.php?quanly=giohang&xoa=<?php echo $item['id']; ?>" class="btn-delete">🗑️ Xóa</a>
            </div>
        <?php } ?>
        
        <div class="cart-total">
            <p>Tổng tiền:</p>
            <div class="total-amount"><?php echo number_format($tongtien, 0, ',', '.'); ?>đ</div>
        </div>
        
        <div class="cart-actions">
            <a href="index.php?quanly=index" class="btn-continue">← Tiếp tục chọn món</a>
            <a href="index.php?quanly=thanhtoan" class="btn-checkout">Thanh toán →</a>
        </div>
        
    <?php } else { ?>
        <div class="empty-cart">
            <div class="empty-cart-icon">🛒</div>
            <h2>Giỏ hàng trống</h2>
            <p>Hãy chọn món ngon nhé!</p><br>
            <a href="index.php?quanly=index" class="btn-checkout">Chọn món ngay</a>
        </div>
    <?php } ?>
</div>

<!-- Auto reset timer -->
<script src="js/timeout.js"></script>

