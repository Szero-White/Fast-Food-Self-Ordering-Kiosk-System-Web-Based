<?php
// Reset payment method nếu chưa submit
if (!isset($_POST['thanhtoan']) && !isset($_POST['hoantat'])) {
    unset($_SESSION['payment_method']);
}

// Ghi log để debug
$debug_msg = date('H:i:s') . " - POST thanhtoan: " . (isset($_POST['thanhtoan']) ? 'YES' : 'NO') . ", hoantat: " . (isset($_POST['hoantat']) ? 'YES' : 'NO');
if (isset($_POST['phuongthuc'])) {
    $debug_msg .= ", phuongthuc: " . $_POST['phuongthuc'];
}
if (isset($_SESSION['payment_method'])) {
    $debug_msg .= ", session: " . $_SESSION['payment_method'];
}
file_put_contents('debug.log', $debug_msg . "\n", FILE_APPEND);

// Xử lý thanh toán
if (isset($_POST['thanhtoan'])) {
    // Lưu phương thức thanh toán
    $_SESSION['payment_method'] = $_POST['phuongthuc'];
    // Không xử lý insert ở đây
} elseif (isset($_POST['hoantat'])) {
    // Xử lý HOÀN TẤT thanh toán - UPDATE đơn hàng đã có
    $madon = $_SESSION['madon'] ?? '';
    $id_donhang = $_SESSION['id_donhang'] ?? 0;
    
    if ($id_donhang > 0) {
        // Tính tổng tiền
        $tongtien = 0;
        foreach ($_SESSION['cart'] as $item) {
            $tongtien += $item['gia'] * $item['soluong'];
        }
        
        // Lấy phương thức thanh toán từ POST (ưu tiên) hoặc session
        $phuongthuc = $_POST['phuongthuc'] ?? $_SESSION['payment_method'] ?? 'cash';
        
        // Update đơn hàng: trangthai = 1 (Hoàn thành) + lưu phương thức thanh toán
        $sql = "UPDATE tbl_donhang SET tongtien = '$tongtien', trangthai = 1, ngaydat = NOW(), phuongthuc = '$phuongthuc' WHERE id = '$id_donhang'";
        $result = mysqli_query($mysqli, $sql);
        file_put_contents('debug.log', date('H:i:s') . " - SQL: $sql, Result: " . ($result ? 'OK' : 'FAIL') . "\n", FILE_APPEND);
        
        // Xóa chi tiết cũ và thêm chi tiết mới
        mysqli_query($mysqli, "DELETE FROM tbl_chitietdonhang WHERE id_donhang = '$id_donhang'");
        foreach ($_SESSION['cart'] as $item) {
            $id_sp = $item['id'];
            $ten_sp = mysqli_real_escape_string($mysqli, $item['ten']);
            $gia = $item['gia'];
            $soluong = $item['soluong'];
            $thanhtien = $gia * $soluong;
            $sql_ct = "INSERT INTO tbl_chitietdonhang (id_donhang, id_sanpham, ten_sanpham, gia, soluong, thanhtien) 
                       VALUES ('$id_donhang', '$id_sp', '$ten_sp', '$gia', '$soluong', '$thanhtien')";
            mysqli_query($mysqli, $sql_ct);
        }
    }
    
    // Hiển thị thông báo thành công
    $_SESSION['payment_success'] = true;
    header("Location: index.php?quanly=camon");
    exit();
}

// Tính tổng tiền
$tongtien = 0;
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<div style='padding:20px;background:red;color:white;text-align:center;font-size:1.5rem;'>Giỏ hàng trống! Vui lòng thêm sản phẩm vào giỏ hàng trước khi thanh toán.</div>";
    echo "<p style='text-align:center;'><a href='index.php' style='background:blue;color:white;padding:10px 20px;text-decoration:none;'>Quay lại trang chủ</a></p>";
    exit();
}
foreach ($_SESSION['cart'] as $item) {
    $tongtien += $item['gia'] * $item['soluong'];
}

// Kiểm tra nếu đã chọn phương thức thanh toán
if (isset($_SESSION['payment_method'])) {
    $method = $_SESSION['payment_method'];
    ?>
    <div class="checkout-container">
        <div class="checkout-header">
            <h1>💳 Thanh toán</h1>
            <p>Phương thức: <?php echo $method == 'qr' ? 'Quét mã QR' : 'Tiền mặt'; ?></p>
        </div>
        
        <div class="order-summary">
            <h3>📝 Đơn hàng của bạn</h3>
            <?php foreach ($_SESSION['cart'] as $item) { ?>
                <div class="order-item">
                    <span><?php echo $item['ten']; ?> x<?php echo $item['soluong']; ?></span>
                    <span><?php echo number_format($item['gia'] * $item['soluong'], 0, ',', '.'); ?>đ</span>
                </div>
            <?php } ?>
            <div class="order-total">
                Tổng: <?php echo number_format($tongtien, 0, ',', '.'); ?>đ
            </div>
        </div>
        
        <?php if ($method == 'qr') { ?>
            <div class="qr-section">
                <div class="qr-code">📲</div>
                <p><strong>Quét mã để thanh toán</strong></p>
                <p style="color: #666; font-size: 0.9rem;">Sau khi quét và thanh toán thành công, bấm nút bên dưới.</p>
            </div>
        <?php } else { ?>
            <div class="payment-info" style="text-align:center; padding:20px; background:#f8f9fa; border-radius:10px; margin:20px 0;">
                <p><strong>💵 Thanh toán bằng tiền mặt tại quầy</strong></p>
                <p>Vui lòng đến quầy thu ngân để thanh toán số tiền trên.</p>
            </div>
        <?php } ?>
        
        <div class="checkout-actions">
            <a href="index.php?quanly=thanhtoan" class="btn-back">← Quay lại chọn phương thức</a>
            <form method="POST" action="index.php?quanly=thanhtoan" style="display:inline;">
                <input type="hidden" name="phuongthuc" value="<?php echo $_SESSION['payment_method'] ?? 'cash'; ?>">
                <button type="submit" name="hoantat" class="btn-pay">✅ Hoàn tất thanh toán</button>
            </form>
        </div>
    </div>
    <?php
    exit();
}
?>

