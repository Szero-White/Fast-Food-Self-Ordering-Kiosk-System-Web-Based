<?php
$sql_sua_danhmucsp = "SELECT * FROM tbl_danhmuc WHERE id_danhmuc = '$_GET[iddanhmuc]' limit 1";
$query_sua_danhmucsp = mysqli_query($mysqli, $sql_sua_danhmucsp);
?>

<!-- Page Header -->
<div class="content-card" style="background: linear-gradient(135deg, rgba(102,126,234,0.1) 0%, rgba(118,75,162,0.1) 100%); border: 1px solid rgba(102,126,234,0.2);">
    <div class="card-body-custom">
        <div class="d-flex align-items-center gap-3">
            <div style="width: 55px; height: 55px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 14px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-edit" style="color: white; font-size: 24px;"></i>
            </div>
            <div>
                <h4 style="margin: 0; font-weight: 700; color: #333;">Sửa danh mục</h4>
                <p style="margin: 0; color: #888; font-size: 14px;">Cập nhật thông tin danh mục</p>
            </div>
        </div>
    </div>
</div>

<?php
while ($dong = mysqli_fetch_array($query_sua_danhmucsp)) {
?>
<!-- Edit Category Form -->
<div class="row">
    <div class="col-lg-6">
        <div class="content-card">
            <div class="card-header-custom">
                <h5><i class="fas fa-folder me-2" style="color: #667eea;"></i>Sửa: <?php echo $dong['tendanhmuc'] ?></h5>
            </div>
            <div class="card-body-custom">
                <form method="POST" action="modules/quanlydanhmuc/xuly.php?iddanhmuc=<?php echo $_GET['iddanhmuc'] ?>">
                    <div class="form-group-custom">
                        <label class="form-label-custom">Tên danh mục <span style="color: #e74c3c;">*</span></label>
                        <input type="text" name="tendanhmuc" class="form-control-custom" value="<?php echo $dong['tendanhmuc'] ?>" required>
                    </div>

                    <div class="form-group-custom">
                        <label class="form-label-custom">Thứ tự hiển thị</label>
                        <input type="number" name="thutu" class="form-control-custom" value="<?php echo $dong['thutu'] ?>">
                        <small style="color: #888;">Số nhỏ hơn sẽ hiển thị trước</small>
                    </div>

                    <div class="d-flex gap-3 mt-4">
                        <button type="submit" name="suadanhmuc" class="btn-custom btn-custom-primary">
                            <i class="fas fa-save me-2"></i>Lưu thay đổi
                        </button>
                        <a href="?action=quanlydanhmucsp&query=them" class="btn-custom btn-custom-secondary text-decoration-none d-inline-flex align-items-center">
                            <i class="fas fa-arrow-left me-2"></i>Quay lại
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php
}
?>