<?php
$statisticsAdminCssVersion = filemtime(__DIR__ . '/../../css_admin/pages/statistics-admin.css');

function statistic_value(mysqli $mysqli, string $sql): int|float
{
    $result = mysqli_query($mysqli, $sql);
    if (!$result) {
        return 0;
    }

    $row = mysqli_fetch_assoc($result);
    return $row ? (float)($row['tong'] ?? 0) : 0;
}

function statistic_money(float|int|null $value): string
{
    return number_format((float)($value ?? 0), 0, ',', '.') . 'đ';
}

function statistic_order_status(int $status): array
{
    return match ($status) {
        1 => ['class' => 'active', 'label' => 'Hoàn thành'],
        2 => ['class' => 'inactive', 'label' => 'Đã hủy'],
        default => ['class' => 'pending', 'label' => 'Đang chọn'],
    };
}

function statistic_payment_info(?string $method): array
{
    return match ($method) {
        'cash' => ['class' => 'cash', 'label' => 'Tiền mặt', 'icon' => 'fa-money-bill-wave'],
        'transfer' => ['class' => 'transfer', 'label' => 'Chuyển khoản', 'icon' => 'fa-building-columns'],
        default => ['class' => 'unknown', 'label' => 'Chưa chọn', 'icon' => 'fa-clock'],
    };
}

$totalProducts = (int)statistic_value($mysqli, 'SELECT COUNT(*) AS tong FROM tbl_sanpham');
$uncategorizedProducts = (int)statistic_value(
    $mysqli,
    'SELECT COUNT(*) AS tong
     FROM tbl_sanpham
     LEFT JOIN tbl_danhmuc ON tbl_sanpham.id_danhmuc = tbl_danhmuc.id_danhmuc
     WHERE tbl_danhmuc.id_danhmuc IS NULL'
);
$totalPosts = (int)statistic_value($mysqli, 'SELECT COUNT(*) AS tong FROM tbl_baiviet');
$pendingOrders = (int)statistic_value($mysqli, 'SELECT COUNT(*) AS tong FROM tbl_donhang WHERE trangthai = 0');
$monthlyRevenue = statistic_value(
    $mysqli,
    'SELECT SUM(tongtien) AS tong
     FROM tbl_donhang
     WHERE MONTH(ngaydat) = MONTH(CURRENT_DATE())
       AND YEAR(ngaydat) = YEAR(CURRENT_DATE())
       AND trangthai = 1'
);
$cashOrders = (int)statistic_value($mysqli, "SELECT COUNT(*) AS tong FROM tbl_donhang WHERE phuongthuc = 'cash' AND trangthai = 1");
$transferOrders = (int)statistic_value($mysqli, "SELECT COUNT(*) AS tong FROM tbl_donhang WHERE phuongthuc = 'transfer' AND trangthai = 1");
$cashRevenue = statistic_value($mysqli, "SELECT SUM(tongtien) AS tong FROM tbl_donhang WHERE phuongthuc = 'cash' AND trangthai = 1");
$transferRevenue = statistic_value($mysqli, "SELECT SUM(tongtien) AS tong FROM tbl_donhang WHERE phuongthuc = 'transfer' AND trangthai = 1");

$metrics = [
    [
        'class' => 'products',
        'icon' => 'fa-utensils',
        'label' => 'Tổng sản phẩm',
        'value' => $totalProducts,
        'note' => $uncategorizedProducts > 0 ? $uncategorizedProducts . ' món chưa phân loại' : '',
        'note_class' => 'warning',
    ],
    [
        'class' => 'posts',
        'icon' => 'fa-newspaper',
        'label' => 'Tổng bài viết',
        'value' => $totalPosts,
        'note' => '',
        'note_class' => '',
    ],
    [
        'class' => 'pending',
        'icon' => 'fa-clock',
        'label' => 'Đang chọn món',
        'value' => $pendingOrders,
        'note' => '',
        'note_class' => '',
    ],
    [
        'class' => 'revenue',
        'icon' => 'fa-wallet',
        'label' => 'Doanh thu tháng',
        'value' => statistic_money($monthlyRevenue),
        'compact' => true,
        'note' => '',
        'note_class' => '',
    ],
    [
        'class' => 'cash',
        'icon' => 'fa-money-bill-wave',
        'label' => 'Tiền mặt',
        'value' => $cashOrders,
        'note' => statistic_money($cashRevenue),
        'note_class' => 'cash',
    ],
    [
        'class' => 'transfer',
        'icon' => 'fa-building-columns',
        'label' => 'Chuyển khoản',
        'value' => $transferOrders,
        'note' => statistic_money($transferRevenue),
        'note_class' => 'transfer',
    ],
];

