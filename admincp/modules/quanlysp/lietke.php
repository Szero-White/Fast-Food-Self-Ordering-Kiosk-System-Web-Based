<?php
$sql_lietke_sp = "
    SELECT tbl_sanpham.*, tbl_danhmuc.tendanhmuc
    FROM tbl_sanpham
    LEFT JOIN tbl_danhmuc ON tbl_sanpham.id_danhmuc = tbl_danhmuc.id_danhmuc
    ORDER BY tbl_sanpham.id_sanpham DESC
";
$query_lietke_sp = mysqli_query($mysqli, $sql_lietke_sp);
?>

<div class="content-card crud-hero food">
    <div class="card-body-custom crud-hero-body">
        <div class="crud-title-group">
            <div class="crud-icon food">
                <i class="fas fa-utensils"></i>
            </div>
            <div>
                <h4 class="crud-title">Quản lý món ăn</h4>
                <p class="crud-subtitle">Danh sách món ăn đang hiển thị trong thực đơn</p>
            </div>
        </div>
        <a href="?action=quanlymonan&query=them" class="btn-custom btn-custom-primary text-decoration-none">
            <i class="fas fa-plus"></i>
            <span>Thêm món mới</span>
        </a>
    </div>
</div>

<div class="content-card">
    <div class="card-header-custom">
        <h5><i class="fas fa-list me-2 crud-card-title-icon food"></i>Danh sách món ăn</h5>
        <div class="input-group crud-search">
            <span class="input-group-text"><i class="fas fa-search"></i></span>
            <input type="text" class="form-control" placeholder="Tìm món ăn..." data-search="#product-table">
        </div>
    </div>
    <div class="card-body-custom crud-table-body">
        <div class="table-container">
            <table class="custom-table" id="product-table">
                <thead>
                    <tr>
                        <th class="crud-table-index">STT</th>
                        <th class="crud-table-image">Hình ảnh</th>
                        <th>Tên món</th>
                        <th>Mã món</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Danh mục</th>
                        <th class="crud-table-actions">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 0;
                    while ($row = mysqli_fetch_array($query_lietke_sp)) {
                        $i++;
                        $idSanPham = (int) $row['id_sanpham'];
                        $tenSanPham = htmlspecialchars($row['tensanpham'] ?? '', ENT_QUOTES, 'UTF-8');
                        $tomTat = htmlspecialchars(mb_substr($row['tomtat'] ?? '', 0, 50, 'UTF-8'), ENT_QUOTES, 'UTF-8');
                        $maSanPham = htmlspecialchars($row['masp'] ?? '', ENT_QUOTES, 'UTF-8');
                        $hinhAnh = htmlspecialchars(upload_url($row['hinhanh'] ?? ''), ENT_QUOTES, 'UTF-8');
                        $soLuong = (int) ($row['soluong'] ?? 0);
                        $giaSanPham = (float) ($row['giasp'] ?? 0);
                        $tenDanhMuc = trim((string) ($row['tendanhmuc'] ?? ''));
                        $coDanhMuc = $tenDanhMuc !== '';
                    ?>
                    <tr>
                        <td><strong>#<?php echo $i; ?></strong></td>
                        <td>
                            <img src="<?php echo $hinhAnh; ?>" alt="<?php echo $tenSanPham; ?>" class="crud-thumb">
                        </td>
                        <td>
                            <div class="crud-entity-title"><?php echo $tenSanPham; ?></div>
                            <small class="crud-muted"><?php echo $tomTat; ?>...</small>
                        </td>
                        <td><span class="crud-code food"><?php echo $maSanPham; ?></span></td>
                        <td><span class="crud-price"><?php echo number_format($giaSanPham, 0, ',', '.'); ?>đ</span></td>
                        <td><?php echo $soLuong; ?></td>
                        <td>
                            <?php if ($coDanhMuc) { ?>
                                <span class="crud-pill"><?php echo htmlspecialchars($tenDanhMuc, ENT_QUOTES, 'UTF-8'); ?></span>
                            <?php } else { ?>
                                <span class="crud-pill warning" title="Món này đang trỏ tới danh mục đã bị xóa, hãy bấm sửa để chọn lại danh mục.">
                                    Chưa phân loại
                                </span>
                            <?php } ?>
                        </td>
                        <td>
                            <div class="action-group crud-action-center">
                                <a href="?action=quanlymonan&query=sua&idsanpham=<?php echo $idSanPham; ?>" class="btn-action edit" title="Sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="modules/quanlysp/xuly.php?idsanpham=<?php echo $idSanPham; ?>" class="btn-action delete" title="Xóa" data-confirm="Bạn có chắc chắn muốn xóa món ăn này?">
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
