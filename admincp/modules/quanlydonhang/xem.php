<?php
$ordersAdminCssVersion = filemtime(__DIR__ . '/../../css_admin/pages/orders-admin.css');

function order_detail_status(int $status): array
{
    return match ($status) {
        1 => ['class' => 'active', 'label' => 'Hoàn thành'],
        2 => ['class' => 'inactive', 'label' => 'Đã hủy'],
        default => ['class' => 'pending', 'label' => 'Đang chọn'],
    };
}

function order_detail_payment(?string $method): array
{
    return match ($method) {
        'cash' => ['class' => 'cash', 'label' => 'Tiền mặt', 'icon' => 'fa-money-bill-wave'],
        'transfer' => ['class' => 'transfer', 'label' => 'Chuyển khoản', 'icon' => 'fa-building-columns'],
        default => ['class' => 'unknown', 'label' => 'Chưa chọn', 'icon' => 'fa-clock'],
    };
}

$idDonHang = (int)($_GET['iddonhang'] ?? 0);
$stmtOrder = mysqli_prepare($mysqli, 'SELECT * FROM tbl_donhang WHERE id = ? LIMIT 1');
mysqli_stmt_bind_param($stmtOrder, 'i', $idDonHang);
mysqli_stmt_execute($stmtOrder);
$query_xem = mysqli_stmt_get_result($stmtOrder);
$row = mysqli_fetch_array($query_xem);

$stmtOrderDetail = mysqli_prepare($mysqli, 'SELECT * FROM tbl_chitietdonhang WHERE id_donhang = ?');
mysqli_stmt_bind_param($stmtOrderDetail, 'i', $idDonHang);
mysqli_stmt_execute($stmtOrderDetail);
$query_ct = mysqli_stmt_get_result($stmtOrderDetail);

if (!$row) {
    echo '<div class="content-card"><div class="card-body-custom">Đơn hàng không tồn tại.</div></div>';
    mysqli_stmt_close($stmtOrder);
    mysqli_stmt_close($stmtOrderDetail);
    return;
}

$status = order_detail_status((int)$row['trangthai']);
$payment = order_detail_payment($row['phuongthuc'] ?? null);
?>

<link rel="stylesheet" href="css_admin/pages/orders-admin.css?v=<?php echo $ordersAdminCssVersion; ?>">

<div class="content-card orders-detail-hero">
    <div class="card-body-custom">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="orders-page-icon detail">
                    <i class="fas fa-file-invoice"></i>
                </div>
                <div>
                    <h4 class="orders-page-title">Chi tiết đơn hàng #<?php echo htmlspecialchars((string)$row['madon'], ENT_QUOTES, 'UTF-8'); ?></h4>
                    <p class="orders-page-subtitle">Ngày đặt: <?php echo date('d/m/Y H:i', strtotime((string)$row['ngaydat'])); ?></p>
                </div>
            </div>
            <div class="orders-header-actions">
                <a href="?action=quanlydonhang&query=lietke" class="btn-custom btn-custom-secondary text-decoration-none d-inline-flex align-items-center orders-back-btn">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại danh sách
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-4">
        <div class="content-card orders-side-card">
            <div class="card-header-custom">
                <h5><i class="fas fa-info-circle me-2 orders-title-icon status"></i>Trạng thái đơn hàng</h5>
            </div>
            <div class="card-body-custom orders-card-center">
                <div class="orders-status-pill <?php echo htmlspecialchars($status['class'], ENT_QUOTES, 'UTF-8'); ?>">
                    <?php echo htmlspecialchars($status['label'], ENT_QUOTES, 'UTF-8'); ?>
                </div>
                <form method="POST" action="modules/quanlydonhang/xuly.php?iddonhang=<?php echo (int)$row['id']; ?>" class="orders-status-form">
                    <div class="form-group-custom">
                        <label class="form-label-custom orders-status-label">Cập nhật trạng thái</label>
                        <select name="trangthai" class="form-control-custom orders-status-select">
                            <option value="0" <?php echo (int)$row['trangthai'] === 0 ? 'selected' : ''; ?>>Đang chọn</option>
                            <option value="1" <?php echo (int)$row['trangthai'] === 1 ? 'selected' : ''; ?>>Hoàn thành</option>
                            <option value="2" <?php echo (int)$row['trangthai'] === 2 ? 'selected' : ''; ?>>Đã hủy</option>
                        </select>
                    </div>
                    <button type="submit" name="capnhatdonhang" class="btn-custom btn-custom-primary orders-save-status-btn">
                        <i class="fas fa-save me-2"></i>Lưu trạng thái
                    </button>
                </form>
            </div>
        </div>

        <div class="content-card orders-side-card">
            <div class="card-header-custom">
                <h5><i class="fas fa-credit-card me-2 orders-title-icon payment"></i>Phương thức thanh toán</h5>
            </div>
            <div class="card-body-custom orders-card-center">
                <div class="orders-payment-display <?php echo htmlspecialchars($payment['class'], ENT_QUOTES, 'UTF-8'); ?>">
                    <i class="fas <?php echo htmlspecialchars($payment['icon'], ENT_QUOTES, 'UTF-8'); ?>"></i>
                    <?php echo htmlspecialchars($payment['label'], ENT_QUOTES, 'UTF-8'); ?>
                </div>
            </div>
        </div>

        <div class="content-card orders-total-card">
            <div class="card-body-custom orders-total-body">
                <p class="orders-total-label">Tổng giá trị đơn hàng</p>
                <h3 class="orders-total-value"><?php echo number_format((float)$row['tongtien'], 0, ',', '.'); ?>đ</h3>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="content-card">
            <div class="card-header-custom">
                <h5><i class="fas fa-shopping-basket me-2 orders-title-icon products"></i>Sản phẩm trong đơn</h5>
            </div>
            <div class="card-body-custom orders-detail-table-body">
                <div class="table-container">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th class="orders-col-stt">STT</th>
                                <th>Sản phẩm</th>
                                <th class="orders-col-qty">SL</th>
                                <th class="orders-col-price">Đơn giá</th>
                                <th class="orders-col-line-total">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $index = 1; ?>
                            <?php while ($ct = mysqli_fetch_array($query_ct)) { ?>
                                <tr>
                                    <td><strong>#<?php echo $index++; ?></strong></td>
                                    <td><?php echo htmlspecialchars((string)$ct['ten_sanpham'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td class="orders-text-center"><?php echo (int)$ct['soluong']; ?></td>
                                    <td class="orders-text-right"><?php echo number_format((float)$ct['gia'], 0, ',', '.'); ?>đ</td>
                                    <td class="orders-line-total"><?php echo number_format((float)$ct['thanhtien'], 0, ',', '.'); ?>đ</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <?php if (!empty($row['ghichu'])) { ?>
            <div class="content-card orders-side-card">
                <div class="card-header-custom">
                    <h5><i class="fas fa-sticky-note me-2 orders-title-icon note"></i>Ghi chú từ khách hàng</h5>
                </div>
                <div class="card-body-custom">
                    <div class="orders-note-box">
                        <p class="orders-note-text"><?php echo htmlspecialchars((string)$row['ghichu'], ENT_QUOTES, 'UTF-8'); ?></p>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