$recentOrdersSql = "
    SELECT donhang.id,
           donhang.madon,
           donhang.tongtien,
           donhang.phuongthuc,
           donhang.ngaydat,
           donhang.trangthai,
           COALESCE(chitiet.sanpham, '') AS sanpham
    FROM tbl_donhang AS donhang
    LEFT JOIN (
        SELECT id_donhang,
               GROUP_CONCAT(CONCAT(ten_sanpham, ' x', soluong) SEPARATOR ', ') AS sanpham
        FROM tbl_chitietdonhang
        GROUP BY id_donhang
    ) AS chitiet ON chitiet.id_donhang = donhang.id
    ORDER BY donhang.ngaydat DESC
    LIMIT 10
";
$recentOrders = mysqli_query($mysqli, $recentOrdersSql);
?>

<link rel="stylesheet" href="css_admin/pages/statistics-admin.css?v=<?php echo $statisticsAdminCssVersion; ?>">

<div class="content-card stat-hero-card">
    <div class="card-body-custom">
        <div class="d-flex align-items-center gap-3">
            <div class="stat-hero-icon">
                <i class="fas fa-chart-pie"></i>
            </div>
            <div>
                <h4 class="stat-hero-title">Thống kê</h4>
                <p class="stat-hero-subtitle">Tổng quan hoạt động nhà hàng</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <?php foreach ($metrics as $metric) { ?>
        <div class="col-xl-3 col-md-6">
            <div class="content-card h-100 stat-metric-card <?php echo htmlspecialchars($metric['class'], ENT_QUOTES, 'UTF-8'); ?>">
                <div class="card-body-custom">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="stat-metric-label"><?php echo htmlspecialchars($metric['label'], ENT_QUOTES, 'UTF-8'); ?></p>
                            <h3 class="stat-metric-value <?php echo !empty($metric['compact']) ? 'compact' : ''; ?>">
                                <?php echo htmlspecialchars((string)$metric['value'], ENT_QUOTES, 'UTF-8'); ?>
                            </h3>
                            <?php if (!empty($metric['note'])) { ?>
                                <p class="stat-metric-note <?php echo htmlspecialchars($metric['note_class'], ENT_QUOTES, 'UTF-8'); ?>">
                                    <?php echo htmlspecialchars($metric['note'], ENT_QUOTES, 'UTF-8'); ?>
                                </p>
                            <?php } ?>
                        </div>
                        <div class="stat-metric-icon <?php echo htmlspecialchars($metric['class'], ENT_QUOTES, 'UTF-8'); ?>">
                            <i class="fas <?php echo htmlspecialchars($metric['icon'], ENT_QUOTES, 'UTF-8'); ?>"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
</div>

<div class="content-card">
    <div class="card-header-custom">
        <h5><i class="fas fa-shopping-bag me-2 stat-section-icon"></i>Đơn hàng gần đây</h5>
        <a href="?action=quanlydonhang&query=lietke" class="btn-custom btn-custom-secondary text-decoration-none d-inline-flex align-items-center stat-view-all-btn">
            <i class="fas fa-eye me-2"></i>Xem tất cả
        </a>
    </div>
    <div class="card-body-custom stat-table-body">
        <div class="table-container">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th>Mã đơn</th>
                        <th>Mô tả đơn</th>
                        <th>Tổng tiền</th>
                        <th>Thanh toán</th>
                        <th>Ngày đặt</th>
                        <th>Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $recentOrders ? mysqli_fetch_array($recentOrders) : null) {
                        $status = statistic_order_status((int)$row['trangthai']);
                        $payment = statistic_payment_info($row['phuongthuc'] ?? null);
                        $productDesc = trim((string)($row['sanpham'] ?? ''));
                    ?>
                        <tr>
                            <td>
                                <strong class="stat-order-code">#<?php echo htmlspecialchars((string)$row['madon'], ENT_QUOTES, 'UTF-8'); ?></strong>
                            </td>
                            <td>
                                <div class="stat-order-desc">
                                    <?php if ($productDesc !== '') { ?>
                                        <?php echo htmlspecialchars($productDesc, ENT_QUOTES, 'UTF-8'); ?>
                                    <?php } else { ?>
                                        <span class="stat-empty-text">Chưa có sản phẩm</span>
                                    <?php } ?>
                                </div>
                            </td>
                            <td>
                                <strong class="stat-order-total"><?php echo statistic_money((float)$row['tongtien']); ?></strong>
                            </td>
                            <td>
                                <span class="stat-payment-badge <?php echo htmlspecialchars($payment['class'], ENT_QUOTES, 'UTF-8'); ?>">
                                    <i class="fas <?php echo htmlspecialchars($payment['icon'], ENT_QUOTES, 'UTF-8'); ?>"></i>
                                    <?php echo htmlspecialchars($payment['label'], ENT_QUOTES, 'UTF-8'); ?>
                                </span>
                            </td>
                            <td><?php echo date('d/m/Y H:i', strtotime((string)$row['ngaydat'])); ?></td>
                            <td>
                                <span class="status-badge <?php echo htmlspecialchars($status['class'], ENT_QUOTES, 'UTF-8'); ?>">
                                    <?php echo htmlspecialchars($status['label'], ENT_QUOTES, 'UTF-8'); ?>
                                </span>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
