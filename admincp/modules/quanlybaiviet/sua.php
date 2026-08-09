<?php
$idBaiViet = (int)($_GET['idbaiviet'] ?? 0);
$stmt = mysqli_prepare($mysqli, 'SELECT * FROM tbl_baiviet WHERE id_bv = ? LIMIT 1');
mysqli_stmt_bind_param($stmt, 'i', $idBaiViet);
mysqli_stmt_execute($stmt);
$query_sua_bv = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($query_sua_bv);

if (!$row) {
    mysqli_stmt_close($stmt);
    echo '<div class="content-card"><div class="card-body-custom">Bài viết không tồn tại.</div></div>';
    return;
}

$tenBaiViet = htmlspecialchars($row['tenbaiviet'] ?? '', ENT_QUOTES, 'UTF-8');
$tomTat = htmlspecialchars($row['tomtat'] ?? '', ENT_QUOTES, 'UTF-8');
$noiDung = htmlspecialchars($row['noidung'] ?? '', ENT_QUOTES, 'UTF-8');
$hinhAnh = htmlspecialchars(upload_url($row['hinhanh'] ?? ''), ENT_QUOTES, 'UTF-8');
?>

<div class="content-card crud-hero info">
    <div class="card-body-custom crud-hero-body">
        <div class="crud-title-group">
            <div class="crud-icon info">
                <i class="fas fa-edit"></i>
            </div>
            <div>
                <h4 class="crud-title">Sửa bài viết</h4>
                <p class="crud-subtitle">Cập nhật nội dung bài viết và ảnh đại diện</p>
            </div>
        </div>
    </div>
</div>

<div class="content-card">
    <div class="card-header-custom">
        <h5><i class="fas fa-newspaper me-2 crud-card-title-icon info"></i>Sửa: <?php echo $tenBaiViet; ?></h5>
    </div>
    <div class="card-body-custom">
        <form method="POST" action="modules/quanlybaiviet/xuly.php?idbaiviet=<?php echo $idBaiViet; ?>" enctype="multipart/form-data">
            <div class="form-group-custom">
                <label class="form-label-custom">Tiêu đề bài viết <span class="crud-required">*</span></label>
                <input type="text" name="tenbaiviet" class="form-control-custom" value="<?php echo $tenBaiViet; ?>" required>
            </div>

            <div class="form-group-custom">
                <label class="form-label-custom">Danh mục bài viết <span class="crud-required">*</span></label>
                <select name="danhmuc" class="form-control-custom" required>
                    <?php
                    $sql_danhmucbv = "SELECT * FROM tbl_danhmucbaiviet ORDER BY id_baiviet DESC";
                    $query_danhmucbv = mysqli_query($mysqli, $sql_danhmucbv);
                    while ($row_danhmucbv = mysqli_fetch_array($query_danhmucbv)) {
                        $selected = (int)$row_danhmucbv['id_baiviet'] === (int)$row['id_danhmuc'] ? 'selected' : '';
                    ?>
                    <option value="<?php echo (int)$row_danhmucbv['id_baiviet']; ?>" <?php echo $selected; ?>>
                        <?php echo htmlspecialchars($row_danhmucbv['tendanhmucbv'], ENT_QUOTES, 'UTF-8'); ?>
                    </option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group-custom">
                <label class="form-label-custom">Tóm tắt</label>
                <textarea rows="4" name="tomtat" class="form-control-custom" data-editor><?php echo $tomTat; ?></textarea>
            </div>

            <div class="form-group-custom">
                <label class="form-label-custom">Nội dung chi tiết</label>
                <textarea rows="10" name="noidung" class="form-control-custom" data-editor><?php echo $noiDung; ?></textarea>
            </div>

            <div class="form-group-custom">
                <label class="form-label-custom">Hình ảnh đại diện</label>
                <div class="row align-items-center g-4">
                    <div class="col-md-4">
                        <img src="<?php echo $hinhAnh; ?>" alt="<?php echo $tenBaiViet; ?>" class="crud-current-image">
                        <p class="crud-muted mt-2">Hình ảnh hiện tại</p>
                    </div>
                    <div class="col-md-8">
                        <div class="image-upload" role="button" tabindex="0" data-upload-target="hinhanh">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p>Bấm để chọn hình ảnh mới</p>
                            <small class="crud-upload-help">Để trống nếu không đổi hình hiện tại.</small>
                        </div>
                        <input type="file" name="hinhanh" id="hinhanh" accept="image/*" class="crud-upload-input" data-preview-target="preview-bv-edit">
                        <div id="preview-bv-edit" class="crud-preview mt-3">
                            <p class="crud-muted">Ảnh mới: <span data-file-name></span></p>
                            <img src="" alt="Ảnh bài viết mới xem trước">
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-3 mt-4 flex-wrap">
                <button type="submit" name="suabaiviet" class="btn-custom btn-custom-success">
                    <i class="fas fa-save"></i>
                    <span>Lưu thay đổi</span>
                </button>
                <a href="?action=quanlybaiviet&query=them" class="btn-custom btn-custom-secondary text-decoration-none">
                    <i class="fas fa-arrow-left"></i>
                    <span>Quay lại</span>
                </a>
            </div>
        </form>
    </div>
</div>
<?php mysqli_stmt_close($stmt); ?>
