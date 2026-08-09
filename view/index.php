<?php
// Start session at the very beginning - before any output
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include("config/config.php");
require_once __DIR__ . '/../config/site_asset_repository.php';
require_once __DIR__ . '/../config/order_notification_repository.php';
require_once __DIR__ . '/controllers/kiosk_session_controller.php';
require_once __DIR__ . '/controllers/cart_controller.php';
require_once __DIR__ . '/controllers/checkout_controller.php';

ensure_order_notification_columns($mysqli);

$currentPage = $_GET['quanly'] ?? '';
$usesHomePageCss = in_array($currentPage, ['', 'index', 'trangchu'], true);
$usesMenuCardCss = $usesHomePageCss || $currentPage === 'danhmucsanpham';
$usesNewsPageCss = $currentPage === 'danhmucbaiviet';
$usesCheckoutPageAssets = $currentPage === 'thanhtoan';
$usesProductDetailCss = $currentPage === 'sanpham';
$usesNewsDetailCss = $currentPage === 'baiviet';
$usesCartPageCss = $currentPage === 'giohang';
$usesStaticPageCss = in_array($currentPage, ['gioithieu', 'lienhe', 'contact'], true);
$layoutGlassCssVersion = filemtime(__DIR__ . '/css/layout-glass.css');
$staticPageCssVersion = filemtime(__DIR__ . '/css/static-pages.css');

handle_kiosk_session_request($mysqli, $currentPage);
handle_cart_request($mysqli, $currentPage);
handle_checkout_request($mysqli, $currentPage);
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quảng cáo thực đơn</title>
    <link rel="icon" href="<?php echo htmlspecialchars(site_asset_url($mysqli, 'site_favicon'), ENT_QUOTES, 'UTF-8'); ?>">
    <link rel="apple-touch-icon" href="<?php echo htmlspecialchars(site_asset_url($mysqli, 'site_favicon'), ENT_QUOTES, 'UTF-8'); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/styl.css">
    <link rel="stylesheet" type="text/css" href="css/layout-glass.css?v=<?php echo $layoutGlassCssVersion; ?>">
    <link rel="stylesheet" type="text/css" href="css/banner-carousel.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <?php if ($usesHomePageCss) { ?>
        <link rel="stylesheet" type="text/css" href="css/home-page.css">
    <?php } ?>
    <?php if ($usesMenuCardCss) { ?>
        <link rel="stylesheet" type="text/css" href="css/home-menu-card.css">
    <?php } ?>
    <?php if ($usesNewsPageCss) { ?>
        <link rel="stylesheet" type="text/css" href="css/news-page.css">
    <?php } ?>
    <?php if ($usesCheckoutPageAssets) { ?>
        <link rel="stylesheet" type="text/css" href="css/checkout-page.css">
    <?php } ?>
    <?php if ($usesProductDetailCss) { ?>
        <link rel="stylesheet" type="text/css" href="css/product-detail.css">
    <?php } ?>
    <?php if ($usesNewsDetailCss) { ?>
        <link rel="stylesheet" type="text/css" href="css/news-detail.css">
    <?php } ?>
    <?php if ($usesCartPageCss) { ?>
        <link rel="stylesheet" type="text/css" href="css/cart-page.css">
    <?php } ?>
    <?php if ($usesStaticPageCss) { ?>
        <link rel="stylesheet" type="text/css" href="css/static-pages.css?v=<?php echo $staticPageCssVersion; ?>">
    <?php } ?>

</head>

<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    
    <!-- Chatbot Widget -->
    <?php include("pages/chatbot.php"); ?>
    
    <div class="wrapper">
        <?php
        include("config/config.php");
        include("pages/header.php");
        include("pages/menu.php");
        include("pages/main.php");
        // Footer loaded by individual pages
        include("pages/footer.php");
        ?>
    </div>
    <?php if ($usesHomePageCss) { ?>
        <script src="js/home-page.js"></script>
    <?php } ?>
    <?php if ($usesCheckoutPageAssets) { ?>
        <script src="js/checkout-page.js"></script>
    <?php } ?>
    
</body>

</html>
