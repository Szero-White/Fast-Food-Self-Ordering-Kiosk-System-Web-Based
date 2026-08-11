<?php
$ordersAdminCssVersion = filemtime(__DIR__ . '/../../css_admin/pages/orders-admin.css');

function order_list_status(int $status): array
{
    return match ($status) {
        1 => ['class' => 'active', 'label' => 'Hoàn thành'],
        2 => ['class' => 'inactive', 'label' => 'Đã hủy'],
        default => ['class' => 'pending', 'label' => 'Đang chọn'],
    };
}

function order_list_payment(?string $method): array
{
    return match ($method) {
        'cash' => ['class' => 'cash', 'label' => 'Tiền mặt', 'icon' => 'fa-money-bill-wave'],
        'transfer' => ['class' => 'transfer', 'label' => 'Chuyển khoản', 'icon' => 'fa-building-columns'],
        default => ['class' => 'unknown', 'label' => 'Chưa chọn', 'icon' => 'fa-clock'],
    };
}

$ordersSql = "
    SELECT donhang.id,
           donhang.madon,
           donhang.tongtien,
           donhang.phuongthuc,
           donhang.ngaydat,
           donhang.trangthai,
           donhang.admin_seen,
           COALESCE(chitiet.sanpham, '') AS sanpham
    FROM tbl_donhang AS donhang
    LEFT JOIN (
        SELECT id_donhang,
               GROUP_CONCAT(CONCAT(ten_sanpham, ' x', soluong) SEPARATOR ', ') AS sanpham
        FROM tbl_chitietdonhang
        GROUP BY id_donhang
    ) AS chitiet ON chitiet.id_donhang = donhang.id
    ORDER BY donhang.ngaydat DESC
";
$orders = mysqli_query($mysqli, $ordersSql);
?>

<link rel="stylesheet" href="css_admin/pages/orders-admin.css?v=<?php echo $ordersAdminCssVersion; ?>">

<div class="content-card orders-hero-card">
    <div class="card-body-custom">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="orders-page-icon list">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div>
                    <h4 class="orders-page-title">Quản lý đơn hàng</h4>
                    <p class="orders-page-subtitle">Theo dõi và cập nhật đơn hàng</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="content-card">
    <div class="card-header-custom">
        <h5><i class="fas fa-list me-2 orders-section-icon"></i>Danh sách đơn hàng</h5>
        <div class="d-flex gap-2">
            <a href="modules/quanlydonhang/export.php" class="btn-custom btn-custom-primary text-decoration-none d-inline-flex align-items-center orders-export-btn">
                <i class="fas fa-file-export me-2"></i>Xuất file
            </a>
            <div class="input-group orders-search">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
                <input type="text" class="form-control" placeholder="Tìm kiếm đơn hàng...">
            </div>
        </div>
    </div>
    <div class="card-body-custom orders-table-body">
        <div class="table-container">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th class="orders-col-id">ID</th>
                        <th>Mã đơn</th>
                        <th>Mô tả đơn hàng</th>
                        <th>Tổng tiền</th>
                        <th class="orders-col-payment">Thanh toán</th>
                        <th>Ngày đặt</th>
                        <th class="orders-col-status">Trạng thái</th>
                        <th class="orders-col-actions">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $orders ? mysqli_fetch_array($orders) : null) {
                        $status = order_list_status((int)$row['trangthai']);
                        $payment = order_list_payment($row['phuongthuc'] ?? null);
                        $productDesc = trim((string)($row['sanpham'] ?? ''));
                    ?>
                        <tr>
                            <td><strong>#<?php echo (int)$row['id']; ?></strong></td>
                            <td>
                                <span class="orders-code"><?php echo htmlspecialchars((string)$row['madon'], ENT_QUOTES, 'UTF-8'); ?></span>
                                <?php if ((int)$row['trangthai'] === 1 && (int)($row['admin_seen'] ?? 1) === 0) { ?>
                                    <span class="order-new-badge">Đơn mới</span>
                                <?php } ?>
                            </td>
                            <td>
                                <div class="orders-product-desc">
                                    <?php if ($productDesc !== '') { ?>
                                        <?php echo htmlspecialchars($productDesc, ENT_QUOTES, 'UTF-8'); ?>
                                    <?php } else { ?>
                                        <span class="orders-empty-text">Chưa có sản phẩm</span>
                                    <?php } ?>
                                </div>
                            </td>
                            <td><strong class="orders-total"><?php echo number_format((float)$row['tongtien'], 0, ',', '.'); ?>đ</strong></td>
                            <td>
                                <span class="orders-payment-badge <?php echo htmlspecialchars($payment['class'], ENT_QUOTES, 'UTF-8'); ?>">
                                    <i class="fas <?php echo htmlspecialchars($payment['icon'], ENT_QUOTES, 'UTF-8'); ?>"></i>
                                    <?php echo htmlspecialchars($payment['label'], ENT_QUOTES, 'UTF-8'); ?>
                                </span>
                            </td>
                            <td><?php echo date('d/m/Y H:i', strtotime((string)$row['ngaydat'])); ?></td>
                            <td><span class="status-badge <?php echo htmlspecialchars($status['class'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($status['label'], ENT_QUOTES, 'UTF-8'); ?></span></td>
                            <td>
                                <div class="action-group orders-action-group">
                                    <a href="?action=quanlydonhang&query=xem&iddonhang=<?php echo (int)$row['id']; ?>" class="orders-action view" title="Xem chi tiết">
                                        <i class="fas fa-eye me-1"></i>Xem
                                    </a>
                                    <a href="modules/quanlydonhang/xuly.php?iddonhang=<?php echo (int)$row['id']; ?>&action=xoa" class="orders-action delete" title="Xóa đơn" data-confirm="Bạn có chắc chắn muốn xóa đơn hàng này?">
                                        <i class="fas fa-trash me-1"></i>Xóa
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
