<div class="content-card crud-hero contact">
    <div class="card-body-custom crud-hero-body">
        <div class="crud-title-group">
            <div class="crud-icon contact">
                <i class="fas fa-envelope"></i>
            </div>
            <div>
                <h4 class="crud-title">Quản lý liên hệ</h4>
                <p class="crud-subtitle">Tiếp nhận và xử lý ý kiến khách hàng</p>
            </div>
        </div>
    </div>
</div>

<div class="content-card">
    <div class="card-header-custom">
        <h5><i class="fas fa-list me-2 crud-card-title-icon contact"></i>Danh sách liên hệ</h5>
        <div class="input-group crud-search">
            <span class="input-group-text"><i class="fas fa-search"></i></span>
            <input type="text" class="form-control" placeholder="Tìm kiếm..." data-search="#contact-table">
        </div>
    </div>
    <div class="card-body-custom crud-table-body">
        <div class="table-container">
            <table class="custom-table" id="contact-table">
                <thead>
                    <tr>
                        <th class="crud-table-id">ID</th>
                        <th>Tên</th>
                        <th>Email</th>
                        <th>SĐT</th>
                        <th>Loại</th>
                        <th>Nội dung</th>
                        <th>Ngày gửi</th>
                        <th class="crud-table-status">Trạng thái</th>
                        <th class="crud-table-actions">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql_lietke_lh = "SELECT * FROM tbl_lienhe ORDER BY ngaygui DESC";
                    $query_lietke_lh = mysqli_query($mysqli, $sql_lietke_lh);
                    while ($row = mysqli_fetch_array($query_lietke_lh)) {
                        $chuaXem = ($row['trangthai'] ?? '') === 'chua_xem';
                        $statusClass = $chuaXem ? 'pending' : 'active';
                        $statusText = $chuaXem ? 'Chưa xem' : 'Đã xem';
                        $idLienHe = (int)$row['id_lienhe'];
                        $tenLienHe = htmlspecialchars($row['ten'] ?: '(Chưa có tên)', ENT_QUOTES, 'UTF-8');
                        $emailLienHe = htmlspecialchars($row['email'] ?: '(Chưa có email)', ENT_QUOTES, 'UTF-8');
                        $sdtLienHe = htmlspecialchars($row['sodienthoai'] ?: '(Chưa có SĐT)', ENT_QUOTES, 'UTF-8');
                        $loaiLienHe = htmlspecialchars($row['loai'] ?: '(Chưa phân loại)', ENT_QUOTES, 'UTF-8');
                        $noiDungRutGon = htmlspecialchars(mb_substr($row['noidung'] ?: '', 0, 50, 'UTF-8'), ENT_QUOTES, 'UTF-8');
                    ?>
                    <tr>
                        <td><strong>#<?php echo $idLienHe; ?></strong></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="crud-avatar"><i class="fas fa-user"></i></span>
                                <span class="crud-entity-title"><?php echo $tenLienHe; ?></span>
                            </div>
                        </td>
                        <td><?php echo $emailLienHe; ?></td>
                        <td><?php echo $sdtLienHe; ?></td>
                        <td><span class="crud-pill"><?php echo $loaiLienHe; ?></span></td>
                        <td class="crud-truncate"><?php echo $noiDungRutGon; ?>...</td>
                        <td><?php echo date('d/m/Y H:i', strtotime($row['ngaygui'])); ?></td>
                        <td><span class="status-badge <?php echo $statusClass; ?>"><?php echo $statusText; ?></span></td>
                        <td>
                            <div class="action-group crud-action-center">
                                <a href="?action=quanlylienhe&query=sua&idlienhe=<?php echo $idLienHe; ?>" class="btn-action view" title="Xem chi tiết">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="modules/quanlylienhe/xuly.php?idlienhe=<?php echo $idLienHe; ?>" class="btn-action delete" title="Xóa" data-confirm="Bạn có chắc chắn muốn xóa liên hệ này?">
                                    <i class="fas fa-trash"></i>
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
