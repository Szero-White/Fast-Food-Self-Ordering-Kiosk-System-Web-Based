<?php
// Kiểm tra thanh toán thành công
if (!isset($_SESSION['payment_success'])) {
    header("Location: index.php?quanly=welcome");
    exit();
}

// Lấy mã đơn hàng từ session
$madon = $_SESSION['madon'] ?? 'FF' . date('Ymd') . rand(100, 999);

// Kiểm tra đơn hàng trong database
include(__DIR__ . '/../../config/config.php');
$check = mysqli_query($mysqli, "SELECT * FROM tbl_donhang WHERE madon = '$madon'");
$donhang_exists = mysqli_num_rows($check) > 0;

// Lưu thông tin đơn hàng trước khi xóa
$order_info = $_SESSION['cart'] ?? array();
$tongtien = 0;
foreach ($order_info as $item) {
    $tongtien += $item['gia'] * $item['soluong'];
}

// Xóa session sau khi hiển thị
$_SESSION = array();
session_destroy();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
</head>
<body>
<style>
    .success-container {
        max-width: 600px;
        margin: 0 auto;
        padding: 40px 20px;
        text-align: center;
        color: white;
    }
    
    .success-icon {
        font-size: 6rem;
        margin-bottom: 20px;
        animation: scaleIn 0.5s ease;
    }
    
    @keyframes scaleIn {
        0% { transform: scale(0); }
        50% { transform: scale(1.2); }
        100% { transform: scale(1); }
    }
    
    .success-title {
        font-size: 2.5rem;
        margin-bottom: 15px;
        color: #2ecc71;
    }
    
    .success-message {
        font-size: 1.2rem;
        margin-bottom: 30px;
        opacity: 0.9;
    }
    
    .order-info {
        background: white;
        border-radius: 15px;
        padding: 25px;
        margin: 30px 0;
        color: #333;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }
    .order-code {
        font-size: 2rem;
        color: #667eea;
        font-weight: bold;
        letter-spacing: 3px;
        margin: 10px 0;
    }
    
    .order-details {
        text-align: left;
        margin-top: 20px;
    }
    .order-item {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px dashed #eee;
    }
    .order-total {
        font-size: 1.5rem;
        color: #e74c3c;
        font-weight: bold;
        text-align: center;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 2px solid #667eea;
    }
    
    .instructions {
        background: rgba(255,255,255,0.95);
        border-radius: 10px;
        padding: 20px;
        margin: 20px 0;
        text-align: left;
        color: #333;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .instructions h3 {
        margin-bottom: 15px;
        text-align: center;
        color: #2c3e50;
    }
    .instructions ol {
        margin: 0;
        padding-left: 25px;
    }
    .instructions li {
        margin: 12px 0;
        font-size: 1.1rem;
        line-height: 1.6;
        color: #333;
    }
    
    .countdown {
        font-size: 1.5rem;
        margin-top: 30px;
        padding: 20px;
        background: rgba(0,0,0,0.2);
        border-radius: 10px;
    }
    .countdown span {
        color: #f39c12;
        font-weight: bold;
        font-size: 2rem;
    }
</style>

<div class="success-container">
    <div class="success-icon">🎉</div>
    
    <h1 class="success-title">Thanh toán thành công!</h1>
    <p class="success-message">Cảm ơn bạn đã đặt món tại FastFood</p>
    
    <?php if ($donhang_exists) { ?>
        <p style="color: #27ae60; font-weight: bold;">✅ Đơn hàng đã lưu vào database!</p>
    <?php } else { ?>
        <p style="color: #e74c3c; font-weight: bold;">❌ Đơn hàng CHƯA lưu vào database!</p>
    <?php } ?>
    
    <div class="order-info">
        <p>Mã đơn hàng của bạn:</p>
        <div class="order-code"><?php echo $madon; ?></div>
        <p style="color: #666;">Vui lòng ghi nhớ mã này</p>
        
        <div class="order-details">
            <?php foreach ($order_info as $item) { ?>
                <div class="order-item">
                    <span><?php echo $item['ten']; ?> x<?php echo $item['soluong']; ?></span>
                    <span><?php echo number_format($item['gia'] * $item['soluong'], 0, ',', '.'); ?>đ</span>
                </div>
            <?php } ?>
            <div class="order-total">
                Tổng: <?php echo number_format($tongtien, 0, ',', '.'); ?>đ
            </div>
        </div>
    </div>
    
    <div class="instructions">
        <h3>📋 Hướng dẫn nhận món:</h3>
        <ol>
            <li>Đến quầy phục vụ</li>
            <li>Đọc mã đơn hàng: <strong><?php echo $madon; ?></strong></li>
            <li>Nhận món và thưởng thức!</li>
        </ol>
    </div>
    
    <div class="countdown">
        ⏱️ Tự động quay về màn hình chính sau <span id="countdown">10</span> giây
    </div>
</div>

<script>
    // Countdown và auto redirect
    let seconds = 10;
    const countdownEl = document.getElementById('countdown');
    
    const timer = setInterval(function() {
        seconds--;
        countdownEl.textContent = seconds;
        
        if (seconds <= 0) {
            clearInterval(timer);
            window.location.href = 'index.php?quanly=welcome';
        }
    }, 1000);
</script>


</body>
</html>
