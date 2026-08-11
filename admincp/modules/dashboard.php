<?php
$dashboardAdminCssVersion = filemtime(__DIR__ . '/../css_admin/pages/dashboard-admin.css');
$dashboardAdminJsVersion = filemtime(__DIR__ . '/../js_admin/pages/dashboard-admin.js');

function dashboard_count(mysqli $mysqli, string $sql): int
{
    $result = mysqli_query($mysqli, $sql);
    if (!$result) {
        return 0;
    }

    $row = mysqli_fetch_assoc($result);
    return (int)($row['tong'] ?? 0);
}

$totalProducts = dashboard_count($mysqli, 'SELECT COUNT(*) AS tong FROM tbl_sanpham');
$uncategorizedProducts = dashboard_count(
    $mysqli,
    'SELECT COUNT(*) AS tong
     FROM tbl_sanpham
     LEFT JOIN tbl_danhmuc ON tbl_sanpham.id_danhmuc = tbl_danhmuc.id_danhmuc
     WHERE tbl_danhmuc.id_danhmuc IS NULL'
);
$totalPosts = dashboard_count($mysqli, 'SELECT COUNT(*) AS tong FROM tbl_baiviet');
$unreadContacts = dashboard_count($mysqli, "SELECT COUNT(*) AS tong FROM tbl_lienhe WHERE trangthai = 'chua_xem'");
$newOrders = dashboard_count($mysqli, 'SELECT COUNT(*) AS tong FROM tbl_donhang WHERE admin_seen = 0 AND trangthai = 1');

$statCards = [
    [
        'icon_class' => 'blue',
        'icon' => 'fa-utensils',
        'trend' => '<i class="fas fa-arrow-up"></i> +12%',
        'trend_class' => '',
        'value' => $totalProducts,
        'label' => 'Tổng món ăn',
        'note' => $uncategorizedProducts > 0 ? $uncategorizedProducts . ' món chưa phân loại' : '',
    ],
    [
        'icon_class' => 'green',
        'icon' => 'fa-newspaper',
        'trend' => '<i class="fas fa-arrow-up"></i> +8%',
        'trend_class' => '',
        'value' => $totalPosts,
        'label' => 'Bài viết',
        'note' => '',
    ],
    [
        'icon_class' => 'orange',
        'icon' => 'fa-shopping-cart',
        'trend' => '<i class="fas fa-arrow-down"></i> ' . $newOrders . ' mới',
        'trend_class' => 'down',
        'value' => $newOrders,
        'label' => 'Đơn hàng mới',
        'note' => '',
    ],
    [
        'icon_class' => 'cyan',
        'icon' => 'fa-envelope',
        'trend' => $unreadContacts > 0 ? '<i class="fas fa-exclamation"></i> Cần xử lý' : '<i class="fas fa-check"></i> OK',
        'trend_class' => $unreadContacts > 0 ? 'down' : '',
        'value' => $unreadContacts,
        'label' => 'Liên hệ chưa xem',
        'note' => '',
    ],
];

$quickActions = [
    [
        'class' => 'menu',
        'href' => 'index.php?action=quanlymonan&query=them',
        'icon' => 'fa-plus',
        'title' => 'Thêm món ăn',
        'desc' => 'Thêm món mới vào thực đơn',
    ],
    [
        'class' => 'post',
        'href' => 'index.php?action=quanlybaiviet&query=them',
        'icon' => 'fa-pen',
        'title' => 'Viết bài mới',
        'desc' => 'Tạo bài viết mới',
    ],
    [
        'class' => 'order',
        'href' => 'index.php?action=quanlydonhang&query=lietke',
        'icon' => 'fa-clipboard-list',
        'title' => 'Xem đơn hàng',
        'desc' => 'Kiểm tra đơn hàng mới',
    ],
    [
        'class' => 'report',
        'href' => 'index.php?action=thongke&query=xem',
        'icon' => 'fa-chart-pie',
        'title' => 'Xem thống kê',
        'desc' => 'Báo cáo doanh thu',
    ],
];

$guides = [
    [
        'class' => 'menu',
        'icon' => 'fa-utensils',
        'title' => 'Quản lý thực đơn',
        'desc' => 'Thêm, sửa, xóa các món ăn và phân loại theo danh mục.',
    ],
    [
        'class' => 'post',
        'icon' => 'fa-newspaper',
        'title' => 'Quản lý bài viết',
        'desc' => 'Tạo và chỉnh sửa các bài viết, tin tức về nhà hàng.',
    ],
    [
        'class' => 'order',
        'icon' => 'fa-shopping-cart',
        'title' => 'Quản lý đơn hàng',
        'desc' => 'Xem và cập nhật trạng thái đơn hàng từ khách hàng.',
    ],
];
?>

