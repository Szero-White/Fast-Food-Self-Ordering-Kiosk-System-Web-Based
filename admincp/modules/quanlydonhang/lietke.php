<!-- Page Header -->
<div class="content-card" style="background: linear-gradient(135deg, rgba(240,147,251,0.1) 0%, rgba(245,87,108,0.1) 100%); border: 1px solid rgba(240,147,251,0.2);">
    <div class="card-body-custom">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div style="width: 55px; height: 55px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 14px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-shopping-cart" style="color: white; font-size: 24px;"></i>
                </div>
                <div>
                    <h4 style="margin: 0; font-weight: 700; color: #333;">Quản lý đơn hàng</h4>
                    <p style="margin: 0; color: #888; font-size: 14px;">Theo dõi và cập nhật đơn hàng</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Orders Table -->
<div class="content-card">
    <div class="card-header-custom">
        <h5><i class="fas fa-list me-2" style="color: #f5576c;"></i>Danh sách đơn hàng</h5>
        <div class="d-flex gap-2">
            <div class="input-group" style="max-width: 300px;">
                <span class="input-group-text" style="background: white; border-right: none;"><i class="fas fa-search" style="color: #888;"></i></span>
                <input type="text" class="form-control" placeholder="Tìm kiếm đơn hàng..." style="border-left: none;">
            </div>
        </div>
    </div>
    <div class="card-body-custom" style="padding: 0;">
        <div class="table-container">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th style="width: 60px;">ID</th>
                        <th>Mã đơn</th>
                        <th>Mô tả đơn hàng</th>
                        <th>Tổng tiền</th>
                        <th style="width: 120px;">Thanh toán</th>
                        <th>Ngày đặt</th>
                        <th style="width: 130px;">Trạng thái</th>
                        <th style="width: 180px; text-align: center;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql_lietke_donhang = "SELECT * FROM tbl_donhang ORDER BY ngaydat DESC";
                    $query_lietke_donhang = mysqli_query($mysqli, $sql_lietke_donhang);
                    while ($row = mysqli_fetch_array($query_lietke_donhang)) {
                        $status_class = '';
                        $status_text = '';
                        switch($row['trangthai']) {
                            case 0: $status_class = 'pending'; $status_text = 'Đang chọn'; break;
                            case 1: $status_class = 'active'; $status_text = 'Hoàn thành'; break;
                            case 2: $status_class = 'inactive'; $status_text = 'Đã hủy'; break;
                        }
                        // Lấy phương thức thanh toán
                        $pttt = $row['phuongthuc'];
                        if ($pttt == 'cash') {
                            $pt_text = 'Tiền mặt';
                            $pt_color = '#27ae60';
                            $pt_bg = 'rgba(39,174,96,0.1)';
                            $pt_icon = '💵';
                        } elseif ($pttt == 'transfer') {
                            $pt_text = 'Chuyển khoản';
                            $pt_color = '#3498db';
                            $pt_bg = 'rgba(52,152,219,0.1)';
                            $pt_icon = '📱';
                        } else {
                            $pt_text = 'Chưa chọn';
                            $pt_color = '#95a5a6';
                            $pt_bg = 'rgba(149,165,166,0.1)';
                            $pt_icon = '⏳';
                        }
                        // Lấy chi tiết sản phẩm trong đơn
                        $sql_ct = "SELECT ten_sanpham, soluong FROM tbl_chitietdonhang WHERE id_donhang = '{$row['id']}'";
                        $query_ct = mysqli_query($mysqli, $sql_ct);
                        $products = [];
                        while ($ct = mysqli_fetch_array($query_ct)) {
                            $products[] = $ct['ten_sanpham'] . ' x' . $ct['soluong'];
                        }
                        $product_desc = count($products) > 0 ? implode(', ', $products) : '<span style="color:#999;font-style:italic;">Chưa có sản phẩm</span>';
                    ?>
                    <tr>
                        <td><strong>#<?php echo $row['id'] ?></strong></td>
                        <td>
                            <span style="font-weight: 600; color: #667eea;"><?php echo $row['madon'] ?></span>
                        </td>
                        <td>
                            <div style="font-size: 13px; color: #555; max-width: 250px; line-height: 1.5;">
                                <?php echo $product_desc; ?>
                            </div>
                        </td>
                        <td><strong style="color: #27ae60;"><?php echo number_format($row['tongtien'], 0, ',', '.') ?>đ</strong></td>
                        <td>
                            <span style="display: inline-flex; align-items: center; gap: 4px; padding: 6px 10px; border-radius: 8px; font-size: 12px; font-weight: 600; background: <?php echo $pt_bg; ?>; color: <?php echo $pt_color; ?>; border: 1px solid <?php echo $pt_color; ?>;">
                                <?php echo $pt_icon . ' ' . $pt_text; ?>
                            </span>
                        </td>
                        <td><?php echo date('d/m/Y H:i', strtotime($row['ngaydat'])) ?></td>
                        <td><span class="status-badge <?php echo $status_class ?>"><?php echo $status_text ?></span></td>
                        <td>
                            <div class="action-group" style="justify-content: center; gap: 10px;">
                                <a href="?action=quanlydonhang&query=xem&iddonhang=<?php echo $row['id'] ?>" class="btn-action" title="Xem chi tiết" style="background: #3498db; color: white; padding: 8px 16px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; text-decoration: none; font-size: 13px; font-weight: 600; box-shadow: 0 2px 5px rgba(52,152,219,0.3);">
                                    <i class="fas fa-eye me-1" style="font-size: 12px;"></i>Xem
                                </a>
                                <a href="modules/quanlydonhang/xuly.php?iddonhang=<?php echo $row['id'] ?>&action=xoa" class="btn-action" title="Xóa đơn" onclick="return confirm('Bạn có chắc chắn muốn xóa đơn hàng này?')" style="background: #e74c3c; color: white; padding: 8px 16px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; text-decoration: none; font-size: 13px; font-weight: 600; box-shadow: 0 2px 5px rgba(231,76,60,0.3);">
                                    <i class="fas fa-trash me-1" style="font-size: 12px;"></i>Xóa
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>