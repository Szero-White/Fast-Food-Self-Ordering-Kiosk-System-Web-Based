<?php require_once __DIR__ . '/../../../config/site_asset_repository.php'; ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FastFood Kiosk - Chào mừng</title>
    <link rel="icon" href="<?php echo htmlspecialchars(site_asset_url($mysqli, 'site_favicon'), ENT_QUOTES, 'UTF-8'); ?>">
    <link rel="apple-touch-icon" href="<?php echo htmlspecialchars(site_asset_url($mysqli, 'site_favicon'), ENT_QUOTES, 'UTF-8'); ?>">
    <link rel="stylesheet" href="css/welcome-page.css">
</head>
<body>
    <div class="logo-container">
        <img src="<?php echo htmlspecialchars(site_asset_url($mysqli, 'site_logo'), ENT_QUOTES, 'UTF-8'); ?>" alt="FastFood Logo">
    </div>

    <h1 class="welcome-title">🍔 FastFood Kiosk</h1>
    <p class="tagline">Đặt món dễ dàng - Nhanh chóng - Tiện lợi</p>

    <a href="index.php?start=1" class="start-btn bounce">
        👆 BẮT ĐẦU
    </a>

    <div class="features">
        <div class="feature">
            <span class="feature-icon">🍕</span>
            <span>Chọn món dễ dàng</span>
        </div>
        <div class="feature">
            <span class="feature-icon">⚡</span>
            <span>Thanh toán nhanh</span>
        </div>
        <div class="feature">
            <span class="feature-icon">🎁</span>
            <span>Nhận ưu đãi</span>
        </div>
    </div>
</body>
</html>
