<?php
$sql_tong_sp = "SELECT COUNT(*) as tong FROM tbl_sanpham";
$query_tong_sp = mysqli_query($mysqli, $sql_tong_sp);
$row_tong_sp = mysqli_fetch_assoc($query_tong_sp);

$sql_tong_bv = "SELECT COUNT(*) as tong FROM tbl_baiviet";
$query_tong_bv = mysqli_query($mysqli, $sql_tong_bv);
$row_tong_bv = mysqli_fetch_assoc($query_tong_bv);

$sql_don_cho = "SELECT COUNT(*) as tong FROM tbl_donhang WHERE trangthai = 0";
$query_don_cho = mysqli_query($mysqli, $sql_don_cho);
$row_don_cho = mysqli_fetch_assoc($query_don_cho);

$sql_doanhthu = "SELECT SUM(tongtien) as tong FROM tbl_donhang WHERE MONTH(ngaydat) = MONTH(CURRENT_DATE()) AND YEAR(ngaydat) = YEAR(CURRENT_DATE()) AND trangthai = 1";
$query_doanhthu = mysqli_query($mysqli, $sql_doanhthu);
$row_doanhthu = mysqli_fetch_assoc($query_doanhthu);

// Thống kê phương thức thanh toán
$sql_cash = "SELECT COUNT(*) as tong FROM tbl_donhang WHERE phuongthuc = 'cash' AND trangthai = 1";
$query_cash = mysqli_query($mysqli, $sql_cash);
$row_cash = mysqli_fetch_assoc($query_cash);

$sql_transfer = "SELECT COUNT(*) as tong FROM tbl_donhang WHERE phuongthuc = 'transfer' AND trangthai = 1";
$query_transfer = mysqli_query($mysqli, $sql_transfer);
$row_transfer = mysqli_fetch_assoc($query_transfer);

$sql_doanhthu_cash = "SELECT SUM(tongtien) as tong FROM tbl_donhang WHERE phuongthuc = 'cash' AND trangthai = 1";
$query_doanhthu_cash = mysqli_query($mysqli, $sql_doanhthu_cash);
$row_doanhthu_cash = mysqli_fetch_assoc($query_doanhthu_cash);

$sql_doanhthu_transfer = "SELECT SUM(tongtien) as tong FROM tbl_donhang WHERE phuongthuc = 'transfer' AND trangthai = 1";
$query_doanhthu_transfer = mysqli_query($mysqli, $sql_doanhthu_transfer);
$row_doanhthu_transfer = mysqli_fetch_assoc($query_doanhthu_transfer);
?>

<!-- Page Header -->
<div class="content-card" style="background: linear-gradient(135deg, rgba(102,126,234,0.1) 0%, rgba(118,75,162,0.1) 100%); border: 1px solid rgba(102,126,234,0.2);">
    <div class="card-body-custom">
        <div class="d-flex align-items-center gap-3">
            <div style="width: 55px; height: 55px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 14px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-chart-pie" style="color: white; font-size: 24px;"></i>
            </div>
            <div>
                <h4 style="margin: 0; font-weight: 700; color: #333;">Thống kê</h4>
                <p style="margin: 0; color: #888; font-size: 14px;">Tổng quan hoạt động nhà hàng</p>
            </div>
        </div>
    </div>
</div>

