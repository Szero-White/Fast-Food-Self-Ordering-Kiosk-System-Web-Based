<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<?php
require_once __DIR__ . '/../../config/site_asset_repository.php';
require_once __DIR__ . '/../../config/kiosk_order_repository.php';

$current_page = $_GET['quanly'] ?? 'index';
$cart_quantity = kiosk_cart_quantity($_SESSION['cart'] ?? []);
?>

<header class="glass-header">
    <div class="glass-container">
        <a href="index.php?quanly=index" class="glass-logo">
            <img src="<?php echo htmlspecialchars(site_asset_url($mysqli, 'site_logo'), ENT_QUOTES, 'UTF-8'); ?>" alt="FastFood Logo">
            <span>FastFood</span>
        </a>

        <nav class="glass-nav">
            <a href="index.php?quanly=index" class="<?php echo $current_page === 'index' ? 'active' : ''; ?>">
                <i class="fas fa-home"></i>
                <span>Trang chủ</span>
            </a>
            <a href="index.php?quanly=gioithieu" class="<?php echo $current_page === 'gioithieu' ? 'active' : ''; ?>">
                <i class="fas fa-info-circle"></i>
                <span>Giới thiệu</span>
            </a>
            <a href="index.php?quanly=danhmucbaiviet" class="<?php echo $current_page === 'danhmucbaiviet' ? 'active' : ''; ?>">
                <i class="fas fa-gift"></i>
                <span>Khuyến mãi</span>
            </a>
            <a href="index.php?quanly=lienhe" class="<?php echo $current_page === 'lienhe' ? 'active' : ''; ?>">
                <i class="fas fa-envelope"></i>
                <span>Liên hệ</span>
            </a>
        </nav>

        <div class="glass-actions">
            <a href="index.php?quanly=giohang" class="glass-btn glass-btn-cart">
                <i class="fas fa-shopping-cart"></i>
                <span>Giỏ hàng</span>
                <span class="glass-badge"><?php echo $cart_quantity; ?></span>
            </a>
            <a href="index.php?quanly=goinhanvien" class="glass-btn glass-btn-call">
                <i class="fas fa-bell"></i>
                <span>Gọi NV</span>
            </a>
        </div>
    </div>
</header>