<link rel="stylesheet" href="css_admin/pages/dashboard-admin.css?v=<?php echo $dashboardAdminCssVersion; ?>">

<div class="content-card dashboard-welcome">
    <div class="card-body-custom">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <h4 class="dashboard-welcome-title">
                    <i class="fas fa-hand-sparkles me-2"></i>Chào mừng trở lại, Admin!
                </h4>
                <p class="dashboard-welcome-text">Chúc bạn một ngày làm việc hiệu quả. Hãy quản lý nhà hàng của bạn một cách chuyên nghiệp.</p>
            </div>
            <div class="text-end">
                <p class="dashboard-date-label">Hôm nay</p>
                <h5 class="dashboard-date-value" id="currentDate"></h5>
            </div>
        </div>
    </div>
</div>

<div class="stats-grid">
    <?php foreach ($statCards as $card) { ?>
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-icon <?php echo htmlspecialchars($card['icon_class'], ENT_QUOTES, 'UTF-8'); ?>">
                    <i class="fas <?php echo htmlspecialchars($card['icon'], ENT_QUOTES, 'UTF-8'); ?>"></i>
                </div>
                <div class="stat-trend <?php echo htmlspecialchars($card['trend_class'], ENT_QUOTES, 'UTF-8'); ?>">
                    <?php echo $card['trend']; ?>
                </div>
            </div>
            <div class="stat-body">
                <h3><?php echo (int)$card['value']; ?></h3>
                <?php if ($card['note'] !== '') { ?>
                    <small class="stat-note-warning"><?php echo htmlspecialchars($card['note'], ENT_QUOTES, 'UTF-8'); ?></small>
                <?php } ?>
                <p><?php echo htmlspecialchars($card['label'], ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
        </div>
    <?php } ?>
</div>

<div class="row">
    <div class="col-lg-7">
        <div class="content-card">
            <div class="card-header-custom">
                <h5><i class="fas fa-bolt me-2 dashboard-section-icon"></i>Thao tác nhanh</h5>
            </div>
            <div class="card-body-custom">
                <div class="row g-3">
                    <?php foreach ($quickActions as $action) { ?>
                        <div class="col-sm-6">
                            <a href="<?php echo htmlspecialchars($action['href'], ENT_QUOTES, 'UTF-8'); ?>" class="dashboard-action <?php echo htmlspecialchars($action['class'], ENT_QUOTES, 'UTF-8'); ?>">
                                <div class="dashboard-action-icon <?php echo htmlspecialchars($action['class'], ENT_QUOTES, 'UTF-8'); ?>">
                                    <i class="fas <?php echo htmlspecialchars($action['icon'], ENT_QUOTES, 'UTF-8'); ?>"></i>
                                </div>
                                <div>
                                    <h6><?php echo htmlspecialchars($action['title'], ENT_QUOTES, 'UTF-8'); ?></h6>
                                    <small><?php echo htmlspecialchars($action['desc'], ENT_QUOTES, 'UTF-8'); ?></small>
                                </div>
                            </a>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="content-card">
            <div class="card-header-custom">
                <h5><i class="fas fa-lightbulb me-2 dashboard-section-icon"></i>Hướng dẫn sử dụng</h5>
            </div>
            <div class="card-body-custom">
                <div class="d-flex flex-column gap-3">
                    <?php foreach ($guides as $guide) { ?>
                        <div class="dashboard-guide">
                            <div class="dashboard-guide-icon <?php echo htmlspecialchars($guide['class'], ENT_QUOTES, 'UTF-8'); ?>">
                                <i class="fas <?php echo htmlspecialchars($guide['icon'], ENT_QUOTES, 'UTF-8'); ?>"></i>
                            </div>
                            <div>
                                <h6><?php echo htmlspecialchars($guide['title'], ENT_QUOTES, 'UTF-8'); ?></h6>
                                <p><?php echo htmlspecialchars($guide['desc'], ENT_QUOTES, 'UTF-8'); ?></p>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="js_admin/pages/dashboard-admin.js?v=<?php echo $dashboardAdminJsVersion; ?>"></script>