<!-- Stats Grid -->
<div class="row g-4 mb-4">
    <!-- Products -->
    <div class="col-xl-3 col-md-6">
        <div class="content-card h-100" style="background: linear-gradient(135deg, rgba(255,107,107,0.1) 0%, rgba(238,90,82,0.1) 100%); border: 1px solid rgba(255,107,107,0.2);">
            <div class="card-body-custom">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p style="color: #888; font-size: 14px; margin-bottom: 8px;">Tổng sản phẩm</p>
                        <h3 style="font-weight: 700; color: #333; margin: 0; font-size: 32px;"><?php echo $row_tong_sp['tong'] ?></h3>
                    </div>
                    <div style="width: 55px; height: 55px; background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%); border-radius: 14px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-utensils" style="color: white; font-size: 24px;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Articles -->
    <div class="col-xl-3 col-md-6">
        <div class="content-card h-100" style="background: linear-gradient(135deg, rgba(17,153,142,0.1) 0%, rgba(56,239,125,0.1) 100%); border: 1px solid rgba(17,153,142,0.2);">
            <div class="card-body-custom">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p style="color: #888; font-size: 14px; margin-bottom: 8px;">Tổng bài viết</p>
                        <h3 style="font-weight: 700; color: #333; margin: 0; font-size: 32px;"><?php echo $row_tong_bv['tong'] ?></h3>
                    </div>
                    <div style="width: 55px; height: 55px; background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); border-radius: 14px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-newspaper" style="color: white; font-size: 24px;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Orders -->
    <div class="col-xl-3 col-md-6">
        <div class="content-card h-100" style="background: linear-gradient(135deg, rgba(243,156,18,0.1) 0%, rgba(241,196,15,0.1) 100%); border: 1px solid rgba(243,156,18,0.2);">
            <div class="card-body-custom">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p style="color: #888; font-size: 14px; margin-bottom: 8px;">Đang chọn món</p>
                        <h3 style="font-weight: 700; color: #333; margin: 0; font-size: 32px;"><?php echo $row_don_cho['tong'] ?></h3>
                    </div>
                    <div style="width: 55px; height: 55px; background: linear-gradient(135deg, #f39c12 0%, #f1c40f 100%); border-radius: 14px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-clock" style="color: white; font-size: 24px;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue -->
    <div class="col-xl-3 col-md-6">
        <div class="content-card h-100" style="background: linear-gradient(135deg, rgba(79,172,254,0.1) 0%, rgba(0,242,254,0.1) 100%); border: 1px solid rgba(79,172,254,0.2);">
            <div class="card-body-custom">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p style="color: #888; font-size: 14px; margin-bottom: 8px;">Doanh thu tháng</p>
                        <h3 style="font-weight: 700; color: #333; margin: 0; font-size: 24px;"><?php echo number_format($row_doanhthu['tong'] ?: 0, 0, ',', '.') ?>đ</h3>
                    </div>
                    <div style="width: 55px; height: 55px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 14px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-wallet" style="color: white; font-size: 24px;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cash Payments -->
    <div class="col-xl-3 col-md-6">
        <div class="content-card h-100" style="background: linear-gradient(135deg, rgba(39,174,96,0.1) 0%, rgba(46,204,113,0.1) 100%); border: 1px solid rgba(39,174,96,0.2);">
            <div class="card-body-custom">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p style="color: #888; font-size: 14px; margin-bottom: 8px;">💵 Tiền mặt</p>
                        <h3 style="font-weight: 700; color: #333; margin: 0; font-size: 32px;"><?php echo $row_cash['tong'] ?></h3>
                        <p style="color: #27ae60; font-size: 13px; margin: 4px 0 0 0;"><?php echo number_format($row_doanhthu_cash['tong'] ?: 0, 0, ',', '.') ?>đ</p>
                    </div>
                    <div style="width: 55px; height: 55px; background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%); border-radius: 14px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-money-bill-wave" style="color: white; font-size: 24px;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Transfer Payments -->
    <div class="col-xl-3 col-md-6">
        <div class="content-card h-100" style="background: linear-gradient(135deg, rgba(52,152,219,0.1) 0%, rgba(93,173,226,0.1) 100%); border: 1px solid rgba(52,152,219,0.2);">
            <div class="card-body-custom">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p style="color: #888; font-size: 14px; margin-bottom: 8px;">📱 Chuyển khoản</p>
                        <h3 style="font-weight: 700; color: #333; margin: 0; font-size: 32px;"><?php echo $row_transfer['tong'] ?></h3>
                        <p style="color: #3498db; font-size: 13px; margin: 4px 0 0 0;"><?php echo number_format($row_doanhthu_transfer['tong'] ?: 0, 0, ',', '.') ?>đ</p>
                    </div>
                    <div style="width: 55px; height: 55px; background: linear-gradient(135deg, #3498db 0%, #5dade2 100%); border-radius: 14px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-university" style="color: white; font-size: 24px;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Orders -->
