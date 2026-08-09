<?php
session_start();
if (!isset($_SESSION['dangnhap'])) {
    header('Location:login.php');
    exit;
}

if (isset($_GET['dangxuat']) && $_GET['dangxuat'] == 1) {
    $_SESSION = [];
    session_destroy();
    header('Location:login.php');
    exit;
}

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/../config/site_asset_repository.php';
require_once __DIR__ . '/../config/order_notification_repository.php';
require_once __DIR__ . '/includes/admin_shell_data.php';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FastFood Admin - Quản Lý Nhà Hàng</title>
    <link rel="icon" href="<?php echo htmlspecialchars($adminFaviconUrl, ENT_QUOTES, 'UTF-8'); ?>">
    <link rel="apple-touch-icon" href="<?php echo htmlspecialchars($adminFaviconUrl, ENT_QUOTES, 'UTF-8'); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css_admin/admin_style.css?v=<?php echo $adminCssVersion; ?>">
    <link rel="stylesheet" type="text/css" href="css_admin/pages/crud-admin.css?v=<?php echo $crudAdminCssVersion; ?>">
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="logo">
                <span class="logo-mark">
                    <img src="<?php echo htmlspecialchars($adminLogoUrl, ENT_QUOTES, 'UTF-8'); ?>" alt="FastFood">
                </span>
                <span>FastFood</span>
            </div>
            <button class="sidebar-toggle" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
        </div>
        
        <div class="sidebar-user">
            <div class="user-avatar">
                <i class="fas fa-user-shield"></i>
            </div>
            <div class="user-info">
                <h6><?php echo $adminName; ?></h6>
                <span>Quản trị viên</span>
            </div>
        </div>

        <nav class="sidebar-nav">
            <?php include("modules/menu.php"); ?>
        </nav>

        <div class="sidebar-footer">
            <a href="index.php?dangxuat=1" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i>
                <span>Đăng xuất</span>
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content" id="mainContent">
        <!-- Top Header -->
        <header class="top-header">
            <div class="header-left">
                <div class="breadcrumb">
                    <h4 id="pageTitle">Tổng quan</h4>
                </div>
            </div>
            <div class="header-right">
                <div class="header-actions">
                    <div class="dropdown">
                        <button class="action-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Thông báo hệ thống" aria-label="Thông báo hệ thống: <?php echo $systemAlertCount; ?>">
                            <i class="fas fa-bell"></i>
                            <?php if ($systemAlertCount > 0) { ?>
                                <span class="badge"><?php echo $systemAlertCount; ?></span>
                            <?php } ?>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end admin-notification-menu">
                            <div class="notification-header">
                                <div>
                                    <strong>Thông báo hệ thống</strong>
                                    <small>Theo dõi các cảnh báo cần kiểm tra</small>
                                </div>
                                <span><?php echo $systemAlertCount; ?></span>
                            </div>
                            <?php if ($systemAlertCount === 0) { ?>
                                <div class="notification-empty">
                                    <i class="fas fa-check-circle"></i>
                                    <div>
                                        <strong>Hệ thống đang ổn định</strong>
                                        <small>Không có cảnh báo cần xử lý</small>
                                    </div>
                                </div>
                            <?php } else { ?>
                                <?php if ($newPaidOrders > 0) { ?>
                                    <a class="notification-item" href="<?php echo count($newPaidOrderItems) === 1 ? 'index.php?action=quanlydonhang&query=xem&iddonhang=' . (int) $newPaidOrderItems[0]['id'] : 'index.php?action=quanlydonhang&query=lietke'; ?>">
                                        <span class="notification-icon success"><i class="fas fa-receipt"></i></span>
                                        <span class="notification-content">
                                            <strong><?php echo $newPaidOrders; ?> đơn hàng vừa thanh toán</strong>
                                            <small>
                                                <?php foreach ($newPaidOrderItems as $index => $order) {
                                                    echo $index > 0 ? ' · ' : '';
                                                    echo '#' . htmlspecialchars((string)$order['madon'], ENT_QUOTES, 'UTF-8') . ' - ' . number_format((float)$order['tongtien'], 0, ',', '.') . 'đ';
                                                } ?>
                                                <?php if ($newPaidOrders > count($newPaidOrderItems)) { ?>
                                                    · còn <?php echo $newPaidOrders - count($newPaidOrderItems); ?> đơn khác
                                                <?php } ?>
                                            </small>
                                            <?php if (count($newPaidOrderItems) === 1) { ?>
                                                <em>Mở chi tiết để xác nhận đơn mới</em>
                                            <?php } ?>
                                        </span>
                                    </a>
                                <?php } ?>
                                <?php if ($lowStockProducts > 0) { ?>
                                    <a class="notification-item" href="<?php echo count($lowStockItems) === 1 ? 'index.php?action=quanlymonan&query=sua&idsanpham=' . (int) $lowStockItems[0]['id_sanpham'] : 'index.php?action=quanlymonan&query=them'; ?>">
                                        <span class="notification-icon warning"><i class="fas fa-box-open"></i></span>
                                        <span class="notification-content">
                                            <strong><?php echo $lowStockProducts; ?> món sắp hết hàng</strong>
                                            <small>
                                                <?php foreach ($lowStockItems as $index => $item) {
                                                    echo $index > 0 ? ' · ' : '';
                                                    echo htmlspecialchars($item['tensanpham'], ENT_QUOTES, 'UTF-8') . ' còn ' . (int) $item['soluong'];
                                                } ?>
                                                <?php if ($lowStockProducts > count($lowStockItems)) { ?>
                                                    · còn <?php echo $lowStockProducts - count($lowStockItems); ?> món khác
                                                <?php } ?>
                                            </small>
                                            <?php if (count($lowStockItems) === 1) { ?>
                                                <em>Mã món: <?php echo htmlspecialchars($lowStockItems[0]['masp'], ENT_QUOTES, 'UTF-8'); ?></em>
                                            <?php } ?>
                                        </span>
                                    </a>
                                <?php } ?>
                                <?php if ($uncategorizedProducts > 0) { ?>
                                    <a class="notification-item" href="index.php?action=quanlymonan&query=them">
                                        <span class="notification-icon danger"><i class="fas fa-triangle-exclamation"></i></span>
                                        <span class="notification-content">
                                            <strong><?php echo $uncategorizedProducts; ?> món chưa phân loại</strong>
                                            <small>Gán lại danh mục để hiển thị đúng trên website</small>
                                        </span>
                                    </a>
                                <?php } ?>
                                <?php if ($missingActiveBanner > 0) { ?>
                                    <a class="notification-item" href="index.php?action=quanlybanner&query=them">
                                        <span class="notification-icon danger"><i class="fas fa-image"></i></span>
                                        <span class="notification-content">
                                            <strong>Chưa có banner đang hiển thị</strong>
                                            <small>Bật ít nhất một banner cho trang chủ</small>
                                        </span>
                                    </a>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    </div>
                    <a href="index.php?action=quanlylienhe&query=lietke" class="action-btn" title="Liên hệ chưa xem" aria-label="Liên hệ chưa xem: <?php echo $unreadContacts; ?>">
                        <i class="fas fa-envelope"></i>
                        <?php if ($unreadContacts > 0) { ?>
                            <span class="badge"><?php echo $unreadContacts; ?></span>
                        <?php } ?>
                    </a>
                </div>
                <div class="user-dropdown dropdown">
                    <button class="user-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <span><?php echo $adminName; ?></span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end admin-user-menu">
                        <li><span class="dropdown-item-text">Quản trị viên</span></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-danger" href="index.php?dangxuat=1">
                                <i class="fas fa-sign-out-alt me-2"></i>Đăng xuất
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Content Area -->
        <div class="content-wrapper">
            <?php
            require_once __DIR__ . "/config/config.php";
            include("modules/main.php");
            ?>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.ckeditor.com/4.24.0-lts/standard/ckeditor.js"></script>
    <script src="js_admin/admin_script.js?v=<?php echo $adminJsVersion; ?>"></script>
    <script src="js_admin/pages/crud-admin.js?v=<?php echo $crudAdminJsVersion; ?>"></script>
</body>
</html>
