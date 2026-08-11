<?php
$idSanPham = (int)($_GET['idsanpham'] ?? 0);
$stmt = mysqli_prepare($mysqli, 'SELECT * FROM tbl_sanpham WHERE id_sanpham = ? LIMIT 1');
mysqli_stmt_bind_param($stmt, 'i', $idSanPham);
mysqli_stmt_execute($stmt);
$query_sua_sp = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($query_sua_sp);

if (!$row) {
    mysqli_stmt_close($stmt);
    echo '<div class="content-card"><div class="card-body-custom">Món ăn không tồn tại.</div></div>';
    return;
}

$tenSanPham = htmlspecialchars($row['tensanpham'] ?? '', ENT_QUOTES, 'UTF-8');
$maSanPham = htmlspecialchars($row['masp'] ?? '', ENT_QUOTES, 'UTF-8');
$tomTat = htmlspecialchars($row['tomtat'] ?? '', ENT_QUOTES, 'UTF-8');
$noiDung = htmlspecialchars($row['noidung'] ?? '', ENT_QUOTES, 'UTF-8');
$hinhAnh = htmlspecialchars(upload_url($row['hinhanh'] ?? ''), ENT_QUOTES, 'UTF-8');
?>

<div class="content-card crud-hero food">
    <div class="card-body-custom crud-hero-body">
        <div class="crud-title-group">
            <div class="crud-icon food">
                <i class="fas fa-edit"></i>
            </div>
            <div>
                <h4 class="crud-title">Sửa thông tin món ăn</h4>
                <p class="crud-subtitle">Cập nhật nội dung, giá bán, số lượng và hình ảnh món ăn</p>
            </div>
        </div>
    </div>
</div>

<div class="content-card">
    <div class="card-header-custom">
        <h5><i class="fas fa-utensils me-2 crud-card-title-icon food"></i>Sửa: <?php echo $tenSanPham; ?></h5>
    </div>
    <div class="card-body-custom">
        <form method="POST" action="modules/quanlysp/xuly.php?idsanpham=<?php echo $idSanPham; ?>" enctype="multipart/form-data">
            <?php echo admin_csrf_field(); ?>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group-custom">
                        <label class="form-label-custom">Tên món ăn <span class="crud-required">*</span></label>
                        <input type="text" name="tensanpham" class="form-control-custom" value="<?php echo $tenSanPham; ?>" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group-custom">
                        <label class="form-label-custom">Mã món <span class="crud-required">*</span></label>
                        <input type="text" name="masp" class="form-control-custom" value="<?php echo $maSanPham; ?>" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group-custom">
                        <label class="form-label-custom">Giá (VNĐ) <span class="crud-required">*</span></label>
                        <input type="number" name="giasp" class="form-control-custom" value="<?php echo (int)$row['giasp']; ?>" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group-custom">
                        <label class="form-label-custom">Số lượng <span class="crud-required">*</span></label>
                        <input type="number" name="soluong" class="form-control-custom" value="<?php echo (int)$row['soluong']; ?>" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group-custom">
                        <label class="form-label-custom">Thứ tự hiển thị</label>
                        <input type="number" name="thutu" class="form-control-custom" value="<?php echo (int)$row['thutu']; ?>">
                    </div>
                </div>
            </div>

            <div class="form-group-custom">
                <label class="form-label-custom">Danh mục <span class="crud-required">*</span></label>
                <select name="danhmuc" class="form-control-custom" required>
                    <?php
                    $sql_danhmuc = "SELECT * FROM tbl_danhmuc ORDER BY id_danhmuc DESC";
                    $query_danhmuc = mysqli_query($mysqli, $sql_danhmuc);
                    while ($row_danhmuc = mysqli_fetch_array($query_danhmuc)) {
                        $selected = (int)$row_danhmuc['id_danhmuc'] === (int)$row['id_danhmuc'] ? 'selected' : '';
                    ?>
                    <option value="<?php echo (int)$row_danhmuc['id_danhmuc']; ?>" <?php echo $selected; ?>>
                        <?php echo htmlspecialchars($row_danhmuc['tendanhmuc'], ENT_QUOTES, 'UTF-8'); ?>
                    </option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group-custom">
                <label class="form-label-custom">Tóm tắt</label>
                <textarea rows="4" name="tomtat" class="form-control-custom"><?php echo $tomTat; ?></textarea>
            </div>

            <div class="form-group-custom">
                <label class="form-label-custom">Nội dung chi tiết</label>
                <textarea rows="8" name="noidung" class="form-control-custom" data-editor><?php echo $noiDung; ?></textarea>
            </div>

            <div class="form-group-custom">
                <label class="form-label-custom">Hình ảnh món ăn</label>
                <div class="row align-items-center g-4">
                    <div class="col-md-4">
                        <img src="<?php echo $hinhAnh; ?>" alt="<?php echo $tenSanPham; ?>" class="crud-current-image">
                        <p class="crud-muted mt-2">Hình ảnh hiện tại</p>
                    </div>
                    <div class="col-md-8">
                        <div class="image-upload" role="button" tabindex="0" data-upload-target="hinhanh">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p>Bấm để chọn hình ảnh mới</p>
                            <small class="crud-upload-help">Để trống nếu không đổi hình hiện tại.</small>
                        </div>
                        <input type="file" name="hinhanh" id="hinhanh" accept="image/*" class="crud-upload-input" data-preview-target="preview-sp-edit">
                        <div id="preview-sp-edit" class="crud-preview mt-3">
                            <p class="crud-muted">Ảnh mới: <span data-file-name></span></p>
                            <img src="" alt="Ảnh món ăn mới xem trước">
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-3 mt-4 flex-wrap">
                <button type="submit" name="suasanpham" class="btn-custom btn-custom-primary">
                    <i class="fas fa-save"></i>
                    <span>Lưu thay đổi</span>
                </button>
                <a href="?action=quanlymonan&query=them" class="btn-custom btn-custom-secondary text-decoration-none">
                    <i class="fas fa-arrow-left"></i>
                    <span>Quay lại</span>
                </a>
            </div>
        </form>
    </div>
</div>
<?php mysqli_stmt_close($stmt); ?>
