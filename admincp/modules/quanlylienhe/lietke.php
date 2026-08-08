<!-- Page Header -->
<div class="content-card" style="background: linear-gradient(135deg, rgba(79,172,254,0.1) 0%, rgba(0,242,254,0.1) 100%); border: 1px solid rgba(79,172,254,0.2);">
    <div class="card-body-custom">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div style="width: 55px; height: 55px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 14px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-envelope" style="color: white; font-size: 24px;"></i>
                </div>
                <div>
                    <h4 style="margin: 0; font-weight: 700; color: #333;">Quản lý liên hệ</h4>
                    <p style="margin: 0; color: #888; font-size: 14px;">Tiếp nhận và xử lý ý kiến khách hàng</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Contacts Table -->
<div class="content-card">
    <div class="card-header-custom">
        <h5><i class="fas fa-list me-2" style="color: #4facfe;"></i>Danh sách liên hệ</h5>
        <div class="d-flex gap-2">
            <div class="input-group" style="max-width: 300px;">
                <span class="input-group-text" style="background: white; border-right: none;"><i class="fas fa-search" style="color: #888;"></i></span>
                <input type="text" class="form-control" placeholder="Tìm kiếm..." style="border-left: none;">
            </div>
        </div>
    </div>
    <div class="card-body-custom" style="padding: 0;">
        <div class="table-container">
            <table class="custom-table">
                <thead>
                    <tr>
                        <th style="width: 60px;">ID</th>
                        <th>Tên</th>
                        <th>Email</th>
                        <th>SĐT</th>
                        <th>Loại</th>
                        <th>Nội dung</th>
                        <th>Ngày gửi</th>
                        <th style="width: 120px;">Trạng thái</th>
                        <th style="width: 120px; text-align: center;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql_lietke_lh = "SELECT * FROM tbl_lienhe ORDER BY ngaygui DESC";
                    $query_lietke_lh = mysqli_query($mysqli, $sql_lietke_lh);
                    while ($row = mysqli_fetch_array($query_lietke_lh)) {
                        $status_class = $row['trangthai'] == 'chua_xem' ? 'pending' : 'active';
                        $status_text = $row['trangthai'] == 'chua_xem' ? 'Chưa xem' : 'Đã xem';
                    ?>
                    <tr>
                        <td><strong>#<?php echo $row['id_lienhe'] ?></strong></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div style="width: 32px; height: 32px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-user" style="color: white; font-size: 12px;"></i>
                                </div>
                                <span style="font-weight: 500;"><?php echo $row['ten'] ?></span>
                            </div>
                        </td>
                        <td><?php echo $row['email'] ?></td>
                        <td><?php echo $row['sodienthoai'] ?></td>
                        <td><span class="badge" style="background: rgba(102,126,234,0.1); color: #667eea; padding: 6px 12px; border-radius: 20px;"><?php echo $row['loai'] ?></span></td>
                        <td style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"><?php echo substr($row['noidung'], 0, 50) . '...' ?></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($row['ngaygui'])) ?></td>
                        <td><span class="status-badge <?php echo $status_class ?>"><?php echo $status_text ?></span></td>
                        <td>
                            <div class="action-group" style="justify-content: center;">
                                <a href="?action=quanlylienhe&query=sua&idlienhe=<?php echo $row['id_lienhe'] ?>" class="btn-action view" title="Xem chi tiết">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="modules/quanlylienhe/xuly.php?idlienhe=<?php echo $row['id_lienhe'] ?>" class="btn-action delete" title="Xóa" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">
                                    <i class="fas fa-trash"></i>
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