<div class="content-card">
    <div class="card-header-custom">
        <h5><i class="fas fa-shopping-bag me-2" style="color: #667eea;"></i>Đơn hàng gần đây</h5>
        <a href="?action=quanlydonhang&query=lietke" class="btn-custom btn-custom-secondary text-decoration-none d-inline-flex align-items-center" style="padding: 8px 16px; font-size: 13px;">
            <i class="fas fa-eye me-2"></i>Xem tất cả
        </a>
    </div>
    <div class="card-body-custom" style="padding: 0;">
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
                    <?php
                    $sql_recent_orders = "SELECT * FROM tbl_donhang ORDER BY ngaydat DESC LIMIT 10";
                    $query_recent_orders = mysqli_query($mysqli, $sql_recent_orders);
                    while ($row = mysqli_fetch_array($query_recent_orders)) {
                        $status_class = '';
                        $status_text = '';
                        switch($row['trangthai']) {
                            case 0: $status_class = 'pending'; $status_text = '🟡 Đang chọn'; break;
                            case 1: $status_class = 'active'; $status_text = '🟢 Hoàn thành'; break;
                            case 2: $status_class = 'inactive'; $status_text = '🔴 Đã hủy'; break;
                        }
                        // Phương thức thanh toán
                        $pttt = $row['phuongthuc'];
                        if ($pttt == 'cash') {
                            $pt_text = '💵 Tiền mặt';
                            $pt_color = '#27ae60';
                            $pt_bg = 'rgba(39,174,96,0.1)';
                        } elseif ($pttt == 'transfer') {
                            $pt_text = '📱 Chuyển khoản';
                            $pt_color = '#3498db';
                            $pt_bg = 'rgba(52,152,219,0.1)';
                        } else {
                            $pt_text = '⏳ Chưa chọn';
                            $pt_color = '#95a5a6';
                            $pt_bg = 'rgba(149,165,166,0.1)';
                        }
                        // Lấy chi tiết sản phẩm
                        $sql_ct = "SELECT ten_sanpham, soluong FROM tbl_chitietdonhang WHERE id_donhang = '{$row['id']}'";
                        $query_ct = mysqli_query($mysqli, $sql_ct);
                        $products = [];
                        while ($ct = mysqli_fetch_array($query_ct)) {
                            $products[] = $ct['ten_sanpham'] . ' x' . $ct['soluong'];
                        }
                        $product_desc = count($products) > 0 ? implode(', ', $products) : '<span style="color:#999;font-style:italic;">Chưa có SP</span>';
                    ?>
                    <tr>
                        <td><strong style="font-family: monospace; color: #667eea;">#<?php echo $row['madon'] ?></strong></td>
                        <td>
                            <div style="font-size: 12px; color: #555; max-width: 200px;">
                                <?php echo $product_desc; ?>
                            </div>
                        </td>
                        <td><strong style="color: #27ae60;"><?php echo number_format($row['tongtien'], 0, ',', '.') ?>đ</strong></td>
                        <td>
                            <span style="display: inline-flex; align-items: center; gap: 4px; padding: 4px 8px; border-radius: 6px; font-size: 11px; font-weight: 600; background: <?php echo $pt_bg; ?>; color: <?php echo $pt_color; ?>; border: 1px solid <?php echo $pt_color; ?>;">
                                <?php echo $pt_text; ?>
                            </span>
                        </td>
                        <td><?php echo date('d/m/Y H:i', strtotime($row['ngaydat'])) ?></td>
                        <td><span class="status-badge <?php echo $status_class ?>"><?php echo $status_text ?></span></td>
                    </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>