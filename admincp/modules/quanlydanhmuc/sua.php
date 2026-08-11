<?php
$idDanhMuc = (int)($_GET['iddanhmuc'] ?? 0);
$stmt = mysqli_prepare($mysqli, 'SELECT * FROM tbl_danhmuc WHERE id_danhmuc = ? LIMIT 1');
mysqli_stmt_bind_param($stmt, 'i', $idDanhMuc);
mysqli_stmt_execute($stmt);
$query_sua_danhmucsp = mysqli_stmt_get_result($stmt);
$dong = mysqli_fetch_assoc($query_sua_danhmucsp);

if (!$dong) {
    mysqli_stmt_close($stmt);
    echo '<div class="content-card"><div class="card-body-custom">Danh mục không tồn tại.</div></div>';
    return;
}

$tenDanhMuc = htmlspecialchars($dong['tendanhmuc'], ENT_QUOTES, 'UTF-8');
?>

<div class="content-card crud-hero">
    <div class="card-body-custom crud-hero-body">
        <div class="crud-title-group">
            <div class="crud-icon">
                <i class="fas fa-edit"></i>
            </div>
            <div>
                <h4 class="crud-title">Sửa danh mục</h4>
                <p class="crud-subtitle">Cập nhật thông tin danh mục</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="content-card">
            <div class="card-header-custom">
                <h5><i class="fas fa-folder me-2 crud-card-title-icon"></i>Sửa: <?php echo $tenDanhMuc; ?></h5>
            </div>
            <div class="card-body-custom">
                <form method="POST" action="modules/quanlydanhmuc/xuly.php?iddanhmuc=<?php echo $idDanhMuc; ?>">
                    <?php echo admin_csrf_field(); ?>
                    <div class="form-group-custom">
                        <label class="form-label-custom">Tên danh mục <span class="crud-required">*</span></label>
                        <input type="text" name="tendanhmuc" class="form-control-custom" value="<?php echo $tenDanhMuc; ?>" required>
                    </div>

                    <div class="form-group-custom">
                        <label class="form-label-custom">Thứ tự hiển thị</label>
                        <input type="number" name="thutu" class="form-control-custom" value="<?php echo (int)$dong['thutu']; ?>">
                        <small class="crud-muted">Số nhỏ hơn sẽ hiển thị trước</small>
                    </div>

                    <div class="d-flex gap-3 mt-4 flex-wrap">
                        <button type="submit" name="suadanhmuc" class="btn-custom btn-custom-primary">
                            <i class="fas fa-save"></i>
                            <span>Lưu thay đổi</span>
                        </button>
                        <a href="?action=quanlydanhmucsp&query=them" class="btn-custom btn-custom-secondary text-decoration-none">
                            <i class="fas fa-arrow-left"></i>
                            <span>Quay lại</span>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php mysqli_stmt_close($stmt); ?>
