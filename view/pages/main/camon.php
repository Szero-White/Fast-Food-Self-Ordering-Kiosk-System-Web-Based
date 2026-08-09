<?php
if (!isset($_SESSION['payment_success'])) {
    header('Location: index.php?quanly=welcome');
    exit();
}

$madon = $_SESSION['madon'] ?? 'FF' . date('Ymd') . rand(100, 999);

include(__DIR__ . '/../../config/config.php');
require_once __DIR__ . '/../../../config/site_asset_repository.php';

$stmt = mysqli_prepare($mysqli, 'SELECT id FROM tbl_donhang WHERE madon = ? LIMIT 1');
mysqli_stmt_bind_param($stmt, 's', $madon);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);
$donhang_exists = mysqli_stmt_num_rows($stmt) > 0;
mysqli_stmt_close($stmt);

$order_info = $_SESSION['cart'] ?? [];
$tongtien = 0;
foreach ($order_info as $item) {
    $tongtien += (float)$item['gia'] * (int)$item['soluong'];
}

$_SESSION = [];
session_destroy();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán thành công</title>
    <link rel="icon" href="<?php echo htmlspecialchars(site_asset_url($mysqli, 'site_favicon'), ENT_QUOTES, 'UTF-8'); ?>">
    <link rel="apple-touch-icon" href="<?php echo htmlspecialchars(site_asset_url($mysqli, 'site_favicon'), ENT_QUOTES, 'UTF-8'); ?>">
    <link rel="stylesheet" href="css/order-success.css">
</head>
<body>
    <main class="success-container">
        <div class="success-icon">🎉</div>

        <h1 class="success-title">Thanh toán thành công!</h1>
        <p class="success-message">Cảm ơn bạn đã đặt món tại FastFood</p>

        <?php if ($donhang_exists) { ?>
            <p class="success-status ok">Đơn hàng đã được lưu vào hệ thống.</p>
        <?php } else { ?>
            <p class="success-status error">Đơn hàng chưa được lưu vào hệ thống.</p>
        <?php } ?>

        <section class="order-info">
            <p>Mã đơn hàng của bạn:</p>
            <div class="order-code"><?php echo htmlspecialchars($madon, ENT_QUOTES, 'UTF-8'); ?></div>
            <p class="order-note">Vui lòng ghi nhớ mã này</p>

            <div class="order-details">
                <?php foreach ($order_info as $item) { ?>
                    <div class="order-item">
                        <span><?php echo htmlspecialchars($item['ten'], ENT_QUOTES, 'UTF-8'); ?> x<?php echo (int)$item['soluong']; ?></span>
                        <span><?php echo number_format((float)$item['gia'] * (int)$item['soluong'], 0, ',', '.'); ?>đ</span>
                    </div>
                <?php } ?>
                <div class="order-total">
                    Tổng: <?php echo number_format($tongtien, 0, ',', '.'); ?>đ
                </div>
            </div>
        </section>

        <section class="instructions">
            <h3>Hướng dẫn nhận món</h3>
            <ol>
                <li>Đến quầy phục vụ.</li>
                <li>Đọc mã đơn hàng: <strong><?php echo htmlspecialchars($madon, ENT_QUOTES, 'UTF-8'); ?></strong></li>
                <li>Nhận món và thưởng thức.</li>
            </ol>
        </section>

        <div class="countdown">
            Tự động quay về màn hình chính sau <span id="countdown">10</span> giây
        </div>
    </main>

    <script src="js/order-success.js"></script>
</body>
</html>