<style>
    .checkout-container {
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    .checkout-header {
        text-align: center;
        margin-bottom: 30px;
        padding: 30px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        color: white;
    }
    
    .order-summary {
        background: white;
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .order-item {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #eee;
    }
    .order-total {
        font-size: 1.5rem;
        color: #e74c3c;
        font-weight: bold;
        text-align: center;
        padding: 20px 0;
        border-top: 2px solid #667eea;
        margin-top: 10px;
    }
    
    .payment-methods {
        background: white;
        border-radius: 15px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .payment-methods h3 {
        margin-bottom: 20px;
        color: #333;
    }
    .payment-option {
        display: flex;
        align-items: center;
        padding: 15px;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        margin-bottom: 10px;
        cursor: pointer;
        transition: all 0.3s;
    }
    .payment-option:hover {
        border-color: #667eea;
    }
    .payment-option input {
        margin-right: 15px;
    }
    .payment-option.selected {
        border-color: #667eea;
        background: #f0f0ff;
    }
    
    .qr-section {
        text-align: center;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 10px;
        margin-top: 20px;
    }
    .qr-code {
        width: 200px;
        height: 200px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: 2px solid #667eea;
        border-radius: 10px;
        margin: 0 auto 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 4rem;
        color: white;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    
    .checkout-actions {
        display: flex;
        gap: 15px;
        justify-content: center;
        margin-top: 30px;
    }
    .btn-back {
        background: #95a5a6;
        color: white;
        text-decoration: none;
        padding: 15px 30px;
        border-radius: 25px;
        font-size: 1.1rem;
        border: none;
        cursor: pointer;
    }
    .btn-pay {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 15px 40px;
        border-radius: 25px;
        font-size: 1.2rem;
        font-weight: bold;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    .btn-pay:hover {
        transform: translateY(-2px);
        box-shadow: 0 7px 20px rgba(0,0,0,0.3);
    }
</style>

<div class="checkout-container">
    <div class="checkout-header">
        <h1>💳 Thanh toán</h1>
        <p>Vui lòng chọn phương thức thanh toán</p>
    </div>
    
    <div class="order-summary">
        <h3>📝 Đơn hàng của bạn</h3>
        <?php foreach ($_SESSION['cart'] as $item) { ?>
            <div class="order-item">
                <span><?php echo $item['ten']; ?> x<?php echo $item['soluong']; ?></span>
                <span><?php echo number_format($item['gia'] * $item['soluong'], 0, ',', '.'); ?>đ</span>
            </div>
        <?php } ?>
        <div class="order-total">
            Tổng: <?php echo number_format($tongtien, 0, ',', '.'); ?>đ
        </div>
    </div>
    
    <form method="POST" action="index.php?quanly=thanhtoan">
        <div class="payment-methods">
            <h3>💰 Chọn phương thức thanh toán</h3>
            
            <label class="payment-option selected">
                <input type="radio" name="phuongthuc" value="transfer" checked>
                <span style="font-size: 1.5rem; margin-right: 10px;">📱</span>
                <div>
                    <strong>Quét mã QR / Chuyển khoản</strong>
                    <p style="font-size: 0.9rem; color: #666; margin: 0;">Momo, ZaloPay, VietQR</p>
                </div>
            </label>
            
            <label class="payment-option">
                <input type="radio" name="phuongthuc" value="cash">
                <span style="font-size: 1.5rem; margin-right: 10px;">💵</span>
                <div>
                    <strong>Tiền mặt</strong>
                    <p style="font-size: 0.9rem; color: #666; margin: 0;">Thanh toán tại quầy</p>
                </div>
            </label>
        </div>
        
        <div class="qr-section">
            <div class="qr-code">📲</div>
            <p><strong>Quét mã để thanh toán</strong></p>
            <p style="color: #666; font-size: 0.9rem;">Hoặc bấm nút bên dưới để hoàn tất</p>
        </div>
        
        <div class="checkout-actions">
            <a href="index.php?quanly=giohang" class="btn-back">← Quay lại</a>
            <button type="submit" name="thanhtoan" class="btn-pay">✅ Xác nhận thanh toán</button>
        </div>
    </form>
</div>

<script>
    // Chọn phương thức thanh toán
    document.querySelectorAll('.payment-option').forEach(option => {
        option.addEventListener('click', function() {
            document.querySelectorAll('.payment-option').forEach(o => o.classList.remove('selected'));
            this.classList.add('selected');
            this.querySelector('input').checked = true;
        });
    });
</script>

<script src="js/timeout.js"></script>

