<?php
$sql_xem = "SELECT * FROM tbl_donhang WHERE id = '$_GET[iddonhang]' LIMIT 1";
$query_xem = mysqli_query($mysqli, $sql_xem);
$row = mysqli_fetch_array($query_xem);

// Lấy chi tiết đơn hàng
$sql_ct = "SELECT * FROM tbl_chitietdonhang WHERE id_donhang = '$_GET[iddonhang]'";
$query_ct = mysqli_query($mysqli, $sql_ct);

$status_text = '';
$status_color = '';
switch($row['trangthai']) {
    case 0: $status_text = 'Đang chọn'; $status_color = '#f39c12'; break;
    case 1: $status_text = 'Hoàn thành'; $status_color = '#27ae60'; break;
    case 2: $status_text = 'Đã hủy'; $status_color = '#e74c3c'; break;
}
?>

<!-- Page Header -->
<div class="content-card" style="background: linear-gradient(135deg, rgba(102,126,234,0.1) 0%, rgba(118,75,162,0.1) 100%); border: 1px solid rgba(102,126,234,0.2);">
    <div class="card-body-custom">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div style="width: 55px; height: 55px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 14px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-file-invoice" style="color: white; font-size: 24px;"></i>
                </div>
                <div>
                    <h4 style="margin: 0; font-weight: 700; color: #333;">Chi tiết đơn hàng #<?php echo $row['madon']; ?></h4>
                    <p style="margin: 0; color: #888; font-size: 14px;">Ngày đặt: <?php echo date('d/m/Y H:i', strtotime($row['ngaydat'])); ?></p>
                </div>
            </div>
            <div style="display: flex; gap: 10px;">
                <a href="?action=quanlydonhang&query=lietke" class="btn-custom btn-custom-secondary text-decoration-none d-inline-flex align-items-center" style="padding: 10px 20px;">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại danh sách
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-4">
        <!-- Trạng thái đơn -->
        <div class="content-card" style="margin-top: 20px;">
            <div class="card-header-custom">
                <h5><i class="fas fa-info-circle me-2" style="color: #f39c12;"></i>Trạng thái đơn hàng</h5>
            </div>
            <div class="card-body-custom" style="text-align: center;">
                <div style="display: inline-block; padding: 15px 30px; background: <?php echo $status_color; ?>; color: white; border-radius: 12px; font-weight: 600; font-size: 16px;">
                    <?php echo $status_text; ?>
                </div>
                <form method="POST" action="modules/quanlydonhang/xuly.php?iddonhang=<?php echo $row['id']; ?>" style="margin-top: 20px;">
                    <div class="form-group-custom">
                        <label class="form-label-custom" style="font-size: 13px;">Cập nhật trạng thái</label>
                        <select name="trangthai" class="form-control-custom" style="font-size: 14px; padding: 10px;">
                            <option value="0" <?php echo $row['trangthai']==0 ? 'selected' : ''; ?>>🟡 Đang chọn</option>
                            <option value="1" <?php echo $row['trangthai']==1 ? 'selected' : ''; ?>>🟢 Hoàn thành</option>
                            <option value="2" <?php echo $row['trangthai']==2 ? 'selected' : ''; ?>>🔴 Đã hủy</option>
                        </select>
                    </div>
                    <button type="submit" name="capnhatdonhang" class="btn-custom btn-custom-primary" style="width: 100%; margin-top: 10px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <i class="fas fa-save me-2"></i>Lưu trạng thái
                    </button>
                </form>
            </div>
        </div>

        <!-- Phương thức thanh toán -->
        <div class="content-card" style="margin-top: 20px;">
            <div class="card-header-custom">
                <h5><i class="fas fa-credit-card me-2" style="color: #27ae60;"></i>Phương thức thanh toán</h5>
            </div>
            <div class="card-body-custom" style="text-align: center;">
                <?php 
                $payment_method = $row['phuongthuc'] ?: 'Chưa chọn';
                $payment_icon = 'fa-money-bill';
                $payment_color = '#95a5a6';
                if ($row['phuongthuc'] == 'cash') {
                    $payment_method = 'Tiền mặt';
                    $payment_icon = 'fa-money-bill-wave';
                    $payment_color = '#27ae60';
                } elseif ($row['phuongthuc'] == 'transfer') {
                    $payment_method = 'Chuyển khoản';
                    $payment_icon = 'fa-university';
                    $payment_color = '#3498db';
                }
                ?>
                <div style="display: inline-flex; align-items: center; gap: 10px; padding: 15px 30px; background: <?php echo $payment_color; ?>15; border: 2px solid <?php echo $payment_color; ?>; border-radius: 12px; color: <?php echo $payment_color; ?>; font-weight: 600; font-size: 16px;">
                    <i class="fas <?php echo $payment_icon; ?>"></i>
                    <?php echo $payment_method; ?>
                </div>
            </div>
        </div>

        <!-- Tổng tiền -->
        <div class="content-card" style="margin-top: 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="card-body-custom" style="text-align: center; color: white;">
                <p style="margin: 0 0 10px 0; font-size: 14px; opacity: 0.9;">Tổng giá trị đơn hàng</p>
                <h3 style="margin: 0; font-weight: 700; font-size: 28px;"><?php echo number_format($row['tongtien'], 0, ',', '.'); ?>đ</h3>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <!-- Chi tiết sản phẩm -->
        <div class="content-card">
            <div class="card-header-custom">
                <h5><i class="fas fa-shopping-basket me-2" style="color: #e74c3c;"></i>Sản phẩm trong đơn</h5>
            </div>
            <div class="card-body-custom" style="padding: 0;">
                <div class="table-container">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th style="width: 60px;">STT</th>
                                <th>Sản phẩm</th>
                                <th style="width: 100px; text-align: center;">SL</th>
                                <th style="width: 150px; text-align: right;">Đơn giá</th>
                                <th style="width: 150px; text-align: right;">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            while($ct = mysqli_fetch_array($query_ct)) {
                            ?>
                            <tr>
                                <td><strong>#<?php echo $i++; ?></strong></td>
                                <td><?php echo $ct['ten_sanpham']; ?></td>
                                <td style="text-align: center;"><?php echo $ct['soluong']; ?></td>
                                <td style="text-align: right;"><?php echo number_format($ct['gia'], 0, ',', '.'); ?>đ</td>
                                <td style="text-align: right; font-weight: 600; color: #27ae60;"><?php echo number_format($ct['thanhtien'], 0, ',', '.'); ?>đ</td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Ghi chú -->
        <?php if($row['ghichu']) { ?>
        <div class="content-card" style="margin-top: 20px;">
            <div class="card-header-custom">
                <h5><i class="fas fa-sticky-note me-2" style="color: #9b59b6;"></i>Ghi chú từ khách hàng</h5>
            </div>
            <div class="card-body-custom">
                <div style="padding: 15px; background: #fff9e6; border-left: 4px solid #f1c40f; border-radius: 8px;">
                    <p style="margin: 0; color: #555; font-style: italic;"><?php echo $row['ghichu']; ?></p>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</div>
